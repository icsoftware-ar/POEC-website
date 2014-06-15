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

class JSJobsmoneybookers
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
		$action = "https://ssl.ditonlinebetalingssystem.dk/popup/default.asp";
		
		if(!empty($order)){
			
		?>

		<form action="<?php echo $this->paymentconfig['paymenturl_moneybookers']; ?>" method="post" name="adminForm" >
		
			<input type='hidden' name='currency' value='<?php echo $this->paymentconfig['acceptedcurrency_moneybookers']; ?>' />
			<input type='hidden' name='amount' value='<?php echo $order->paidamount; ?>' />
			<input type='hidden' name='status_url' value='<?php echo $this->paymentconfig['notifyurl_monerybookers']; ?>' />
			<input type='hidden' name='transaction_id' value='<?php echo $orderid; ?>' />
			<input type='hidden' name='pay_from_email' value='<?php echo $order->useremail;?>' />
			<input type='hidden' name='pay_to_email' value='<?php echo $this->paymentconfig['merchantemail_moneybookers'];?>' />
			<input type='hidden' name='recipient_description' value='<?php echo $this->app->getCfg( 'sitename' );?>' />
			<input type='hidden' name='language' value='<?php echo $this->paymentconfig['language_moneybookers'];?>' />
			<input type='hidden' name='return_url' value='<?php echo $this->paymentconfig['returnurl_moneybookers'];?>' />
			<input type='hidden' name='cancel_url' value='<?php echo $this->paymentconfig['cancelurl_moneybookers'];?>' />
			<input type='hidden' name='detail1_description' value='<?php echo JText::_('SELLER_PACKAGE');?>' />
			<input type='hidden' name='detail1_text' value='<?php echo $order->packagetitle;?>' />
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'moneybookers'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		//code not tested
		$vars = array();
		$data = array();
		$filter = JFilterInput::getInstance();
		foreach($_POST as $key => $value){
			$key = $filter->clean($key);
			$value = JRequest::getString($key);
			$vars[$key]=$value;
		}

		$vars['calculated_md5sig']=strtoupper(md5(@$this->paymentconfig['merchantid_moneybookers'].@$vars['transaction_id'].strtoupper(md5($this->paymentconfig['secretword_moneybookers'])).@$vars['mb_amount'].@$vars['mb_currency'].@$vars['status']));


		if (@$vars['md5sig']==$vars['calculated_md5sig']) {
			$data['orderid'] = $vars['transaction_id'];
			$data['payer_amount'] = $vars['mb_amount'];
		}else{
			$data = false;
		}
		
		return $data;
	}
	
}
