<?php
/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	April 05, 2012
 ^
 + Project: 		JS Jobs
 ^ 
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JSJobspaypal
{
	Private $paymentconfig = '';
	Private $app = '';
	function __construct()
	{
		$this->paymentconfig = $this->loadConfig();
		$this->app = JFactory::getApplication();
	}
	function generateRequest($orderid){
		$order = $this->getOrderById($orderid);
		if($this->paymentconfig['testmode_paypal'] == 1) $action ='https://www.sandbox.paypal.com/cgi-bin/webscr';
		else $action ='https://www.paypal.com/cgi-bin/webscr';
		
		if(!empty($order)){
		?>

		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
		
		<input type="hidden" name="business" value="<?php echo $this->paymentconfig['accountid_paypal'];?>">
		<input type="hidden" name="cmd" value="_cart">
		<input type="hidden" name="add" value="1">
		<input type="hidden" name="item_name" value="<?php echo $order->packagetitle; ?>">
		<input type="hidden" name="amount" value="<?php echo $order->paidamount; ?>">
		<input type="hidden" name="currency_code" value="<?php echo $order->currencycode; ?>">
		<input type="hidden" name="return" value="<?php echo $this->paymentconfig['returnurl_paypal']; ?>">
		<input type="hidden" name="notify_url" value="<?php echo $this->paymentconfig['notifyurl_paypal']."&fr=".$orderid; ?>">
		<input type="hidden" name="cancel_return" value="<?php echo $this->paymentconfig['cancelurl_paypal']; ?>">
		<input type="hidden" name="rm" value="2">
				<script language=Javascript>
						//document.adminForm.shopping_url.value = window.location.href;
						document.adminForm.submit();
				</script>
		</form>
<?php
		}else{
		}
	}
	function onPaymentNotification(){
		
		$data = $this->getData();
		if($data != false){
			$date = date("Y-m-d H:i:s");
			$db = JFactory::getDBO();
			$query = "UPDATE `#__js_job_paymenthistory` AS payment SET payment.payer_email = ".$db->quote($data['payer_email']).", payment.payer_firstname = ".$db->quote($data['payer_firstname']).", payment.payer_lastname = ".$db->quote($data['payer_lastname']).", payment.payer_amount = ".$db->quote($data['payer_amount']).", payment.payer_itemname = ".$db->quote($data['payer_itemname']).", payment.payer_itemname2 = ".$db->quote($data['payer_itemname2']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$data['orderid'];
			$db->setQuery($query);
			if(!$db->query()) return false;
		}
		return true;
	}
	function getOrderById($orderid){
		if(!is_numeric($orderid)) return false;
		$db = JFactory::getDBO();
		$query = "SELECT pro_order.*,currency.code AS currencycode
					FROM `#__js_job_paymenthistory` AS pro_order 
					JOIN `#__js_job_currencies` AS currency ON currency.id = pro_order.currencyid
					WHERE pro_order.id = ".$orderid;
		$db->setQuery($query);
		$result = $db->loadObject();
		if(!empty($result)){
			if(empty($result->currencycode)) $result->currencycode = "USD";
			return $result;
		}else $this->app->redirect(JRoute::_ ('index.php?option=com_jsjobs&view=employer&layout=norecordfound'));
	}
	function loadConfig(){
		$db = JFactory::getDBO();
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'paypal'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		if ($_GET['fr'] != "") $data['orderid'] = $_GET['fr'];

		if ($_GET['for'] != "") $for = $_GET['for'];

		$req = 'cmd=_notify-synch';

		if ($_GET['tx'] != "") $tx_token = $_GET['tx'];
		           
		$testmode = $this->paymentconfig['testmode_paypal'];
		$auth_token = $this->paymentconfig['authtoken_paypal'];
		$req .= "&tx=$tx_token&at=$auth_token";

		// post back to PayPal system to validate
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		if ($testmode == '1') {
			$act = "www.sandbox.paypal.com";
			$header .= "Host: www.sandbox.paypal.com\r\n";
		}else{ $act = "www.paypal.com";
			$header .= "Host: www.paypal.com\r\n";
		}
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

		
		//$fp = fsockopen ("$act", 80, $errno, $errstr, 30);
		$fp = fsockopen('ssl://'.$act,"443",$err_num,$err_str,30);

		// If possible, securely post back to paypal using HTTPS
		// Your PHP server will need to be SSL enabled
		// $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

		if (!$fp) {
			// HTTP ERROR
		} else {
			fputs ($fp, $header . $req);
			// read the body data
			$res = '';
			$headerdone = false;
			while (!feof($fp)) {
				$line = fgets ($fp, 1024);
				if (strcmp($line, "\r\n") == 0) {
					// read the header
					$headerdone = true;
				}
				else if ($headerdone)
				{
					// header has been read. now read the contents
					$res .= $line;
				}
			}

			// parse the data
			$lines = explode("\n", $res);
			$keyarray = array();
			$paypalstatus = $lines[0];
			$date = date('Y-m-d H:i:s');
			$status = 1;
			if (strcmp ($lines[0], "SUCCESS") == 0) {
				for ($i=1; $i<count($lines);$i++){
					list($key,$val) = explode("=", $lines[$i]);
					$keyarray[urldecode($key)] = urldecode($val);
				}
				// check the payment_status is Completed
				// check that txn_id has not been previously processed
				// check that receiver_email is your Primary PayPal email
				// check that payment_amount/payment_currency are correct
				// process payment
				$data['payer_firstname'] = $keyarray['first_name'];
				$data['payer_lastname'] = $keyarray['last_name'];
				$data['payer_itemname'] = $keyarray['item_name'];
				$data['payer_amount'] = $keyarray['payment_gross'];
				$data['payer_email'] = $keyarray['payer_email'];

				$data['payer_itemname2'] = $keyarray['item_name1'];

			}
			else if (strcmp ($lines[0], "FAIL") == 0) {
				$data = false;
			}


		}

		fclose ($fp);

		return $data;
	}
}
