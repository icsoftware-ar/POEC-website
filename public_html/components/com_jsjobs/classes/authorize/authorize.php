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

class JSJobsauthorize
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
		$action = "https://secure.authorize.net/gateway/transact.dll";
		//$action = "https://test.authorize.net/gateway/transact.dll"; for developer account
		
		if(!empty($order)){

			$loginID		= $this->paymentconfig['loginid_authorize'];
			$transactionKey = $this->paymentconfig['transactionkey_authorize'];
			$amount 		= $order->paidamount;
			$description 	= $order->packagetitle;
			$label 			= "Submit Payment"; // The is the label on the 'submit' button
			if($this->paymentconfig['testmode_authorize'] == 1) $testMode = "true";else $testmode = "false";
			// an invoice is generated using the date and time
			$invoice	= date('Y-m-d H:i:s');
			// a sequence number is randomly generated
			$sequence	= $orderid;
			// a timestamp is generated
			$timeStamp	= time();
			// The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
			// newer have the necessary hmac function built in.  For older versions, it
			// will try to use the mhash library.
			if( phpversion() >= '5.1.2' )
				{ $fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^" . $order->currencycode, $transactionKey); }
			else 
				{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^" . $order->currencycode, $transactionKey)); }
			
		?>

		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
		
			<input type='hidden' name='x_login' value='<?php echo $loginID; ?>' />
			<input type='hidden' name='x_amount' value='<?php echo $amount; ?>' />
			<input type='hidden' name='x_description' value='<?php echo $description; ?>' />
			<input type='hidden' name='x_invoice_num' value='<?php echo $invoice; ?>' />
			<input type='hidden' name='x_fp_sequence' value='<?php echo $sequence; ?>' />
			<input type='hidden' name='x_fp_timestamp' value='<?php echo $timeStamp; ?>' />
			<input type='hidden' name='x_fp_hash' value='<?php echo $fingerprint; ?>' />
			<input type='hidden' name='x_test_request' value='<?php echo $testMode; ?>' />
			<input type='hidden' name='x_Relay_URL' value='<?php echo JURI::root().$this->paymentconfig['notifyurl_authorize']; ?>' />
			<input type='hidden' name='x_return_policy_url' value='<?php echo $this->paymentconfig['returnurl_authorize']; ?>' />
			<input type='hidden' name='x_cancel_url' value='<?php echo $this->paymentconfig['cancelurl_authorize']; ?>' />
			<input type='hidden' name='x_cust_id' value='<?php echo $orderid; ?>' />
			<input type='hidden' name='x_currency_code' value='<?php echo $order->currencycode;?>' />
			<input type='hidden' name='x_show_form' value='PAYMENT_FORM' />
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
			$query = "UPDATE `#__js_job_sellerpaymenthistory` AS payment SET payment.payer_email = ".$db->quote($data['payer_email']).", payment.payer_firstname = ".$db->quote($data['payer_firstname']).", payment.payer_lastname = ".$db->quote($data['payer_lastname']).", payment.payer_amount = ".$db->quote($data['payer_amount']).", payment.payer_itemname = ".$db->quote($data['payer_itemname']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date).", payment.payer_tx_token = ".$db->quote($data['payer_tx_token'])." WHERE payment.id = ".$data['orderid'];
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'authorize'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		
		//return data array
		$darray = array();
		foreach($_REQUEST as $key => $value){
			$darray[$key] = $value;
		}
		if($darray['x_response_code'] == 1){
			$data['payer_email'] = $darray['x_email'];
			$data['payer_firstname'] = $darray['x_first_name'];
			$data['payer_lastname'] = $darray['x_last_name'];
			$data['payer_amount'] = $darray['x_amount'];
			$data['payer_itemname'] = $darray['x_description'];
			$data['payer_status'] = $darray['x_response_code'];
			$data['payer_tx_token'] = $darray['x_invoice_num'];
			$data['orderid'] = $darray['x_cust_id'];
		}else{
			$data = false;
		}
		
		return $data;
	}
}
