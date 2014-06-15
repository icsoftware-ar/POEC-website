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

class JSJobseway
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
		switch($this->paymentconfig['countrycode_eway']){
			case 'UK':
				$posturlpart = "https://payment.ewaygateway.com/Request";
				$currencycode = "GBP";
			break;
			case 'NZ':
				$posturlpart = "https://nz.ewaygateway.com/Request";
				$currencycode = "AUD";
			break;
			case 'AS':
				$posturlpart = "https://au.ewaygateway.com/Request";
				$currencycode = "AUD";
			break;
		}
		// Eway payment in PHP
		$pathvalue=JURI::root().$this->paymentconfig['returnurl_eway'];

		$ewayurl.="?CustomerID=".$this->paymentconfig['customerid_eway'];
		$ewayurl.="&UserName=".$this->paymentconfig['username_eway'];
		$ewayurl.="&Amount=".number_format($order->paidamount,2);
		$ewayurl.="&Currency=".$currencycode;
		$ewayurl.="&PageTitle=".$order->packagetitle;
		$ewayurl.="&PageDescription=".$order->packagetitle;
		$ewayurl.="&PageFooter=".$_POST['PageFooter'];	
		$ewayurl.="&Language=".$this->paymentconfig['language_eway'];
		$ewayurl.="&CustomerFirstName=".$order->username;
		$ewayurl.="&CustomerEmail=".$order->useremail;
		$ewayurl.="&CancelURL=".$this->paymentconfig['cancelurl_eway'];
		$ewayurl.="&ReturnUrl=".JURI::root().$this->paymentconfig['returnurl_eway'];
		$ewayurl.="&MerchantReference=".$orderid;
		$ewayurl.="&MerchantInvoice=".$orderid;
			
		$spacereplace = str_replace(" ", "%20", $ewayurl);	
		$posturl=$posturlpart.$spacereplace;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $posturl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			
			if (CURL_PROXY_REQUIRED == 'True') 
			{
				$proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
				curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
				curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
				curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
			}
			
			$response = curl_exec($ch);
			
			function fetch_data($string, $start_tag, $end_tag)
			{
				$position = stripos($string, $start_tag);  
				$str = substr($string, $position);  		
				$str_second = substr($str, strlen($start_tag));  		
				$second_positon = stripos($str_second, $end_tag);  		
				$str_third = substr($str_second, 0, $second_positon);  		
				$fetch_data = trim($str_third);		
				return $fetch_data; 
			}
			
			
			$responsemode = fetch_data($response, '<result>', '</result>');
			$responseurl = fetch_data($response, '<uri>', '</uri>');
			if($responsemode=="True")
			{ 		
			  header("location: ".$responseurl);
			  exit;
			}
			else
			{
				//transaction not complete
				$msg = JText::_('TRANSACTION_ERROR');
				header("location: ".JURI::root());
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
		$query = "SELECT payment.* FROM `#__js_auto_paymentmethodconfig` AS payment WHERE payment.configfor = 'eway'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
				
		switch($this->paymentconfig['countrycode_eway']){
			case 'UK':$posturlpart = "https://payment.ewaygateway.com/Result/?";break;
			case 'NZ':$posturlpart = "https://nz.ewaygateway.com/Result/?";break;
			case 'AS':$posturlpart = "https://au.ewaygateway.com/Result/?";break;
		}
		$querystring="CustomerID=".$this->paymentconfig['customerid_eway']."&UserName=".$this->paymentconfig['username_eway']."&AccessPaymentCode=".$_REQUEST['AccessPaymentCode'];
		$posturl=$posturlpart.$querystring;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		if (CURL_PROXY_REQUIRED == 'True')
		{
			$proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
			curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
		}

		function fetch_data($string, $start_tag, $end_tag)
		{

			$position = stripos($string, $start_tag);
			$str = substr($string, $position);
			$str_second = substr($str, strlen($start_tag));
			$second_positon = stripos($str_second, $end_tag);
			$str_third = substr($str_second, 0, $second_positon);
			$fetch_data = trim($str_third);
			return $fetch_data;
		}

		$response = curl_exec($ch);
		$authecode = fetch_data($response, '<authCode>', '</authCode>');
		$responsecode = fetch_data($response, '<responsecode>', '</responsecode>');
		$retrunamount = fetch_data($response, '<returnamount>', '</returnamount>');
		$trxnnumber = fetch_data($response, '<trxnnumber>', '</trxnnumber>');
		$trxnstatus = fetch_data($response, '<trxnstatus>', '</trxnstatus>');
		$trxnresponsemessage = fetch_data($response, '<trxnresponsemessage>', '</trxnresponsemessage>');

		$merchantreference = fetch_data($response, '<merchantreference>', '</merchantreference>');
		$merchantinvoice = fetch_data($response, '<merchantinvoice>', '</merchantinvoice>');
		
		if($trxnstatus == "True" || $trxnstatus == true){
			$data['orderid'] = $merchantinvoice;
			$data['payer_amount'] = $retrunamount;
			$data['payer_tx_token'] = $trxnnumber;
		}else{
			$data = false;
		}
		
		return $data;
	}
	
}
