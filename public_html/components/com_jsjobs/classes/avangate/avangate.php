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

class JSJobsavangate
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
		$avangatelink = $orderresult[1]->paymentlink;
		$action = $avangatelink."&REF=".$orderid;
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
			$query = "UPDATE `#__js_job_sellerpaymenthistory` AS payment SET payment.payer_amount = ".$db->quote($data['payer_amount']).", payment.payer_itemname = ".$db->quote($data['payer_itemname']).", payment.payer_email = ".$db->quote($data['payer_email']).", payment.payer_firstname = ".$db->quote($data['payer_firstname']).", payment.payer_lastname = ".$db->quote($data['payer_lastname']).", payment.payer_tx_token = ".$db->quote($data['payer_tx_token']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$data['orderid'];
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
			$avangatelink = $this->getPaymentMethodLinkByPackageid($result->packageid,$result->packagefor);
			$return[0] = $result;
			$return[1] = $avangatelink;
			return $return;
		}else $this->app->redirect(JRoute::_ ('index.php?option=com_jsjobs&view=employer&layout=norecordfound'));
	}
	function getPaymentMethodLinkByPackageid($packageid,$packagefor){
		$db = JFactory::getDBO();
		$query = "SELECT paymentlink.link AS paymentlink
					FROM `#__js_job_paymentmethodlinks` AS paymentlink
					JOIN `#__js_job_paymentmethods` AS paymentmethod ON paymentmethod.id = paymentlink.paymentmethodid
					WHERE paymentmethod.paymentmethod = 'avangate' AND paymentlink.packageid = ".$packageid." AND paymentlink.packagefor=".$packagefor;
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
	function loadConfig(){
		$db = JFactory::getDBO();
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'avangate'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		//code not tested

		// avangate
		$pass		= $this->paymentconfig['privatekey_avangate'];	/* pass to compute HASH */
		$result		= ""; 				/* string for compute HASH for received data */
		$return		= ""; 				/* string to compute HASH for return result */
		$signature	= $_POST["HASH"];	/* HASH received */
		$body		= "";

		ob_start();
		while(list($key, $val) = each($_POST)){
			$$key=$val;

			/* get values */
			if($key != "HASH"){

				if(is_array($val)) $result .= ArrayExpand($val);
				else{
					$size		= strlen(StripSlashes($val));
					$result	.= $size.StripSlashes($val);
				}

			}

		}
		$body = ob_get_contents();
		ob_end_flush();

		$return = strlen($_POST["IPN_PID"][0]).$_POST["IPN_PID"][0].strlen($_POST["IPN_PNAME"][0]).$_POST["IPN_PNAME"][0];
		$return .= strlen($_POST["IPN_DATE"]).$_POST["IPN_DATE"].strlen($date_return).$date_return;

		function ArrayExpand($array){
			$retval = "";
			for($i = 0; $i < sizeof($array); $i++){
				$size		= strlen(StripSlashes($array[$i]));
				$retval	.= $size.StripSlashes($array[$i]);
			}

			return $retval;
		}

		function hmac ($key, $data){
		   $b = 64; // byte length for md5
		   if (strlen($key) > $b) {
			   $key = pack("H*",md5($key));
		   }
		   $key  = str_pad($key, $b, chr(0x00));
		   $ipad = str_pad('', $b, chr(0x36));
		   $opad = str_pad('', $b, chr(0x5c));
		   $k_ipad = $key ^ $ipad ;
		   $k_opad = $key ^ $opad;
		   return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
		}

		$hash =  hmac($pass, $result); /* HASH for data received */

		$body .= $result."\r\n\r\nHash: ".$hash."\r\n\r\nSignature: ".$signature."\r\n\r\nReturnSTR: ".$return;

		if($hash == $signature){
			echo "Verified OK!";

			/* ePayment response */
			$result_hash =  hmac($pass, $return);
			echo "<EPAYMENT>".$date_return."|".$result_hash."</EPAYMENT>";
			/* Begin automated procedures (START YOUR CODE)*/

			
			$data['payer_tx_token'] = $_POST["REFNO"];
			$data['orderid'] = $_POST["REFNOEXT"];// reference id or orderid
			$data['payer_firstname'] = $_POST["FIRSTNAME"];
			$data['payer_lastname'] = $_POST["LASTNAME"];

			$data['payer_email'] = $_POST["CUSTOMEREMAIL"];

			$data['payer_itemname'] = $_POST["IPN_PNAME"][0];
			$data['payer_amount'] = $_POST["IPN_TOTAL"][0];

		}else{
			$data = false;
		}
		return $data;
	}
	
}
