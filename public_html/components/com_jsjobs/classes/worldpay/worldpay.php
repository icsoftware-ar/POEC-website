<?php
/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	April 05, 2012
 ^
 + Project: 		JS JObs
 ^ 
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JSJobsworldpay
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
		$action = $this->paymentconfig['paymenturl_worldpay'];
		
		if(!empty($order)){
		?>

		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
		
			<input type='hidden' name='instId' value='<?php echo $this->paymentconfig['instid_worldpay']; ?>' />
			<input type='hidden' name='cartId' value='<?php echo $orderid; ?>' />
			<input type='hidden' name='amount' value='<?php echo $order->paidamount; ?>' />
			<input type='hidden' name='currency' value='<?php echo $order->currencycode;?>' />
			<input type='hidden' name='testMode' value='<?php if($this->paymentconfig['testmode_worldpay'] == 1) echo "100"; else echo "0";?>' />
			<input type='hidden' name='desc' value='<?php echo $order->packagetitle; ?>' />
			<input type='hidden' name='MC_callback' value='<?php echo JURI::root().$this->paymentconfig['notifyurl_worldpay']; ?>' />
			<input type='hidden' name='name' value='<?php if($this->paymentconfig['testmode_worldpay'] == 1) echo "AUTHORISED"; else echo $order->username; ?>' />
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
			$query = "UPDATE `#__js_job_paymenthistory` AS payment SET payment.payer_amount = ".$db->quote($data['payer_amount']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$data['orderid'];
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'worldpay'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		
		//code not tested
		//return data array
		$darray = array();
		$filter = JFilterInput::getInstance();
		foreach($_POST as $key => $value){
			$key = $filter->clean($key);
			if(preg_match("#^[0-9a-z_-]{1,30}$#i",$key)&&!preg_match("#^cmd$#i",$key)){
				$value = JRequest::getString($key);
				$darray[$key] = $value;
			}
		}
		if($darray['instId'] == $this->paymentconfig['instid_worldpay']){
			$data['orderid'] = $darray['cartId'];
			$data['payer_amount'] = $darray['amount'];
		}else{
			$data = false;
		}
		
		return $data;
	}
}
