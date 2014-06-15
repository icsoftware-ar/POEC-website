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
 * File Name:	views/employer/tmpl/jobsearch.php
 ^ 
 * Description: template for job search
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');

$editor = & JFactory :: getEditor();

global $mainframe;

$document = &JFactory::getDocument();
 $document->addScript( JURI::base() . '/includes/js/joomla.javascript.js');
	$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);

JHTML :: _('behavior.calendar');
$width_big = 40;
$width_med = 25;
$width_sml = 15;
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
				<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_SEARCH_RESUME'); ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			if (sizeof($this->jobseekerlinks) != 0){
				foreach($this->jobseekerlinks as $lnk)	{ ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			}
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_MY_FOLDER');
			$count = 0;
			if (sizeof($this->employerlinks) != 0){
				foreach($this->employerlinks as $lnk)	{
					if ($count == 1) { ?>
						<a href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
					<?php }	?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php $count++;
				}
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_SEARCH_RESUME');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
	$printform = 1;
	if($this->canview == 0){ 
			$printform = 0;?>
			<?php
				$message = "<font color='red'><strong>" . JText::_('JS_YOU_CAN_NOT_VIEW_RESUME_SEARCH_FORM') . "</strong></font>";?>
			<div id="errormessage" class="errormessage">
				<div id="message"><?php echo $message;?></div>
			</div>
				<?php
        }

if ($printform == 1) {
if ($this->userrole->rolefor == 1) { // employer
?>
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$this->Itemid); ?>" method="post" name="adminForm" id="adminForm">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
      <?php if ( $this->searchresumeconfig['search_resume_title'] == '1' ) { ?>
				  <tr>
					<td width="20%" align="right"><?php echo JText::_('JS_APPLICATION_TITLE'); ?></td>
					  <td width="60%"><input class="inputbox" type="text" name="title" size="40" maxlength="255"  />
					</td>
				  </tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_name'] == '1' ) { ?>
				  <tr>
					<td width="20%" align="right"><?php echo JText::_('JS_NAME'); ?></td>
					  <td width="60%"><input class="inputbox" type="text" name="name" size="40" maxlength="255"  />
					</td>
				  </tr>
       <?php } ?>
	      <?php if ( $this->searchresumeconfig['search_resume_nationality'] == '1' ) { ?>
				  <tr>
					<td align="right"><?php echo JText::_('JS_NATIONALITY'); ?></td>
					<td><?php echo $this->searchoptions['nationality']; ?>
					</td>
				  </tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_gender'] == '1' ) { ?>
				<tr>
					<td  align="right" class="textfieldtitle">	<?php echo JText::_('JS_GENDER');  ?>	</td>
					<td><?php echo $this->searchoptions['gender'];	?>	</td>
				</tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_available'] == '1' ) { ?>
				<tr>
					<td valign="top" align="right"><?php echo JText::_('JS_I_AM_AVAILABLE'); ?></td>
					<td><input type='checkbox' name='iamavailable' value='1' <?php if(isset($this->resume)) echo ($this->resume->iamavailable == 1) ? "checked='checked'" : ""; ?> /></td>
				</tr>
 	  <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_category'] == '1' ) { ?>
				 <tr>
					<td valign="top" align="right"><?php echo JText::_('JS_CATEGORIES'); ?></td>
					<td><?php echo $this->searchoptions['jobcategory']; ?></td>
				  </tr>
	   <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_subcategory'] == '1' ) { ?>
				 <tr>
					<td valign="top" align="right"><?php echo JText::_('JS_SUB_CATEGORIES'); ?></td>
					<td id="fj_subcategory"><?php echo $this->searchoptions['jobsubcategory']; ?></td>
				  </tr>
	   <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_type'] == '1' ) { ?>
				  <tr>
					<td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
					<td><?php echo $this->searchoptions['jobtype']; ?></td>
				  </tr>
	  <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_salaryrange'] == '1' ) { ?>
				  <tr>
					<td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
					<td><?php echo $this->searchoptions['currency'];  ?><?php echo $this->searchoptions['jobsalaryrange']; ?></td>
				  </tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_heighesteducation'] == '1' ) { ?>
				   <tr>
					<td valign="top" align="right"><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?></td>
					<td><?php echo $this->searchoptions['heighestfinisheducation']; ?></td>
				  </tr>
       <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_experience'] == '1' ) { ?>
				   <tr>
					<td valign="top" align="right"><?php echo JText::_('JS_EXPERIENCE'); ?></td>
					<td><input class="inputbox" type="text" name="experience" size="10" maxlength="15"  /></td>
				  </tr>
      <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_zipcode'] == '1' ) { ?>
				   <tr>
					<td valign="top" align="right"><?php echo JText::_('JS_ZIPCODE'); ?></td>
					<td><input class="inputbox" type="text" name="zipcode" size="10" maxlength="15"  /></td>
				  </tr>
      <?php } ?>
      <?php if ( $this->searchresumeconfig['search_resume_keywords'] == '1' ) { ?>
				   <tr>
					<td valign="top" align="right"><?php echo JText::_('JS_KEYWORDS'); ?></td>
					<td><input class="inputbox" type="text" name="keywords" size="40"   /></td>
				  </tr>
      <?php } ?>

	<tr>
		<td colspan="2" align="center">
			<input type="submit" id="button" class="button" name="submit_app" onclick="document.adminForm.submit();" value="<?php echo JText::_('JS_SEARCH_RESUME'); ?>" />
		</td>
	</tr>
    </table>

			<input type="hidden" name="isresumesearch" value="1" />
			<input type="hidden" name="view" value="employer" />
			<input type="hidden" name="layout" value="resume_searchresults" />
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

	xhr.open("GET","index.php?option=com_jsjobs&task=listsubcategoriesForSearch&val="+val,true);
	xhr.send(null);
}
</script>


		</form>
<?php

} else{ // not allowed employer ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php

}	
}
}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
<script type="text/javascript" language="javascript">
	function setLayoutSize(){
		var totalwidth = document.getElementById("rl_maindiv").offsetWidth;
		var per_width = (totalwidth*0.23)-10;
		var totalimagesdiv = document.getElementsByName("rl_imagediv").length;
		for(var i = 0;i<totalimagesdiv;i++){
			document.getElementsByName("rl_imagediv")[i].style.minWidth = per_width+"px";
			document.getElementsByName("rl_imagediv")[i].style.width = per_width+"px";
		}
		var totalimages = document.getElementsByName("rl_image").length;
		for(var i = 0;i<totalimages;i++){
			//document.getElementsByName("rl_image")[i].style.minWidth = per_width+"px";
			document.getElementsByName("rl_image")[i].style.width = per_width+"px";
			document.getElementsByName("rl_image")[i].style.maxWidth = per_width+"px";
		}
	}
	setLayoutSize();
</script>
