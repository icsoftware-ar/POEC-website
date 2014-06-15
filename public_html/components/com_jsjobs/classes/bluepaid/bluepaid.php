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

class JSJobsbluepaid
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
		$action = $this->paymentconfig['paymenturl_bluepaid'];
		
		if(!empty($order)){
		?>

		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
		
			<input type='hidden' name='devise' value='<?php echo $this->paymentconfig['currencycode_bluepaid']; ?>' />
			<input type='hidden' name='divers' value='<?php echo $orderid; ?>' />
			<input type='hidden' name='montant' value='<?php echo $order->paidamount; ?>' />
			<input type='hidden' name='id_boutique' value='<?php echo $this->paymentconfig['bluepaidid_bluepaid'];?>' />
			<input type='hidden' name='langue' value='<?php echo $this->paymentconfig['language_bluepaid'];?>' />
			<input type='hidden' name='email_client' value='<?php echo $order->useremail; ?>' />
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'bluepaid'";
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
		if(in_array($darray['etat'],array("attente","ok"))) {
			$data['orderid'] = $darray['divers'];
			$data['payer_amount'] = $darray['montant'];
		}else{
			$data = false;
		}
		
		return $data;
	}
}
