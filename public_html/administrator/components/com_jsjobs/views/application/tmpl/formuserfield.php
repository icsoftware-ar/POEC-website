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
 
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();

$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('../components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');  

$yesno = array(
	'0' => array('value' => 1,'text' => JText::_('YES')),
	'1' => array('value' => 0,'text' => JText::_('NO')),);

$fieldtype = array(
	'0' => array('value' => 'text','text' => JText::_('Text Field')),
	'1' => array('value' => 'checkbox','text' => JText::_('Check Box')),
	'2' => array('value' => 'date','text' => JText::_('Date')),
	'3' => array('value' => 'select','text' => JText::_('Drop Down')),
	'4' => array('value' => 'emailaddress','text' => JText::_('Email Address')),
//	'5' => array('value' => 'editortext','text' => JText::_('Editor Text Area')),
	'6' => array('value' => 'textarea','text' => JText::_('Text Area')),);

if(isset($this->userfield))	{
	$lstype = JHTML::_('select.genericList', $fieldtype, 'type', 'class="inputbox" '. 'onchange="toggleType(this.options[this.selectedIndex].value);"', 'value', 'text', $this->userfield->type);
	$lsrequired = JHTML::_('select.genericList', $yesno, 'required', 'class="inputbox" '. '', 'value', 'text', $this->userfield->required);
	$lsreadonly = JHTML::_('select.genericList', $yesno, 'readonly', 'class="inputbox" '. '', 'value', 'text', $this->userfield->readonly);
	$lspublished = JHTML::_('select.genericList', $yesno, 'published', 'class="inputbox" '. '', 'value', 'text', $this->userfield->published);
}else{
	$lstype = JHTML::_('select.genericList', $fieldtype, 'type', 'class="inputbox" '. 'onchange="toggleType(this.options[this.selectedIndex].value);"', 'value', 'text', 0);
	$lsrequired = JHTML::_('select.genericList', $yesno, 'required', 'class="inputbox" '. '', 'value', 'text', 0);
	$lsreadonly = JHTML::_('select.genericList', $yesno, 'readonly', 'class="inputbox" '. '', 'value', 'text', 0);
	$lspublished = JHTML::_('select.genericList', $yesno, 'published', 'class="inputbox" '. '', 'value', 'text', 1);
}	
	

?>
<script language="javascript">

// for joomla 1.6
Joomla.submitbutton = function(task){
        if (task == ''){
                return false;
        }else{
                if (task == 'saveuserfield'){
                    returnvalue = validate_form(document.adminForm);
                }else returnvalue  = true;
                if (returnvalue){
                        Joomla.submitform(task);
                        return true;
                }else return false;
        }
}
// for joomla 1.5
function submitbutton(pressbutton) {
	if (pressbutton) {
		document.adminForm.task.value=pressbutton;
	}
	if(pressbutton == 'saveuserfield'){
		returnvalue = validate_form(document.adminForm);
	}else returnvalue  = true;
	
	if (returnvalue == true){
		try {
			  document.adminForm.onsubmit();
	        }
		catch(e){}
		document.adminForm.submit();
	}
}

function validate_form(f)
{
	var msg = new Array();
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>'; //send token
        } 
        else {
		msg.push('<?php echo JText::_( 'JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_RETRY');?>');
		alert (msg.join('\n'));			
		return false;
	}
	return true;
}

function hasClass(el, selector) {
   var className = " " + selector + " ";
  
   if ((" " + el.className + " ").replace(/[\n\t]/g, " ").indexOf(className) > -1) {
    return true;
   }
   return false;
  }

