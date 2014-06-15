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
 * File Name:	views/jobseeker/mycoverletters.php
 ^
 * Description: view for my coverletters
 ^
 * History:		NONE
 ^
 */
 
 defined('_JEXEC') or die('Restricted access');

 global $mainframe;
 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
     
 $link = "index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=".$this->Itemid;
 $resumecatlink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$this->Itemid;

// echo 's '.$this->options;
?>
<script language=Javascript>
    function confirmdeletecoverletter(){
        return confirm("<?php echo JText::_('JS_ARE_YOU_SURE_DELETE_THE_COVER_LETTER'); ?>");
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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_COVER_LETTERS');
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_ADD_COVER_LETTER');
			$count = 0;
			if (sizeof($this->jobseekerlinks) != 0){
				foreach($this->jobseekerlinks as $lnk)	{ 
				 if ($this->config['tmenu_jsaddcoverletter'] == 1) {
					if ($count == 1) { ?>
						<a href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
					<?php }
					}	?>
						<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php $count++;	
				}
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_MY_COVER_LETTERS');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
	if ($this->coverletters){

if ($this->userrole->rolefor == 2) { // job seeker

?>
<form action="index.php" method="post" name="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<tr id="mc_title_row" height="17" valign="center">
			<td class="sectionheadline" width="65%"><?php echo JText::_('JS_TITLE'); ?></td>
			<td class="sectionheadline" width="25"><?php echo JText::_('JS_CREATED'); ?></td>
			<td class="sectionheadline" width="10"></td>
			<td class="sectionheadline" width="10"></td>
			<td class="sectionheadline" width="10"></td>
		</tr>
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isnew = date("Y-m-d H:i:s", strtotime("-".$this->config['newdays']." days"));
		//$tdclass = array("odd", "even");
		$isodd =1;
		jimport('joomla.filter.output');
		$i=0;
			foreach($this->coverletters as $letter)	{ 

			$row = $letter;
			$checked = JHTML::_('grid.id', $i, $row->id);
			$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=edit&cid[]='.$row->id);

			$i++;
					$isodd = 1 - $isodd; ?>
				<tr id="mc_field_row" height="30" class="<?php echo $tdclass[$isodd]; ?>" > 
					<td><?php echo $letter->title;	?></td>
					<td><?php echo date($this->config['date_format'],strtotime($letter->created)); ?></td>
					<td  align="center" valign="middle">
						<span id="icon">
							<a class="icon"  href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&cl=<?php echo $letter->aliasid; ?>"  title="<?php echo JText::_('JS_EDIT'); ?>">
								<img width="15" height="15" src="components/com_jsjobs/images/edit.png" />
							</a>
						</span>
					</td>
					<td align="center" valign="middle">
						<span id="icon">
							<a class="icon" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_coverletter&vct=1&cl=<?php echo $letter->aliasid; ?>" title="<?php echo JText::_('JS_VIEW'); ?>">
								<img width="15" height="15" src="components/com_jsjobs/images/view.png" />
							</a>
						</span>
					</td>
					<td  align="center" valign="middle">
						<span id="icon">
							<a class="icon" href="index.php?option=com_jsjobs&c=jsjobs&task=deletecoverletter&cl=<?php echo $letter->aliasid; ?>" onclick=" return confirmdeletecoverletter();" title="<?php echo JText::_('JS_DELETE'); ?>">
								<img  width="15" height="15" src="components/com_jsjobs/images/delete.png" />
							</a>
						</span>
					</td>
				</tr>
				<?php
			}
		 ?>		
	</table>
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="deletecoverletter" />
			<input type="hidden" id="id" name="id" value="" />
			<input type="hidden" name="boxchecked" value="0" />

	</form>
	<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid='.$this->Itemid); ?>" method="post">
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

} else{ // not allowed job posting?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php
}	
}else{ // no result found in this category?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND');?></b></div>
	</div>
<?php 
	
}
}//ol
?>	

<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
