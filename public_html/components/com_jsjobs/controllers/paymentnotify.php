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

class JSJobsControllerPaymentnotify extends JControllerLegacy
{

	function __construct()
	{
		$classobject = '';
		parent :: __construct();
	}
	function onorder(){
		$orderfor = JRequest::getVar('for','');
		$orderid = JRequest::getVar('orderid','');
		$packagefor = JRequest::getVar('packagefor',''); //1 for employer 2 for jobseeker 
		if(($orderfor != '') && ($orderid != '')){
			$this->loadClass($orderfor);
			$this->classobject->generateRequest($orderid,$packagefor);
		}else{
			echo 'no order found';exit;
		}
	}
	function paymentnotify(){
		
		$paymentfor = JRequest::getVar('for','');
		if($paymentfor != ''){
			$this->loadClass($paymentfor);
			$this->classobject->onPaymentNotification();
		}
		die();
	}
	
	function loadClass($for){
		$classpath = JPATH_BASE."/components/com_jsjobs/classes/".$for."/".$for.".php";
		include_once($classpath);
		switch($for){
			case 'payza':$this->classobject = new JSJobspayza;break;
			case 'alipay':$this->classobject = new JSJobsalipay;break;
			case 'authorize':$this->classobject = new JSJobsauthorize;break;
			case 'worldpay':$this->classobject = new JSJobsworldpay;break;
			case 'bluepaid':$this->classobject = new JSJobsbluepaid;break;
			case 'epay':$this->classobject = new JSJobsepay;break;
			case 'eway':$this->classobject = new JSJobseway;break;
			case 'googlecheckout':$this->classobject = new JSJobsgooglecheckout;break;
			case 'hsbc':$this->classobject = new JSJobshsbc;break;
			case 'moneybookers':$this->classobject = new JSJobsmoneybookers;break;
			case 'paypal':$this->classobject = new JSJobspaypal;break;
			case 'sagepay':$this->classobject = new JSJobssagepay;break;
			case 'westernunion':$this->classobject = new JSJobswesternunion;break;
			case '2checkout':$this->classobject = new JSJobs2checkout;break;
			case 'fastspring':$this->classobject = new JSJobsfastspring;break;
			case 'avangate':$this->classobject = new JSJobsavangate;break;
			case 'pagseguro':$this->classobject = new JSJobspagseguro;break;
			case 'payfast':$this->classobject = new JSJobspayfast;break;
			case 'ideal':$this->classobject = new JSJobsideal;break;
			case 'others':$this->classobject = new JSJobsothers;break;
		}
	}

}
?>
