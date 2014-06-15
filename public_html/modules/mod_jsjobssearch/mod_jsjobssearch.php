<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Oct 2nd, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jsjobssearch.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		1.0.3 - Nov 27, 2010
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

$document = &JFactory::getDocument();
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
if($jversion < 3){
	$document->addScript('components/com_jsjobs/js/jquery.js');
	JHtml::_('behavior.mootools');
}else{
	JHtml::_('behavior.framework');
	JHtml::_('jquery.framework');
}	
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');

JHTML :: _('behavior.calendar');
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

$sh_category = $params->get('category', 1);
$sh_subcategory = $params->get('subcategory', 1);
$sh_jobtype = $params->get('jobtype', 1);
$sh_jobstatus = $params->get('jobstatus', 1);
$sh_salaryrange = $params->get('salaryrange', 1);
$sh_shift = $params->get('shift', 1);
$sh_durration = $params->get('durration', 1);
$sh_startpublishing = $params->get('startpublishing', 1);
$sh_stoppublishing = $params->get('stoppublishing', 1);
$sh_company = $params->get('company', 1);
$sh_addresses = $params->get('addresses', 1);
$colperrow = $params->get('colperrow', 3);


$colwidth = Round(100/$colperrow,1);
$colwidth = $colwidth.'%';
$colcount = 1;

//scs				
if($params->get('Itemid')) $itemid = $params->get('Itemid');			
else  $itemid =  JRequest::getVar('Itemid');
//sce
$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
$componentPath =  'components/com_jsjobs';
require_once $componentPath.'/models/mpjsjobs.php';
$config = array( 'table_path' => $componentAdminPath.'/tables');
$divclass=array('odd','even');

$model = new JSJobsModelMpJsjobs($config);
$result = $model->jobsearch($sh_category,$sh_subcategory,$sh_company,$sh_jobtype,$sh_jobstatus,$sh_shift,$sh_salaryrange,'');

$js_dateformat = $result[0];
$currency = $result[1];
$job_categories = $result[2];
$search_companies = $result[3];
$job_type= $result[4];
$job_status = $result[5];
$search_shift = $result[6];
$salaryrangefrom =$result[7];
$salaryrangeto =$result[8];
$salaryrangetypes =$result[9];
$job_subcategories = $result[10];

