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
 * File Name:	views/employer/tmpl/packages.php
 ^ 
 * Description: template view packages
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 global $mainframe;
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
						echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_PURCHASE_HISTORY');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_PURCHASE_HISTORY');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->userrole->rolefor == 1) { // employer

if(isset($this->packages)) {

?>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		if ( isset($this->packages) ){
		foreach($this->packages as $package)	{ 
		$isodd = 1 - $isodd; ?>
		<tr><td colspan="5" height="5"></td></tr>
		<tr id="mc_field_row" class="<?php echo $tdclass[$isodd]; ?>" > <td colspan="5">
                            <table cellpadding="0" cellspacing="1" border="0" width="100%">
                                    <tr><td height="3"></td></tr>
                                    <tr height="16">
                                            <td colspan="4" align="center" class="sectionheadline">
												<span id="sectionheadline_text">
												<span id="sectionheadline_left"></span>
												<span id="anchor"><a class="anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=package_details&gd=<?php echo $package->id; ?>&Itemid=<?php echo $this->Itemid; ?>"><?php echo $package->title; ?></a></span>
												<span id="sectionheadline_right"></span>
												</span>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_COMPANIES_ALLOWED'); ?>	</strong></td>
                                            <td width="30%"><?php if($package->companiesallow == -1) echo JText::_('JS_UNLIMITED'); else echo $package->companiesallow; ?></td>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBS_ALLOWED'); ?></strong></td>
                                            <td width="30%"><?php if($package->jobsallow == -1) echo JText::_('JS_UNLIMITED'); else echo $package->jobsallow; ?></td>
                                    </tr>
                                    <tr>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_PACKAGE_EXPIRE_IN_DAYS'); ?>	</strong></td>
                                            <td width="30%"><?php echo $package->packageexpireindays; ?></td>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_PAID_AMOUNT'); ?></strong></td>
                                            <td width="30%"><?php if ($package->paidamount !=0) echo $package->symbol.$package->paidamount;else echo $package->paidamount; ?></td>
                                    </tr>
                                    <tr>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_PAYMENT'); ?>	</strong></td>
                                            <td width="30%"><?php if($package->transactionverified == 1) echo JText::_('JS_VERIFIED'); else echo '<strong>'.JText::_('JS_NOT_VERIFIED').'</strong>'; ?></td>
                                            <td width="20%">&nbsp;<strong><?php echo JText::_('JS_BUY_DATE'); ?></strong></td>
											<td width="30%"><?php echo date($this->config['date_format'],strtotime($package->created)); ?></td>
                                    </tr>
                                   
                                    <tr><td height="3"></td></tr>
                            </table>
		</td></tr>
		<?php
		}
		} ?>		
	</table>
	<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=purchasehistory&Itemid='.$this->Itemid); ?>" method="post">
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
}else{ // no result found in this category ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND');?></b></div>
	</div>
<?php
	
}

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
