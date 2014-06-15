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

class JSJobshsbc
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
		$debug = $this->paymentconfig['testmode_hsbc']?'T':'P';			
		?>

		<form action="https://www.cpi.hsbc.com/servlet" method="post" name="adminForm" >
		

		<input type="hidden" name="ShopperEmail" value="<?php echo substr($order->useremail, 0, 30);?>" />
		<input type="hidden" name="UserId" value="<?php echo $order->uid;?>" />
		<input type="hidden" name="TimeStamp" value="<?php echo time() . '000';?>" />
		<input type="hidden" name="StorefrontId" value="<?php echo $this->paymentconfig['merchantid_hsbc'];?>" />
		<input type="hidden" name="PurchaseCurrency" value="<?php echo $this->paymentconfig['acceptedcurrencycode_hsbc'];?>" />
		<input type="hidden" name="PurchaseAmount" value="<?php echo $order->paidamount;?>" />
		<input type="hidden" name="TransactionType" value="<?php echo $this->paymentconfig['instantcapture_hsbc']?'Capture':'Auth';?>" />
		<input type="hidden" name="OrderId" value="<?php echo $order->id;?>" />
		<input type="hidden" name="OrderDesc" value="<?php echo $order->packagetitle; ?>" />
		<input type="hidden" name="Mode" value="<?php echo $debug; ?>" />
		<input type="hidden" name="CpiDirectResultUrl" value="<?php echo $this->paymentconfig['notifyurl_hsbc']; ?>" />
		<input type="hidden" name="CpiReturnUrl" value="<?php echo $this->paymentconfig['returnurl_hsbc']; ?>" />
		<input type="hidden" name="OrderHash" value="<?php echo $this->generate($vars, $this->paymentconfig['cpihash_hsbc']); ?>" />

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
		$query = "SELECT pro_order.*,currency.code AS currencycode,user.email AS useremail
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'hsbc'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){

		//code not tested
		$finalReturn = isset($_GET['hsbc_return']);
		$app =& JFactory::getApplication();
		$error_url = JURI::root();

		$vars = array(
					'CpiResultsCode' => @$_POST['CpiResultsCode'],
					'PurchaseDate' => @$_POST['PurchaseDate'],
					'MerchantData' => @$_POST['MerchantData'],
					'OrderId' => @$_POST['OrderId'],
					'PurchaseAmount' => @$_POST['PurchaseAmount'],
					'PurchaseCurrency' => @$_POST['PurchaseCurrency'],
					'ShopperEmail' => @$_POST['ShopperEmail'],
					'StorefrontId' => @$_POST['StorefrontId']
				);

		if( empty($_POST['OrderHash']) ) {
			if($finalReturn) {
				$app->enqueueMessage(JText::sprintf('NOTIFICATION_REFUSED_FOR_THE_ORDER','HSBC'),'Invalid Hash');
				$app->redirect($error_url);
			}
			return false;
		}

		if( $_POST['StorefrontId'] != $this->paymentconfig['merchantid_hsbc'] ) {
			if($finalReturn) {
				$app->enqueueMessage(JText::sprintf('NOTIFICATION_REFUSED_FOR_THE_ORDER','HSBC'),'Invalid store id');
				$app->redirect($error_url);
			}
			return false;
		}

		if( $_POST['OrderHash'] != $this->generate($vars, $this->paymentconfig['cpihash_hsbc']) ) {
			if($finalReturn) {
				$app->enqueueMessage(JText::sprintf('NOTIFICATION_REFUSED_FOR_THE_ORDER','HSBC'),'Invalid processed Hash');
				$app->redirect($error_url);
			}
			return false;
		}

		$data['orderid'] = $vars['OrderId'];
		$data['payer_amount'] = $vars['PurchaseAmount'].$vars['PurchaseCurrency'];

		return $data;
	}


	function crypt($d,$k) {
		if( function_exists('mcrypt_module_open') && defined('MCRYPT_DES' ) ) {
			$module = mcrypt_module_open (MCRYPT_DES, '', MCRYPT_MODE_CBC, '');
			$ks = mcrypt_enc_get_key_size($module);
			$key = substr($k, 0, $ks);
			mcrypt_generic_init($module, $key, $this->iv);
			$ret = mcrypt_generic($module, $d);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
			return trim($ret);
		}

		return $this->crypt_DES(substr($k,0,8), $d, 1, 1, $this->iv, 0);
	}

	function decrypt($d,$k) {
		if( function_exists('mcrypt_module_open') && defined('MCRYPT_DES' ) ) {
			$module = mcrypt_module_open (MCRYPT_DES, '', MCRYPT_MODE_CBC, '');
			$size = mcrypt_enc_get_key_size($module);
			$key = substr($k, 0, $size);
			mcrypt_generic_init($module, $key, $this->iv);
			$ret = mdecrypt_generic($module, $d);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
			while( strlen($ret) > 0 && $ret[strlen($ret)-1] == "\4" ) {
				$ret = substr($ret, 0, -1);
			}
			return trim($ret);
		}

		$r =& $this->crypt_DES(substr($k,0,8), $d, 0, 1, $this->iv, 0);
		while( strlen($r) > 0 && $r[strlen($r)-1] == "\4" ) {
			$r = substr($r, 0, -1);
		}
		return $r;
	}

	function generate($data, $key) {
		if( (!is_array($data) && empty($data)) || empty($key) )
			return null;

		if( is_array($data) ) {
			asort($data,SORT_STRING);
			$data = implode($data);
		}

		$a = $this->decrypt(base64_decode($key), 'wsx1WSCOU/1LIPzFBNbR9QtTF2XmOUfRs4hGBBARAgAG');
		return base64_encode( $this->sha($data.$a, $a) );
	}

	function sha($data,$key) {
		if( function_exists('mhash') ) {
			return mhash(MHASH_SHA1, $data, $key);
		}

		if( !function_exists('sha1') ) {
			die('SHA1 function is not present');
		}
		if (strlen($key)>64)
			$key = pack('H*', sha1($key));
		$key = str_pad($key,64,chr(0x00));
		$ipad = str_repeat(chr(0x36),64);
		$opad = str_repeat(chr(0x5c),64);
		return pack('H*', sha1( ($key ^ $opad) . pack('H*', sha1(($key ^ $ipad) . $data)) ));
	}

	function crypt_DES($key, $message, $encrypt, $mode, $iv, $padding) {
		$spfunction1 = array (0x1010400,0,0x10000,0x1010404,0x1010004,0x10404,0x4,0x10000,0x400,0x1010400,0x1010404,0x400,0x1000404,0x1010004,0x1000000,0x4,0x404,0x1000400,0x1000400,0x10400,0x10400,0x1010000,0x1010000,0x1000404,0x10004,0x1000004,0x1000004,0x10004,0,0x404,0x10404,0x1000000,0x10000,0x1010404,0x4,0x1010000,0x1010400,0x1000000,0x1000000,0x400,0x1010004,0x10000,0x10400,0x1000004,0x400,0x4,0x1000404,0x10404,0x1010404,0x10004,0x1010000,0x1000404,0x1000004,0x404,0x10404,0x1010400,0x404,0x1000400,0x1000400,0,0x10004,0x10400,0,0x1010004);
		$spfunction2 = array (-0x7fef7fe0,-0x7fff8000,0x8000,0x108020,0x100000,0x20,-0x7fefffe0,-0x7fff7fe0,-0x7fffffe0,-0x7fef7fe0,-0x7fef8000,-0x80000000,-0x7fff8000,0x100000,0x20,-0x7fefffe0,0x108000,0x100020,-0x7fff7fe0,0,-0x80000000,0x8000,0x108020,-0x7ff00000,0x100020,-0x7fffffe0,0,0x108000,0x8020,-0x7fef8000,-0x7ff00000,0x8020,0,0x108020,-0x7fefffe0,0x100000,-0x7fff7fe0,-0x7ff00000,-0x7fef8000,0x8000,-0x7ff00000,-0x7fff8000,0x20,-0x7fef7fe0,0x108020,0x20,0x8000,-0x80000000,0x8020,-0x7fef8000,0x100000,-0x7fffffe0,0x100020,-0x7fff7fe0,-0x7fffffe0,0x100020,0x108000,0,-0x7fff8000,0x8020,-0x80000000,-0x7fefffe0,-0x7fef7fe0,0x108000);
		$spfunction3 = array (0x208,0x8020200,0,0x8020008,0x8000200,0,0x20208,0x8000200,0x20008,0x8000008,0x8000008,0x20000,0x8020208,0x20008,0x8020000,0x208,0x8000000,0x8,0x8020200,0x200,0x20200,0x8020000,0x8020008,0x20208,0x8000208,0x20200,0x20000,0x8000208,0x8,0x8020208,0x200,0x8000000,0x8020200,0x8000000,0x20008,0x208,0x20000,0x8020200,0x8000200,0,0x200,0x20008,0x8020208,0x8000200,0x8000008,0x200,0,0x8020008,0x8000208,0x20000,0x8000000,0x8020208,0x8,0x20208,0x20200,0x8000008,0x8020000,0x8000208,0x208,0x8020000,0x20208,0x8,0x8020008,0x20200);
		$spfunction4 = array (0x802001,0x2081,0x2081,0x80,0x802080,0x800081,0x800001,0x2001,0,0x802000,0x802000,0x802081,0x81,0,0x800080,0x800001,0x1,0x2000,0x800000,0x802001,0x80,0x800000,0x2001,0x2080,0x800081,0x1,0x2080,0x800080,0x2000,0x802080,0x802081,0x81,0x800080,0x800001,0x802000,0x802081,0x81,0,0,0x802000,0x2080,0x800080,0x800081,0x1,0x802001,0x2081,0x2081,0x80,0x802081,0x81,0x1,0x2000,0x800001,0x2001,0x802080,0x800081,0x2001,0x2080,0x800000,0x802001,0x80,0x800000,0x2000,0x802080);
		$spfunction5 = array (0x100,0x2080100,0x2080000,0x42000100,0x80000,0x100,0x40000000,0x2080000,0x40080100,0x80000,0x2000100,0x40080100,0x42000100,0x42080000,0x80100,0x40000000,0x2000000,0x40080000,0x40080000,0,0x40000100,0x42080100,0x42080100,0x2000100,0x42080000,0x40000100,0,0x42000000,0x2080100,0x2000000,0x42000000,0x80100,0x80000,0x42000100,0x100,0x2000000,0x40000000,0x2080000,0x42000100,0x40080100,0x2000100,0x40000000,0x42080000,0x2080100,0x40080100,0x100,0x2000000,0x42080000,0x42080100,0x80100,0x42000000,0x42080100,0x2080000,0,0x40080000,0x42000000,0x80100,0x2000100,0x40000100,0x80000,0,0x40080000,0x2080100,0x40000100);
		$spfunction6 = array (0x20000010,0x20400000,0x4000,0x20404010,0x20400000,0x10,0x20404010,0x400000,0x20004000,0x404010,0x400000,0x20000010,0x400010,0x20004000,0x20000000,0x4010,0,0x400010,0x20004010,0x4000,0x404000,0x20004010,0x10,0x20400010,0x20400010,0,0x404010,0x20404000,0x4010,0x404000,0x20404000,0x20000000,0x20004000,0x10,0x20400010,0x404000,0x20404010,0x400000,0x4010,0x20000010,0x400000,0x20004000,0x20000000,0x4010,0x20000010,0x20404010,0x404000,0x20400000,0x404010,0x20404000,0,0x20400010,0x10,0x4000,0x20400000,0x404010,0x4000,0x400010,0x20004010,0,0x20404000,0x20000000,0x400010,0x20004010);
		$spfunction7 = array (0x200000,0x4200002,0x4000802,0,0x800,0x4000802,0x200802,0x4200800,0x4200802,0x200000,0,0x4000002,0x2,0x4000000,0x4200002,0x802,0x4000800,0x200802,0x200002,0x4000800,0x4000002,0x4200000,0x4200800,0x200002,0x4200000,0x800,0x802,0x4200802,0x200800,0x2,0x4000000,0x200800,0x4000000,0x200800,0x200000,0x4000802,0x4000802,0x4200002,0x4200002,0x2,0x200002,0x4000000,0x4000800,0x200000,0x4200800,0x802,0x200802,0x4200800,0x802,0x4000002,0x4200802,0x4200000,0x200800,0,0x2,0x4200802,0,0x200802,0x4200000,0x800,0x4000002,0x4000800,0x800,0x200002);
		$spfunction8 = array (0x10001040,0x1000,0x40000,0x10041040,0x10000000,0x10001040,0x40,0x10000000,0x40040,0x10040000,0x10041040,0x41000,0x10041000,0x41040,0x1000,0x40,0x10040000,0x10000040,0x10001000,0x1040,0x41000,0x40040,0x10040040,0x10041000,0x1040,0,0,0x10040040,0x10000040,0x10001000,0x41040,0x40000,0x41040,0x40000,0x10041000,0x1000,0x40,0x10040040,0x1000,0x41040,0x10001000,0x40,0x10000040,0x10040000,0x10040040,0x10000000,0x40000,0x10001040,0,0x10041040,0x40040,0x10000040,0x10040000,0x10001000,0x10001040,0,0x10041040,0x41000,0x41000,0x1040,0x1040,0x40040,0x10000000,0x10041000);
		$masks = array (4294967295,2147483647,1073741823,536870911,268435455,134217727,67108863,33554431,16777215,8388607,4194303,2097151,1048575,524287,262143,131071,65535,32767,16383,8191,4095,2047,1023,511,255,127,63,31,15,7,3,1,0);

		$keys = $this->des_createKeysx ($key);
		$m=0;
		$len = strlen($message);
		$chunk = 0;
		$iterations = ((count($keys) == 32) ? 3 : 9); //single or triple des
		if ($iterations == 3) {$looping = (($encrypt) ? array (0, 32, 2) : array (30, -2, -2));}
		else {$looping = (($encrypt) ? array (0, 32, 2, 62, 30, -2, 64, 96, 2) : array (94, 62, -2, 32, 64, 2, 30, -2, -2));}

		if ($padding == 2) $message .= "        "; //pad the message with spaces
		else if ($padding == 1) {$temp = chr (8-($len%8)); $message .= $temp . $temp . $temp . $temp . $temp . $temp . $temp . $temp; if ($temp==8) $len+=8;} //PKCS7 padding
		else if (!$padding) $message .= (chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0)); //pad the message out with null bytes

		$result = "";
		$tempresult = "";

		if ($mode == 1) { //CBC mode
			$cbcleft = (ord($iv{$m++}) << 24) | (ord($iv{$m++}) << 16) | (ord($iv{$m++}) << 8) | ord($iv{$m++});
			$cbcright = (ord($iv{$m++}) << 24) | (ord($iv{$m++}) << 16) | (ord($iv{$m++}) << 8) | ord($iv{$m++});
			$m=0;
		}

		while ($m < $len) {
			$left = (ord($message{$m++}) << 24) | (ord($message{$m++}) << 16) | (ord($message{$m++}) << 8) | ord($message{$m++});
			$right = (ord($message{$m++}) << 24) | (ord($message{$m++}) << 16) | (ord($message{$m++}) << 8) | ord($message{$m++});

			if ($mode == 1) {if ($encrypt) {$left ^= $cbcleft; $right ^= $cbcright;} else {$cbcleft2 = $cbcleft; $cbcright2 = $cbcright; $cbcleft = $left; $cbcright = $right;}}

			$temp = (($left >> 4 & $masks[4]) ^ $right) & 0x0f0f0f0f; $right ^= $temp; $left ^= ($temp << 4);
			$temp = (($left >> 16 & $masks[16]) ^ $right) & 0x0000ffff; $right ^= $temp; $left ^= ($temp << 16);
			$temp = (($right >> 2 & $masks[2]) ^ $left) & 0x33333333; $left ^= $temp; $right ^= ($temp << 2);
			$temp = (($right >> 8 & $masks[8]) ^ $left) & 0x00ff00ff; $left ^= $temp; $right ^= ($temp << 8);
			$temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);

			$left = (($left << 1) | ($left >> 31 & $masks[31]));
			$right = (($right << 1) | ($right >> 31 & $masks[31]));

			for ($j=0; $j<$iterations; $j+=3) {
				$endloop = $looping[$j+1];
				$loopinc = $looping[$j+2];
				for ($i=$looping[$j]; $i!=$endloop; $i+=$loopinc) { //for efficiency
				$right1 = $right ^ $keys[$i];
				$right2 = (($right >> 4 & $masks[4]) | ($right << 28 & 0xffffffff)) ^ $keys[$i+1];
				$temp = $left;
				$left = $right;
				$right = $temp ^ ($spfunction2[($right1 >> 24 & $masks[24]) & 0x3f] | $spfunction4[($right1 >> 16 & $masks[16]) & 0x3f]
						| $spfunction6[($right1 >>  8 & $masks[8]) & 0x3f] | $spfunction8[$right1 & 0x3f]
						| $spfunction1[($right2 >> 24 & $masks[24]) & 0x3f] | $spfunction3[($right2 >> 16 & $masks[16]) & 0x3f]
						| $spfunction5[($right2 >>  8 & $masks[8]) & 0x3f] | $spfunction7[$right2 & 0x3f]);
				}
				$temp = $left; $left = $right; $right = $temp; //unreverse left and right
			} //for either 1 or 3 iterations

			$left = (($left >> 1 & $masks[1]) | ($left << 31));
			$right = (($right >> 1 & $masks[1]) | ($right << 31));

			$temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);
			$temp = (($right >> 8 & $masks[8]) ^ $left) & 0x00ff00ff; $left ^= $temp; $right ^= ($temp << 8);
			$temp = (($right >> 2 & $masks[2]) ^ $left) & 0x33333333; $left ^= $temp; $right ^= ($temp << 2);
			$temp = (($left >> 16 & $masks[16]) ^ $right) & 0x0000ffff; $right ^= $temp; $left ^= ($temp << 16);
			$temp = (($left >> 4 & $masks[4]) ^ $right) & 0x0f0f0f0f; $right ^= $temp; $left ^= ($temp << 4);

			if ($mode == 1) {if ($encrypt) {$cbcleft = $left; $cbcright = $right;} else {$left ^= $cbcleft2; $right ^= $cbcright2;}}
			$tempresult .= (chr($left>>24 & $masks[24]) . chr(($left>>16 & $masks[16]) & 0xff) . chr(($left>>8 & $masks[8]) & 0xff) . chr($left & 0xff) . chr($right>>24 & $masks[24]) . chr(($right>>16 & $masks[16]) & 0xff) . chr(($right>>8 & $masks[8]) & 0xff) . chr($right & 0xff));

			$chunk += 8;
			if ($chunk == 512) {$result .= $tempresult; $tempresult = ""; $chunk = 0;}
		}

		return ($result . $tempresult);
	}

	function des_createKeysx ($key) {
		$pc2bytes0  = array (0,0x4,0x20000000,0x20000004,0x10000,0x10004,0x20010000,0x20010004,0x200,0x204,0x20000200,0x20000204,0x10200,0x10204,0x20010200,0x20010204);
		$pc2bytes1  = array (0,0x1,0x100000,0x100001,0x4000000,0x4000001,0x4100000,0x4100001,0x100,0x101,0x100100,0x100101,0x4000100,0x4000101,0x4100100,0x4100101);
		$pc2bytes2  = array (0,0x8,0x800,0x808,0x1000000,0x1000008,0x1000800,0x1000808,0,0x8,0x800,0x808,0x1000000,0x1000008,0x1000800,0x1000808);
		$pc2bytes3  = array (0,0x200000,0x8000000,0x8200000,0x2000,0x202000,0x8002000,0x8202000,0x20000,0x220000,0x8020000,0x8220000,0x22000,0x222000,0x8022000,0x8222000);
		$pc2bytes4  = array (0,0x40000,0x10,0x40010,0,0x40000,0x10,0x40010,0x1000,0x41000,0x1010,0x41010,0x1000,0x41000,0x1010,0x41010);
		$pc2bytes5  = array (0,0x400,0x20,0x420,0,0x400,0x20,0x420,0x2000000,0x2000400,0x2000020,0x2000420,0x2000000,0x2000400,0x2000020,0x2000420);
		$pc2bytes6  = array (0,0x10000000,0x80000,0x10080000,0x2,0x10000002,0x80002,0x10080002,0,0x10000000,0x80000,0x10080000,0x2,0x10000002,0x80002,0x10080002);
		$pc2bytes7  = array (0,0x10000,0x800,0x10800,0x20000000,0x20010000,0x20000800,0x20010800,0x20000,0x30000,0x20800,0x30800,0x20020000,0x20030000,0x20020800,0x20030800);
		$pc2bytes8  = array (0,0x40000,0,0x40000,0x2,0x40002,0x2,0x40002,0x2000000,0x2040000,0x2000000,0x2040000,0x2000002,0x2040002,0x2000002,0x2040002);
		$pc2bytes9  = array (0,0x10000000,0x8,0x10000008,0,0x10000000,0x8,0x10000008,0x400,0x10000400,0x408,0x10000408,0x400,0x10000400,0x408,0x10000408);
		$pc2bytes10 = array (0,0x20,0,0x20,0x100000,0x100020,0x100000,0x100020,0x2000,0x2020,0x2000,0x2020,0x102000,0x102020,0x102000,0x102020);
		$pc2bytes11 = array (0,0x1000000,0x200,0x1000200,0x200000,0x1200000,0x200200,0x1200200,0x4000000,0x5000000,0x4000200,0x5000200,0x4200000,0x5200000,0x4200200,0x5200200);
		$pc2bytes12 = array (0,0x1000,0x8000000,0x8001000,0x80000,0x81000,0x8080000,0x8081000,0x10,0x1010,0x8000010,0x8001010,0x80010,0x81010,0x8080010,0x8081010);
		$pc2bytes13 = array (0,0x4,0x100,0x104,0,0x4,0x100,0x104,0x1,0x5,0x101,0x105,0x1,0x5,0x101,0x105);
		$masks = array (4294967295,2147483647,1073741823,536870911,268435455,134217727,67108863,33554431,16777215,8388607,4194303,2097151,1048575,524287,262143,131071,65535,32767,16383,8191,4095,2047,1023,511,255,127,63,31,15,7,3,1,0);

		$iterations = ((strlen($key) > 8) ? 3 : 1); //changed by Paul 16/6/2007 to use Triple DES for 9+ byte keys
		$keys = array (); // size = 32 * iterations but you don't specify this in php
		$shifts = array (0, 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 0);
		$m=0;
		$n=0;

		for ($j=0; $j<$iterations; $j++) { //either 1 or 3 iterations
			$left = (ord($key{$m++}) << 24) | (ord($key{$m++}) << 16) | (ord($key{$m++}) << 8) | ord($key{$m++});
			$right = (ord($key{$m++}) << 24) | (ord($key{$m++}) << 16) | (ord($key{$m++}) << 8) | ord($key{$m++});

			$temp = (($left >> 4 & $masks[4]) ^ $right) & 0x0f0f0f0f; $right ^= $temp; $left ^= ($temp << 4);
			$temp = (($right >> 16 & $masks[16]) ^ $left) & 0x0000ffff; $left ^= $temp; $right ^= ($temp << 16);
			$temp = (($left >> 2 & $masks[2]) ^ $right) & 0x33333333; $right ^= $temp; $left ^= ($temp << 2);
			$temp = (($right >> 16 & $masks[16]) ^ $left) & 0x0000ffff; $left ^= $temp; $right ^= ($temp << 16);
			$temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);
			$temp = (($right >> 8 & $masks[8]) ^ $left) & 0x00ff00ff; $left ^= $temp; $right ^= ($temp << 8);
			$temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);

			$temp = ($left << 8) | (($right >> 20 & $masks[20]) & 0x000000f0);
			$left = ($right << 24) | (($right << 8) & 0xff0000) | (($right >> 8 & $masks[8]) & 0xff00) | (($right >> 24 & $masks[24]) & 0xf0);
			$right = $temp;

			for ($i=0; $i < count($shifts); $i++) {
				if ($shifts[$i] > 0) {
					$left = (($left << 2) | ($left >> 26 & $masks[26]));
					$right = (($right << 2) | ($right >> 26 & $masks[26]));
				} else {
					$left = (($left << 1) | ($left >> 27 & $masks[27]));
					$right = (($right << 1) | ($right >> 27 & $masks[27]));
				}
				$left = $left & -0xf;
				$right = $right & -0xf;

				$lefttemp = $pc2bytes0[$left >> 28 & $masks[28]] | $pc2bytes1[($left >> 24 & $masks[24]) & 0xf]
					| $pc2bytes2[($left >> 20 & $masks[20]) & 0xf] | $pc2bytes3[($left >> 16 & $masks[16]) & 0xf]
					| $pc2bytes4[($left >> 12 & $masks[12]) & 0xf] | $pc2bytes5[($left >> 8 & $masks[8]) & 0xf]
					| $pc2bytes6[($left >> 4 & $masks[4]) & 0xf];
				$righttemp = $pc2bytes7[$right >> 28 & $masks[28]] | $pc2bytes8[($right >> 24 & $masks[24]) & 0xf]
					| $pc2bytes9[($right >> 20 & $masks[20]) & 0xf] | $pc2bytes10[($right >> 16 & $masks[16]) & 0xf]
					| $pc2bytes11[($right >> 12 & $masks[12]) & 0xf] | $pc2bytes12[($right >> 8 & $masks[8]) & 0xf]
					| $pc2bytes13[($right >> 4 & $masks[4]) & 0xf];
				$temp = (($righttemp >> 16 & $masks[16]) ^ $lefttemp) & 0x0000ffff;
				$keys[$n++] = $lefttemp ^ $temp; $keys[$n++] = $righttemp ^ ($temp << 16);
			}
		}
		return $keys;
	}


}
