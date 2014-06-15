<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/formresumesearch.php
 ^ 
 * Description: Form template for a company
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access'); 
jimport('joomla.html.pane');

$document = &JFactory::getDocument();
$version = new JVersion;

$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');


JHTML :: _('behavior.calendar');
$width_big = 40;
$width_med = 25;
$width_sml = 15;

	if($this->config['date_format']=='m/d/Y') $dash = '/';else $dash = '-';
	$dateformat = $this->config['date_format'];
	$firstdash = strpos($dateformat,$dash,0);
	$firstvalue = substr($dateformat, 0,$firstdash);
	$firstdash = $firstdash + 1;
	$seconddash = strpos($dateformat,$dash,$firstdash);
	$secondvalue = substr($dateformat, $firstdash,$seconddash-$firstdash);
	$seconddash = $seconddash + 1;
	$thirdvalue = substr($dateformat, $seconddash,strlen($dateformat)-$seconddash);
	$js_dateformat = '%'.$firstvalue.$dash.'%'.$secondvalue.$dash.'%'.$thirdvalue;
?>
<!--<link rel="stylesheet" type="text/css" media="all" href="<?php //echo $mainframe->getBasePath(); ?>../components/com_jsjobs/css/jsjobs01.css" />-->

<script language="javascript">
// for joomla 1.6
Joomla.submitbutton = function(task){
        if (task == ''){
                return false;
        }else{
                if (task == 'save'){
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
                alert('Some values are not acceptable.  Please retry.');
				return false;
        }
		return true;
}
</script>
<?php $trclass = array('row0','row1'); $isodd = 0;?>
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
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_SEARCH_RESUME'); ?></div>


<form action="index.php?option=com_jsjobs&task=view&layout=resume_searchresults" method="post" name="adminForm" id="adminForm"  >
 <input type="hidden" name="isjobsearch" value="1" />
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		
		 <?php if ( $this->config['search_resume_title'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td width="20%" align="right"><?php echo JText::_('JS_APPLICATION_TITLE'); ?></td>
					  <td width="60%"><input class="inputbox" type="text" name="title" size="40" maxlength="255"  />
					</td>
				  </tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_name'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td width="20%" align="right"><?php echo JText::_('JS_NAME'); ?></td>
					  <td width="60%"><input class="inputbox" type="text" name="name" size="40" maxlength="255"  />
					</td>
				  </tr>
       <?php } ?>
	      <?php if ( $this->searchresumeconfig['search_resume_nationality'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td align="right"><?php echo JText::_('JS_NATIONALITY'); ?></td>
					<td><?php echo $this->searchoptions['nationality']; ?>
					</td>
				  </tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_gender'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td  align="right" class="textfieldtitle">	<?php echo JText::_('JS_GENDER');  ?>	</td>
					<td><?php echo $this->searchoptions['gender'];	?>	</td>
				</tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_available'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td valign="top" align="right"><?php echo JText::_('JS_I_AM_AVAILABLE'); ?></td>
					<td><input type='checkbox' name='iamavailable' value='1' <?php if(isset($this->resume)) echo ($this->resume->iamavailable == 1) ? "checked='checked'" : ""; ?> /></td>
				</tr>
 	  <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_category'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td valign="top" align="right"><?php echo JText::_('JS_CATEGORIES'); ?></td>
					<td><?php echo $this->searchoptions['jobcategory']; ?></td>
				  </tr>
	   <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_subcategory'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td valign="top" align="right"><?php echo JText::_('JS_SUB_CATEGORIES'); ?></td>
					<td id="fj_subcategory"><?php echo $this->searchoptions['jobsubcategory']; ?></td>
				  </tr>
	   <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_type'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
					<td><?php echo $this->searchoptions['jobtype']; ?></td>
				  </tr>
	  <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_salaryrange'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
					<td> 
						<?php echo $this->searchoptions['currency']; ?>
						<?php echo $this->searchoptions['jobsalaryrange']; ?></td>
				  </tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_heighesteducation'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td valign="top" align="right"><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?></td>
					<td><?php echo $this->searchoptions['heighestfinisheducation']; ?></td>
				  </tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_experience'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td valign="top" align="right"><?php echo JText::_('JS_EXPERIENCE'); ?></td>
					<td><input class="inputbox" type="text" name="experience" size="10" maxlength="15"  /></td>
				  </tr>
      <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_zipcode'] == '1' ) { ?>
				  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
					<td valign="top" align="right"><?php echo JText::_('JS_ZIPCODE'); ?></td>
					<td><input class="inputbox" type="text" name="zipcode" size="10" maxlength="15"  /></td>
				  </tr>
      <?php } ?>
  <tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
        <td colspan="2" height="5"></td>
      <tr>
	<tr class="<?php echo $trclass[$isodd];$isodd = 1-$isodd;?>">
		<td colspan="2" align="center">
		<input class="button" type="submit" onclick="document.adminForm.submit();" name="submit_app" onClick="return myValidate();" value="<?php echo JText::_('JS_SEARCH_RESUME'); ?>" />
		</td>
	</tr>
    </table>


			<input type="hidden" name="isresumesearch" value="1" />
		 	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		 	<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task11" value="view" />
		  

<script language="javascript">
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

	xhr.open("GET","index.php?option=com_jsjobs&task=listsubcategoriesforsearch&val="+val,true);
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
