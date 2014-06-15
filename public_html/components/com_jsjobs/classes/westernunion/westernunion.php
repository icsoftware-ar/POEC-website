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

class JSJobswesternunion
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
			$text = JText::_('JS_ORDER_IS_COMPLETE').'<br/>'.
			JText::_('JS_PLEASE_TRANSFERT_MONEY')." ".$order->currencysymbol." ".$order->paidamount.'<br/>';
			if($this->paymentconfig['showaccountinfo_westernunion'] == 1) $text .= JText::_('JS_ACCOUNT_INFO')." ".$this->paymentconfig['accountinfo_westernunion'].'<br/>';
			if($this->paymentconfig['showname_westernunion'] == 1) $text .= JText::_('JS_NAME')." ".$this->paymentconfig['name_westernunion'].'<br/>';
			if($this->paymentconfig['showcountryname_westernunion'] == 1) $text .= JText::_('JS_COUNTRY_NAME')." ".$this->paymentconfig['countryname_westernunion'].'<br/>';
			if($this->paymentconfig['showcityname_westernunion'] == 1) $text .= JText::_('JS_CITY_NAME')." ".$this->paymentconfig['cityname_westernunion'].'<br/>';
			$text .= JText::_('JS_SEND_THE_MTCN_NUMBER_TO_EMAIL')." ".$this->paymentconfig['emailaddress_westernunion'].'<br/>';
			
			$text .= JText::_('JS_INCLUDE_ORDER_NUMBER_TO_TRANSFER')." ".$order->id.'<br/>'. 
			JText::_('JS_THANK_YOU_FOR_PURCHASE');
			echo $text;
		}else{
		}
	}

	function onPaymentNotification(){
		
		$data = $this->getData();
		if($data != false){
			$date = date("Y-m-d H:i:s");
			$db = JFactory::getDBO();
			$query = "UPDATE `#__js_job_paymenthistory` AS payment SET payment.payer_email = ".$db->quote($data['payer_email']).", payment.payer_firstname = ".$db->quote($data['payer_firstname']).", payment.payer_lastname = ".$db->quote($data['payer_lastname']).", payment.payer_amount = ".$db->quote($data['payer_amount']).", payment.payer_itemname = ".$db->quote($data['payer_itemname']).", payment.payer_itemname2 = ".$db->quote($data['payer_itemname2']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$data['orderid'];
			$db->setQuery($query);
			if(!$db->query()) return false;
		}
		return true;
	}
	function getOrderById($orderid){
		if(!is_numeric($orderid)) return false;
		$db = JFactory::getDBO();
		$query = "SELECT pro_order.*,currency.code AS currencycode,currency.symbol AS currencysymbol
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'westernunion'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
}
