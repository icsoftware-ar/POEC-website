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

class JSJobsideal
{
	Private $paymentconfig = '';
	Private $app = '';
	Private $_partner_id = '';
	function __construct()
	{
		$this->paymentconfig = $this->loadConfig();
		$this->app = JFactory::getApplication();
		$helperclasspath = "components/com_jsjobs/classes/ideal/Payment.php";
		include_once($helperclasspath);
	}
	function generateRequest($orderid){
		$order = $this->getOrderById($orderid);
				
		if(!empty($order)){
			$amount=$order->packageprice;
			$description=$order->packagedescription;
			$return_url=$this->paymentconfig['returnurl_ideal'];
			$notifyurl = JURI::root().$this->paymentconfig['notifyurl_ideal'];
			$partner_id = $this->paymentconfig['partnerid_ideal'];
			$this->_partner_id=$partner_id;
			$testmode = $this->paymentconfig['testmode_ideal'];
			$iDEAL = new Mollie_iDEAL_Payment ($partner_id);
			
			if($testmode==1) $iDEAL->setTestMode();
			if (isset($_POST['bank_id']) and !empty($_POST['bank_id'])) 
			{
				if ($iDEAL->createPayment($_POST['bank_id'], $amount, $description, $return_url, $report_url)) 
				{
					
					header("Location: " . $iDEAL->getBankURL());
					exit;	
				}
				else 
				{
					$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=package_buynow&pb=1&gd='.$orderid;
					$msg=htmlspecialchars($iDEAL->getErrorMessage());
					$this->app->redirect(JRoute::_ ($link,$msg));					
				}
			}


		?>
<?php
		}else{
			die('Access Denied');
		}
	}
	function onPaymentNotification(){
		
		if (isset($_GET['transaction_id'])) {
			
			$iDEAL = new Mollie_iDEAL_Payment ($this->_partner_id);
			
			$transaction_id=$iDEAL->checkPayment($_GET['transaction_id']);
			$consumer=$iDEAL->getConsumerInfo(); 
			$paidamount=$iDEAL->getAmount();
			$isverified=$iDEAL->getPaidStatus();
			if ($iDEAL->getPaidStatus())
			{
				$date = date("Y-m-d H:i:s");
				$db = JFactory::getDBO();
				$query = "UPDATE `#__js_job_paymenthistory` AS payment SET payment.payer_amount = ".$db->quote($paidamount).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$transaction_id;
				$db->setQuery($query);
				if(!$db->query()) return false;
				return true;
			}else {
				return false;   
			}
		}else{
				return false;   
		}	
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'ideal'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
}
