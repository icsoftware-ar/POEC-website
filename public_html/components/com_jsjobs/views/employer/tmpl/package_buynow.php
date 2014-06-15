<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 17, 2010
 ^
 + Project: 		JS Jobs
 * File Name:	views/employer/tmpl/package_buynow.php
 ^ 
 * Description: template buy now package
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.component.model');
 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=package_buynow&Itemid='.$this->Itemid;
 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);

?>
<?php if ($this->config['offline'] == '1'){ ?>
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
		</div>
	</div>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo $this->config['offline_text']; ?></b></div>
	</div>
<?php }else{ ?>
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
			<span id="tp_curloc">
				<?php if ($this->config['cur_location'] == 1) {
					$pb = $this->pb; 
					if ($pb == '1'){
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=packages&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_PACKAGES'); ?></a> > <?php echo JText::_('JS_PACKAGE_BUY_NOW');
					}elseif ($pb == '2'){
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=packages&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_PACKAGES'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=package_details&gd=<?php echo $this->package->id; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_PACKAGE_DETAILS'); ?></a> > <?php echo JText::_('JS_PACKAGE_BUY_NOW');
					}
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			if (sizeof($this->jobseekerlinks) != 0){
				foreach($this->jobseekerlinks as $lnk)	{ ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			}
			if (sizeof($this->employerlinks) != 0){
				foreach($this->employerlinks as $lnk)	{ ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center">
				<?php echo '[ '.$this->package->title;
					$curdate = date('Y-m-d H:i:s');
					if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)){
						if($this->package->discountmessage) echo $this->package->discountmessage;
					}
					echo '] '.JText::_('JS_BUY_NOW');  ?>
				</span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->userrole->rolefor == 1) { // employer
?>
<form action="index.php" method="post" name="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		
		if ( isset($this->package) ){
		$isodd = 1 - $isodd; ?>
		<tr id="mc_field_row" class="<?php echo $tdclass[$isodd]; ?>" > <td colspan="5">
			<table cellpadding="0" cellspacing="1" border="0" width="100%">
				<tr><td height="3"></td></tr>
				<tr id="mc_title_row" class="<?php echo $tdclass[1-$isodd]; ?>">
					<td colspan="4" height="16" align="center" class="sectionheadline">
					<span id="sectionheadline_text">
					<span id="sectionheadline_left"></span>
					<?php echo $this->package->title;
						  $curdate = date('Y-m-d H:i:s');
						if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)){
							if($this->package->discountmessage) echo $this->package->discountmessage;
						}
					?>					<span id="sectionheadline_right"></span>
					</span>
					</td>
				</tr>
                 <tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_COMPANIES_ALLOWED'); ?>	</strong></td>
					<td width="30%"><?php if($this->package->companiesallow == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->companiesallow; ?></td>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBS_ALLOWED'); ?></strong></td>
					<td width="30%"><?php if($this->package->jobsallow == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->jobsallow; ?></td>
				</tr>
                 <tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_VIEW_RESUME_IN_DETAILS'); ?>	</strong></td>
					<td width="30%"><?php if($this->package->viewresumeindetails == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->viewresumeindetails; ?></td>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_RESUME_SEARCH'); ?></strong></td>
					<td width="30%"><?php if($this->package->resumesearch == 1) echo JText::_('JS_YES'); else echo JText::_('JS_NO'); ?></td>
				</tr>
                 <tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_FEATURED_COMPANIES'); ?>	</strong></td>
					<td width="30%"><?php if($this->package->featuredcompaines == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->featuredcompaines; ?></td>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_GOLD_COMPANIES'); ?></strong></td>
					<td width="30%"><?php if($this->package->goldcompanies == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->goldcompanies; ?></td>
				</tr>
                 <tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_FEATURED_JOBS'); ?>	</strong></td>
					<td width="30%"><?php if($this->package->featuredjobs == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->featuredjobs; ?></td>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_GOLD_JOBS'); ?></strong></td>
					<td width="30%"><?php if($this->package->goldjobs == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->goldjobs; ?></td>
				</tr>
                 <tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_SAVE_RESUME_SEARCH'); ?></strong></td>
					<td width="30%"><?php if($this->package->saveresumesearch == 1) echo JText::_('JS_YES'); else echo JText::_('JS_NO'); ?></td>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBSEEKER_SHORT_LIST'); ?></strong></td>
					<td width="30%"><?php if($this->package->jobseekershortlist == 1) echo JText::_('JS_YES'); else echo JText::_('JS_NO'); ?></td>
				</tr>
                 <tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_PACKAGE_EXPIRE_IN_DAYS'); ?></strong></td>
					<td colspan="4" align="center"><?php echo $this->package->packageexpireindays; ?></td>	
				</tr>
                 <tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_SHORT_DETAILS'); ?></strong></td>
					<td colspan="4"><?php echo $this->package->shortdetails; ?></td>				
				</tr>
                 <tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></td>
                     <td colspan="4"><?php echo $this->package->description; ?></td>
                 </tr>
				<tr><td height="5"></td></tr>
                 <tr>
					<td colspan="4">
						<div id="pkg_price_list">
							<span id="price_title"><?php echo JText::_('JS_PRICE'); ?>:</span>
							<span id="price_data">
								<?php
									if ($this->package->price != 0){
									   $curdate = date('Y-m-d H:i:s');
										if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)){
											 if($this->package->discounttype == 2){
												 $discountamount = ($this->package->price * $this->package->discount)/100;
												  $discountamount = $this->package->price - $discountamount;
												 echo $this->package->symbol.$discountamount.' [ '. $this->package->discount .'% '.JText::_('JS_DISCOUNT').' ]';
											 }else{
												 $discountamount = $this->package->price - $this->package->discount;
												 echo $this->package->symbol.$discountamount.' [ '. JText::_('JS_DISCOUNT').' : '.$this->package->symbol.$this->package->discount .' ]';
											 }
										}else {echo $this->package->symbol.$this->package->price;$discountamount = 1;}
									}else{ echo JText::_('JS_FREE'); }
								?>
							</span>
						</div>
					</td>
				</tr>
				
			</table>
        <?php if (($this->package->price == 0) || ($discountamount == 0) ){
            $showpaymentmethod = false;
        }else{
            $showpaymentmethod = true;
		}
        if($showpaymentmethod == true){ ?>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" id="jsjobs_paymentmethodstable">
			<tr>
				<th><?php echo JText::_('JS_PAYMENT_METHODS'); ?></th>
			</tr>
				<?php
					if(isset($this->paymentmethod)){
						
						foreach($this->paymentmethod AS $key=>$paymethod) {
							$methodname = 'isenabled_'.$key;
							if($key=='ideal'){
								$partner_id=$this->idealdata['ideal']['partnerid_ideal'];
								$ideal_testmode=$this->idealdata['ideal']['testmode_ideal'];
								$idealhelperclasspath = "components/com_jsjobs/classes/ideal/Payment.php";
								include_once($idealhelperclasspath);
								$idealhelperobject = new Mollie_iDEAL_Payment($partner_id);
								if($ideal_testmode==1) $bank_array = $idealhelperobject->getBanks();
							}
							if($paymethod[$methodname] == 1){
							 ?>
			<tr>
				<td>
					<div id="jsjobs_paymentmethodstd_div">
						<span id="jsjobs_paymentmehtodtd_div_span"><?php echo $paymethod['title_'.$key]; ?></span>
							<?php if($key=='ideal') { ?>
								<select name="bank_id">
									<option value=''><?php echo JText::_('JS_SELECT_BANK') ?></option>
									<?php if(isset($bank_array) AND (is_array($bank_array))){
											 foreach ($bank_array as $bank_id => $bank_name) { ?>
											<option value="<?php echo htmlspecialchars($bank_id) ?>"><?php echo htmlspecialchars($bank_name) ?></option>
										<?php } 
									 }else { ?>
											<option value="0"><?php echo JText::_('JS_NO_BANK_FOUND') ?></option>
									<?php } ?>
								</select>
							<?php  } ?>
							<input id="jsjobs_button" class="button" rel="button" type="button" onclick="setpaymentmethods('<?php echo $key ; ?>')" name="submit_app" value="<?php echo JText::_('JS_BUY_NOW'); ?>" />
					</div>
				</td>
			</tr>
				<?php $isodd = 1 - $isodd;       
					}
					}
				}?>
        </table>			
			
        <?php }elseif($showpaymentmethod == false){?>
				<div style="float:right;">
					<input id="jsjobs_button" class="button"  type="button" rel="button" onclick="setpaymentmethods('free')" name="submit_app" value="<?php echo JText::_('JS_BUY_NOW'); ?>" />
				</div>
       <?php } ?>
		 
		 
		 
		 
		<?php
		//}
		}
		 ?>
			</td>
		</tr>
	</table>
		 
			<input type="hidden" name="task" value="saveemployerpayment" />
			<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
			<input type="hidden" name="packageid" value="<?php if(isset($this->package)) echo $this->package->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />

			<input type="hidden" name="packagefor" id="packagefor" value="1"  />
			<input type="hidden" name="paymentmethod" id="paymentmethod"  />
			<input type="hidden" name="paymentmethodid" id="paymentmethodid"  />
			
	</form>
	<?php
} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php

}	
}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
<script type="text/javascript">
function setpaymentmethods(paymethod){
var paymethodvalue = document.getElementById('paymentmethod').value=paymethod;
document.adminForm.submit();
}
</script>


