<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Oct 29th, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jssresumeearch.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		NONE
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
$document->addScript( JURI::base() . '/includes/js/joomla.javascript.js');

$sh_title = $params->get('title', 1);
$sh_name = $params->get('name', 1);
$sh_nationality = $params->get('natinality', 1);
$sh_gender = $params->get('gender', 1);
$sh_iamavailable = $params->get('iamavailable', 1);

$sh_category = $params->get('category', 1);
$sh_subcategory = $params->get('subcategory', 1);
$sh_jobtype = $params->get('jobtype', 1);
$sh_salaryrange = $params->get('salaryrange', 1);
$sh_heighesteducation = $params->get('heighesteducation', 1);
$sh_experience = $params->get('experience', 1);
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
$divclass=array('odd','even');

require_once $componentPath.'/models/mpjsjobs.php';
$config = array(
	'table_path' => $componentAdminPath.'/tables'
);
$model = new JSJobsModelMpJsjobs($config);
$result = $model->resumesearch($sh_gender,$sh_nationality,$sh_category,$sh_subcategory,$sh_jobtype,$sh_heighesteducation,$sh_salaryrange,'');

$gender = $result[0];
$nationality = $result[1];
$job_categories = $result[2];
$job_type = $result[3];
$heighest_finisheducation= $result[4];
$salary_range = $result[5];
$currency = $result[6];
$job_subcategories = $result[7];

$lang = JFactory::getLanguage();
$lang->load('com_jsjbos');

?>
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$itemid); ?>" method="post" name="mrsadminForm" id="mrsadminForm">
	<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
      <?php if ( $sh_title == 1 ) { ?>
      <tr>
        <td width="<?php echo $colwidth ; ?>" align="left"><?php echo JText::_('JS_APPLICATION_TITLE'); ?>
		<br><input class="inputbox" type="text" name="title" size="27" maxlength="255"  />
        </td>
       <?php } ?>
      <?php if ( $sh_name == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?> 
        <td <?php echo $colwidth ; ?> align="left"><?php echo JText::_('JS_NAME'); ?>
		<br><input class="inputbox" type="text" name="name" size="27" maxlength="255"  />
        </td>
       <?php } ?>
	      <?php if ( $sh_nationality == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?> 
        <td <?php echo $colwidth ; ?> align="left"><?php echo JText::_('JS_NATIONALITY'); ?>
		<br><?php echo $nationality; ?>
        </td>
       <?php } ?>
      <?php if ( $sh_gender == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?> 
			<td  <?php echo $colwidth ; ?> align="left" class="textfieldtitle">	<?php echo JText::_('JS_GENDER');  ?>	
			<br><?php echo $gender;	?>	</td>
       <?php } ?>

      <?php if ( $sh_category == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?> 
        <td <?php echo $colwidth ; ?> valign="top" align="left"><?php echo JText::_('JS_CATEGORIES'); ?>
		<br><?php echo $job_categories; ?></td>
       <?php } ?>
      <?php if ( $sh_subcategory == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?>
        <td id="modresumefj_subcategory" <?php echo $colwidth ; ?> valign="top" align="left"><?php echo JText::_('JS_SUB_CATEGORIES'); ?>
		<br><?php echo $job_subcategories; ?></td>
       <?php } ?>
      <?php if ( $sh_jobtype == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?> 
        <td <?php echo $colwidth ; ?> valign="top" align="left"><?php echo JText::_('JS_JOBTYPE'); ?>
		<br><?php echo $job_type; ?></td>
       <?php } ?>
      <?php if ( $sh_salaryrange == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?> 
        <td <?php echo $colwidth ; ?> valign="top" align="left"><?php echo JText::_('JS_SALARYRANGE'); ?>
		<br><?php echo $currency.' '.$salary_range; ?></td>
       <?php } ?>
      <?php if ( $sh_heighesteducation == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?> 
        <td <?php echo $colwidth ; ?> valign="top" align="left"><?php echo JText::_('JS_HEIGHTESTEDUCATION'); ?>
		<br><?php echo $heighest_finisheducation; ?></td>
       <?php } ?>
      <?php if ( $sh_experience == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?> 
        <td <?php echo $colwidth ; ?> valign="top" align="left"><?php echo JText::_('JS_EXPERIENCE'); ?>
		<br><input class="inputbox" type="text" name="experience" size="27" maxlength="25"  /></td>
       <?php } ?>

      <?php if ( $sh_iamavailable == 1 ) { if($colcount == $colperrow){ echo '</tr><tr>'; $colcount = 0; } $colcount++; ?> 
				<td <?php echo $colwidth ; ?> valign="top" align="left"><?php echo JText::_('JS_AVAILABLE'); ?>
				<input type='checkbox' name='iamavailable' value='1'  /></td>
       <?php } ?>
		<?php									
			for($i = $colcount; $i < $colperrow; $i++){
				echo '<td></td>';
			}
			echo '</tr>';
			$colcount=0;
		?>									
	<tr>
		<td colspan="<?php echo $colperrow ;?>" align="center" nowrap="nowrap">
		<input type="submit" class="button" id="button" name="submit_app" onclick="document.mrsadminForm.submit();" value="<?php echo JText::_('JS_SEARCH_RESUME'); ?>" />&nbsp;&nbsp;&nbsp;<a id="button" class="button minpad" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resumesearch&Itemid=<?php echo $itemid;?>"><?php echo JText::_('JS_ADVANCED_SEARCH');?></a>
		</td>
	</tr>
    </table>

 			<input type="hidden" name="isresumesearch" value="1" />
			<input type="hidden" name="view" value="employer" />
			<input type="hidden" name="layout" value="resume_searchresults" />
			<input type="hidden" name="uid" value="" />
			<input type="hidden" name="option" value="com_jsjobs" />
			<input type="hidden" name="zipcode" value="" />
			
		  
		  
			  
		</form>
<script language="javascript" type="text/javascript">
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

</script>
