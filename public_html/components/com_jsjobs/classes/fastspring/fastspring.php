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

class JSJobsfastspring
{
	Private $paymentconfig = '';
	Private $app = '';
	function __construct()
	{
		$this->paymentconfig = $this->loadConfig();
		$this->app = JFactory::getApplication();
	}
	function generateRequest($orderid){
		$orderresult = $this->getOrderById($orderid);
		$order = $orderresult[0];
		$fastspringlink = $orderresult[1]->paymentlink;
		$action = $fastspringlink."&referrer=".$orderid;
		if(!empty($order)){
		?>
		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
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
			$query = "UPDATE `#__js_job_paymenthistory` AS payment SET payment.payer_amount = ".$db->quote($data['payer_amount']).", payment.payer_itemname = ".$db->quote($data['payer_itemname']).", payment.payer_email = ".$db->quote($data['payer_email']).", payment.payer_firstname = ".$db->quote($data['payer_firstname']).", payment.payer_lastname = ".$db->quote($data['payer_lastname']).", payment.payer_tx_token = ".$db->quote($data['payer_tx_token']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$data['orderid'];
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
			$fastspringlink = $this->getPaymentMethodLinkByPackageid($result->packageid,$result->packagefor);
			$return[0] = $result;
			$return[1] = $fastspringlink;
			return $return;
		}else $this->app->redirect(JRoute::_ ('index.php?option=com_jsjobs&view=employer&layout=norecordfound'));
	}
	function getPaymentMethodLinkByPackageid($packageid,$packagefor){
		$db = JFactory::getDBO();
		$query = "SELECT paymentlink.link AS paymentlink
					FROM `#__js_auto_paymentmethodlinks` AS paymentlink
					JOIN `#__js_auto_paymentmethods` AS paymentmethod ON paymentmethod.id = paymentlink.paymentmethodid
					WHERE paymentmethod.paymentmethod = 'fastspring' AND paymentlink.packageid = ".$packageid." AND paymentlink.packagefor = ".$packagefor;
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
	function loadConfig(){
		$db = JFactory::getDBO();
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'fastspring'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		//code not tested

		if (md5($_REQUEST['security_data'] . $this->paymentconfig['privatekey_fastspring']) != $_REQUEST['security_hash']){
			/* FAILED CHECK */  
			$data = false;
		}else{
			$data['payer_email'] = $_POST['CustomerEmail'];
			$data['payer_tx_token'] = $_POST['OrderReference'];
			$data['payer_itemname'] = $_POST['OrderItemProductName'];
			$data['payer_firstname'] = $_POST['CustomerFirstName'];
			$data['payer_lastname'] = $_POST['CustomerLastName'];
			$data['orderid'] = $_POST['OrderReferrer']; // reference id or orderid
			$data['payer_amount'] = $_POST['OrderTotal'];
		}
		return $data;
	}
	
}
