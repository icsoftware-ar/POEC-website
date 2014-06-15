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

class JSJobssagepay
{
	Private $paymentconfig = '';
	Private $app = '';
	function __construct()
	{
		$this->paymentconfig = $this->loadConfig();
		$this->app = JFactory::getApplication();
	}
	function generateRequest($orderid){
		if(!function_exists('mcrypt_encrypt')){
			$app =& JFactory::getApplication();
			$app->enqueueMessage(JText::_('THE_SAGEPAY_PAYMENT_METHOD_REQUIRES_THE_PHP_EXTENSION_MCRYPT_TO_BE_INSTALLED_AND_ACTIVATED_ON_YOUR_SERVER_PLEASE_CONTACT_YOUR_HOSTING_COMPANY_TO_SET_IT_UP'));
			return false;
		}
		$order = $this->getOrderById($orderid);
		
		if(!empty($order)){
			$server_url = JURI::root().'index.php';
			$postData = array(
				'VendorTxCode' => $orderid,
				'Amount' => round($order->paidamount),
				'Currency' => $order->currencycode,
				'Description' => $order->packagetitle,
				'SuccessURL' => $server_url . '?' . $this->paymentconfig['notifyurl_sagepay'],
				'FailureURL' => $server_url . '?' . $this->paymentconfig['notifyurl_sagepay'],
				'CustomerName' => $order->username,
				'SendEMail' => 0,

				'AllowGiftAid' => 0,
				'ApplyAVSCV2' => 0,
				'Apply3DSecure' => 0,
			);
			$t = array();
			foreach($postData as $k => $v) {
				$t[] = $k . '=' . $v;
			}
			$postData = implode('&',$t);
			unset($t);

			$vars = array(
				'navigate' => '',
				'VPSProtocol' => '2.23',
				'TxType' => 'PAYMENT',			  
				'Vendor' => $this->paymentconfig['vendorname_sagepay'],
				'Crypt' => $this->encryptAndEncode($postData, $this->paymentconfig['password_sagepay'], '' ),
			);
			switch( $this->paymentconfig['mode_sagepay'] ) {
				case 'LIVE':
					$action = 'https://live.sagepay.com/gateway/service/vspform-register.vsp';
					break;
				case 'TEST':
					$action = 'https://test.sagepay.com/gateway/service/vspform-register.vsp';
					break;
				case 'SIMU':
				default:
					$action = 'https://test.sagepay.com/Simulator/VSPFormGateway.asp';
					break;
			}
		?>

		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
<?php 
			foreach( $vars as $name => $value ) {
				echo '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars((string)$value).'" />';
			}
?>		
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
			$query = "UPDATE `#__js_job_paymenthistory` AS payment SET payment.payer_amount = ".$db->quote($data['payer_amount']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date).", payment.payer_tx_token = ".$db->quote($data['payer_tx_token'])." WHERE payment.id = ".$data['orderid'];
			$db->setQuery($query);
			if(!$db->query()) return false;
		}
		return true;
	}
	function getOrderById($orderid){
		if(!is_numeric($orderid)) return false;
		$db = JFactory::getDBO();
		$query = "SELECT pro_order.*,currency.code AS currencycode,user.name AS username
					FROM `#__js_job_paymenthistory` AS pro_order 
					JOIN `#__js_job_currencies` AS currency ON currency.id = pro_order.currencyid
					JOIN `#__users` AS user ON user.id = pro_order.uid
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'sagepay'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){

		//code not tested
		$data = $this->decodeAndDecrypt($_REQUEST['crypt'], $this->paymenconfig['password_sagepay']);

		$vars = array();
		parse_str($data, $vars);

		$data['orderid'] = $vars['VendorTxCode'];
		$data['payer_amount'] = $vars['Amount'];
		$data['payer_tx_token'] = $vars['VPSTxId'];
		return $data;
	}
	function simpleXor($in, $k) {
		$lst = array();
		$output = '';
		for($i = 0; $i < strlen($k); $i++) {
			$lst[$i] = ord(substr($k, $i, 1));
		}
		for($i = 0; $i < strlen($in); $i++) {
			$output .= chr(ord(substr($in, $i, 1)) ^ ($lst[$i % strlen($k)]));
		}
		return $output;
	}
	function encryptAndEncode($in, $password, $type) {
		if($type == 'XOR') {
			return base64_encode($this->simpleXor($in, $password));
		} else {
			$this->addPKCS5Padding($in);
			$iv = $password;
			$strCrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $password, $in, MCRYPT_MODE_CBC, $iv);
			return "@" . bin2hex($strCrypt);
		}
	}

	function decodeAndDecrypt($in, $password) {
		if( substr($in,0,1) == '@') {
			$iv = $password;
			$in = substr($in,1);
			$in = pack('H*', $in);
			return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $password, $in, MCRYPT_MODE_CBC, $iv);
		} else {
			return $this->simpleXor(base64_decode(str_replace(' ','+',$in)), $password);
		}
	}

	function addPKCS5Padding(&$input) {
		$blocksize = 16;
		$padding = '';
		$padlength = $blocksize - (strlen($input) % $blocksize);
		for($i = 1; $i <= $padlength; $i++) {
			$padding .= chr($padlength);
		}
		$input .= $padding;
	}
}