$lang = JFactory::getLanguage();
$lang->load('com_jsjobs');


	
?>
 <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='. $itemid);?>" method="post" name="mjsadminForm" id="mjsadminForm">
	<input type="hidden" name="isjobsearch" value="1" />
	<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
      <tr><td width="<?php echo $colwidth ;?>" align="left"><?php echo JText::_('JS_JOB_TITLE'); ?>
		<br><input class="inputbox" type="text" name="title" size="27" maxlength="255"  /> </td> 
      <?php if ( $sh_category == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
		<td  width="<?php echo $colwidth ;?>" valign="top" align="left"><?php echo JText::_('JS_CATEGORIES'); ?>
		<br><?php echo $job_categories; ?></td>
       <?php } ?>
      <?php if ( $sh_subcategory == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
		<td id="modfj_subcategory" width="<?php echo $colwidth ;?>" valign="top" align="left"><?php echo JText::_('JS_SUB_CATEGORIES'); ?>
		<br><?php echo $job_subcategories; ?></td>
       <?php } ?>
      <?php if ( $sh_jobtype == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
      <td  width="<?php echo $colwidth ;?>" valign="top" align="left"><?php echo JText::_('JS_JOBTYPE'); ?>
	  <br><?php echo $job_type; ?></td> 
       <?php } ?>
      <?php if ( $sh_jobstatus == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
        <td  width="<?php echo $colwidth ;?>" valign="top" align="left"><?php echo JText::_('JS_JOBSTATUS'); ?>
        <br><?php echo $job_status; ?></td>
       <?php } ?>
      <?php if ( $sh_salaryrange == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
	  <td width="<?php echo $colwidth ;?>" valign="top" align="left"><?php echo JText::_('JS_SALARYRANGE'); ?>
        <br><?php echo $currency.' '.$salaryrangefrom.' '.$currency.' '.$salaryrangeto.' '.$salaryrangetypes; ?></td>
       <?php } ?>
      <?php if ( $sh_shift == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
        <td  width="<?php echo $colwidth ;?>" valign="top" align="left"><?php echo JText::_('JS_SHIFT'); ?>
        <br><?php echo $search_shift; ?></td>
       <?php } ?>
      <?php if ( $sh_durration == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
        <td width="<?php echo $colwidth ;?>" valign="top" align="left"><?php echo JText::_('JS_DURATION'); ?>
        <br><input class="inputbox" type="text" name="durration" size="10" maxlength="15"  /></td>
       <?php } ?>
      <?php if ( $sh_startpublishing == 1 ) {if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; 
       $startdatevalue = '';	  ?>
			<td  width="<?php echo $colwidth ;?>" valign="top" align="left"><?php echo JText::_('JS_START_PUBLISHING'); ?>
			<?php  if($jversion == '1.5'){ ?><input class="inputbox" type="text" name="startpublishing" id="startpublishingsr" readonly class="Shadow Bold" size="10" value="" />
			<input type="reset" class="button" value="..." onclick="return showCalendar('startpublishingsr','<?php echo $js_dateformat; ?>');"  />
			<?php 
			}else{	echo JHTML::_('calendar', $startdatevalue,'startpublishing', 'startpublishing',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); }?>
			</td>
		<?php } ?>
      <?php if ( $sh_stoppublishing == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; 
       $stopdatevalue = '';	  ?>
			<td width="<?php echo $colwidth ;?>" valign="top" align="left"><?php echo JText::_('JS_STOP_PUBLISHING'); ?>
			<?php  if($jversion == '1.5'){ ?><input class="inputbox" type="text" name="stoppublishing" id="stoppublishingsr" readonly class="Shadow Bold" size="10" value="" />
			<input type="reset" class="button" value="..." onclick="return showCalendar('stoppublishingsr','<?php echo $js_dateformat; ?>');"  />
			<?php 
			}else{	echo JHTML::_('calendar', $stopdatevalue,'stoppublishing', 'stoppublishing',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); }?>
			</td>
		<?php } ?>

      <?php if ( $sh_company == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
        <td width="<?php echo $colwidth ;?>" align="left"><?php echo JText::_('JS_COMPANYNAME'); ?>
        <br><?php echo $search_companies; ?>
        </td>
       <?php } ?>
      <?php if ( $sh_addresses == 1 ) { 
		    /*if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
			<td width="<?php echo $colwidth ;?>" align="left"><?php echo JText::_('JS_COUNTRY'); ?>
			<br><span id="modsearchjob_country">
				  <?php //echo $this->options['country']; ?>
				  <input class="inputbox" type="text" name="country" size="27" maxlength="100" />
			</td>
			<?php if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
			<td width="<?php echo $colwidth ;?>" align="left"><?php echo JText::_('JS_STATE'); ?>
			<br><span id="modsearchjob_state">
					<input class="inputbox" type="text" name="state" size="27" maxlength="100" />
			</td>
			<?php if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
			<td width="<?php echo $colwidth ;?>" align="left"><?php echo JText::_('JS_COUNTY'); ?>
			<br><span id="modsearchjob_county">
					<input class="inputbox" type="text" name="county" size="27" maxlength="100" />
			 </td>*/
			if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
			<td  width="<?php echo $colwidth ;?>" align="left"><?php echo JText::_('JS_CITY'); ?>
			<br><span id="modsearchjob_city">
					<input class="inputbox" type="text" name="searchcity" id="citymod" size="27" maxlength="100"  />
			</td>
			<!--
			<?php /*if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
			<td width="<?php echo $colwidth ;?>" align="left"><?php echo JText::_('JS_ZIPCODE');*/ ?>
			<br><input class="inputbox" type="text" name="zipcode" size="27" maxlength="100"  />
			</td>-->
       <?php } ?>
		<?php									
			for($i = $colcount; $i < $colperrow; $i++){
				echo '<td></td>';
			}
			echo '</tr>';
			$colcount=0;
		?>									
	<tr>
		<td colspan="<?php echo  $colperrow ; ?>" align="center">
		<input id="button" type="submit" class="button" name="submit_app" onclick="document.mjsadminForm.submit();" value="<?php echo JText::_('JS_SEARCH_JOB'); ?>" />&nbsp;&nbsp;&nbsp;<span id="themeanchor"><a id="button" class="button minpad" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $itemid;?>"><?php echo JText::_('JS_ADVANCED_SEARCH'); ?></a></span>
		</td>
	</tr>
    </table>

			<input type="hidden" name="view" value="jobseeker" />
			<input type="hidden" name="layout" value="job_searchresults" />
			<input type="hidden" name="uid" value="" />
			<input type="hidden" name="option" value="com_jsjobs" />
			
		  
<?php if ( $sh_addresses == 1 ) { ?>
<script language=Javascript>
	
	
        jQuery(document).ready(function() {
            jQuery("#citymod").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
                theme: "jsjobs",
                width:jQuery("span#modsearchjob_city").width(),
                preventDuplicates: true,
                hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
                noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
                searchingText: "<?php echo JText::_('SEARCHING...');?>",
                tokenLimit: 1

            });
        });
	
function modsearchjob_dochange(src, val){
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
 
        	document.getElementById("modsearchjob_"+src).innerHTML=xhr.responseText; //retuen value

			if(src=='state'){
			countyhtml = "<input class='inputbox' type='text' name='county' size='27' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='city' size='27' maxlength='100'  />";
			document.getElementById('modsearchjob_county').innerHTML=countyhtml; //retuen value
			document.getElementById('modsearchjob_city').innerHTML=cityhtml; //retuen value
			}else if(src=='county'){
				cityhtml = "<input class='inputbox' type='text' name='city' size='27' maxlength='100'  />";
				document.getElementById('modsearchjob_city').innerHTML=cityhtml; //retuen value
			}
 
      }
    }
 
	xhr.open("GET","index2.php?option=com_jsjobs&task=listmodsearchaddressdata&data="+src+"&val="+val+"&for=modsearchjob_",true);
	xhr.send(null);

}
function modfj_getsubcategories(src, val){
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

	xhr.open("GET","index.php?option=com_jsjobs&task=listsubcategoriesForSearch&val="+val+"&md="+1,true);
	xhr.send(null);
}



//window.onLoad=modsearchjob_dochange('country', -1);         // value in first dropdown
</script>
<?php } ?>
			  
		</form>

