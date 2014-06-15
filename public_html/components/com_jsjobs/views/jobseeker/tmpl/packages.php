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
 * File Name:	views/jobseeker/tmpl/packages.php
 ^ 
 * Description: template view for packages
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 global $mainframe;
 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=packages&Itemid='.$this->Itemid;
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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_PACKAGES');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_PACKAGES');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
$printform  = 1;
if(isset($this->userrole))
if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 1) { // employer
    if($this->config['employerview_js_controlpanel'] == 1)
        $printform = true;
    else{ ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
	<?php
        
        $printform = 0;
    }
}

if ($this->pagination == '0') { ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND');?></b></div>
	</div>
	<?php
	
    $printform = 0;
}
if( $printform == 1){

?>

	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		if ( isset($this->packages) ){
		foreach($this->packages as $package)	{ 
		$isodd = 1 - $isodd; ?>
		<tr id="mc_field_row" height="20" class="<?php echo $tdclass[$isodd]; ?>" > <td colspan="5">
                        <table cellpadding="0" cellspacing="1" border="0" width="100%" ><tr><td>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr id="mc_title_row">
                                            <td colspan="4" height="16" align="center" class="sectionheadline">
											<span id="sectionheadline_text">
											<span id="sectionheadline_left"></span>
                                            <?php echo $package->title; ?>
                                            <?php
                                                   $curdate = date('Y-m-d H:i:s');
                                                    if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)){
                                                        if($package->discountmessage) echo $package->discountmessage;
                                                    }
                                            ?>
											<span id="sectionheadline_right"></span>
											</span>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_RESUME_ALLOWED'); ?>	</strong></td>
                                            <td width="30%"><?php if($package->resumeallow == -1) echo JText::_('JS_UNLIMITED'); else echo $package->resumeallow; ?></td>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_COVERLETTERS_ALLOWED'); ?></strong></td>
                                            <td width="30%"><?php if($package->coverlettersallow == -1) echo JText::_('JS_UNLIMITED'); else echo $package->coverlettersallow; ?></td>
                                    </tr>
                                    <tr>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_JOB_SEARCH'); ?></strong></td>
                                            <td width="30%"><?php if($package->jobsearch == 1) echo JText::_('JS_YES'); else echo JText::_('JS_NO'); ?></td>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_SAVE_JOB_SEARCH'); ?></strong></td>
                                            <td width="30%"><?php if($package->savejobsearch == 1) echo JText::_('JS_YES'); else echo JText::_('JS_NO'); ?></td>
                                    </tr>
                                    <tr>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_FEATURED_RESUMES'); ?>	</strong></td>
                                            <td width="30%"><?php if($package->featuredresume == -1) echo JText::_('JS_UNLIMITED'); else echo $package->featuredresume; ?></td>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_GOLD_RESUMES'); ?></strong></td>
                                            <td width="30%"><?php if($package->goldresume == -1) echo JText::_('JS_UNLIMITED'); else echo $package->goldresume; ?></td>
                                    </tr>
                                    <tr>

                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_APPLY_JOBS'); ?></strong></td>
                                            <td width="30%"><?php if($package->applyjobs == -1) echo JText::_('JS_UNLIMITED'); else echo $package->applyjobs; ?></td>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_PACKAGE_EXPIRE_IN_DAYS'); ?></strong></td>
                                            <td width="30%"><?php echo $package->packageexpireindays; ?></td>
                                    </tr>
                                    <tr>
                                            <td width="30%">&nbsp;<strong><?php echo JText::_('JS_FEATURED_RESUME_EXPIRE_IN_DAYS'); ?></strong></td>
                                            <td width="10%"><?php echo $package->freaturedresumeexpireindays; ?></td>
                                            <td width="25%">&nbsp;<strong><?php echo JText::_('JS_GOLD_RESUME_EXPIRE_IN_DAYS'); ?></strong></td>
                                            <td width="35%"><?php echo $package->goldresumeexpireindays; ?></td>
                                    </tr>

                                    </tr>

                                    <tr><td height="5"></td></tr>
                                    <tr>
                                            <td colspan="4"><?php echo $package->shortdetails; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
											<div id="pkg_price_list">
												<span id="price_title"><?php echo JText::_('JS_PRICE'); ?>:</span>
												<span id="price_data">
													<?php
														if ($package->price != 0){
														   $curdate = date('Y-m-d H:i:s');
															if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)){
																 if($package->discounttype == 2){
																	 $discountamount = ($package->price * $package->discount)/100;
																	  $discountamount = $package->price - $discountamount;
																	 echo $package->symbol.$discountamount.' [ '. $package->discount .'% '.JText::_('JS_DISCOUNT').' ]';
																 }elseif($package->discounttype == 1){
																	 $discountamount = $package->price - $package->discount;
																	 echo $package->symbol.$discountamount.' [ '. JText::_('JS_DISCOUNT').' : '.$package->symbol.$package->discount .' ]';
																 }
															}else echo $package->symbol.$package->price;
														}else{ echo JText::_('JS_FREE'); } ?>
												</span>
											</div>
										</td>
                                    </tr>
                                    <tr>
										<td id="btn_gap_td" colspan="4" align="center">
											<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=package_details&gd='.$package->id.'&Itemid='.$this->Itemid; ?>
											<a id="button" class="button minpad" href="<?php echo $link?>" class="pageLink"><strong><?php echo JText::_('JS_VIEW'); ?></strong></a>
											<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=package_buynow&pb=1&gd='.$package->id.'&Itemid='.$this->Itemid; ?>
											<a id="button" class="button minpad" href="<?php echo $link?>" class="pageLink"><strong><?php echo JText::_('JS_BUY_NOW'); ?></strong></a>
										</td>
                                    </tr>
                                    <tr><td height="3"></td></tr>
                            </table>
                        </td></tr></table>
		</td></tr>
		<tr><td colspan="5" height="5"></td></tr>
		<?php
		}
		} ?>		
	</table>
	<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=packages&Itemid='.$this->Itemid); ?>" method="post">
	<div id="jl_pagination">
		<div id="jl_pagination_pageslink">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<div id="jl_pagination_box">
			<?php	
				echo JText::_('JS_DISPLAY_#');
				echo $this->pagination->getLimitBox();
			?>
		</div>
		<div id="jl_pagination_counter">
			<?php echo $this->pagination->getResultsCounter(); ?>
		</div>
	</div>
	</form>	<?php
}

}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
