<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jal 08, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/jobseeker/tmpl/new_injsjobs.php
 ^ 
 * Description: template view for new in JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
  global $mainframe;
   $document =& JFactory::getDocument();
   $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);

  if ($this->config['showemployerlink'] == 0){ // user can not register as a employer
	$usertypeid='';
	if ($this->usertype) $usertypeid = $this->usertype->id;
		echo '<form action="index.php" method="POST" name="adminForm">';

			echo '<input type="hidden" name="usertype" value="2" />'; //2 for job seeker
			echo '<input type="hidden" name="dated" value="'. date('Y-m-d H:i:s') .'" />';
			echo '<input type="hidden" name="uid" value="'.  $this->uid .'" />';
			echo '<input type="hidden" name="id" value="'. $usertypeid .'" />';
			echo '<input type="hidden" name="option" value="'. $this->option .'" />';
			echo '<input type="hidden" name="task" value="savenewinjsjobs" />';
			echo '<script language=Javascript>';
				echo 'document.adminForm.submit();';
			echo '</script>';

		echo '</form>';			
  
  }

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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_WELCOME_JSJOBS');
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_WELCOME_JSJOBS');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->config['showemployerlink'] == 1){ // ask user role
?>
<form action="index.php" method="POST" name="adminForm">

	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<tr>
			<td align="center" colspan="2">
				<strong><?php echo JText::_('JS_WELCOME_JSJOBS_TEXT'); ?>  </strong>
			</td>
		</tr>	
		<tr><td height="15" colspan="2"></td></tr>	
		<tr>
			<td width="50%" align="right">
				<?php echo JText::_('JS_SELECT_ROLE'); ?> :&nbsp;
			</td>
			<td width="50%"> <?php echo $this->lists['usertype']; ?>
			</td>
		</tr>		
		<tr><td height="15" colspan="2"></td></tr>	
		<tr>
			<td align="center" colspan="2">
				<input id="button" type="submit" class="button" name="submit_app" onclick="document.adminForm.submit();" value="<?php echo JText::_('JS_SUBMIT'); ?>" />
			</td>
		</tr>	
	</table>
			<input type="hidden" name="date" value="<?php echo date('Y-m-d H:i:s'); ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="id" value="<?php if ($this->usertype) echo $this->usertype->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="savenewinjsjobs" />
			<input type="hidden" name="Itemid" value="<?php $this->Itemid?>" />
</form>	
<?php }else{ // user can not register as a employer ?>
<div width="100%" align="center">
<br><br><h1>Please wait ...</h1>
</div>

<?php } 
}//ol
?>		
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
