<?php
/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	April 05, 2012
 ^
 + Project: 		JS Autoz
 ^ 
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JSJobsalipay
{
	Private $paymentconfig = '';
	Private $app = '';
	function __construct()
	{
		$this->paymentconfig = $this->loadConfig();
		$this->app = JFactory::getApplication();
		$helperclasspath = "components/com_jsjobs/classes/alipay/alipayhelperclass.php";
		include_once($helperclasspath);
	}
	function generateRequest($orderid){
		$order = $this->getOrderById($orderid);

				
		if(!empty($order)){
			$notifyurl = JURI::root().$this->paymentconfig['notifyurl_alipay'];
		if ($this->paymentconfig['paymentmode_alipay'] == "Partner")
		{
			$order_params = array(
				"seller_email" => $this->paymentconfig['merchantemail_alipay'],
				"service" => "create_partner_trade_by_buyer",
				"partner" => $this->paymentconfig['partnerid_alipay'],
				"return_url" => $this->paymentconfig['returnurl_alipay'],
				"notify_url" => $notifyurl,
				"_input_charset" => "utf-8",
				"subject" => 'order number : '.$orderid,
				"body" => '',
				"out_trade_no" => $orderid,
				"payment_type"=> "1",
				"price" => $order->paidamount,
				"quantity" => "1",
				"logistics_type"=>"EXPRESS",
				"logistics_fee"=> "0.00",
				"logistics_payment"=>"BUYER_PAY",
				'receive_name' => $order->user_name,
				'receive_address' => "",
				'receive_zip' => "",
				'receive_phone' =>""
			);
		}
		else {
			$order_params = array(
				"seller_email" => $this->paymentconfig['merchantemail_alipay'],
				"service" => "create_direct_pay_by_user",
				"partner" => $this->paymentconfig['partnerid_alipay'],
				"return_url" => $this->paymentconfig['returnurl_alipay'],
				"notify_url" => $notifyurl,
				"_input_charset" => "utf-8",
				"subject" => 'order number : '.$orderid,
				"body" => '',
				"out_trade_no" => $orderid,
				"payment_type"=> "1",
				"total_fee" => $order->paidamount
			);
		}

		$alipay = new alipayhelperclass();
		$alipay->set_order_params($order_params);
		$alipay->set_transport($this->paymentconfig['transport_alipay']);
		$alipay->set_security_code($this->paymentconfig['securitycode_alipay']);
		$alipay->set_sign_type('MD5');
		$sign = $alipay->_sign($alipay->_order_params);
		$alipay_link = $alipay->create_payment_link();

			JRequest::setVar('noform',1);
		?>

		<form action="<?php echo $alipay_link; ?>" method="post" name="adminForm" >
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
		
		$vars = array();
		$data = array();
		$filter = JFilterInput::getInstance();
		foreach($_REQUEST as $key => $value){
			$key = $filter->clean($key);
			if(preg_match("#^[0-9a-z_-]{1,30}$#i",$key)&&!preg_match("#^cmd$#i",$key)){
				$value = JRequest::getString($key);
				$vars[$key]=$value;
				$data[]=$key.'='.urlencode($value);
			}
		}
		$data = implode('&',$data).'&cmd=_notify-validate';

		$dbOrder = $this->getOrderById((int)@$vars['out_trade_no']);
		if(empty($dbOrder)){
			echo "Could not load any order for your notification ".@$vars['out_trade_no'];
			return false;
		}
		
		//code not tested
		$alipay = new alipayhelperclass();
		$alipay->set_transport($this->paymentconfig['transport_alipay']);
		$alipay->set_security_code($this->paymentconfig['securitycode_alipay']);
		$alipay->set_sign_type('MD5');
		$alipay->set_partner_id($this->paymentconfig['partnerid_alipay']);

		if($alipay->_transport == "https") {
			$notify_url = $alipay->_notify_gateway . "service=notify_verify" ."&partner=" .$alipay->_partner_id . "&notify_id=".$_POST["notify_id"];
		} else {
			$notify_url = $alipay->_notify_gateway . "partner=" . $alipay->_partner_id . "&notify_id=".$_POST["notify_id"];
		}
		$url_array  = parse_url($this->paymentconfig['notifyurl_alipay']);
		$errno='';
		$errstr='';
		$notify = array();
		$response = array();
		if($url_array['scheme'] == 'https') {
			$transport = 'ssl://';
			$url_array['port'] = '443';
		} else {
			$transport = 'tcp://';
			$url_array['port'] = '80';
		}
		$fp = @fsockopen($transport . $url_array['host'], $url_array['port'], $errno, $errstr, 60);
		if($fp) {
			fputs($fp, "POST " . $url_array['path'] . " HTTP/1.1\r\n");
			fputs($fp, "HOST: " . $url_array['host'] . "\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($url_array['query']) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $url_array['query'] . "\r\n\r\n");
			while(!feof($fp)) {
				$notify[] = @fgets($fp, 1024);
			}
			fclose($fp);
			$response=implode(',', $notify);
		}
		if(is_array($_POST)) {
			$tmp_array = array();
			foreach($_POST as $key=>$value) {
				if($value != '' && $key != 'sign' && $key != 'sign_type') {
					$tmp_array[$key] = $value;
				}
			}
			ksort($tmp_array);
			reset($tmp_array);
			$params = $tmp_array;
		} else {
			return false;
		}
		$sign = $alipay->_sign($params);
		if((preg_match('/true$/i', $response) && $sign == $_POST['sign']) || $_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS' || $_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS' || $_POST['trade_status']== 'WAIT_BUYER_PAY') {

			$date = date("Y-m-d H:i:s");
			$db = JFactory::getDBO();
			$query = "UPDATE `#__js_job_paymenthistory` AS payment SET payment.payer_amount = ".$db->quote($params['total_fee']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$parms['out_trade_no'];
			$db->setQuery($query);
			if(!$db->query()) return false;
			
			return true;
		} else {
				return false;
		}
		return true;
	}
	function getOrderById($orderid){
		if(!is_numeric($orderid)) return false;
		$db = JFactory::getDBO();
		$query = "SELECT pro_order.*,currency.code AS currencycode,user.name AS user_name
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'alipay'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
}
