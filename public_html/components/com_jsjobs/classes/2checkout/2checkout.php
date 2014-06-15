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

class JSJobs2checkout
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
		$action = "https://www.2checkout.com/checkout/purchase";
		
		if(!empty($order)){
		?>
		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
		
			<input type='hidden' name='sid' value='<?php echo $this->paymentconfig['sid_2checkout']; ?>' />
			<input type='hidden' name='mode' value='2CO' />
			<input type='hidden' name='li_0_type' value='product' />
			<input type='hidden' name='li_0_name' value='<?php echo $order->packagetitle;?>' />
			<input type='hidden' name='li_0_price' value='<?php echo $order->paidamount;?>' />
			<input type='hidden' name='x_receipt_link_url' value='<?php echo $this->paymentconfig['notifyurl_2checkout']."&fr=".$orderid;?>' />
		<?php if($this->paymentconfig['demo_2checkout'] == 1) { ?>
			<input type='hidden' name='demo' value='Y' />
		<?php } ?>
			<input type='hidden' name='currency_code' value='<?php echo $this->paymentconfig['currencycode_2checkout']; ?>' />
			<input type='hidden' name='lang' value='<?php echo $this->paymentconfig['language_2checkout']; ?>' />
			<input type='hidden' name='merchant_order_id' value='<?php echo $orderid; ?>' />
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
			return $result;
		}else $this->app->redirect(JRoute::_ ('index.php?option=com_jsjobs&view=employer&layout=norecordfound'));
	}
	function loadConfig(){
		$db = JFactory::getDBO();
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = '2checkout'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		//code not tested
		$darray = array();
		foreach($_POST as $key => $value){
			$key = $this->clean($key);
			$darray[$key] = $this->clean($value);
		}
		$data['orderid'] = $darray['merchant_order_id'];
		$data['payer_amount'] = $darray['total'];
		$data['payer_itemname'] = $darray['li_0_name'];
		$data['payer_email'] = $darray['email'];
		$data['payer_firstname'] = $darray['first_name'];
		$data['payer_lastname'] = $darray['last_name'];
		$data['payer_tx_token'] = $darray['invoice_id'];
		
		return $data;
	}
	function clean($arg){
		$result =  trim(stripslashes(htmlentities($arg, ENT_QUOTES)));
		return $result;
	}
	
}
