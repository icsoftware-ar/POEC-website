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
 * File Name:	views/employer/tmpl/mydepartments.php
 ^
 * Description: template view for my departments
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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_DEPARTMENTS');
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
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formdepartment&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_NEW_DEPARTMENT');
			$count = 0;
			if (sizeof($this->employerlinks) != 0){
				foreach($this->employerlinks as $lnk)	{ 
				 if ($this->config['tmenu_emnewdepartment'] == 1) {
					if ($count == 1) { ?>
						<a href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
					<?php }	?>
					<?php }	?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php $count++;	
				}
			} 
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_MY_DEPARTMENTS');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->departments){

if ($this->userrole->rolefor == 1) { // employer


?>
<form action="index.php" method="post" name="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<tr id="mc_title_row" class="<?php echo $this->theme['sortlinks']; ?>" valign="middle" height="16">
			<td valign="center" class="sectionheadline" align="left" >
				<?php echo JText::_('JS_NAME'); ?>
			</td>
			<td valign="center" class="sectionheadline" align="left" >
				<?php echo JText::_('JS_COMPANY'); ?>
			</td>
			<td width="30" class="sectionheadline"><?php echo JText::_('JS_STATUS'); ?></td>
			<td class="sectionheadline" width="30"></td>
			<td class="sectionheadline" width="20"></td>
			<td class="sectionheadline" width="30"></td>
			<td class="sectionheadline" width="30"></td>
		</tr>
		
		<?php 
		
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		foreach($this->departments as $department)	{ 
			$isodd = 1 - $isodd; 
			?>
			<tr id="mc_field_row" class="<?php echo $tdclass[$isodd]; ?>"> 
						<td class="maintext">&nbsp;<?php echo $department->name;?></td>
						<td class="maintext"><?php echo $department->companyname; ?></td>
						<td class="maintext"><strong><?php if ($department->status == 1) echo '<font color="green">'.JText::_('JS_APPROVED').'</font>'; elseif ($department->status == 0) echo  JText::_('JS_PENDDING');  ?></strong></td>
						<td></td>
					<td class="maintext" align="center">
						<span id="icon">
							<a class="icon" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formdepartment&pd=<?php echo $department->aliasid; ?>" title="<?php echo  JText::_('JS_EDIT');  ?>"><img width="15" height="15" src="components/com_jsjobs/images/edit.png" /></a>
						</span>
					</td>
					<td class="maintext" align="center" valign="middle">
						<span id="icon">
							<a class="icon" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_department&vp=1&pd=<?php echo $department->aliasid; ?>" title="<?php echo  JText::_('JS_VIEW');  ?>" >
								<img width="15" height="15" src="components/com_jsjobs/images/view.png" />
							</a>
						</span>
					</td>
					<td class="maintext" align="center">
						<span id="icon">
							<a class="icon" href="index.php?option=com_jsjobs&c=jsjobs&task=deletedepartment&pd=<?php echo $department->aliasid; ?>" title="<?php echo  JText::_('JS_DELETE');  ?>">
								<img width="15" height="15" src="components/com_jsjobs/images/delete.png" />
							</a>
						</span>
					</td>
						
					</tr>
					
		<?php 
		}
		?>		
	</table>
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="deletedepartment" />
			<input type="hidden" id="id" name="id" value="" />

	</form>

<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mydepartments&Itemid='.$this->Itemid); ?>" method="post">
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
}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
