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
 * File Name:	views/employer/tmpl/package_details.php
 ^ 
 * Description: template view package detail
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 global $mainframe;
 //$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$this->Itemid;
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
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=packages&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_PACKAGES'); ?></a> > <?php echo JText::_('JS_PACKAGE_DETAILS'); ?>
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
				echo '] '.JText::_('JS_PACKAGE_DETAILS');  ?>
				</span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if (isset($this->userrole->rolefor)){
        if ($this->userrole->rolefor == 1) // employer
            $allowed = true;
        else
            $allowed = false;
}else { if ($this->config['visitorview_emp_viewpackage'] == 1) $allowed = true; else $allowed = false; } // user not logined
if ($allowed == true) {
?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		
		if ( isset($this->package) ){
		$isodd = 1 - $isodd; ?>
			<tr id="mc_field_row" class="<?php echo $tdclass[1-$isodd]; ?>"> <td>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" >
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
					<td width="30%">&nbsp;<strong><?php echo JText::_('JS_FEATURED_COMPANIES_EXPIRE_IN_DAYS'); ?></strong></td>
					<td width="10%"><?php echo $this->package->featuredcompaniesexpireindays; ?></td>	
					<td width="25%">&nbsp;<strong><?php echo JText::_('JS_FEATURED_JOBS_EXPIRE_IN_DAYS'); ?></strong></td>
					<td width="35%"><?php echo $this->package->featuredjobsexpireindays; ?></td>	
				</tr>
				<tr>
					<td width="30%">&nbsp;<strong><?php echo JText::_('JS_GOLD_COMPANIES_EXPIRE_IN_DAYS'); ?></strong></td>
					<td width="10%"><?php echo $this->package->goldcompaniesexpireindays; ?></td>	
					<td width="25%">&nbsp;<strong><?php echo JText::_('JS_GOLD_JOBS_EXPIRE_IN_DAYS'); ?></strong></td>
					<td width="35%"><?php echo $this->package->goldjobsexpireindays; ?></td>	
				</tr>
			   
			<tr>
				<td width="20%">&nbsp;<strong><?php echo JText::_('JS_SAVE_RESUME_SEARCH'); ?></strong></td>
				<td width="30%"><?php if($this->package->saveresumesearch == 1) echo JText::_('JS_YES'); else echo JText::_('JS_NO'); ?></td>
				<td>&nbsp;<strong><?php echo JText::_('JS_PACKAGE_EXPIRE_IN_DAYS'); ?></strong></td>
				<td colspan="4"><?php echo $this->package->packageexpireindays; ?></td>		
			</tr>
			<tr>
			
				<td width="20%">&nbsp;<strong><?php echo JText::_('JS_SHORT_DETAILS'); ?></strong></td>
				<td colspan="4"><?php echo $this->package->shortdetails; ?></td>				
			</tr>
			<tr>
				<td width="20%">&nbsp;<strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></td>
				<td colspan="4"><?php echo $this->package->description; ?></td>
			</tr>
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
									}else echo $this->package->symbol.$this->package->price;
								}else{ echo JText::_('JS_FREE'); } ?>
						</span>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<div id="pkg_bn_btn">
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=package_buynow&pb=2&gd='.$this->package->id.'&Itemid='.$this->Itemid; ?>
						<a id="button" class="button" href="<?php echo $link?>"><?php echo JText::_('JS_BUY_NOW'); ?></a>
					</div>
				</td>
			</tr>
		</table></td></tr></table>
		<?php
		//}
		}
		 ?>	
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
