<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/info.php
 ^ 
 * Description: JS Jobs Information
 ^ 
 * History:		NONE
 ^ 
 */
defined('_JEXEC') or die('Restricted access');
 global $mainframe;
 JRequest :: setVar('layout', 'view_company');
$_SESSION['cur_layout']='view_company';


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
			<form action="index.php" method="POST" name="adminForm" id="adminForm">
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			</form>
			<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform" >
			<?php
		$trclass = array("row0", "row1");
		$i = 0;
		$isodd = 1;
		?>
		 <tr class="row1" height="25"><td width="5">&nbsp;</td>
		<td colspan="2" align="center" class="maintext"><font size="+1"><strong><?php echo $this->company->name; ?></strong></font></td>
	  </tr>
	  <tr> <td colspan="3" height="1"></td> </tr>
	  <tr height="200" class="row1"><td ></td>
		<td width="200" valign="top">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="admintable">
				<tr class="row1">
					<td height="210" style="max-width:200px;max-height:20px;overflow:hidden;text-overflow:ellipsis" align="center">
						<div style="max-width: 200px;max-height:200px;">

					 <?php if ($this->company->logoisfile !=-1) { ?>
							<img  width="200"  src="../<?php echo $this->config['data_directory'];?>/data/employer/comp_<?php echo $this->company->id;?>/logo/<?php echo $this->company->logofilename;?>" />
					  <?php }else { ?>
							<img width="200" height="54" src="../components/com_jsjobs/images/blank_logo.png" />
					  <?php } ?>
						</div>
					
				</td>
				</tr>
			</table>	
		</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
				      <tr> <td colspan="3" height="1"></td> </tr>
				      <?php if ($this->company->url) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td width="7"></td>
				        <td class="maintext"><b><?php echo JText::_('JS_URL'); ?></b></td>
						<td class="maintext"><?php echo $this->company->url; ?></td>
				      </tr>
					  <?php } ?>
				      <?php if ($this->company->contactname) { ?>
				    <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTNAME'); ?></b></td>
						<td class="maintext"><?php echo $this->company->contactname; ?></td>
				      </tr>
					  <?php } ?>
				      <?php if ($this->company->contactemail) { ?>
				       <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTEMAIL'); ?></b></td>
						<td class="maintext"><?php echo $this->company->contactemail; ?></td>
				      </tr>
					  <?php } ?>
					   <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTPHONE'); ?></b></td>
						<td class="maintext"><?php echo $this->company->contactphone; ?></td>
				      </tr>
				      <?php if ($this->company->address1) { ?>
				       <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_ADDRESS1'); ?></b></td>
						<td class="maintext"><?php echo $this->company->address1; ?></td>
				      </tr>
					  <?php } ?>
				      <?php if ($this->company->address2) { ?>
				       <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_ADDRESS2'); ?></b></td>
						<td class="maintext"><?php echo $this->company->address2; ?></td>
				      </tr>
					  <?php } ?>
					   <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_LOCATION'); ?></b></td>
						<td class="maintext">
					      <?php 
					      
							 if($this->company->multicity != '') echo $this->company->multicity;$comma = 1;
						   if ($this->company->zipcode) { if ($comma) echo ', '; else $comma = 1; echo $this->company->zipcode; }?>
					  </td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
			</table>
		</td>
	  </tr>
	  <tr> <td colspan="3" height="1"></td> </tr>
		<?php
		$trclass = array("row0", "row1");
		$i = 0;
		$isodd = 0;
		foreach($this->fieldsordering as $field){ 
			switch ($field->field) {
				case "jobcategory": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CATEGORIES'); ?></b></td>
						<td class="maintext"><?php echo $this->company->cat_title; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
				<?php break;
				case "contactphone": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTPHONE'); ?></b></td>
						<td class="maintext"><?php echo $this->company->contactphone; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
					  <?php } ?>
				<?php break;
				case "contactfax": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTFAX'); ?></b></td>
						<td class="maintext"><?php echo $this->company->companyfax; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
					  <?php } ?>
				<?php break;
				case "since": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_SINCE'); ?></b></td>
						<td class="maintext"><?php echo date($this->config['date_format'],strtotime($this->company->since)); ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
				  <?php } ?>
				<?php break;
				case "companysize": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_COMPANY_SIZE'); ?></b></td>
						<td class="maintext"><?php echo $this->company->companysize; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
				  <?php } ?>
				<?php break;
				case "income": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				     <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_INCOME'); ?></b></td>
						<td class="maintext"><?php echo $this->company->income; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
					  <?php } ?>
				<?php break;
				case "description": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_DESCRIPTION'); ?></b></td>
						<td class="maintext"><?php echo $this->company->description; ?></td>
				      </tr>
					 
					  <?php } ?>
					<?php } ?>	
				<?php } ?>	
							
		</table>	
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
</table>							
<script language="javascript" type="text/javascript">
	dhtml.cycleTab('tab1');
</script>

