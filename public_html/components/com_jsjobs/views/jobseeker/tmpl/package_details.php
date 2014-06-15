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
 * File Name:	views/jobseeker/tmpl/package_details.php
 ^ 
 * Description: template view for package details
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 global $mainframe;
 //$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=packages&Itemid='.$this->Itemid;
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
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=packages&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_PACKAGES'); ?></a> > <?php echo JText::_('JS_PACKAGE_DETAILS');
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
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_PACKAGE_DETAILS');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
$printform = 1;
if(isset($this->userrole))
if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 1) { // employer
    if($this->config['employerview_js_controlpanel'] == 1)
        $printform = true;
    else{
        echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');
        $printform = 0;
    }
}

if($printform == 1){


?>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		
		if ( isset($this->package) ){
		//foreach($this->package as $package)	{ 
		$isodd = 1 - $isodd; ?>
		<tr id="mc_field_row" height="20" class="<?php echo $tdclass[$isodd]; ?>" > <td colspan="5">
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
						?>
					<span id="sectionheadline_right"></span>
					</span>
					</td>
				</tr>
				<tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_RESUME_ALLOWED'); ?>	</strong></td>
					<td width="30%"><?php if($this->package->resumeallow == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->resumeallow; ?></td>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_COVERLETTERS_ALLOWED'); ?></strong></td>
					<td width="30%"><?php if($this->package->coverlettersallow == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->coverlettersallow; ?></td>
				</tr>
				<tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_JOB_SEARCH'); ?></strong></td>
					<td width="30%"><?php if($this->package->jobsearch == 1) echo JText::_('JS_YES'); else echo JText::_('JS_NO'); ?></td>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_SAVE_JOB_SEARCH'); ?></strong></td>
					<td width="30%"><?php if($this->package->savejobsearch == 1) echo JText::_('JS_YES'); else echo JText::_('JS_NO'); ?></td>
				</tr>
				<tr>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_FEATURED_RESUMES'); ?>	</strong></td>
					<td width="30%"><?php if($this->package->featuredresume == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->featuredresume; ?></td>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_GOLD_RESUMES'); ?></strong></td>
					<td width="30%"><?php if($this->package->goldresume == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->goldresume; ?></td>
				</tr>
				<tr>
				
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_APPLY_JOBS'); ?></strong></td>
					<td width="30%"><?php if($this->package->applyjobs == -1) echo JText::_('JS_UNLIMITED'); else echo $this->package->applyjobs; ?></td>
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_PACKAGE_EXPIRE_IN_DAYS'); ?></strong></td>
					<td width="30%"><?php echo $this->package->packageexpireindays; ?></td>	
				</tr>	
				<tr>
					<td width="30%">&nbsp;<strong><?php echo JText::_('JS_FEATURED_RESUME_EXPIRE_IN_DAYS'); ?></strong></td>
					<td width="10%"><?php echo $this->package->freaturedresumeexpireindays; ?></td>	
					<td width="25%">&nbsp;<strong><?php echo JText::_('JS_GOLD_RESUME_EXPIRE_IN_DAYS'); ?></strong></td>
					<td width="35%"><?php echo $this->package->goldresumeexpireindays; ?></td>	
				</tr>
			   
				<tr><td height="5"></td></tr>
				<tr>
				
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_SHORT_DETAILS'); ?></strong></td>
					<td colspan="4"><?php echo $this->package->shortdetails; ?></td>				
				</tr>
				<tr><td height="5"></td></tr>
				<tr>
				
					<td width="20%">&nbsp;<strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></td>
                                         <td colspan="4"><?php echo $this->package->description; ?></td>
                                </tr>
				<tr><td height="5"></td></tr>
				<tr >
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
										}else echo $this->package->symbol.$this->package->price;
									}else{ echo JText::_('JS_FREE'); } ?>
							</span>
						</div>
					</td>
				</tr>
				<tr>
				<td colspan="5" align="center">
					<div id="pkg_bn_btn">
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=package_buynow&pb=2&gd='.$this->package->id.'&Itemid='.$this->Itemid; ?>
						<a id="button" class="button" href="<?php echo $link?>" class="pkgLink"><?php echo JText::_('JS_BUY_NOW'); ?></a>
					</div>
				</td>
			</tr>
				<tr><td height="5"></td></tr>
				
				
				<tr><td height="3"></td></tr>
			</table>	
		</td></tr>
		<tr> <td colspan="5" height="1">	</td></tr>
		<?php
		//}
		}
		 ?>	
			<tr><td colspan="2" height="10"></td></tr>	  
	
	</table>
			
			
	<?php
}
}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
