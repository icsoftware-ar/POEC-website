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
 * File Name:	views/employer/tmpl/formdepartment.php
 ^
 * Description: template view for form department
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
   
$editor = & JFactory :: getEditor();
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
        }
        else {
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
						if(isset($this->department)){
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mydepartments&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_DEPARTMENTS'); ?></a> > <?php echo JText::_('JS_EDIT_DEPARTMENT_INFO');
						}else{
							echo JText::_('JS_CUR_LOC'); ?> :  <?php echo JText::_('JS_DEPARTMENT');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_DEPARTMENT_INFO');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->userrole->rolefor == 1) { // employer

?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate"  onSubmit="return myValidate(this);">
 <table cellpadding="5" cellspacing="0" border="0" width="100%" >
 					<tr>
			       <td valign="top" align="right"><label id="companyidmsg" for="companyid"><?php echo JText::_('JS_COMPANY'); ?></label>&nbsp;<font color="red">*</font></td>
			        <td ><?php echo $this->lists['companies']; ?></td>
			      	</tr>
					<tr>
			        <td  align="right"><label id="namemsg" for="name"><?php echo JText::_('JS_DEPARTMENT_NAME'); ?></label>&nbsp;<font color="red">*</font></td>
			         <td ><input class="inputbox required" type="text" name="name" id="name"  value="<?php if(isset($this->department)) echo $this->department->name; ?>" />
			        </td></tr>
			      	<tr>
					<td colspan="2" valign="top" align="center" ><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label></td>
					</tr>
					<tr>
					<td colspan="2" align="center">
					<?php
						$editor =& JFactory::getEditor();
						if(isset($this->department))
							echo $editor->display('description', $this->department->description, '100%', '100%', '60', '20', false);
						else
							echo $editor->display('description', '', '100%', '100%', '60', '20', false);

					?>	
					</td>
					</tr>
					
					<tr><td colspan="2" height="10"></td></tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" id="button" class="button" value="<?php echo JText::_('JS_SAVE'); ?>"/>


			</td></tr>
		
					
					
 </table> 
			
			<?php
				if(isset($this->department)) {
					if (($this->department->created=='0000-00-00 00:00:00') || ($this->department->created==''))
						$curdate = date('Y-m-d H:i:s');
					else $curdate = $this->department->created;
				}else $curdate = date('Y-m-d H:i:s');
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="savedepartment" />
			<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
			<input type="hidden" name="id" value="<?php if(isset($this->department)) echo $this->department->id; ?>" /> 
</form>
		
<?php 

} else{ ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php
	
}
}
?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
