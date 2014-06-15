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
 * File Name:	views/application/tmpl/viewjob.php
 ^ 
 * Description: template view for a job
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
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mydepartments&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_MY_DEPARTMENTS'); ?></a> > <?php echo JText::_('JS_VIEW_DEPARTMENT');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_DEPARTMENT_INFO');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php if( isset($this->department)){ ?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
      <tr>
        <td colspan="3" height="5"></td>
      </tr>
		<?php
		$trclass = array("odd", "even");
		
		$isodd = 0;
		?>
		<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td width="7"></td>
			<td class="maintext"><b><?php echo JText::_('JS_NAME'); ?></b></td>
			<td class="maintext"><?php echo $this->department->name; ?></td>
		</tr>
		<tr id="mc_field_row"  class="<?php echo $this->theme[$trclass[1 - $isodd]]; ?>"><td></td>
			<td class="maintext"><b><?php echo JText::_('JS_COMPANY'); ?></b></td>
			<td class="maintext">
			<?php
			if($this->isjobsharing) 	$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md='.$this->department->scompanyaliasid.'&Itemid='.$this->Itemid; 
			else $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md='.$this->department->companyaliasid.'&Itemid='.$this->Itemid; 
			?>
			
			<span id="anchor"><a class="anchor" href="<?php echo $link?>"><strong><?php echo $this->department->companyname; ?></strong></a></span>
			</td>
		</tr>
		<tr id="mc_field_row"  class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td width="7"></td>
			<td class="maintext"><b><?php echo JText::_('JS_DESCRIPTION'); ?></b></td>
			<td class="maintext"><?php echo $this->department->description; ?></td>
		</tr>
    </table>
<?php }else { ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND'); ?></b></div>
	</div>
	<?php
		 
		 }?>
<?php 
}//ol
?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
