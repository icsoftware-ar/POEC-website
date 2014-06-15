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


 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
 
 	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	
	$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
	$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');


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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_JOB_ALERT_INFO');
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
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_JOB_ALERT_INFO');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>


<script language="Javascript" type="text/javascript">
function displayError(message,divcolor){
	document.getElementById('message').innerHTML = message;
	document.getElementById('errormessage').style.position = '';
	$('#errormessage').slideDown('50');
}
</script>

<?php
$printform = 0;

if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 2) { // job seeker
	$printform = 1;
	if ((isset($this->jobsetting)) &&($this->jobsetting->id != 0)) { // not new form
		if ($this->jobsetting->status == 1) { // Employment Application is actve
			$printform = 1;
		}else if($this->jobsetting->status == 0){ // not allowed job posting
			$printform = 0;
		} else{ // not allowed job posting
			$printform = 0;
		}
	}
}elseif (isset($this->userrole->rolefor) && $this->userrole->rolefor != 1) { // not employer
    if($this->config['overwrite_jobalert_settings'] == 1) $printform = 1;

}else{ // not allowed job posting
	if($this->config['overwrite_jobalert_settings'] == 1) $printform = 1;
	else $printform = 0;
}
if ($printform == 1) {
    if ($this->cansetjobalert == 1) $cansetjobalert = 1;
    
}
if ($printform == 1) {
if ($cansetjobalert == 1) { // add new resume, in edit case always 1

?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data"  onSubmit="return myValidate(this);">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php
		$i = 0; ?>
					
					  <tr>
						<td colspan="2" align="right">
						<?php if(isset($this->jobsetting->id)) {?>
							<a id="button" class="button minpad" href= "<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobalertunsubscribe&email='.$this->jobsetting->contactemail);?> " ><?php echo JText::_( 'JS_UNSUB_JOB_ALERT' ); ?></a>
						<?php }else { ?>							
							<a id="button" class="button minpad" href="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobalertunsubscribe');?>" ><?php echo JText::_( 'JS_UNSUB_JOB_ALERT' ); ?></a>
						<?php } ?>							
						</td>
					</tr>
				      <tr>
						<td valign="top" align="right"><label id="namemsg" for="name"><?php echo JText::_('JS_NAME');?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required " type="text" name="name" id="name" size="40" maxlength="100" value="<?php if(isset($this->jobsetting)) echo $this->jobsetting->name; ?>" />
				        </td>
				      </tr>
					
				      <tr>
				        <td valign="top" align="right"><label id="jobcategorymsg" for="categoryid"><?php echo JText::_('JS_CATEGORIES'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><?php echo $this->lists['jobcategory'];?></td>
				      </tr>
                                      <tr  >
                                        <td valign="top" align="right"><label id="subcategoryidmsg" for="subcategoryid"><?php echo JText::_('JS_SUB_CATEGORY'); ?></label></td>
                                        <td id="fj_subcategory"><?php echo $this->lists['subcategory'];?></td>
                                      </tr>
				      <tr>
                                          <td valign="top" align="right"><label id="contactemailmsg" for="contactemail"><?php echo JText::_('JS_CONTACTEMAIL');?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required validate-email" type="text" name="contactemail" id="contactemail" size="40" maxlength="100" value="<?php if(isset($this->jobsetting)) echo $this->jobsetting->contactemail; ?>" />
				        </td>
				      </tr>
				      <tr>
				        <td align="right"><label id="citymsg" for="city"><?php echo JText::_('JS_CITY'); ?></label></td>
				        <td id="jobalert_city">
								<input class="inputbox required" type="text" name="city" id="jobalertcity" size="40" maxlength="100" value="" />
								<input type="hidden" name="cityidforedit" id="cityidforedit" value="<?php if(isset($this->multiselectedit)) echo $this->multiselectedit; ?>" />

				        </td>
				      </tr>
				      <tr>
				        <td align="right"><label id="zipcodemsg" for="zipcode"><?php echo JText::_('JS_ZIPCODE'); ?></label></td>
				        <td><input class="inputbox" type="text" name="zipcode" size="40" maxlength="100" value="<?php if(isset($this->jobsetting)) echo $this->jobsetting->zipcode; ?>" />
				        </td>
				      </tr>
				      <tr>
				        <td align="right" valign="top"><label id="keywordsmsg" for="keywords"><?php echo JText::_('JS_KEYWORDS'); ?></label></td>
				        <td><textarea class="inputbox" cols="46" name="keywords" rows="4" style="resize:none;" ><?php if(isset($this->jobsetting)) echo $this->jobsetting->keywords; ?></textarea>
				        </td>
				      </tr>
				      <tr>
				        <td align="right"><label id="alerttypemsg" for="alerttype"><?php echo JText::_('JS_ALERT_TYPE'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><?php echo $this->lists['alerttype'];?></td>
				      </tr>
					<?php 
						$user = JFactory::getUser();
						if ( $this->config['job_alert_captcha'] == 1 && ($user->guest)){ ?>
						<tr>
							<td valign="top" align="right"><label id="captchamsg" for="captcha"><?php echo JText::_('JS_CAPTCHA'); ?></label><?php  echo '<font color="red">*</font>'; ?></td>
							<td colspan="3"><?php echo $this->captcha;  ?> </td>
						</tr>
					<?php } ?>
				      
        <td colspan="2" height="10"></td>
      <tr>
	<tr>
		<td colspan="2" align="center">
		<input id="button" class="button" type="submit" name="submit_app" value="<?php echo JText::_('JS_SAVE'); ?>" />
		</td>
	</tr>
    </table>


	<?php 
				if(isset($this->jobsetting)) {
					if (($this->jobsetting->created=='0000-00-00 00:00:00') || ($this->jobsetting->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->jobsetting->created;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="id" value="<?php if(isset($this->jobsetting->id)) echo $this->jobsetting->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="savejobalertsetting" />
			<input type="hidden" name="check" value="" />
            <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		
		  </form>
<script language=Javascript>


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





function deleteJobAlert(){
	document.adminForm.action = 'index.php?option=com_jsjobs&c=jsjobs&task=deleteJobAlertSetting';
	//document.getElementById('action').value=actionvalue;
	document.forms["adminForm"].submit();
	// document.adminForm.submit(document.adminForm.action);
 }
function dochange(src, val){
	var pagesrc = 'jobalert_'+src;
	document.getElementById(pagesrc).innerHTML="Loading ...";
	var xhr; 
	try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	catch (e) 
	{
		try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
		catch (e2) 
		{
		  try {  xhr = new XMLHttpRequest();     }
		  catch (e3) {  xhr = false;   }
		}
	 }

	xhr.onreadystatechange = function(){
      if(xhr.readyState == 4 && xhr.status == 200){
		
        	document.getElementById(pagesrc).innerHTML=xhr.responseText; //retuen value

			if(src=='state'){
				//countyhtml = "<input class='inputbox' type='text' name='county' size='40' maxlength='100'  />";
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				//document.getElementById('jobalert_county').innerHTML=countyhtml; //retuen value
				document.getElementById('jobalert_city').innerHTML=cityhtml; //retuen value
			}/*}else if(src=='county'){
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('jobalert_city').innerHTML=cityhtml; //retuen value
			}*/
      }
    }
 
	xhr.open("GET","index.php?option=com_jsjobs&task=listaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
 }
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
			  

		
<?php 
} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php

}
} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php


}
}//ol
?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
