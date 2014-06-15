<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/formjob.php
 ^ 
 * Description: Form template for a job
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access'); 
jimport('joomla.html.pane');
    $document = &JFactory::getDocument();
	$document->addStyleSheet('../components/com_jsjobs/css/token-input-jsjobs.css');

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
	$document->addScript('../components/com_jsjobs/js/jquery.tokeninput.js');


$editor = &JFactory::getEditor();
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');  


	
?>
<script language="javascript">
function submitbutton(pressbutton) {
	if (pressbutton) {
		document.adminForm.task.value=pressbutton;
	}
	if(pressbutton == 'save'){
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

<table width="100%" >
	<tr>
		<td align="left" width="175"  valign="top">
			<table width="100%" ><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top" align="left">


			<form action="index.php" method="POST" name="adminForm" id="adminForm">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
		<?php
		$trclass = array("row0", "row1");
		$isodd = 1;
		$i = 0;?>
		  <tr class="<?php echo $trclass[0]; ?>">
			<td width="20%" align="right"><label id="namemsg" for="name"><?php echo JText::_('JS_NAME'); ?></label>&nbsp;<font color="red">*</font></td>
			  <td width="60%">
				<input class="inputbox required " type="text" name="name" id="name" size="40" maxlength="100" value="<?php if(isset($this->jobalert)) echo $this->jobalert->name; ?>" />
			</td>
		  </tr>
		  <tr class="<?php echo $trclass[1]; ?>">
			<td valign="top" align="right"><label id="jobcategorymsg" for="categoryid"><?php echo JText::_('JS_CATEGORIES'); ?></label>&nbsp;<font color="red">*</font></td>
			<td><?php if(isset($this->lists['jobcategory'])) echo $this->lists['jobcategory']; ?></td>
		  </tr>
		  <tr class="<?php echo $trclass[0]; ?>">
			<td valign="top" align="right"><label id="subcategoryidmsg" for="subcategoryid"><?php echo JText::_('JS_SUB_CATEGORY'); ?></label></td>
			<td id="fj_subcategory"><?php echo $this->lists['subcategory'];?></td>
		  </tr>
		  <tr class="<?php echo $trclass[1]; ?>">
			<td width="20%" align="right"><label id="contactemailmsg" for="contactemail"><?php echo JText::_('JS_CONTACTEMAIL'); ?></label>&nbsp;<font color="red">*</font></td>
			  <td width="60%">
				<input class="inputbox required validate-email" type="text" name="contactemail" id="contactemail" size="40" maxlength="100" value="<?php if(isset($this->jobalert)) echo $this->jobalert->contactemail; ?>" />
			</td>
		  </tr>
		  <tr class="<?php echo $trclass[0]; ?>">
			<td width="20%" align="right"><label id="citymsg" for="city"><?php echo JText::_('JS_CITY'); ?></label>&nbsp;<font color="red">*</font></td>
			  <td width="60%" id="jobalert_city">
					<input class="inputbox required" type="text" name="city" id="jobalertcity" size="40" maxlength="100" value="" />
					<input type="hidden" name="cityidforedit" id="cityidforedit" value="<?php if(isset($this->multiselectedit)) echo $this->multiselectedit; ?>" />
			</td>
		  </tr>
		  <tr class="<?php echo $trclass[1]; ?>">
			<td width="20%" align="right"><label id="zipcodemsg" for="zipcode"><?php echo JText::_('JS_ZIPCODE'); ?></label></td>
			  <td width="60%">
				  <input class="inputbox" type="text" name="zipcode" size="40" maxlength="100" value="<?php if(isset($this->jobalert)) echo $this->jobalert->zipcode; ?>" />
			</td>
		  </tr>
		  <tr class="<?php echo $trclass[0]; ?>">
			<td width="20%" align="right"><label id="keywordsmsg" for="keywords"><?php echo JText::_('JS_KEYWORDS'); ?></label></td>
			  <td width="60%">
				  <textarea class="inputbox" cols="46" name="keywords" rows="4" style="resize:none;" ><?php if(isset($this->jobalert)) echo $this->jobalert->keywords; ?></textarea>
			</td>
		  </tr>
		  <tr class="<?php echo $trclass[1]; ?>">
			<td valign="top" align="right"><label id="alerttypemsg" for="alerttype"><?php echo JText::_('JS_ALERT_TYPE'); ?></label>&nbsp;<font color="red">*</font></td>
			<td><?php if(isset($this->lists['alerttype'])) echo $this->lists['alerttype']; ?></td>
		  </tr>
		  <tr class="<?php echo $trclass[0]; ?>">
			<td valign="top" align="right">
			<?php if(isset($this->lists['status'])) echo $this->lists['status']; ?>
			</td>
		  </tr>
		  
		
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('JS_SAVE_JOB_ALERT'); ?>" />
		</td>
	</tr>

			    </table>
			<input type="hidden" name="created" value="<?php echo $this->jobalert->created; ?>" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="view" value="application" />
			<input type="hidden" name="layout" value="formjobalert" />
			<input type="hidden" name="uid" value="<?php echo $this->jobalert->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="savejobalert" />
		  <input type="hidden" name="id" value="<?php if(isset($this->jobalert)) echo $this->jobalert->id; ?>" />

<script language=Javascript>
			
			
			
function fj_getsubcategories(src, val){
	var xhr;
	try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	catch (e){
		try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
		catch (e2) {
		  try {  xhr = new XMLHttpRequest();     }
		  catch (e3) {  xhr = false;   }
		}
	 }

	xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 && xhr.status == 200){
        	document.getElementById(src).innerHTML=xhr.responseText; //retuen value
            }
        }

	xhr.open("GET","index.php?option=com_jsjobs&task=listsubcategories&val="+val,true);
	xhr.send(null);
}


			
	</script>

				
			  </form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>				
 
<script type="text/javascript">
	
	        jQuery(document).ready(function() {
            var value = jQuery("#cityidforedit").val();
            if(value != ""){
                jQuery("#jobalertcity").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
                    noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
                    searchingText: "<?php echo JText::_('SEARCHING...');?>",
                    tokenLimit: 5,
                    prePopulate: <?php if(isset($this->multiselectedit)) echo $this->multiselectedit;else echo "''"; ?>
                });

            }else{
                jQuery("#jobalertcity").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
                    noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
                    searchingText: "<?php echo JText::_('SEARCHING...');?>",
                    tokenLimit: 5

                });
            }
        });


</script>
