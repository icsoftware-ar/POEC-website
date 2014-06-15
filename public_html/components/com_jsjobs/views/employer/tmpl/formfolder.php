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
 * File Name:	views/employer/tmpl/formcompany.php
 ^ 
 * Description: template for form company
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

 global $mainframe;

$editor = & JFactory :: getEditor();
JHTML :: _('behavior.calendar');
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);


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
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_FOLDERS'); ?></a> > <?php echo JText::_('JS_FOLDERS_INFO');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_FOLDERS_INFO');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->canaddnewfolder == 1) { // add new Folder, in edit case always 1

?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" onSubmit="return myValidate(this);">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php
		if(isset($this->folders)) ;?>
				      <tr>
				        <td width="20%" align="right"><label id="namemsg" for="name"><strong><?php echo JText::_('JS_FOLDER_NAME'); ?></strong></label>&nbsp;<font color="red">*</font></td>
				          <td width="60%"><input class="inputbox required" type="text" name="name" id="name" size="40" maxlength="255" value="<?php if(isset($this->folders)) echo $this->folders->name; ?>" />
				        </td>
				      </tr>
						<?php if ( $this->config['comp_editor'] == '1' ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="decriptionmsg" for="decription"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->folders))
										echo $editor->display('decription', $this->folders->decription, '100%', '100%', '60', '20', false);
									else
										echo $editor->display('decription', '', '100%', '100%', '60', '20', false);
								?>	
								</td>
							</tr>
						<?php } else {?>
				       <tr>
				        <td valign="top" align="right"><label id="decriptionmsg" for="decription"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td>
							<textarea class="inputbox required" name="decription" id="decription" cols="60" rows="5"><?php if(isset($this->folders)) echo $this->folders->decription; ?></textarea>
						</td>
				      </tr>
						<?php } ?>

    <tr>
        <td colspan="2" height="10"></td>
      <tr>
	<tr>
		<td colspan="2" align="center">
			<input id="button" class="button" type="submit" name="submit_app" onclick="return myValidate(f)" value="<?php echo JText::_('JS_SAVE_FOLDER'); ?>" />
		</td>
	</tr>
    </table>


                        <?php if(isset($this->folders)) $curdate = $this->folders->created;
                        else $curdate = date('Y-m-d H:i:s');    ?>
				<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
				<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
				<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
				<input type="hidden" name="status" value="<?php echo $this->folders->status; ?>" />
				<input type="hidden" name="global"  value="1" />
				<input type="hidden" name="task" value="savefolder" />
				<input type="hidden" name="check" value="" />
			<?php if(isset($this->packagedetail[0])) echo '<input type="hidden" name="packageid" value="'.$this->packagedetail[0].'" />';?>
			<?php if(isset($this->packagedetail[1])) echo '<input type="hidden" name="paymenthistoryid" value="'.$this->packagedetail[1].'" />'; ?>
			
				<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
					<input type="hidden" name="id" value="<?php if(isset($this->folders)) echo $this->folders->id; ?>" />
		</form>
<?php
} else{ // can not add new folder ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<?php $e_p_link=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=packages&Itemid='.$this->Itemid); ?>
		<div id="message"><b><?php echo "<strong><font color='red'>".JText::_('JS_FOLDER_LIMIT_EXCEED')." <a href=".$e_p_link.">".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";?></b></div>
	</div>

<?php
}

}//ol
?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
