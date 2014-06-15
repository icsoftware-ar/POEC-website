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
 * File Name:	views/jobseeker/tmpl/formcoverletter.php
 ^ 
 * Description: template for form cover letter
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
global $mainframe;
$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);

$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;
JHTML::_('behavior.formvalidation'); 
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
<script language="javascript">
function myValidate(f) {
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
        }else {
                alert('<?php echo JText::_( 'JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_RETRY');?>');
				return false;
        }
		return true;
}

</script>
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
			<span id="tp_curloc">
				<?php if ($this->config['cur_location'] == 1) {
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_COVER_LETTER_FORM');
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_MY_COVER_LETTERS');
			$count = 0;
			if (sizeof($this->jobseekerlinks) != 0){
				foreach($this->jobseekerlinks as $lnk)	{ 
					if ($count == 1) {
						echo '<a href="'.$cutomlink.'">'.$cutomlinktext.'</a>';
					}	?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_COVER_LETTER_FORM');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
//$_SESSION['test'] = 111;

if ($this->userrole->rolefor == 2) { // job seeker
if ($this->canaddnewcoverletter == 1) { // add new coverletter, in edit case always 1
?>

		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" onSubmit="return myValidate(this);">
			<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
						
							<tr>
								<td width="150" align="right" class="textfieldtitle">
									<label id="titlemsg" for="title"><?php echo JText::_('JS_TITLE'); ?></label>&nbsp;<font color="red">*</font>:</td>
								<td>
									<input class="inputbox required" type="text" name="title" id="title" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->coverletter)) echo $this->coverletter->title;?>" />
								</td>
							</tr>
							<tr>
								<td align="right" class="textfieldtitle"><label id="descriptionmsg" for="description"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font>:</td>
								<td>
									<textarea class="inputbox required" name="description" id="description" cols="60" rows="9"><?php if(isset($this->coverletter)) echo $this->coverletter->description; ?></textarea>
								</td>
							</tr>
							



					<tr><td colspan="2" height="10"></td></tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" id="button" class="button" value="<?php echo JText::_('JS_SAVE_COVER_LETTER'); ?>"/>


			</td></tr>
                    </table>
			<?php 
				if(isset($this->coverletter)) {
					if (($this->coverletter->created=='0000-00-00 00:00:00') || ($this->coverletter->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->coverletter->created;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="id" value="<?php if (isset($this->coverletter)) echo $this->coverletter->id; ?>" />
			<input type="hidden" name="layout" value="empview" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="savecoverletter" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
			<?php if(isset($this->packagedetail[0])) echo '<input type="hidden" name="packageid" value="'.$this->packagedetail[0].'" />';?>
			<?php if(isset($this->packagedetail[1])) echo '<input type="hidden" name="paymenthistoryid" value="'.$this->packagedetail[1].'" />'; ?>

		
		
		</form>
		
<?php
} else{ // can not add new coverletter 
	$message = '';
	$j_p_link=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=packages&Itemid='.$this->Itemid);
	if(empty($this->packagedetail[0]->packageexpiredays) && !empty($this->packagedetail)){ //$this->packagecombo == 2 means user have no package
		$message = "<strong><font color='orangered'>".JText::_('JS_COVERLETTER_LIMIT_EXCEED')." <a href=".$j_p_link.">".JText::_('JS_JOBSEEKER_PACKAGES')."</a></font></strong>";
	}elseif(isset($this->packagedetail[0]->id) && empty($this->packagedetail[0]->id)){
		$message = "<strong><font color='orangered'>".JText::_('JS_JOB_NO_PACKAGE')." <a href=".$j_p_link.">".JText::_('JS_JOBSEEKER_PACKAGES')."</a></font></strong>";
	}else{
		$days="";
		if((isset($this->packagedetail[0]->packageexpiredays)) AND (isset($this->packagedetail[0]->packageexpireindays)))
			$days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
		if($days == 1) $days = $days.' '.JText::_('JS_DAY'); else $days = $days.' '.JText::_('JS_DAYS');
		$package_title="";
		if(isset($this->packagedetail[0]->packagetitle)) $package_title=$this->packagedetail[0]->packagetitle;
		$message = "<strong><font color='red'>".JText::_('JS_YOUR_PACKAGE').' &quot;'.$package_title.'&quot; '.JText::_('JS_HAS_EXPIRED').' '.$days.' ' .JText::_('JS_AGO')." <a href=".$j_p_link.">".JText::_('JS_JOBSEEKER_PACKAGES')."</a></font></strong>";
	} ?>
	<?php if($message != ''){ ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><?php echo $message;?></div>
	</div>
	<?php } 
	}?>
<?php 
} else{ // not allowed cover letter ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php

}
}//ol
?>		
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
