<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/formuserfield.php
 ^ 
 * Description: Default template for form user field
 ^ 
 * History:		NONE
 ^ 
 */
 
// this is the basic listing scene when you click on the component 
// in the component menu
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'formresumeuserfield');
$_SESSION['cur_layout']='formresumeuserfield';



$yesno = array(
	'0' => array('value' => 1,'text' => JText::_('YES')),
	'1' => array('value' => 0,'text' => JText::_('NO')),);

$fieldtype = array(
	'0' => array('value' => 'text','text' => JText::_('Text Field')),
	//'1' => array('value' => 'checkbox','text' => JText::_('Check Box')),
	//'2' => array('value' => 'date','text' => JText::_('Date')),
	//'3' => array('value' => 'select','text' => JText::_('Drop Down')),
	//'4' => array('value' => 'emailaddress','text' => JText::_('Email Address')),
//	//'5' => array('value' => 'editortext','text' => JText::_('Editor Text Area')),
	//'6' => array('value' => 'textarea','text' => JText::_('Text Area')),
	);
$section = array(
	'0' => array('value' => '10','text' => JText::_('JS_PERSONAL_INFORMATION')),
	'1' => array('value' => '20','text' => JText::_('JS_BASIC_INFORMATION')),
	'2' => array('value' => '31','text' => JText::_('JS_ADDRESS')),
	'3' => array('value' => '32','text' => JText::_('JS_ADDRESS1')),
	'4' => array('value' => '33','text' => JText::_('JS_ADDRESS2')),
	'5' => array('value' => 'editortext','text' => JText::_('JS_ADDRESS3')),
	'6' => array('value' => 'textarea','text' => JText::_('Text Area')),
	);
	
		$lstype = JHTML::_('select.genericList', $fieldtype, 'type[]', 'class="inputbox" '. 'onchange="toggleType(this.options[this.selectedIndex].value);"', 'value', 'text', '');
		//$lsreadonly = JHTML::_('select.genericList', $yesno, 'readonly[]', 'class="inputbox" '. '', 'value', 'text', $this->userfield->readonly);

?>
<table width="100%">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
			<form action="index.php" method="POST" name="adminForm" id="adminForm" >
				<table class="adminform">
					<?php 
					$i = 0;
					foreach($this->userfields as $field){
								$lsrequired = JHTML::_('select.genericList', $yesno, 'required['.$i.']', 'class="inputbox" '. '', 'value', 'text', $field->required);
								$lspublished = JHTML::_('select.genericList', $yesno, 'published['.$i.']', 'class="inputbox" '. '', 'value', 'text', $field->published);
					?>
						<?php if($field->field != 'section_userfields') { ?>
							<tr class="row0">
								<td width="20%">Field type:</td>
								<td width="20%"><?php echo $lstype; ?>
								</td>
								<td>&nbsp;</td>
							</tr>
						<?php } ?>	
							<input type="hidden" name="userfield[]" class="inputbox" readonly="readonly"  value="<?php echo $field->field; ?>" />

						<tr class="row0">
							<td width="20%"><strong><?php if($field->field != 'section_userfields') echo JText::_( 'JS_FIELD_TITLE'); else echo  JText::_('JS_SECTION_TITLE'); ?></strong>:</td>
							<td width="20%" align="left"><input type="text" name="title[]" class="inputbox" value="<?php echo $field->fieldtitle; ?>" /></td>
							<td>&nbsp;</td>
						</tr>
						<?php if($field->field != 'section_userfields') { ?>
							<tr class="row1">
								<td width="20%">Required?:</td>
								<td width="20%"><?php echo $lsrequired; ?></td>
								<td style="display:none;"width="20%"><?php echo $lspublished; ?></td>
								<td>&nbsp;</td>
							</tr>
						<?php } ?>	
						<tr class="row0">
							<td width="20%">Published:</td>
							<td width="20%"><?php echo $lspublished; ?></td>
							<td>&nbsp;</td>
						</tr>
						<tr class="row1"><td colspan="3" height="25"></td></tr>
					<input type="hidden" name="id[]" value="<?php echo $field->id ?>" />
						
					<?php $i++; }?>
					</table>
					<div id="page1"></div>
					

					<input type="hidden" name="section" value="1000" />

					<input type="hidden" name="valueCount" value="<?php echo $i; ?>" />
					<input type="hidden" name="fieldfor" value="<?php echo $this->fieldfor;?>" />
					<input type="hidden" name="view" value="application" />
					<input type="hidden" name="layout" value="formresumeuserfield" />
					<input type="hidden" name="task" value="saveresumeuserfields" />
					<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			</form>
			
			
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>							
