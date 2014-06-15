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
 * File Name:	views/employer/tmpl/mycompanies.php
 ^ 
 * Description: template view for my companies
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
global $mainframe;
$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
?>

<script language=Javascript>
    function confirmdeletecompany(){
        return confirm("<?php echo JText::_('JS_ARE_YOU_SURE_DELETE_THE_COMPANY'); ?>");
    }
</script>
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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_COMPANIES');
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_NEW_COMPANY');
			$count = 0;
			if (sizeof($this->employerlinks) != 0){
				foreach($this->employerlinks as $lnk)	{ 
					if ($this->config['tmenu_emnewcompany'] == 1) {
						if ($count == 1) { ?>
							<a href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
						<?php }	
					} ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>	
				<?php $count++;	
				}
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_MY_COMPANIES');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>

<?php
if ($this->companies){

if ($this->userrole->rolefor == 1) { // employer


?>
<form action="index.php" method="post" name="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<tr  id="mc_title_row" valign="middle" height="16">
			<td valign="center" align="left" class="sectionheadline"><?php echo JText::_('JS_NAME'); ?></td>
			<td valign="center" align="center" class="sectionheadline"><?php echo JText::_('JS_CATEGORY'); ?></td>
			<td width="25%" class="sectionheadline" align="center"><?php echo JText::_('JS_STATUS'); ?></td>
			<td class="sectionheadline" width="30"></td>
			<td class="sectionheadline" width="20"></td>
			<td class="sectionheadline" width="30"></td>
			<td class="sectionheadline" width="30"></td>
			<td class="sectionheadline" width="30"></td>
			<td class="sectionheadline" width="30"></td>
		</tr>
		
		<?php 
		$isnew = date("Y-m-d H:i:s", strtotime("-".$this->config['newdays']."days"));
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		foreach($this->companies as $company)	{ 
			$isodd = 1 - $isodd; 
			$g_f_company = 0;
			if($company->isgold == 1) $g_f_company = 1; // gold company
			if($company->isfeatured == 1) $g_f_company = 2; // featured company
			if($company->isgold == 1 && $company->isfeatured == 1) $g_f_company = 3;//gold and featured company
			?>
			<tr id="mc_field_row" class="<?php echo $tdclass[$isodd]; ?>" height="20">
						<td >&nbsp;<?php echo $company->name;?></td>
						<td align="center" ><?php echo $company->cat_title; ?></td>
						<td align="center" ><strong><?php if ($company->status == 1) echo '<font color="green">'.JText::_('JS_APPROVED').'</font>'; elseif ($company->status == 0) {echo '<span class="jobstatusmsg"> '. JText::_('JS_PENDDING'). '</span>';} elseif ($company->status == -1) echo '<font color="red"> '. JText::_('JS_REJECTED'). '</font>'; ?></strong></td>
						<td></td>
					<td  align="center">
						<span id="icon">
							<a class="icon" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&md=<?php echo $company->aliasid; ?>"  title="<?php echo JText::_('JS_EDIT'); ?>">
								<img width="15" height="15" src="components/com_jsjobs/images/edit.png" />
							</a>
						</span>
					</td>
					<td  align="center" valign="middle">
						<span id="icon">
								<?php 
									if($this->isjobsharing) $com_view_link="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md=".$company->saliasid;  
									else $com_view_link="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md=".$company->aliasid;
								?>
								<a class="icon" href="<?php echo $com_view_link; ?>"  title="<?php echo JText::_('JS_VIEW'); ?>">
									<img width="15" height="15" src="components/com_jsjobs/images/view.png" />
								</a>
						</span>
					</td>
					<td  align="center">
						<span id="icon">
							<a class="icon" href="index.php?option=com_jsjobs&c=jsjobs&task=deletecompany&md=<?php echo $company->aliasid; ?>" onclick=" return confirmdeletecompany();"  title="<?php echo JText::_('JS_DELETE'); ?>">
								<img width="15" height="15" src="components/com_jsjobs/images/delete.png" />
							</a>
						</span>
					</td>
					
					<td  align="center">
					<?php if ($company->status == 1) { ?> 
					<?php $link = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&task=addtogoldcompany&md='.$company->aliasid.'&Itemid='.$this->Itemid); ?>
					<span id="icon">
						<?php if($g_f_company == 1 || $g_f_company == 3){?>
						<img width="15" height="15" src="components/com_jsjobs/images/gold.png" title="<?php echo JText::_('JS_GOLD_COMPANY');?>"/>
						<?php }else{ ?>
						<a class="icon" href="<?php echo $link?>"  title="<?php echo JText::_('JS_ADD_TO_GOLD_COMPANIES'); ?>"><img width="15" height="15" src="components/com_jsjobs/images/addgold.png" /></a>
						<?php } ?>
					</span>
					<?php } ?>
					</td>
					<td  align="center">
					<?php if ($company->status == 1) { ?> 
					<?php $link = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&task=addtofeaturedcompany&md='.$company->aliasid.'&Itemid='.$this->Itemid); ?>
					<span id="icon">
						<?php if($g_f_company == 2 || $g_f_company == 3){ ?>
						<img width="15" height="15" src="components/com_jsjobs/images/featured.png" title="<?php echo JText::_('JS_FEATURED_COMPANY');?>"/>
						<?php }else{ ?>
						<a class="icon" href="<?php echo $link?>" title="<?php echo JText::_('JS_ADD_TO_FEATURED_COMPANIES'); ?>"><img width="15" height="15" src="components/com_jsjobs/images/addfeatured.png" /></a>
						<?php } ?>
					</span>
					<?php } ?>
					</td>
					</tr>
					
		<?php 
		}
		?>		
	</table>
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="deletecompany" />
		<input type="hidden" id="id" name="id" value="" />

	</form>

<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$this->Itemid); ?>" method="post">
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
</form>	
<?php

} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php

}	
}else{ // no result found in this category ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND');?></b></div>
	</div>
<?php
	
}
}  //ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
