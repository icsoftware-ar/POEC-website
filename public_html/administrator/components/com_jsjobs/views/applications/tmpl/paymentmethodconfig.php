<?php
/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	April 05, 2012
 ^
 + Project: 		JS JObs
 ^ 
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
$document =& JFactory::getDocument();
	$version = new JVersion;
	$joomla = $version->getShortVersion();
	$jversion = substr($joomla,0,3);

	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('../components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	

$document->addScript('components/com_jsjobs/include/js/jquery_idTabs.js');
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

 global $mainframe;
 
 $ADMINPATH = JPATH_BASE .'\components\com_jsjobs';

$yesno = array(
	'0' => array('value' => 1,'text' => JText::_('JS_YES')),
	'1' => array('value' => 0,'text' => JText::_('JS_NO')),);
$alipay_mode = array(
	'0' => array('value' => "Partner",'text' => JText::_('PARTNER')),
	'1' => array('value' => "Direct",'text' => JText::_('DIRECT')),);
$alipay_transport = array(
	'0' => array('value' => "http",'text' => JText::_('http')),
	'1' => array('value' => "https",'text' => JText::_('https')),);
$bluepaid_currency = array(
	'0' => array('value' => "EUR",'text' => JText::_('EUR')),
	'1' => array('value' => "USD",'text' => JText::_('USD')),
	'2' => array('value' => "JPY",'text' => JText::_('JPY')),
	'3' => array('value' => "CAD",'text' => JText::_('CAD')),
	'4' => array('value' => "AUD",'text' => JText::_('AUD')),
	'5' => array('value' => "GBP",'text' => JText::_('GBP')),
	'6' => array('value' => "CHF",'text' => JText::_('CHF')),);
$bluepaid_lang = array(
	'0' => array('value' => "EN",'text' => JText::_('English')),
	'1' => array('value' => "DE",'text' => JText::_('German')),
	'2' => array('value' => "ES",'text' => JText::_('Spanish')),
	'3' => array('value' => "FR",'text' => JText::_('French')),
	'4' => array('value' => "IT",'text' => JText::_('Italian')),
	'5' => array('value' => "NL",'text' => JText::_('Dutch')),
	'6' => array('value' => "PT",'text' => JText::_('Portuguese')),);
$eway_lang = array(
	'0' => array('value' => "EN",'text' => JText::_('English')),
	'1' => array('value' => "DE",'text' => JText::_('German')),
	'2' => array('value' => "ES",'text' => JText::_('Spanish')),
	'3' => array('value' => "FR",'text' => JText::_('French')),
	'4' => array('value' => "NL",'text' => JText::_('Dutch')),);
$eway_country = array(
	'0' => array('value' => "UK",'text' => JText::_('United Kingdom')),
	'1' => array('value' => "AS",'text' => JText::_('Australia')),
	'2' => array('value' => "NZ",'text' => JText::_('New Zealand')),);
$windowstate_epay = array(
	'0' => array('value' => "1",'text' => JText::_('POPUP')),
	'1' => array('value' => "2",'text' => JText::_('SAME_WINDOW')),);
$md5mode_epay = array(
	'0' => array('value' => "2",'text' => JText::_('FROM_EPAY')),
	'1' => array('value' => "3",'text' => JText::_('TO_AND_FROM_EPAY')),);
$yesno_epay = array(
	'0' => array('value' => "1",'text' => JText::_('JS_NO')),
	'1' => array('value' => "2",'text' => JText::_('JS_YES')),);
	
$currency_alipay = array('0' => array('value' => "CNY",'text' => JText::_('CNY')));

$currencycode_googlecheckout = array(
	'0' => array('value' => "USD",'text' => JText::_('USD')),
	'1' => array('value' => "GBP",'text' => JText::_('GBP')),);

$hsbc_currency = array(
	'0' => array('value' => "978",'text' => JText::_('EUR')),
	'1' => array('value' => "840",'text' => JText::_('USD')),
	'2' => array('value' => "124",'text' => JText::_('CAD')),
	'3' => array('value' => "036",'text' => JText::_('AUD')),
	'4' => array('value' => "826",'text' => JText::_('GBP')),);
$moneybookers_currency = array(
	'0' => array('value' => "EUR",'text' => JText::_('EUR')),'1' => array('value' => "JPY",'text' => JText::_('JPY')),
	'2' => array('value' => "USD",'text' => JText::_('USD')),'3' => array('value' => "HKD",'text' => JText::_('HKD')),
	'4' => array('value' => "SGD",'text' => JText::_('SGD')),'5' => array('value' => "GBP",'text' => JText::_('GBP')),	
	'6' => array('value' => "CAD",'text' => JText::_('CAD')),'7' => array('value' => "AUD",'text' => JText::_('AUD')),
	'8' => array('value' => "CHF",'text' => JText::_('CHF')),'9' => array('value' => "DKK",'text' => JText::_('DKK')),
	'10' => array('value' => "SEK",'text' => JText::_('SEK')),'11' => array('value' => "NOK",'text' => JText::_('NOK')),
	'12' => array('value' => "ILS",'text' => JText::_('ILS')),'13' => array('value' => "MYR",'text' => JText::_('MYR')),
	'14' => array('value' => "TRY",'text' => JText::_('TRY')),'15' => array('value' => "NZD",'text' => JText::_('NZD')),
	'16' => array('value' => "AED",'text' => JText::_('AED')),'17' => array('value' => "MAD",'text' => JText::_('MAD')),
	'18' => array('value' => "SAR",'text' => JText::_('SAR')),'19' => array('value' => "QAR",'text' => JText::_('QAR')),
	'20' => array('value' => "INR",'text' => JText::_('INR')),'21' => array('value' => "ISK",'text' => JText::_('ISK')),
	'22' => array('value' => "BGN",'text' => JText::_('BGN')),'23' => array('value' => "PLN",'text' => JText::_('PLN')),
	'24' => array('value' => "EEK",'text' => JText::_('EEK')),'25' => array('value' => "SKK",'text' => JText::_('SKK')),
	'26' => array('value' => "CZK",'text' => JText::_('CZK')),'27' => array('value' => "HUF",'text' => JText::_('HUF')),
	'28' => array('value' => "THB",'text' => JText::_('THB')),'29' => array('value' => "TWD",'text' => JText::_('TWD')),
	'30' => array('value' => "KRW",'text' => JText::_('KRW')),'31' => array('value' => "LVL",'text' => JText::_('LVL')),
	'32' => array('value' => "ZAR",'text' => JText::_('ZAR')),'33' => array('value' => "RON",'text' => JText::_('RON')),
	'34' => array('value' => "LTL",'text' => JText::_('LTL')),'35' => array('value' => "HRK",'text' => JText::_('HRK')),
	'36' => array('value' => "JOD",'text' => JText::_('JOD')),'37' => array('value' => "OMR",'text' => JText::_('OMR')),
	'38' => array('value' => "TND",'text' => JText::_('TND')),'39' => array('value' => "RSD",'text' => JText::_('RSD')),
	);
$moneybookers_language = array(
	'0' => array('value' => "EN",'text' => JText::_('English')),
	'1' => array('value' => "DE",'text' => JText::_('German')),
	'2' => array('value' => "ES",'text' => JText::_('Spanish')),
	'3' => array('value' => "FR",'text' => JText::_('French')),
	'4' => array('value' => "IT",'text' => JText::_('Italian')),
	'5' => array('value' => "PL",'text' => JText::_('Polish')),
	'6' => array('value' => "GR",'text' => JText::_('Greek')),
	'7' => array('value' => "RO",'text' => JText::_('Romanian')),
	'8' => array('value' => "RU",'text' => JText::_('Russian')),
	'9' => array('value' => "TR",'text' => JText::_('Turkish')),
	'10' => array('value' => "CN",'text' => JText::_('Chinese')),
	'11' => array('value' => "CZ",'text' => JText::_('Czech')),
	'12' => array('value' => "DA",'text' => JText::_('Danish')),
	'13' => array('value' => "SV",'text' => JText::_('Swedish')),
	'14' => array('value' => "FI",'text' => JText::_('Finnish')),
	'15' => array('value' => "NL",'text' => JText::_('Dutch')),);
$sagepay_mode = array(
	'0' => array('value' => "LIVE",'text' => JText::_('Live')),
	'1' => array('value' => "TEST",'text' => JText::_('Test')),
	'2' => array('value' => "SIMU",'text' => JText::_('SIMU')),);

$checkout_language = array(
	'0' => array('value' => "zh",'text' => JText::_('Chinese')),'1' => array('value' => "da",'text' => JText::_('Danish')),
	'2' => array('value' => "fr",'text' => JText::_('French')),'3' => array('value' => "gr",'text' => JText::_('German')),
	'4' => array('value' => "it",'text' => JText::_('Italian')),'5' => array('value' => "el",'text' => JText::_('Greek')),
	'6' => array('value' => "jp",'text' => JText::_('Japanese')),'7' => array('value' => "no",'text' => JText::_('Norwegian')),
	'8' => array('value' => "sl",'text' => JText::_('Slovenian')),'9' => array('value' => "pt",'text' => JText::_('Portuguese')),
	'10' => array('value' => "nl",'text' => JText::_('Dutch')),
	);

$checkout_currencycode = array(
	'0' => array('value' => "ARS",'text' => JText::_('ARS')),'1' => array('value' => "AUD",'text' => JText::_('AUD')),
	'2' => array('value' => "BRL",'text' => JText::_('BRL')),'3' => array('value' => "GBP",'text' => JText::_('GBP')),
	'4' => array('value' => "DKK",'text' => JText::_('DKK')),'5' => array('value' => "CAD",'text' => JText::_('CAD')),
	'6' => array('value' => "EUR",'text' => JText::_('EUR')),'7' => array('value' => "HKD",'text' => JText::_('HKD')),
	'8' => array('value' => "ILS",'text' => JText::_('ILS')),'9' => array('value' => "INR",'text' => JText::_('INR')),
	'10' => array('value' => "JPY",'text' => JText::_('JPY')),'11' => array('value' => "LTL",'text' => JText::_('LTL')),
	'12' => array('value' => "MXN",'text' => JText::_('MXN')),'13' => array('value' => "MYR",'text' => JText::_('MYR')),
	'14' => array('value' => "NZD",'text' => JText::_('NZD')),'15' => array('value' => "NOK",'text' => JText::_('NOK')),
	'16' => array('value' => "RON",'text' => JText::_('RON')),'17' => array('value' => "PHP",'text' => JText::_('PHP')),
	'18' => array('value' => "RUB",'text' => JText::_('RUB')),'19' => array('value' => "SGD",'text' => JText::_('SGD')),
	'20' => array('value' => "SEK",'text' => JText::_('SEK')),'21' => array('value' => "ZAR",'text' => JText::_('ZAR')),
	'22' => array('value' => "CHF",'text' => JText::_('CHF')),'23' => array('value' => "TRY",'text' => JText::_('TRY')),
	'24' => array('value' => "USD",'text' => JText::_('USD')),'25' => array('value' => "AED",'text' => JText::_('AED')),);

$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;
?>

<table width="100%" >
	<tr>
		<td align="left" width="188"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="789" valign="top" align="left">
				<div id="jsjobs_info_heading"><?php echo JText::_('PAYMENT_METHODS_CONFIGURATION'); ?></div>
			<form action="index.php" method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data" >
						<div id="tabs_wrapper" class="tabs_wrapper">
							<div class="idTabs">
								<span><a class="selected" href="#payza"><?php echo JText::_('PAYZA_SETTINGS');?></a></span> 
								<span><a  href="#alipay"><?php echo JText::_('ALIPAY_SETTING');?></a></span> 
								<span><a  href="#authorize"><?php echo JText::_('AUTHORIZE_SETTING');?></a></span> 
								<span><a  href="#worldpay"><?php echo JText::_('WORLDPAY_SETTING');?></a></span> 
								<span><a  href="#bluepaid"><?php echo JText::_('BLUEPAID_SETTING');?></a></span> 
								<span><a  href="#epay"><?php echo JText::_('EPAY_SETTING');?></a></span> 
								<span><a  href="#eway"><?php echo JText::_('EWAY_SETTING');?></a></span> 
								<span><a  href="#googlecheckout"><?php echo JText::_('GOOGLE_CHECKOUT_SETTING');?></a></span> 
								<span><a  href="#hsbc"><?php echo JText::_('HSBC_SETTING');?></a></span> 
								<span><a  href="#moneybookers"><?php echo JText::_('MONEYBOOKERS_SETTING');?></a></span> 
								<span style="margin-top:5px;"><a  href="#paypal"><?php echo JText::_('PAYPAL_SETTING');?></a></span> 
								<span><a  href="#sagepay"><?php echo JText::_('SAGEPAY_SETTING');?></a></span> 
								<span><a  href="#westernunion"><?php echo JText::_('WESTERNUNION_SETTING');?></a></span> 
								<span><a  href="#2checkout"><?php echo JText::_('2CHECKOUT_SETTING');?></a></span> 
								<span><a  href="#fastspring"><?php echo JText::_('FASTSPRING_SETTING');?></a></span> 
								<span><a  href="#avangate"><?php echo JText::_('AVANGATE_SETTING');?></a></span> 
								<span><a  href="#pagseguro"><?php echo JText::_('PAGSEGURO_SETTING');?></a></span> 
								<span><a  href="#payfast"><?php echo JText::_('PAYFAST_SETTING');?></a></span> 
								<span><a  href="#ideal"><?php echo JText::_('IDEAL_SETTING');?></a></span> 
								<span><a  href="#others"><?php echo JText::_('OTHERS_SETTING');?></a></span> 
							</div>
							<div id="payza">
								<fieldset>
									<legend><?php echo JText::_('PAYZA_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('MERCHANT_EMAIL'); ?></td>
											<td><input type="text" name="merchantemail_payza" value="<?php echo $this->paymentmethodconfig['merchantemail_payza']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('IPN_SECURITY_CODE'); ?></td>
											<td><input type="text" name="ipnsecuritycode_payza" value="<?php echo $this->paymentmethodconfig['ipnsecuritycode_payza']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input id="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_payza']; ?>" class="inputbox" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('CANCEL_URL'); ?></td>
											<td><input type="text" name="cancelurl_payza" value="<?php echo $this->paymentmethodconfig['cancelurl_payza']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('RETURN_URL'); ?></td>
											<td><input type="text" name="returnurl_payza" value="<?php echo $this->paymentmethodconfig['returnurl_payza']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('PAYZA_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_payza', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_payza']); ?></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('TESTMODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'testmode_payza', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['testmode_payza']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="alipay">
								<fieldset>
									<legend><?php echo JText::_('ALIPAY_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('MERCHANT_EMAIL'); ?></td>
											<td><input type="text" name="merchantemail_alipay" value="<?php echo $this->paymentmethodconfig['merchantemail_alipay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('SECURITY_CODE'); ?></td>
											<td><input type="text" name="securitycode_alipay" value="<?php echo $this->paymentmethodconfig['securitycode_alipay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('PAYMENT_MODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $alipay_mode, 'paymentmode_alipay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['paymentmode_alipay']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('PARTNER_ID'); ?></td>
											<td><input type="text" name="partnerid_alipay" value="<?php echo $this->paymentmethodconfig['partnerid_alipay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('ACCEPETED_CURRENCY'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $currency_alipay, 'currency_alipay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['currency_alipay']); ?></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('TRANSPORT'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $alipay_transport, 'transport_alipay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['transport_alipay']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input id="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_alipay']; ?>" class="inputbox" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('RETURN_URL'); ?></td>
											<td><input type="text" name="returnurl_alipay" value="<?php echo $this->paymentmethodconfig['returnurl_payza']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('ALIPAY_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_alipay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_alipay']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="authorize">
								<fieldset>
									<legend><?php echo JText::_('AUTHORIZE_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('LOGIN_ID'); ?></td>
											<td><input type="text" name="loginid_authorize" value="<?php echo $this->paymentmethodconfig['loginid_authorize']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('TRANSACTION_KEY'); ?></td>
											<td><input type="text" name="transactionkey_authorize" value="<?php echo $this->paymentmethodconfig['transactionkey_authorize']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('TEST_MODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'testmode_authorize', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['testmode_authorize']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input id="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_authorize']; ?>" class="inputbox" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('RETURN_URL'); ?></td>
											<td><input type="text" name="returnurl_authorize" value="<?php echo $this->paymentmethodconfig['returnurl_authorize']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('CANCEL_URL'); ?></td>
											<td><input type="text" name="cancelurl_authorize" value="<?php echo $this->paymentmethodconfig['cancelurl_authorize']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('AUTHORIZE_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_authorize', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_authorize']); ?></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('NOTE'); ?></td>
											<td ><?php echo JText::_('ONLY_SIM_PAYMENT_MEHTOD'); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="worldpay">
								<fieldset>
									<legend><?php echo JText::_('WORLDPAY_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('PAYMENT_URL'); ?></td>
											<td><input type="text" name="paymenturl_worldpay" value="<?php echo $this->paymentmethodconfig['paymenturl_worldpay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('INSTALLATION_ID'); ?></td>
											<td><input type="text" name="instid_worldpay" value="<?php echo $this->paymentmethodconfig['instid_worldpay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('TEST_MODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'testmode_worldpay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['testmode_authorize']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input id="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_worldpay']; ?>" class="inputbox" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('WORLDPAY_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_worldpay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_worldpay']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="bluepaid">
								<fieldset>
									<legend><?php echo JText::_('BLUEPAID_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('PAYMENT_URL'); ?></td>
											<td><input type="text" name="paymenturl_bluepaid" value="<?php echo $this->paymentmethodconfig['paymenturl_bluepaid']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('BLUEPAID_ID'); ?></td>
											<td><input type="text" name="bluepaidid_bluepaid" value="<?php echo $this->paymentmethodconfig['bluepaidid_bluepaid']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('CURRENCY_CODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $bluepaid_currency, 'currencycode_bluepaid', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['currencycode_bluepaid']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('LANGUAGE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $bluepaid_lang, 'language_bluepaid', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['language_bluepaid']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input id="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_bluepaid']; ?>" class="inputbox" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('BLUEPAID_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_bluepaid', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_bluepaid']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="epay">
								<fieldset>
									<legend><?php echo JText::_('EPAY_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('MERCHANT_NUMBER'); ?></td>
											<td><input type="text" name="merchantnumber_epay" value="<?php echo $this->paymentmethodconfig['merchantnumber_epay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('WINDOW_STATE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $windowstate_epay, 'windowstate_epay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['windowstate_epay']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('MD5MODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $md5mode_epay, 'md5mode_epay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['md5mode_epay']); ?></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('AUTHSMS'); ?></td>
											<td><input type="text" name="authsms_epay" value="<?php echo $this->paymentmethodconfig['authsms_epay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('AUTHMAIL'); ?></td>
											<td><input type="text" name="authmail_epay" value="<?php echo $this->paymentmethodconfig['authmail_epay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('GROUP'); ?></td>
											<td><input type="text" name="group_epay" value="<?php echo $this->paymentmethodconfig['group_epay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('INSTANT_CAPTURE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno_epay, 'instantcapture_epay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['instantcapture_epay']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('SPLIT_PAYMENT'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno_epay, 'splitpayment_epay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['splitpayment_epay']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('ADD_FEE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno_epay, 'addfee_epay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['addfee_epay']); ?></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('RETURN_URL'); ?></td>
											<td><input type="text" name="returnurl_epay" value="<?php echo $this->paymentmethodconfig['returnurl_epay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('CANCEL_URL'); ?></td>
											<td><input type="text" name="cancelurl_epay" value="<?php echo $this->paymentmethodconfig['cancelurl_epay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input id="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_epay']; ?>" class="inputbox" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('EPAY_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_epay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_epay']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="eway">
								<fieldset>
									<legend><?php echo JText::_('EWAY_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('CUSTOMER_ID'); ?></td>
											<td><input type="text" name="customerid_eway" value="<?php echo $this->paymentmethodconfig['customerid_eway']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('USER_NAME'); ?></td>
											<td><input type="text" name="username_eway" value="<?php echo $this->paymentmethodconfig['username_eway']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('COUNTRY'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $eway_country, 'countrycode_eway', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['countrycode_eway']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('CURRENCY_CODE'); ?></td>
											<td ><?php echo JText::_('CURRENCY_CODE_WERE_SET_ACCORGIND_TO_COUNTRY_AUD_NZD_GBP'); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('LANGUAGE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $eway_lang, 'language_eway', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['language_eway']); ?></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('RETURN_URL'); ?></td>
											<td>
												<input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['returnurl_eway']; ?>" class="inputbox" maxlength="255" size="80" readonly="true" />
												<br clear="all" />
												<small><?php echo JText::_('DONOT_CHANGE_IT'); ?></small>
											</td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('CANCEL_URL'); ?></td>
											<td><input type="text" name="cancelurl_eway" value="<?php echo $this->paymentmethodconfig['cancelurl_eway']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('EWAY_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_eway', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_eway']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="googlecheckout">
								<fieldset>
									<legend><?php echo JText::_('GOOGLE_CHECKOUT_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('MERCHANT_ID'); ?></td>
											<td><input type="text" name="merchantid_googlecheckout" value="<?php echo $this->paymentmethodconfig['merchantid_googlecheckout']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('MERCHANT_KEY'); ?></td>
											<td><input type="text" name="merchantkey_googlecheckout" value="<?php echo $this->paymentmethodconfig['merchantkey_googlecheckout']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('CURRENCY_CODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $currencycode_googlecheckout, 'currencycode_googlecheckout', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['currencycode_googlecheckout']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('SERVER_TO_SERVER'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'servertoserver_googlecheckout', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['servertoserver_googlecheckout']); ?></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_googlecheckout']; ?>" class="inputbox"  maxlength="255" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('TESTMODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'testmode_googlecheckout', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['testmode_googlecheckout']); ?></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('GOOGLE_CHECKOUT_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_googlecheckout', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_googlecheckout']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="hsbc">
								<fieldset>
									<legend><?php echo JText::_('HSBC_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('MERCHANT_NUMBER'); ?></td>
											<td><input type="text" name="merchantid_hsbc" value="<?php echo $this->paymentmethodconfig['merchantid_hsbc']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('CPI_STORE_KEY'); ?></td>
											<td><input type="text" name="cpihash_hsbc" value="<?php echo $this->paymentmethodconfig['cpihash_hsbc']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('CURRENCY_CODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $hsbc_currency, 'acceptedcurrencycode_hsbc', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['acceptedcurrencycode_hsbc']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('INSTANT_CAPTURE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'instantcapture_hsbc', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['instantcapture_hsbc']); ?></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('RETURN_URL'); ?></td>
											<td><input type="text" name="returnurl_hsbc" value="<?php echo $this->paymentmethodconfig['returnurl_hsbc']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_hsbc']; ?>" class="inputbox"  maxlength="255" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('TESTMODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'testmode_hsbc', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['testmode_hsbc']); ?></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('HSBC_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_hsbc', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_hsbc']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="moneybookers">
								<fieldset>
									<legend><?php echo JText::_('MONEYBOOKERS_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('PAYMENT_URL'); ?></td>
											<td><input type="text" name="paymenturl_moneybookers" value="<?php echo $this->paymentmethodconfig['paymenturl_moneybookers']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('MERCHANT_EMAIL'); ?></td>
											<td><input type="text" name="merchantemail_moneybookers" value="<?php echo $this->paymentmethodconfig['merchantemail_moneybookers']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('MERCHANT_ID'); ?></td>
											<td><input type="text" name="merchantid_moneybookers" value="<?php echo $this->paymentmethodconfig['merchantid_moneybookers']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('MERCHANT_SECRET_WORD'); ?></td>
											<td><input type="text" name="secretword_moneybookers" value="<?php echo $this->paymentmethodconfig['secretword_moneybookers']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('CURRENCY_CODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $moneybookers_currency, 'acceptedcurrency_moneybookers', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['acceptedcurrency_moneybookers']); ?></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('LANGUAGE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $moneybookers_language, 'language_moneybookers', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['language_moneybookers']); ?></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('RETURN_URL'); ?></td>
											<td><input type="text" name="returnurl_moneybookers" value="<?php echo $this->paymentmethodconfig['returnurl_moneybookers']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('CANCEL_URL'); ?></td>
											<td><input type="text" name="cancelurl_moneybookers" value="<?php echo $this->paymentmethodconfig['cancelurl_moneybookers']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_moneybookers']; ?>" class="inputbox"  maxlength="255" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('MONEYBOOKERS_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_moneybookers', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_moneybookers']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="paypal">
								<fieldset>
									<legend><?php echo JText::_('PAYPAL_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('ACCOUNT_ID'); ?></td>
											<td><input type="text" name="accountid_paypal" value="<?php echo $this->paymentmethodconfig['accountid_paypal']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('AUTH_TOKEN'); ?></td>
											<td><input type="text" name="authtoken_paypal" value="<?php echo $this->paymentmethodconfig['authtoken_paypal']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('RETURN_URL'); ?></td>
											<td><input type="text" name="returnurl_paypal" value="<?php echo $this->paymentmethodconfig['returnurl_paypal']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('CANCEL_URL'); ?></td>
											<td><input type="text" name="cancelurl_paypal" value="<?php echo $this->paymentmethodconfig['cancelurl_paypal']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_paypal']; ?>" class="inputbox" maxlength="255" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('TESTMODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'testmode_paypal', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['testmode_paypal']); ?></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('PAYPAL_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_paypal', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_paypal']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="sagepay">
								<fieldset>
									<legend><?php echo JText::_('SAGEPAY_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('VENDOR_NAME'); ?></td>
											<td><input type="text" name="vendorname_sagepay" value="<?php echo $this->paymentmethodconfig['vendorname_sagepay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('PASWORD'); ?></td>
											<td><input type="text" name="password_sagepay" value="<?php echo $this->paymentmethodconfig['password_sagepay']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('MODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $sagepay_mode, 'mode_sagepay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['mode_sagepay']); ?></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_sagepay']; ?>" class="inputbox" maxlength="255" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('SAGEPAY_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_sagepay', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_sagepay']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="westernunion">
								<fieldset>
									<legend><?php echo JText::_('WESTERNUNION_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NAME'); ?></td>
											<td>
												<input type="text" name="name_westernunion" value="<?php echo $this->paymentmethodconfig['name_westernunion']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" />
												<input type="checkbox" name="showname_westernunion" value="1" <?php if($this->paymentmethodconfig['showname_westernunion'] == '1') echo 'checked="true"'; ?> /><br clear="all" />
												<small><?php echo JText::_('MARK_CHECKBOX_IF_YOU_WANT_TO_SHOW_IT');?></small>
											</td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('COUNTRY_NAME'); ?></td>
											<td>
												<input type="text" name="countryname_westernunion" value="<?php echo $this->paymentmethodconfig['countryname_westernunion']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" />
												<input type="checkbox" name="showcountryname_westernunion" value="1" <?php if($this->paymentmethodconfig['showcountryname_westernunion'] == '1') echo 'checked="true"'; ?> /><br clear="all" />
												<small><?php echo JText::_('MARK_CHECKBOX_IF_YOU_WANT_TO_SHOW_IT');?></small>
											</td>
										</tr>
										<tr>
											<td  class="key"><?php echo JText::_('CITY_NAME'); ?></td>
											<td>
												<input type="text" name="cityname_westernunion" value="<?php echo $this->paymentmethodconfig['cityname_westernunion']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" />
												<input type="checkbox" name="showcityname_westernunion" value="1" <?php if($this->paymentmethodconfig['showcityname_westernunion'] == '1') echo 'checked="true"'; ?> /><br clear="all" />
												<small><?php echo JText::_('MARK_CHECKBOX_IF_YOU_WANT_TO_SHOW_IT');?></small>
											</td>
										</tr>
										<tr>
											<td class="key" width="25%"><?php echo JText::_('EMAIL_ADDRESS'); ?></td>
											<td>
												<input type="text" name="emailaddress_westernunion" value="<?php echo $this->paymentmethodconfig['emailaddress_westernunion']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" />
												<br clear="all" />
												<small><?php echo JText::_('NOTE_EMAIL_ADDRESS_SHOWN_FOR_MTCN_NUMBER');?></small>
											</td>
										</tr>
										<tr>
											<td class="key" width="25%"><?php echo JText::_('ACCOUNT_INFORMATION'); ?></td>
											<td>
												<textarea name="accountinfo_westernunion" cols="50" rows="10"><?php echo $this->paymentmethodconfig['accountinfo_westernunion']; ?></textarea>
												<input type="checkbox" name="showaccountinfo_westernunion" value="1" <?php if($this->paymentmethodconfig['showaccountinfo_westernunion'] == '1') echo 'checked="true"'; ?> /><br clear="all" />
												<small><?php echo JText::_('MARK_CHECKBOX_IF_YOU_WANT_TO_SHOW_IT');?></small><br clear="all" />
												<small><?php echo JText::_('NOTE_EMAIL_ADDRESS_SHOWN_FOR_MTCN_NUMBER');?></small>
											</td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('WESTERNUNION_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_westernunion', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_westernunion']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="2checkout">
								<fieldset>
									<legend><?php echo JText::_('2CHECKOUT_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('SELLER_ID'); ?></td>
											<td><input type="text" name="sid_2checkout" value="<?php echo $this->paymentmethodconfig['sid_2checkout']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('CURRENCY_CODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $checkout_currencycode, 'currencycode_2checkout', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['currencycode_2checkout']); ?></td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('LANGUAGE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $checkout_language, 'language_2checkout', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['language_2checkout']); ?></td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('DEMO'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'demo_2checkout', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['demo_2checkout']); ?></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_2checkout']; ?>" class="inputbox" maxlength="255" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('2CHECKOUT_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_2checkout', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_2checkout']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="fastspring">
								<fieldset>
									<legend><?php echo JText::_('FASTSPRING_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('PRIVATE_KEY'); ?></td>
											<td><input type="text" name="privatekey_fastspring" value="<?php echo $this->paymentmethodconfig['privatekey_fastspring']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td>
												<input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_fastspring']; ?>" class="inputbox" maxlength="255" size="80" readonly="true" /><br clear="all" />
												<small><?php echo JText::_('PASTE_URL_IN_FASTSPRING_NOTIFICATION_URL');?></small>
											</td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('FASTSPRING_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_fastspring', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_fastspring']); ?></td>
										</tr>
										<tr>
											<td colspan="2" class="key"><?php echo JText::_('NOTE_PAST_THE_PRODUCT_URL_IN_SELLER_PACKAGE'); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="avangate">
								<fieldset>
									<legend><?php echo JText::_('AVANGATE_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('PRIVATE_KEY'); ?></td>
											<td><input type="text" name="privatekey_avangate" value="<?php echo $this->paymentmethodconfig['privatekey_avangate']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td>
												<input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_avangate']; ?>" class="inputbox" maxlength="255" size="80" readonly="true" /><br clear="all" />
												<small><?php echo JText::_('PASTE_URL_IN_AVANGATE_NOTIFICATION_URL');?></small>
											</td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('AVANGATE_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_avangate', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_avangate']); ?></td>
										</tr>
										<tr>
											<td colspan="2" class="key"><?php echo JText::_('NOTE_PAST_THE_PRODUCT_URL_IN_SELLER_PACKAGE'); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="pagseguro">
								<fieldset>
									<legend><?php echo JText::_('PAGSEGURO_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('EMAIL_ADDRESS'); ?></td>
											<td><input type="text" name="emailaddress_pagseguro" value="<?php echo $this->paymentmethodconfig['emailaddress_pagseguro']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('TOKEN'); ?></td>
											<td><input type="text" name="token_pagseguro" value="<?php echo $this->paymentmethodconfig['token_pagseguro']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td>
												<input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_pagseguro']; ?>" class="inputbox" maxlength="255" size="80" readonly="true" /><br clear="all" />
												<small><?php echo JText::_('PASTE_URL_IN_PAGSEGURO_NOTIFICATION_URL');?></small>
											</td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('PAGSEGURO_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_pagseguro', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_pagseguro']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="payfast">
								<fieldset>
									<legend><?php echo JText::_('PAYFAST_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('USERNAME'); ?></td>
											<td><input type="text" name="username_payfast" value="<?php echo $this->paymentmethodconfig['username_payfast']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('MERCHANT_ID'); ?></td>
											<td><input type="text" name="merchantid_payfast" value="<?php echo $this->paymentmethodconfig['merchantid_payfast']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('MERCHANT_KEY'); ?></td>
											<td><input type="text" name="merchantkey_payfast" value="<?php echo $this->paymentmethodconfig['merchantkey_payfast']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('PDT_KEY'); ?></td>
											<td><input type="text" name="pdtkey_payfast" value="<?php echo $this->paymentmethodconfig['pdtkey_payfast']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('RETURN_URL'); ?></td>
											<td>
												<input type="text" name="returnurl_payfast" value="<?php echo $this->paymentmethodconfig['returnurl_payfast']; ?>" class="inputbox" maxlength="255" size="80" readonly="true" /><br clear="all" />
											</td>
										</tr>
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td>
												<input type="text" name="notifyurl" value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_payfast']; ?>" class="inputbox" maxlength="255" size="80" readonly="true" /><br clear="all" />
											</td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('TESTMODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'testmode_payfast', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['testmode_payfast']); ?></td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('PAYFAST_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_payfast', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_payfast']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="ideal">
								<fieldset>
									<legend><?php echo JText::_('IDEAL_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td  class="key" width="25%"><?php echo JText::_('PARTNER_ID'); ?></td>
											<td><input type="text" name="partnerid_ideal" value="<?php echo $this->paymentmethodconfig['partnerid_ideal']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('RETURN_URL'); ?></td>
											<td><input type="text" name="returnurl_ideal" value="<?php echo $this->paymentmethodconfig['returnurl_ideal']; ?>" class="inputbox" size="<?php echo $big_field_width; ?>" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key" ><?php echo JText::_('NOTIFY_URL'); ?></td>
											<td><input id="notifyurl"  value="<?php echo JURI::root().$this->paymentmethodconfig['notifyurl_ideal']; ?>" class="inputbox" size="80" readonly="true" /></td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('TESTMODE'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'testmode_ideal', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['testmode_ideal']); ?></td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('IDEAL_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_ideal', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_ideal']); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div id="others">
								<fieldset>
									<legend><?php echo JText::_('OTHERS_SETTINGS'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>
											<td class="key"><?php echo JText::_('TITLE'); ?></td>
											<td ><input type="text" name="title_others" value="<?php echo $this->paymentmethodconfig['title_others']; ?>" class="inputbox" maxlength="255" /></td>
										</tr>
										<tr>
											<td class="key"><?php echo JText::_('OTHERS_ENABLED'); ?></td>
											<td ><?php echo JHTML::_('select.genericList', $yesno, 'isenabled_others', 'class="inputbox" '. '', 'value', 'text', $this->paymentmethodconfig['isenabled_others']); ?></td>
										</tr>
										<tr>
                                                                                    <td colspan="2" class="key"><?php echo JText::_('NOTE_PAST_THE_PRODUCT_URL_IN_SELLER_PACKAGE').'<br clear="all" />'.JText::_('PACKAGE_NOITFICATION_WILL_NOT_WORK'); ?></td>
										</tr>
									</table>
								</fieldset>
							</div>
						</div>
			<input type="hidden" name="task" value="savepaymentconf" />
			<input type="hidden" name="view" value="applications" />
			<input type="hidden" name="layout" value="paymentmethodconfig" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left"  valign="top">
			
		</td>
	</tr>
</table>
