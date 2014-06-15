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

class JSJobsepay
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
		$action = "https://ssl.ditonlinebetalingssystem.dk/popup/default.asp";
		
		if(!empty($order)){
			if($this->paymentconfig['addfee_epay'] == 1) $addfee = 1;
			else $addfee = 0;
			
		?>

		<form action="<?php echo $action; ?>" method="post" name="adminForm" >
		
			<input type='hidden' name='merchantnumber' value='<?php echo $this->paymentconfig['merchantnumber_epay']; ?>' />
			<input type='hidden' name='orderid' value='<?php echo $orderid; ?>' />
			<input type='hidden' name='amount' value='<?php echo $order->paidamount; ?>' />
			<input type='hidden' name='currency' value='<?php echo $this->get_iso_code($order->currencycode); ?>' />
			<input type='hidden' name='windowstate' value='<?php echo $this->paymentconfig['windowstate_epay'];?>' />
			<input type='hidden' name='accepturl' value='<?php echo $this->paymentconfig['returnurl_epay'];?>' />
			<input type='hidden' name='declineurl' value='<?php echo $this->paymentconfig['cancelurl_epay'];?>' />
			<input type='hidden' name='callbackurl' value='<?php echo JURI::root().$this->paymentconfig['notifyurl_epay'];?>' />
			<input type='hidden' name='authsms' value='<?php echo JURI::root().$this->paymentconfig['authsms_epay'];?>' />
			<input type='hidden' name='authmail' value='<?php echo JURI::root().$this->paymentconfig['authmail_epay'];?>' />
			<input type='hidden' name='instantcapture' value='<?php echo $this->paymentconfig['instantcapture_epay'];?>' />
			<input type='hidden' name='splitpayment' value='<?php echo $this->paymentconfig['splitpayment_epay'];?>' />
			<input type='hidden' name='group' value='<?php echo $this->paymentconfig['group_epay'];?>' />
			<input type='hidden' name='addfee' value='<?php echo $addfee;?>' />
			<input type='hidden' name='instantcallback' value='1' />
			<input type='hidden' name='cms' value='jsautoz' />
<?php 
		if($this->paymentconfig['md5mode_epay'] == 3)
		{
			$md5key = md5($this->get_iso_code($order->currencycode) . intval($order->paidamount*100) . $orderid . $this->paymentconfig['md5key_epay']);
			?>
			<input type="hidden" name="md5key" value="<?php echo $md5key;?>" />
			<?php
		}

?>			
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
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'epay'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
				
		//code not tested
		$md5key = md5($_GET["amount"] . $_GET["orderid"] . $_GET["tid"] . $this->paymentconfig['md5key_epay']);
		
		if($md5key == $_GET["eKey"]){
			$data['orderid'] = $_GET['orderid'];
			$data['payer_amount'] = $_GET['amount'];
			$data['payer_tx_token'] = $_GET['tid'];
		}else{
			$data = false;
		}
		
		return $data;
	}
	function get_iso_code($code) {
		switch (strtoupper($code)){
			case 'ADP': return '020';
			case 'AED': return '784';
			case 'AFA': return '004';
			case 'ALL': return '008';
			case 'AMD': return '051';
			case 'ANG': return '532';
			case 'AOA': return '973';
			case 'ARS': return '032';
			case 'AUD': return '036';
			case 'AWG': return '533';
			case 'AZM': return '031';
			case 'BAM': return '977';
			case 'BBD': return '052';
			case 'BDT': return '050';
			case 'BGL': return '100';
			case 'BGN': return '975';
			case 'BHD': return '048';
			case 'BIF': return '108';
			case 'BMD': return '060';
			case 'BND': return '096';
			case 'BOB': return '068';
			case 'BOV': return '984';
			case 'BRL': return '986';
			case 'BSD': return '044';
			case 'BTN': return '064';
			case 'BWP': return '072';
			case 'BYR': return '974';
			case 'BZD': return '084';
			case 'CAD': return '124';
			case 'CDF': return '976';
			case 'CHF': return '756';
			case 'CLF': return '990';
			case 'CLP': return '152';
			case 'CNY': return '156';
			case 'COP': return '170';
			case 'CRC': return '188';
			case 'CUP': return '192';
			case 'CVE': return '132';
			case 'CYP': return '196';
			case 'CZK': return '203';
			case 'DJF': return '262';
			case 'DKK': return '208';
			case 'DOP': return '214';
			case 'DZD': return '012';
			case 'ECS': return '218';
			case 'ECV': return '983';
			case 'EEK': return '233';
			case 'EGP': return '818';
			case 'ERN': return '232';
			case 'ETB': return '230';
			case 'EUR': return '978';
			case 'FJD': return '242';
			case 'FKP': return '238';
			case 'GBP': return '826';
			case 'GEL': return '981';
			case 'GHC': return '288';
			case 'GIP': return '292';
			case 'GMD': return '270';
			case 'GNF': return '324';
			case 'GTQ': return '320';
			case 'GWP': return '624';
			case 'GYD': return '328';
			case 'HKD': return '344';
			case 'HNL': return '340';
			case 'HRK': return '191';
			case 'HTG': return '332';
			case 'HUF': return '348';
			case 'IDR': return '360';
			case 'ILS': return '376';
			case 'INR': return '356';
			case 'IQD': return '368';
			case 'IRR': return '364';
			case 'ISK': return '352';
			case 'JMD': return '388';
			case 'JOD': return '400';
			case 'JPY': return '392';
			case 'KES': return '404';
			case 'KGS': return '417';
			case 'KHR': return '116';
			case 'KMF': return '174';
			case 'KPW': return '408';
			case 'KRW': return '410';
			case 'KWD': return '414';
			case 'KYD': return '136';
			case 'KZT': return '398';
			case 'LAK': return '418';
			case 'LBP': return '422';
			case 'LKR': return '144';
			case 'LRD': return '430';
			case 'LSL': return '426';
			case 'LTL': return '440';
			case 'LVL': return '428';
			case 'LYD': return '434';
			case 'MAD': return '504';
			case 'MDL': return '498';
			case 'MGF': return '450';
			case 'MKD': return '807';
			case 'MMK': return '104';
			case 'MNT': return '496';
			case 'MOP': return '446';
			case 'MRO': return '478';
			case 'MTL': return '470';
			case 'MUR': return '480';
			case 'MVR': return '462';
			case 'MWK': return '454';
			case 'MXN': return '484';
			case 'MXV': return '979';
			case 'MYR': return '458';
			case 'MZM': return '508';
			case 'NAD': return '516';
			case 'NGN': return '566';
			case 'NIO': return '558';
			case 'NOK': return '578';
			case 'NPR': return '524';
			case 'NZD': return '554';
			case 'OMR': return '512';
			case 'PAB': return '590';
			case 'PEN': return '604';
			case 'PGK': return '598';
			case 'PHP': return '608';
			case 'PKR': return '586';
			case 'PLN': return '985';
			case 'PYG': return '600';
			case 'QAR': return '634';
			case 'ROL': return '642';
			case 'RUB': return '643';
			case 'RUR': return '810';
			case 'RWF': return '646';
			case 'SAR': return '682';
			case 'SBD': return '090';
			case 'SCR': return '690';
			case 'SDD': return '736';
			case 'SEK': return '752';
			case 'SGD': return '702';
			case 'SHP': return '654';
			case 'SIT': return '705';
			case 'SKK': return '703';
			case 'SLL': return '694';
			case 'SOS': return '706';
			case 'SRG': return '740';
			case 'STD': return '678';
			case 'SVC': return '222';
			case 'SYP': return '760';
			case 'SZL': return '748';
			case 'THB': return '764';
			case 'TJS': return '972';
			case 'TMM': return '795';
			case 'TND': return '788';
			case 'TOP': return '776';
			case 'TPE': return '626';
			case 'TRL': return '792';
			case 'TRY': return '949';
			case 'TTD': return '780';
			case 'TWD': return '901';
			case 'TZS': return '834';
			case 'UAH': return '980';
			case 'UGX': return '800';
			case 'USD': return '840';
			case 'UYU': return '858';
			case 'UZS': return '860';
			case 'VEB': return '862';
			case 'VND': return '704';
			case 'VUV': return '548';
			case 'XAF': return '950';
			case 'XCD': return '951';
			case 'XOF': return '952';
			case 'XPF': return '953';
			case 'YER': return '886';
			case 'YUM': return '891';
			case 'ZAR': return '710';
			case 'ZMK': return '894';
			case 'ZWD': return '716';
		}
		return '208';
	}
	
}
