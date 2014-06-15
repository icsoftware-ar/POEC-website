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

class JSJobspagseguro
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
		$action ='https://pagseguro.uol.com.br/checkout/checkout.jhtml';
		
		if(!empty($order)){
		?>
		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
		
		<input type="hidden" name="email_cobranca" value="<?php echo $this->paymentconfig['emailaddress_pagseguro'];?>">
		<input type="hidden" name="tipo" value="CP">
		<input type="hidden" name="moeda" value="BRL">
		<input type="hidden" name="reference" value="<?php echo $orderid; ?>">
		<input type="hidden" name="item_id_1" value="<?php echo $orderid; ?>">
		<input type="hidden" name="item_descr_1" value="<?php echo $order->packagetitle; ?>">
		<input type="hidden" name="item_quant_1" value="1">
		<input type="hidden" name="item_valor_1" value="<?php echo round($order->paidamount);?>">
		<input type="hidden" name="item_frete_1" value="0">
		<input type="hidden" name="item_peso_1" value="0">
		<input type="hidden" name="tipo_frete" value="EN">
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
			$query = "UPDATE `#__js_job_paymenthistory` AS payment SET payment.payer_email = ".$db->quote($data['payer_email']).", payment.payer_firstname = ".$db->quote($data['payer_firstname']).",  payment.payer_amount = ".$db->quote($data['payer_amount']).", payment.payer_itemname = ".$db->quote($data['payer_itemname']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$data['orderid'];
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'payza'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		//not verfied code

		$notificationcode = $_POST['notificationCode'];
		$url = "https://ws.pagseguro.uol.com.br/v2/transactions/notifications/".$notificationcode."?email=".$this->paymentconfig['emailaddress_pagseguro']."&token=".$this->paymentconfig['token_pagseguro'];
		
		
		$response = '';
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($ch);

		curl_close($ch);	
		if($response != 'Unauthorized')
		{
			if(preg_match('#<reference>(.*)</reference>#', $string, $res)){
				$data['orderid'] = $res[1];
			}
			if(preg_match('#<grossAmount>(.*)</grossAmount>#', $string, $res)){
				$data['payer_amount'] = $res[1];
			}
			if(preg_match('#<description>(.*)</description>#', $string, $res)){
				$data['payer_itemname'] = $res[1];
			}
			if(preg_match('#<name>(.*)</name>#', $string, $res)){
				$data['payer_firstname'] = $res[1];
			}
			if(preg_match('#<email>(.*)</email>#', $string, $res)){
				$data['payer_email'] = $res[1];
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
