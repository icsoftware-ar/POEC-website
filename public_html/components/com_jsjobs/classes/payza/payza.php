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

class JSJobspayza
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
		if($this->paymentconfig['testmode_payza'] == 1) $action ='https://sandbox.Payza.com/sandbox/payprocess.aspx';
		else $action ='https://www.alertpay.com/PayProcess.aspx';
		
		if(!empty($order)){
		?>

		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
		
		<input type="hidden" name="ap_purchasetype" value="item">
		<input type="hidden" name="apc_1" value="<?php echo $order->id;?>">
		<input type="hidden" name="ap_merchant" value="<?php echo $this->paymentconfig['merchantemail_payza'];?>">
		<input type="hidden" name="ap_itemname" value="<?php echo $order->packagetitle; ?>">
		<input type="hidden" name="ap_currency" value="<?php echo $order->currencycode; ?>">
		<input type="hidden" name="ap_returnurl" value="<?php echo $this->paymentconfig['returnurl_payza']; ?>">
		<input type="hidden" name="ap_cancelurl" value="<?php echo $this->paymentconfig['cancelurl_payza']; ?>">
		<input type="hidden" name="ap_amount" value="<?php echo round($order->paidamount); ?>">
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
			$query = "UPDATE `#__js_job_paymenthistory` AS payment SET payment.payer_email = ".$db->quote($data['payer_email']).", payment.payer_firstname = ".$db->quote($data['payer_firstname']).", payment.payer_lastname = ".$db->quote($data['payer_lastname']).", payment.payer_amount = ".$db->quote($data['payer_amount']).", payment.payer_itemname = ".$db->quote($data['payer_itemname']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$data['orderid'];
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
		}else $this->app->redirect(JRoute::_ ('index.php?option=com_jsjob&view=employer&layout=norecordfound'));
	}
	function loadConfig(){
		$db = JFactory::getDBO();
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'payza'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		//The value is the url address of EPD V2 handler and the identifier of the token string 
		if($this->paymentconfig['testmode_payza'] == 1) define("EPD_V2_HANDLER", "https://sandbox.Payza.com/sandbox/IPN2.ashx");
		else define("EPD_V2_HANDLER", "https://secure.payza.com/ipn2.ashx");
		
		define("TOKEN_IDENTIFIER", "token=");
		
		// get the token from Payza
		$token = urlencode($_POST['token']);

		//preappend the identifier string "token=" 
		$token = TOKEN_IDENTIFIER.$token;
		
		/**
		 * 
		 * Sends the URL encoded TOKEN string to the Payza's EPD handler
		 * using cURL and retrieves the response.
		 * 
		 * variable $response holds the response string from the Payza's EPD V2.
		 */
		
		$response = '';
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, EPD_V2_HANDLER);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($ch);

		curl_close($ch);	
		if(strlen($response) > 0)
		{
			//urldecode the received response from Payza's IPN V2
			$response = urldecode($response);
			
			if($response == "INVALID TOKEN")
			{
				//the token is not valid
			}
			else
			{
				//split the response string by the delimeter "&"
				$aps = explode("&", $response);
									
				//define an array to put the IPN information
				$info = array();
				
				foreach ($aps as $ap)
				{
					//put the IPN information into an associative array $info
					$ele = explode("=", $ap);
					$info[$ele[0]] = $ele[1];
				}
				
				//setting information about the transaction from the IPN information array
				$data['payer_email'] =$info['ap_custemailaddress'];
				$data['payer_firstname'] =$info['ap_custfirstname'];
				$data['payer_lastname'] =$info['ap_custlastname'];
				$data['payer_amount'] =$info['ap_totalamount'];
				$data['payer_itemname'] =$info['ap_itemname'];
				$data['payer_itemname'] =$info['ap_itemname'];
				$data['orderid'] =$info['apc_1'];
			}
		}
		else
		{
			//something is wrong, no response is received from Payza
			$data = false;
		}
		return $data;
	}
}