</script>

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
			<form action="index.php" method="post" name="adminForm" id="adminForm" >
				<input type="hidden" name="check" value="post"/>
				<table class="adminform">
					<tr class="row0">
						<td width="20%">Field type:</td>
						<td width="20%"><?php echo $lstype; ?>
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="row1">
						<td width="20%">Field name:</td>
						<td align="left"  width="20%"><input onchange="prep4SQL(this);" type="text" name="name" mosReq=1 mosLabel="Name" class="inputbox required" value="<?php if(isset($this->userfield)) echo $this->userfield->name; ?>"  /></td>

						<td>&nbsp;</td>
					</tr>
					<tr class="row0">
						<td width="20%">Field title:</td>
						<td width="20%" align="left"><input type="text" name="title" class="inputbox required" value="<?php if(isset($this->userfield)) echo $this->userfield->title; ?>" /></td>
						<td>&nbsp;</td>
					</tr>
					<tr class="row0">
						<td width="20%">Required?:</td>
						<td width="20%"><?php echo $lsrequired; ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr class="row0">
						<td width="20%">Read-Only?:</td>
						<td width="20%"><?php echo $lsreadonly; ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr class="row1">
						<td width="20%">Published:</td>
						<td width="20%"><?php echo $lspublished; ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr class="row0">
						<td width="20%">Field Size:</td>
						<td width="20%"><input type="text" name="size" class="inputbox validate-numeric" maxlength="6" value="<?php if(isset($this->userfield)) echo $this->userfield->size; ?>" /></td>
						<td>&nbsp;</td>
					</tr>
					</table>
					<div id="page1"></div>
					
					<div id="divText">
						<table class="adminform">
						<tr class="row0">
							<td width="20%">Max Length:</td>
							<td width="20%"><input type="text" name="maxlength" class="inputbox validate-numeric" maxlength="6" value="<?php if(isset($this->userfield)) echo $this->userfield->maxlength; ?>" /></td>
							<td>&nbsp;</td>
						</tr>
						</table>
					</div>
					<div id="divColsRows">
						<table class="adminform">
						<tr class="row0">
							<td width="20%">Columns:</td>
							<td width="20%"><input type="text" name="cols" class="inputbox validate-numeric" maxlength="6" value="<?php if(isset($this->userfield)) echo $this->userfield->cols; ?>" /></td>
							<td>&nbsp;</td>
						</tr>
						<tr class="row1">
							<td width="20%">Rows:</td>
							<td width="20%"><input type="text" name="rows" class="inputbox validate-numeric" maxlength="6" value="<?php if(isset($this->userfield)) echo $this->userfield->rows; ?>" /></td>
							<td>&nbsp;</td>
						</tr>

						</table>
					</div>
					
					<div id="divValues" style="text-align:left;height: 200px;overflow: auto;">
						Use the table below to add new values.<br />
						<input type="button" class="button" onclick="insertRow();" value="Add a Value" />
						<table align=left id="divFieldValues" cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform" >
						<thead>
							<th class="title" width="20%">Title</th>
							<th class="title" width="80%">Value</th>
						</thead>
						<tbody id="fieldValuesBody">
						<tr>
							<td>&nbsp;</td>
						</tr>
						<?php
							$i = 0; 
							if (isset($this->userfield->type) && $this->userfield->type == 'select') {
								foreach ($this->fieldvalues as $value){	?>
									<tr id="jsjobs_trcust<?php echo $i; ?>">
										<input type="hidden" value="<?php echo $value->id; ?>" name="jsIds[<?php echo $i; ?>]" />
										<td width="20%"><input type="text" value="<?php echo $value->fieldtitle; ?>" name="jsNames[<?php echo $i; ?>]" /></td>
										<td width="80%"><input type="text" value="<?php echo $value->fieldvalue; ?>" name="jsValues[<?php echo $i; ?>]" />
										<span class="jquery_span_closetr" data-rowid="jsjobs_trcust<?php echo $i; ?>" data-optionid="<?php echo $value->id;?>" style="float:right;padding:4px;background:#b31212;" ></span>
										</td>
										
									</tr>
									<?php	$i++; 
								}
								$i--;//for value to store correctly
							} else { ?>
								<tr id="jsjobs_trcust0">
									<td width="20%"><input type="text" value="" name="jsNames[0]" /></td>
									<td width="80%"><input type="text" value="" name="jsValues[0]" />
									<span class="jquery_span_closetr" data-rowid="jsjobs_trcust0" style="float:right;padding:4px;background:#b31212;" ></span>
									</td>
								</tr>
							<?php } ?>		
						</tbody>
						</table>
					</div>
				  <table >
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
				
				  </table>
				  <input type="hidden" name="id" value="<?php if(isset($this->userfield))echo $this->userfield->id ?>" />
					<input type="hidden" name="valueCount" value="<?php echo $i; ?>" />
					<input type="hidden" name="fieldfor" value="<?php echo $this->fieldfor; ?>" />
					<input type="hidden" name="view" value="application" />
					<input type="hidden" name="layout" value="formuserfield" />
					<input type="hidden" name="task" value="saveuserfield" />
					<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			</form>
			
		<script type="text/javascript">
		  function getObject(obj) {
		    var strObj;
		    if (document.all) {
		      strObj = document.all.item(obj);
		    } else if (document.getElementById) {
		      strObj = document.getElementById(obj);
		    }
		    return strObj;
		  }

		  function insertRow() {
		    var oTable = getObject("fieldValuesBody");
		    var oRow, oCell ,oCellCont, oInput,oSpan;
		    var i, j;
		    i=document.adminForm.valueCount.value;
		    i++;
		    // Create and insert rows and cells into the first body.
		    oRow = document.createElement("TR");
		    jQuery(oRow).attr('id',"jsjob_trcust"+i);
		    oTable.appendChild(oRow);

		    oCell = document.createElement("TD");
		    oInput=document.createElement("INPUT");
		    oInput.name="jsNames["+i+"]";
		    oInput.setAttribute('id',"jsNames_"+i);
		    oCell.appendChild(oInput);
		    oRow.appendChild(oCell);
		    
		    oCell = document.createElement("TD");
		    oInput=document.createElement("INPUT");
		    oInput.name="jsValues["+i+"]";
			oInput.setAttribute('id',"jsValues_"+i);
		    oCell.appendChild(oInput);

		    oSpan=document.createElement("SPAN");
			oSpan.setAttribute('style',"float:right;padding:4px;background:#b31212;");
			jQuery(oSpan).click(function(){
				jQuery('#jsjob_trcust'+i).remove();
				document.adminForm.valueCount.value=document.adminForm.valueCount.value-1;
				
			});
		    oCell.appendChild(oSpan);
		    
		    oRow.appendChild(oCell);
		    oInput.focus();

		    document.adminForm.valueCount.value=i;
		  }

		  function disableAll() {
		    jQuery("#divValues").slideUp();
		    jQuery("#divColsRows").slideUp();
		    jQuery("#divText").slideUp();
		    /*
		    
		    var elem;
		    try{ 
		    	divValues.slideOut();
		    	divColsRows.slideOut();
		    	//divWeb.slideOut();
		    	//divShopperGroups.slideOut();
		    	//divAgeVerification.slideOut();
		    	divText.slideOut();
		    
		    } catch(e){ }
		    if (elem=getObject('jsNames[0]')) {
		      elem.setAttribute('mosReq',0);
		    }
		    */
		  }
		  function toggleType( type ) {
			disableAll();
			prep4SQL( document.adminForm.name );
			selType(type);
		  }
		  function selType(sType) {
		    var elem;
		    
		    switch (sType) {
		      case 'editorta':
		      case 'textarea':
		        jQuery("#divText").slideDown();
		        jQuery("#divColsRows").slideDown();
		      break;
		      
		     	
		      case 'emailaddress':
		      case 'password':
		      case 'text':
		        jQuery("#divText").slideDown();
		      break;
		      
		      case 'select':
		      case 'multiselect':
		        jQuery("#divValues").slideDown();
		        /*
		        if (elem=getObject('jsNames[0]')) {
		          elem.setAttribute('mosReq',1);
		        }
		        */
		      break;
		      
		      case 'radio':
		      case 'multicheckbox':
		        jQuery("#divColsRows").slideDown();
		        jQuery("#divValues").slideDown();
		        /*
		        if (elem=getObject('jsNames[0]')) {
		          elem.setAttribute('mosReq',1);
		        }
		        */
		      break;

		      case 'delimiter':
		      default: 
		        
		    }
		  }

		  function prep4SQL(o){
			if(o.value!='') {
				o.value=o.value.replace('js_','');
		    	o.value='js_' + o.value.replace(/[^a-zA-Z]+/g,'');
			}
		  }
			

		</script>  
		
		<script type="text/javascript">
		</script>
		<?php //if($i > 0 ){ ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
					toggleType(jQuery('#type').val());
			});
			
			
			jQuery("span.jquery_span_closetr").each(function(){
				var span = jQuery(this);
				jQuery(span).click(function(){
					var span_current=jQuery(this);
					if(jQuery(span_current).attr('data-optionid')!='undefined'){
						jQuery.post("index.php?option=com_jsjobs&c=jsjobs&task=deleteuserfieldoption",{id:jQuery(span_current).attr('data-optionid')},function(data){
							if(data){
								var tr_id=jQuery(span_current).attr('data-rowid');
								jQuery('#'+tr_id).remove();
								document.adminForm.valueCount.value=document.adminForm.valueCount.value-1;
							}else{
								alert('<?php echo JText::_('JS_OPTION_VALUE_IN_USE');?>');
								
							}
							
						});
					}else{
						var tr_id=jQuery(span_current).attr('data-rowid');
						jQuery('#'+tr_id).remove();
						document.adminForm.valueCount.value=document.adminForm.valueCount.value-1;
					}
				});
			});
		</script>

		<?php //} ?>	
			
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>							
