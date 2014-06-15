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

class JSJobsgooglecheckout
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
		if(!empty($order)){
			$data = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'."\n";
			$data .= '<checkout-shopping-cart xmlns="http://checkout.google.com/schema/2"><shopping-cart><items>';
			$data .= '<item><item-name>Order #'.$orderid.' - '.$order->packagetitle.'</item-name><item-description></item-description><unit-price currency="'.$this->paymentconfig['currencycode_googlecheckout'].'">'.$order->paidamount.'</unit-price><quantity>1</quantity></item>';
			$data .= '</items></shopping-cart><checkout-flow-support><merchant-checkout-flow-support/></checkout-flow-support></checkout-shopping-cart>';

			if( $this->paymentconfig['servertoserver_googlecheckout'] == 1 ) {
				$ret =& $this->webCall('checkout', $data);
				
				if( $ret !== false ) {
					if( preg_match('#<redirect-url>(.*)</redirect-url>#iU', $ret, $redirect) ) {
						$redirect = html_entity_decode(trim($redirect[1]));
						$this->app->redirect($redirect);
					}
				}
			} else {
				$vars = array(
					'signature' => base64_encode($this->signature($data, $this->paymentconfig['merchantkey_googlecheckout'])),
					'cart' => base64_encode($data)
				);
				if( $this->paymentconfig['testmode_googlecheckout'] == 1 ) {
					$url = 'https://sandbox.google.com/checkout/api/checkout/v2/checkout/Merchant/';
				} else {
					$url = 'https://checkout.google.com/api/checkout/v2/checkout/Merchant/';
				}
				$url .= $this->paymentconfig['merchantid_googlecheckout'];
?>
			<form method="POST"
				  action="https://sandbox.google.com/checkout/api/checkout/v2/checkoutForm/Merchant/824667198743220"
				  accept-charset="utf-8">

			  <!-- Sell physical goods and services with possible tax and shipping -->
			  <input type="hidden" name="item_name_1" value="5 lbs. Dog Food"/>
			  <input type="hidden" name="item_description_1" value="5 lb. bag of dog food"/>
			  <input type="hidden" name="item_price_1" value="35.00"/>
			  <input type="hidden" name="item_currency_1" value="USD"/>
			  <input type="hidden" name="item_quantity_1" value="1"/>
			  <input type="hidden" name="item_merchant_id_1" value="5LBDOGCHOW"/>

			  <!-- No tax code -->

			  <!-- No shipping code -->

			  <input type="hidden" name="_charset_" />

			  <!-- Button code -->
			  <input type="image"
				name="Google Checkout"
				alt="Fast checkout through Google"
				src="http://sandbox.google.com/checkout/buttons/checkout.gif?merchant_id=824667198743220&w=180&h=46&style=white&variant=text&loc=en_US"
				height="46"
				width="180" />

			</form>


<?php

/*
		<form action="<?php echo $url; ?>" method="post" name="adminForm" >
			<input type='' name='signature' value='<?php echo $vars['signature']; ?>' />
			<input type='' name='cart' value='<?php echo $vars['cart']; ?>' />
			<script language=Javascript>
					//document.adminForm.shopping_url.value = window.location.href;
					document.adminForm.submit();
			</script>
		</form>
*/
			}
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
		$query = "SELECT pro_order.*,currency.code AS currencycode,user.name AS username,user.email AS useremail
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'googlecheckout'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		//code not tested
		$compare_mer_id = '';
		$compare_mer_key = '';
		if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$compare_mer_id = $_SERVER['PHP_AUTH_USER'];
			$compare_mer_key = $_SERVER['PHP_AUTH_PW'];
		}
		if( $compare_mer_id != $this->paymentconfig['merchantid_googlecheckout'] || $compare_mer_key != $this->paymentconfig['merchantkey_googlecheckout'] ) {
			header('HTTP/1.1 401 Unauthorized');
			return false;
		}

		$orderId = 0;
		$response = isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:file_get_contents('php://input');
		if (get_magic_quotes_gpc()) { $response = stripslashes($response); }

		$vars =& $this->parseResponse($response);
		$orderid = (int)$vars['order-num'];

		if( in_array($vars['state'], array('REVIEWING','CHARGING')) || empty($vars['state']) || in_array($vars['type'], array('risk-information-notification','charge-amount-notification')) ) {
			$this->sendAck($vars);
			exit;
		}

		$dbOrder = $this->getOrderById($orderId);
		if(empty($dbOrder)){
			echo "Could not load any order for your notification ".$orderId;
			header('HTTP/1.0 400 Bad Request');
			exit;
		}

		if($vars['state'] == 'CHARGEABLE') {
			if( $vars['type'] != 'authorization-amount-notification' ) {
				$this->sendAck($vars);
				exit;
			}

			$data = '<'.'?xml version="1.0" encoding="UTF-8"?'.'>'."\n";
			$data .= '<charge-order xmlns="http://checkout.google.com/schema/2" google-order-number="'.$vars['google-order'].'">';

			if( $vars['currency'] != '' ) {
				$data .= '<amount currency="'.$vars['currency'].'">'.$vars['amount'].'</amount>';
			}
			$data .= '</charge-order>';

			$serial = $vars['serial'];
			$ret =& $this->webCall('request', $data);
			$vars =& $this->parseResponse($ret);
			$vars['serial'] = $serial;

			if( $vars['type'] == 'request-received' ) {
				$this->sendAck($vars);
				exit;
			}
		}


		$returndata['orderid'] = $orderid;
		$returndata['amount'] = $vars['amount'];

		$this->sendAck($vars);

		return $returndata;
	}

	function webCall($type, &$data) {

		if( $type == 'request' ) {
			$called_action = 'request';
		} else if( $type == 'checkout' ) {
			if( $params->server_to_server ) {
				$called_action = 'merchantCheckout';
			} else {
				$called_action = 'checkout';
			}
		}

		if( $this->paymentconfig['testmode_googlecheckout'] == 1 ) {
			$url = 'https://sandbox.google.com/checkout/api/checkout/v2/'.$called_action.'/Merchant/';
		} else {
			$url = 'https://checkout.google.com/api/checkout/v2/'.$called_action.'/Merchant/';
		}
		$url .= $this->paymentconfig['merchantid_googlecheckout'];

		$headers = array(
			'Authorization: Basic '.base64_encode($this->paymentconfig['merchantid_googlecheckout'].':'.$this->paymentconfig['merchantkey_googlecheckout']),
			'Content-Type: application/xml; charset=UTF-8',
			'Accept: application/xml; charset=UTF-8',
			'User-Agent: HikaShop Google Checkout Plugin'
		);

		$session = curl_init($url);
		curl_setopt($session, CURLOPT_POST, true);
		curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($session, CURLOPT_POSTFIELDS, $data);
		curl_setopt($session, CURLOPT_HEADER, true);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

		$ret = curl_exec($session);
		curl_close($session);

		return $ret;
	}
	function signature($data, $key) {
		$blocksize = 64;
		if (strlen($key) > $blocksize) {
			$key = pack('H*', sha1($key));
		}
		$key = str_pad($key, $blocksize, chr(0x00));
		$ipad = str_repeat(chr(0x36), $blocksize);
		$opad = str_repeat(chr(0x5c), $blocksize);
		$hmac = pack(
			'H*', sha1(
				($key^$opad).pack(
					'H*', sha1(
						($key^$ipad).$data
					)
				)
			)
		);
		return $hmac;
	}
	function parseResponse(&$xml) {
		$vars = array(
			'currency' => '',
			'amount' => 0,
			'serial' => '',
			'order-num' => '',
			'state' => ''
		);

		if( preg_match('#<(.*) xmlns="http://checkout.google.com/schema/2" serial-number=#iU', $xml, $ggreg) ) {
			$vars['type'] = trim($ggreg[1]);
		}

		if( preg_match('#serial-number="(.*)"#iU', $xml, $ggreg) ) {
			$vars['serial'] = $ggreg[1];
		}
		if( preg_match('/<item-name>.* #(.*)<\/item-name>/iU', $xml, $ggreg) ) {
			$vars['order-num'] = trim($ggreg[1]);
		}
		if( preg_match('#<google-order-number>(.*)</google-order-number>#iU', $xml, $ggreg) ) {
			$vars['google-order'] = trim($ggreg[1]);
		}
		if( preg_match('#<order-total currency="(.*)">(.*)</order-total>#iU', $xml, $ggreg) ) {
			$vars['currency'] = $ggreg[1];
			$vars['amount'] = (int)$ggreg[2];
		}
		if( preg_match('#<new-financial-order-state>(.*)</new-financial-order-state>#iU', $xml, $ggreg) ) {
			$vars['state'] = trim($ggreg[1]);
		} else if( preg_match('#<financial-order-state>(.*)</financial-order-state>#iU', $xml, $ggreg) ) {
			$vars['state'] = trim($ggreg[1]);
		}

		return $vars;
	}
	function sendAck(&$vars) {
		$acknowledgment = '<notification-acknowledgment xmlns="http://checkout.google.com/schema/2"';
		if(!empty($vars['serial'])) {
			$acknowledgment .= ' serial-number="'.$vars['serial'].'"';
		}
		$acknowledgment .= ' />';

		$msg = ob_get_clean();
		echo $acknowledgment;
		ob_start();
		echo $msg;
	}
	
}
