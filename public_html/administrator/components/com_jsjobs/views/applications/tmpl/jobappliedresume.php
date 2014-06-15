<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/jobappliedresumes.php
 ^ 
 * Description: Default template for job applied resumes view
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

JRequest :: setVar('layout', 'jobappliedresume');
$_SESSION['cur_layout']='jobappliedresume';
$version = new JVersion;
$joomla = $version->getShortVersion();

$document =& JFactory::getDocument();
$document->addStyleSheet('../components/com_jsjobs/css/jsjobsresumerating.css');
$document->addStyleSheet('../components/com_jsjobs/themes/graywhite/css/jsjobsgraywhite.css');
	if($joomla < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('../components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	



$actions = array(
	'0' => array('value' => 1,'text' => JText::_('JS_SHORT_LIST')),
	'1' => array('value' => 2,'text' => JText::_('JS_SEND_MESSAGE')),
	'2' => array('value' => 3,'text' => JText::_('JS_FOLDER')),
	'3' => array('value' => 4,'text' => JText::_('JS_COMMENTS')),
        );
$actioncombo = JHTML::_('select.genericList', $actions, 'action', 'class="inputbox" '. '', 'value', 'text', '');

$status = array(
	'1' => JText::_('JOB_APPROVED'),
	'-1' => JText::_('JOB_REJECTED'));

?>
<script type="text/javascript">
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

function tabaction(jobid,action){
	
	document.getElementById('jobid').value=jobid;
	document.getElementById('tab_action').value=action;
	document.getElementById('task').value='aappliedresumetabactions';
	document.forms.adminForm.submit();
}	
function tabsearch(jobid,searchtype,selected_tab){
	var element = jQuery("#jsjobs_appliedapplication_tab_container .jsjobs_appliedapplication_tab_selected");
	element.removeClass("jsjobs_appliedapplication_tab_selected");
	jQuery(selected_tab).parents('span').addClass('jsjobs_appliedapplication_tab_selected');	
	var searchhtml='#jsjobs_appliedresume_tab_search';
	jQuery(searchhtml).slideDown("slow");
}	
function jobappliedresumesearch(jobid,action){
	document.getElementById('jobid').value=jobid;
	document.getElementById('tab_action').value=action; // 6 for search 
	document.getElementById('task').value='aappliedresumetabactions';
	document.forms["adminForm"].submit();
	
	}	
function closetabsearch(src){
		jQuery(src).slideUp("slow");
}
function actioncall(jobapplyid,jobid, resumeid, action){
        if(action == 3){ // folder
            getfolders('resumeaction_'+jobapplyid,jobid,resumeid,jobapplyid);
        }else if(action == 4){ // comments
            getresumecomments('resumeaction_'+jobapplyid,jobapplyid);
        }else if(action == 5){ // email candidate
            mailtocandidate('resumeaction_'+jobapplyid,resumeid,jobapplyid);
        }else{
			var src = '#resumeactionmessage_'+jobapplyid;
			var htmlsrc = '#jsjobs_appliedresume_data_action_message_'+jobapplyid;
			jQuery(src).html("Loading ...");
			//document.getElementById(src).innerHTML="Loading ...";
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
				  
				//document.getElementById(src).innerHTML=xhr.responseText; //retuen value
				jQuery(src).html(xhr.responseText);
				jQuery(htmlsrc).slideDown("slow");

				setTimeout(function(){closeresumeactiondiv(htmlsrc)},3000);

			  }
			}
			xhr.open("GET","index.php?option=com_jsjobs&task=saveshortlistcandiate&jobid="+jobid+"&resumeid="+resumeid+"&action="+action,true);
			xhr.send(null);
			
			
			
			
			
        }
	
}
function closeresumeactiondiv(src){
		jQuery(src).slideUp("slow");
}

function actionchangestatus(jobapplyid,jobid, resumeid, action){

		var src = '#resumeactionmessage_'+jobapplyid;
		var htmlsrc = '#jsjobs_appliedresume_data_action_message_'+jobapplyid;
		jQuery(src).html("Loading ...");
		//document.getElementById(src).innerHTML="Loading ...";
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
				jQuery(src).html(xhr.responseText);
				jQuery(htmlsrc).slideDown("slow",function(){
					setTimeout(function(){
						jQuery(htmlsrc).slideUp("slow",function(){
								var target=jQuery('div[data-containerid=container_'+jobapplyid+']');
								target.fadeOut('slow',function(){ jQuery(this).remove();});				
							});
					},3000);
				});


				
	  }
		}
		xhr.open("GET","index.php?option=com_jsjobs&task=updateactionstatus&jobid="+jobid+"&resumeid="+resumeid+"&applyid="+jobapplyid+"&action_status="+action,true);
		xhr.send(null);
		
		
}

function setresumeid(resumeid, action){
        document.getElementById('resumeid').value=resumeid;
        document.getElementById('action').value=document.getElementById(action).value;
        document.forms["adminForm"].submit();
}
function saveaddtofolder(jobapplyid,jobid,resumeid){
		var src = '#resumeactionmessage_'+jobapplyid;
		var htmlsrc = '#jsjobs_appliedresume_data_action_message_'+jobapplyid;
		var clearhtml ='#resumeaction_'+jobapplyid;
        var folderid=document.getElementById('folderid').value;
		jQuery(src).html("Loading ...");
		//document.getElementById(src).innerHTML="Loading ...";
		
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
					jQuery(src).html(xhr.responseText);
					jQuery(clearhtml).html("");
					jQuery(htmlsrc).slideDown("slow");
					setTimeout(function(){closeresumeactiondiv(htmlsrc)},3000);
		  }
		}
		xhr.open("GET","index.php?option=com_jsjobs&task=saveresumefolder&jobid="+jobid+"&resumeid="+resumeid+"&applyid="+jobapplyid+"&folderid="+folderid,true);
		xhr.send(null);
	
	
}
function saveresumecomments(jobapplyid,resumeid){
	
			var src = '#resumeactionmessage_'+jobapplyid;
			var htmlsrc = '#jsjobs_appliedresume_data_action_message_'+jobapplyid;
			var clearhtml ='#resumeaction_'+jobapplyid;
			
			var comments=document.getElementById('comments').value;
			jQuery(src).html("Loading ...");
			//document.getElementById(src).innerHTML="Loading ...";
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
					jQuery(src).html(xhr.responseText);
					jQuery(clearhtml).html("");
					jQuery(htmlsrc).slideDown("slow");
					setTimeout(function(){closeresumeactiondiv(htmlsrc)},3000);
				  
					

			  }
			}
			xhr.open("GET","index.php?option=com_jsjobs&task=saveresumecomments&jobapplyid="+jobapplyid+"&resumeid="+resumeid+"&comments="+comments,true);
			xhr.send(null);
	
	
}
function getfolders(src,jobid,resumeid,applyid){
	document.getElementById(src).innerHTML="Loading ...";
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
        	document.getElementById(src).innerHTML=xhr.responseText; //retuen value

      }
    }

	xhr.open("GET","index.php?option=com_jsjobs&task=getmyforlders&jobid="+jobid+"&resumeid="+resumeid+"&applyid="+applyid,true);
	xhr.send(null);
}
function mailtocandidate(src,resumeid,jobapplyid){
	document.getElementById(src).innerHTML="Loading ...";
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
        	document.getElementById(src).innerHTML=xhr.responseText; //retuen value

      }
    }
	xhr.open("GET","index.php?option=com_jsjobs&task=mailtocandidate&resumeid="+resumeid+"&jobapplyid="+jobapplyid,true);
	xhr.send(null);
}
function sendmailtocandidate(jobapplyid){
    	var src = 'resumeactionmessage_'+jobapplyid;
		var arr = new Array();
		var emmailaddress=document.getElementById('emmailaddress').value;	
		if(emmailaddress){
			var result = echeck(emmailaddress);
			if(result == false){
				alert('<?php echo JText::_('JS_INVALID_EMAIL');?>');
				document.getElementById('emmailaddress').focus();
				return false;
			}
			arr[0] = emmailaddress;
			arr[1] = document.getElementById('jsmailaddress').value;
			arr[2] = document.getElementById('jssubject').value;
			arr[3] = document.getElementById('candidatemessage').value;
			sendtocandidate(arr,jobapplyid);
			
		}else{
			alert('<?php echo JText::_('JS_YOUR_EMAIL_IS_REQUIRED');?>');
				document.getElementById('emmailaddress').focus();
			return false;
		}
}
function sendtocandidate(arr,jobapplyid){
	
		var src = '#resumeactionmessage_'+jobapplyid;
		var htmlsrc = '#jsjobs_appliedresume_data_action_message_'+jobapplyid;
		var clearhtml ='#resumeaction_'+jobapplyid;
		jQuery(src).html("Loading ...");
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
			  if(xhr.responseText == true || xhr.responseText){
					jQuery(src).html(xhr.responseText);
					jQuery(clearhtml).html("");
					jQuery(htmlsrc).slideDown("slow");
					setTimeout(function(){closeresumeactiondiv(htmlsrc)},3000);
			  }
		  }
		}
		xhr.open("GET","index.php?option=com_jsjobs&task=sendtocandidate&val="+JSON.stringify(arr),true);
		xhr.send(null);
}

function getresumecomments(src,jobapplyid){
	document.getElementById(src).innerHTML="Loading ...";
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
        	document.getElementById(src).innerHTML=xhr.responseText; //retuen value

      }
    }
	xhr.open("GET","index.php?option=com_jsjobs&task=getresumecomments&jobapplyid="+jobapplyid,true);
	xhr.send(null);
}
function getjobdetail(src,jobid, resumeid){
	document.getElementById(src).innerHTML="Loading ...";
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
        	document.getElementById(src).innerHTML=xhr.responseText; //retuen value

      }
    }

	xhr.open("GET","index.php?option=com_jsjobs&task=getresumedetail&jobid="+jobid+"&resumeid="+resumeid,true);
	xhr.send(null);
}

function clsjobdetail(src){
        document.getElementById(src).innerHTML="";

}
function clsaddtofolder(src){
        document.getElementById(src).innerHTML="";
}

function setrating(src,newrating,ratingid,jobid,resumeid){
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
               if(xhr.responseText == 1)
                document.getElementById(src).style.width=parseInt(newrating*20)+'%';

      }
    }
	xhr.open("GET","index.php?option=com_jsjobs&task=saveresumerating&ratingid="+ratingid+"&jobid="+jobid+"&resumeid="+resumeid+"&newrating="+newrating,true);
	xhr.send(null);


}
function echeck(str) {
	var at="@";
	var dot=".";
	var lat=str.indexOf(at);
	var lstr=str.length;
	var ldot=str.indexOf(dot);

	if (str.indexOf(at)==-1) return false;
	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr) return false;
	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr) return false;
	if (str.indexOf(at,(lat+1))!=-1) return false;
	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot) return false;
	if (str.indexOf(dot,(lat+2))==-1) return false;
	if (str.indexOf(" ")!=-1) return false;
	return true;
}


</script>
<table width="100%" >
	<tr>
		<td align="left" width="175"  valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">

			<form action="index.php" method="post" name="adminForm" id="adminForm">
			<div id="jsjobs_appliedapplication_tab_container">
				<a  onclick="tabaction(<?php echo $this->oi; ?>,'1')">
					<span class='<?php if($this->tabaction==1) {echo 'jsjobs_appliedapplication_tab_selected';}else{echo '';} ?>' id='jsjobs_appliedapplication_tab' >
					<?php echo JText::_('JS_INBOX'); ?>
					</span>
				</a>
				<a onclick="tabaction(<?php echo $this->oi; ?>,'5')" >
					<span class='<?php if($this->tabaction==5) {echo 'jsjobs_appliedapplication_tab_selected';}else{echo '';} ?>' id='jsjobs_appliedapplication_tab'>
					<?php echo JText::_('JS_SHORTLIST'); ?>
					</span>
				</a>
				<a  onclick="tabaction(<?php echo $this->oi; ?>,'2')" >	
					<span class='<?php if($this->tabaction==2) {echo 'jsjobs_appliedapplication_tab_selected';}else{echo '';} ?>' id='jsjobs_appliedapplication_tab'>
						<?php echo JText::_('JS_SPAM'); ?>
					</span>
				</a>
				<a onclick="tabaction(<?php echo $this->oi; ?>,'3')" >	
					<span class='<?php if($this->tabaction==3) {echo 'jsjobs_appliedapplication_tab_selected';}else{echo '';} ?>' id='jsjobs_appliedapplication_tab'>
					<?php echo JText::_('JS_HIRED'); ?>
					</span>
				</a>
				<a  onclick="tabaction(<?php echo $this->oi; ?>,'4')" >	
					<span class='<?php if($this->tabaction==4) {echo 'jsjobs_appliedapplication_tab_selected';}else{echo '';} ?>' id='jsjobs_appliedapplication_tab'>
					<?php echo JText::_('JS_REJECTED'); ?>
					</span>
				</a>	
				<?php  ?>
				<a id="appliedresume_tabnarmalsearch" onclick="tabsearch(<?php echo $this->oi; ?>,'search','#appliedresume_tabnarmalsearch')" >				
					<span class="<?php if($this->tabaction==6) {echo 'jsjobs_appliedapplication_tab_selected';}else{echo '';} ?>" id='jsjobs_appliedapplication_tab' >
						<?php echo JText::_('JS_ADVANCE_SEARCH'); ?>
					</span>
				</a>	
				<!--<span id='jsjobs_appliedapplication_tab'>
					<a id="appliedresume_tabadvancesearch" onclick="tabsearch(<?php echo $this->oi; ?>,'advancesearch','#appliedresume_tabadvancesearch')" ><?php echo JText::_('JS_ADVANCE_SEARCH'); ?></a>
				</span>-->
				<div id="jsjobs_appliedresume_action_allexport">
					<?php  $exportalllink='index.php?option=com_jsjobs&c=jsjobs&task=exportallresume&bd='.$this->oi;?>
					<a href="<?php echo $exportalllink; ?>" >
						<img src="../components/com_jsjobs/images/exportall.png"  />			
						<span id="jsjobs_appliedresume_action_allexport_text" ><?php echo JText::_('JS_ALL_EXPORT'); ?></span>		
					</a>
					
				</div>
				
			</div>	
		<div id="jsjobs_appliedresume_tab_search" style="display: none;">
		<?php 
		$printform = 1;
		if ($printform == 1) { ?>
			<span id="jsjobs_appliedresume_tab_search_title"> 
				<?php echo JText::_('JS_SEARCH_RESUME'); ?>
			</span>
			<div id="jsjobs_appliedresume_tab_search_data">
				<span class="jsjobs_appliedresume_tab_search_data_text">
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_APPLICATION_TITLE').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<input class="inputbox" type="text" name="title" size="20" maxlength="255"  />
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_NAME').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<input class="inputbox" type="text" name="name" size="20" maxlength="255"  />
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_EXPERIENCE').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<input class="inputbox" type="text" name="experience" size="20" maxlength="15"  />
					</span>
				</span>
				<span class="jsjobs_appliedresume_tab_search_data">
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_NATIONALITY').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<?php echo $this->searchoptions['nationality']; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_CATEGORIES').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<?php echo $this->searchoptions['jobcategory']; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_SUB_CATEGORIES').":"; ?>
					</span>
					<span id="fj_subcategory" class="jsjobs_appliedresume_tab_search_data_value">
						<?php echo $this->searchoptions['jobsubcategory']; ?>
					</span>
				</span>
				<span class="jsjobs_appliedresume_tab_search_data">
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_GENDER').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<?php echo $this->searchoptions['gender']; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_JOBTYPE').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<?php echo $this->searchoptions['jobtype']; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_SALARYRANGE').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<?php echo $this->searchoptions['currency'];  ?><?php echo $this->searchoptions['jobsalaryrange']; ?>
					</span>
				</span>
				<span class="jsjobs_appliedresume_tab_search_data">
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<?php echo $this->searchoptions['heighestfinisheducation']; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_title">
						<?php echo JText::_('JS_I_AM_AVAILABLE').":"; ?>
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<span style='text-align:center;font-size:11px;padding-left:19%'><?php echo JText::_('JS_YES'); ?></span><input type="radio" name="iamavailable" value="yes" class="radio" <?php if (isset($_POST['iamavailable']) && $_POST['iamavailable'] == 'yes'): ?>checked='checked'<?php endif; ?> /> 
						<span style='text-align:center;font-size:11px;'><?php echo JText::_('JS_NO'); ?></span><input type="radio" name="iamavailable" value="no"  class="radio" <?php if (isset($_POST['iamavailable']) && $_POST['iamavailable'] ==  'no'): ?>checked='checked'<?php endif; ?> /> 					
						<!--<span style='text-align:center;font-size:9px;padding-left:19%'><?php echo JText::_('JS_YES'); ?></span><input style='padding-top:10%' type='checkbox' name='iamavailable' value='1' />
						<span style='text-align:center;font-size:9px;'><?php echo JText::_('JS_NO'); ?></span><input style='padding-top:10%' type="checkbox" name="iamavailable" value="0">-->
					</span>
					<span class="jsjobs_appliedresume_tab_search_data_value">
						<input type="submit" id="button" class="button" name="submit_app" onclick="jobappliedresumesearch(<?php echo $this->oi; ?>,'6');" value="<?php echo JText::_('JS_SEARCH_RESUME'); ?>" />
					</span>
					<span id='jsjobs_appliedresume_tab_search_close_button'>
						<input type="button" id="button" class="button" name="submit_app" onclick="closetabsearch('#jsjobs_appliedresume_tab_search')" value="<?php echo JText::_('JS_CLOSE'); ?>" />
						<!--<span class="button" onclick="closetabsearch('#jsjobs_appliedresume_tab_search')"><?php echo 'close me'; ?></span>-->
					</span>	
				</span>
			</div>	
		<?php } ?>
		</div>
			
			<?php
			jimport('joomla.filter.output');
			$k = 0;
			$count = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
					$count++;
					$row =& $this->items[$i];
					$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=edit&cid[]='.$row->id);
					$resumelink = 'index.php?option=com_jsjobs&view=application&layout=view_resume&rd='.$row->appid.'&oi='.$this->oi;
					$plink = 'index.php?option=com_jsjobs&view=application&layout=resumeprint&rd='.$row->appid.'&oi='.$this->oi;
					$exportlink='index.php?option=com_jsjobs&task=exportresume&bd='.$this->oi.'&rd='.$row->appid;
			?>
			<div id="jsjobs_appliedapplication_container" class="<?php echo "row$k";?>" data-containerid="container_<?echo $row->jobapplyid;?>">
				<div id="jsjobs_appliedresume_container">
					<div id="jsjobs_appliedresume_top">
						<table id="jsjobs_appliedresume_container_table" cellpadding="1" cellspacing="0" border="0" width="100%">
							<tr>
								<td nowrap="nowrap">
									<span id="jsjobs_appliedresume_applicantname" class="<?php if ($row->resumeview == 0) echo 'bold'; ?>"> 
										<?php echo $row->applicationtitle; ?>
										<span id="jsjobs_appliedresume_applicanttitle" > 
													<?php echo "( ".JText::_('JS_RESUME_TITLE')." )"; ?>
										</span>
									</span>
								</td>
								<td nowrap="nowrap">
									<span id="jsjobs_appliedresume_applieddate" > 
										<?php echo JText::_('JS_APPLIED_DATE').":"; ?>
										<span id="jsjobs_appliedresume_applieddate_value" > 
											<?php echo date($this->config['date_format'],strtotime($row->apply_date)); ?>
										</span>
									</span>
								</td>
								<td >
									<div style='float:right;'>
									<?php
										$id = $row->jobapplyid;
										$percent = 0;
										$stars = '';
										$percent = $row->rating * 20;
										$stars = '-small';
										$html="
											<div class=\"jsjobs-container".$stars."\"".( " style=\"margin-top:5px;\"" ).">
											<ul class=\"jsjobs-stars".$stars."\">
											<li id=\"rating_".$id."\" class=\"current-rating\" style=\"width:".(int)$percent."%;\"></li>
											<li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',1,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Very Poor')."\" class=\"one-star\">1</a></li>
											<li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',2,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Poor')."\" class=\"two-stars\">2</a></li>
											<li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',3,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Regular')."\" class=\"three-stars\">3</a></li>
											<li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',4,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Good')."\" class=\"four-stars\">4</a></li>
											<li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',5,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Very Good')."\" class=\"five-stars\">5</a></li>
											</ul>
											</div>
										";
										$html .="</small></span>";
										echo $html;
									?>
									</div>
								</td>
							</tr>
						</table>	
					</div>	
					<div id="jsjobs_appliedresume_data">
						<table cellpadding="1" cellspacing="0" border="0" width="100%">
							<tr>
								<td >
									<div id="jsjobs_appliedresume_data_detail">
										<span id="jsjobs_appliedresume_data_detail_applicantsummery" > 
											<?php echo JText::_('JS_CURRENT_SALARY').":"; ?>
											<span id="jsjobs_appliedresume_data_detail_applicantsummery_value" > 
												<?php echo $row->symbol . $row->rangestart . ' - ' . $row->symbol.' '. $row->rangeend; ?>
											</span>
										</span>
										<span id="jsjobs_appliedresume_data_detail_applicantsummery" > 
											<?php echo JText::_('JS_TOTAL_EXPERIENCE').":"; ?>
											<span id="jsjobs_appliedresume_data_detail_applicantsummery_value" > 
												<?php echo $row->total_experience;?>
											</span>
										</span>
										<span id="jsjobs_appliedresume_data_detail_applicantsummery" > 
											<?php echo JText::_('JS_EXPECTED_SALARY').":"; ?>
											<span id="jsjobs_appliedresume_data_detail_applicantsummery_value" > 
												<?php echo $row->dsymbol . $row->drangestart . ' - ' . $row->dsymbol.' '. $row->drangeend; ?>
											</span>
										</span>
										<span id="jsjobs_appliedresume_data_detail_applicantsummery" > 
											<?php echo JText::_('JS_EDUCATION').":"; ?>
											<span id="jsjobs_appliedresume_data_detail_applicantsummery_value" > 
												<?php echo $row->education; ?>
											</span>
										</span>
										<span id="jsjobs_appliedresume_data_detail_applicantsummery" > 
											<?php echo JText::_('JS_LOCATION').":"; ?>
											<span id="jsjobs_appliedresume_data_detail_applicantlocation_value" > 
													<?php
														$comma="";
														if ($row->cityname) { echo $row->cityname; $comma = 1; }
														elseif ($row->address_city) { echo $row->address_city; $comma = 1; }
														//if ($row->countyname) { if($comma) echo', '; echo $row->countyname; $comma = 1; }
														//elseif ($row->address_county) { if($comma) echo', '; echo $row->address_county; $comma = 1; }
														if ($row->statename) { if($comma) echo', '; echo $row->statename; $comma = 1; }
														elseif ($row->address_state) { if($comma) echo', '; echo $row->address_state; $comma = 1; }
														if ($row->countryname) { if($comma) echo', '; echo $row->countryname; $comma = 1; }
													 ?>
											</span>
										</span>
									</div>
								</td>
								<td style="width:12%" >
									<?php if($row->photo) { ?>
										<img  width='75px' height='75px' src="<?php echo "../".$this->config['data_directory'];?>/data/jobseeker/resume_<?php echo $row->appid.'/photo/'.$row->photo; ?>"  />
									<?php }else{ ?>
										<img  src="../components/com_jsjobs/images/jsjobs_logo.png" width='75px' height='75px' />
									<?php } ?>
									
								</td>
							</tr>
						</table>
					</div>	
					<div id="resumedetail_<?php echo $row->appid; ?>"></div>
					<div id="jsjobs_appliedresume_data_comments_bottom">
						<span id="jsjobs_appliedresume_data_comments" >
							<span id="jsjobs_appliedresume_data_comments_title">
								<?php echo JText::_('JS_NOTES')."::";?>
							</span>
							<?php echo  $row->comments; ?>
						</span>
						<span id="jsjobs_appliedresime_data_comments_link">
							
							<?php 
								$resumelink = 'index.php?option=com_jsjobs&view=application&layout=view_resume&rd='.$row->appid.'&oi='.$this->oi;
							
							?> 
							<a id="button" class="button minpad" href="<?php echo $resumelink;?>"><?php echo JText::_('JS_VIEW_RESUME');?></a>
						</span>
						<div id="jsjobs_appliedresume_data_action_message_<?php echo $row->jobapplyid; ?>" class="jsjobs_appliedresume_data_action_message" style="display: none;">
							<span id="resumeactionmessage_<?php echo $row->jobapplyid; ?>" ></span>
						</div>
					</div>	
					<div id="jsjobs_appliedresume_actioncontainer">
						<!--<strong><?php echo JText::_('JS_ACTION').":"; ?></strong>-->
						<div id="jsjobs_appliedresume_action">
							<img src="../components/com_jsjobs/images/copytofolder.png" />
							<span id="resume_action_style" onclick="actioncall(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'3')"><?php echo JText::_('JS_COPY_TO_FOLDER'); ?></span>
						</div>
						<div id="jsjobs_appliedresume_action">
							<img src="../components/com_jsjobs/images/emailcandidate.png"   />
							 <span id="resume_action_style" onclick="actioncall(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'5')" ><?php echo JText::_('JS_EMAIL_CANDIDATE'); ?></span>
						</div>
						<?php if($this->tabaction!=5){ ?>
							<div id="jsjobs_appliedresume_action">
								<img src="../components/com_jsjobs/images/apr_shortlist.png"   />
								<!--<span id="resume_action_style"  onclick="actioncall(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'1')" ><?php echo JText::_('JS_SHORT_LIST'); ?></span>-->
								<span id="resume_action_style"  onclick="actionchangestatus(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'5')" ><?php echo JText::_('JS_SHORT_LIST'); ?></span>
							</div>
						<?php }  ?>
						<?php if($this->tabaction!=2){ ?>
							<div id="jsjobs_appliedresume_action">
								<img src="../components/com_jsjobs/images/markspam.png"   />
								<span id="resume_action_style" onclick="actionchangestatus(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'2')" ><?php echo JText::_('JS_MARK_SPAM'); ?></span>
							</div>
						<?php }  ?>
						<?php if($this->tabaction!=3){ ?>
							<div id="jsjobs_appliedresume_action">
								<img src="../components/com_jsjobs/images/hired.png" />
								<span id="resume_action_style" onclick="actionchangestatus(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'3')" ><?php echo JText::_('JS_HIRED'); ?></span>
							</div>
						<?php } ?>
						<?php if($this->tabaction==2){ ?>
							<div id="jsjobs_appliedresume_action">
								<img src="../components/com_jsjobs/images/notespam.png"   />
								<span id="resume_action_style" onclick="actionchangestatus(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'1')" ><?php echo JText::_('JS_NOT_SPAM'); ?></span>
							</div>
						<?php } ?>
						<?php if($this->tabaction!=4){ ?>
							<div id="jsjobs_appliedresume_action">
								<img src="../components/com_jsjobs/images/reject.png" />
								<span id="resume_action_style" onclick="actionchangestatus(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'4')" ><?php echo JText::_('JS_REJECTED'); ?></span>
							</div>
						<?php } ?>
						<div id="jsjobs_appliedresume_action">
							<?php 
								$printlink = 'index.php?option=com_jsjobs&view=application&layout=resumeprint&rd='.$row->appid.'&oi='.$this->oi;
							?>
							<img src="../components/com_jsjobs/images/print.png"   />
							<span id="resume_action_style"><a target="_blank" href="<?php echo $printlink?>"><?php echo JText::_('JS_PRINT'); ?></a></span>
							<!--<span class="resume_action_style" onclick="actioncall(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'1')" ><?php echo JText::_('JS_PRINT'); ?></span>-->
						</div>
						<div id="jsjobs_appliedresume_action">
							<?php  $exportlink='index.php?option=com_jsjobs&c=jsjobs&task=exportresume&bd='.$row->id.'&rd='.$row->appid;?>
							<img src="../components/com_jsjobs/images/export.png"  />
							<span id="resume_action_style"><a href="<?php echo $exportlink; ?>" ><?php echo JText::_('JS_EXPORT'); ?></a></span>
						</div>
						<div id="jsjobs_appliedresume_action">
							<img src="../components/com_jsjobs/images/addnote.png" />
							<span id="resume_action_style" onclick="actioncall(<?php echo $row->jobapplyid; ?>,<?php echo $row->id; ?>,<?php echo $row->appid; ?>,'4')" ><?php echo JText::_('JS_ADD_NOTE'); ?></span>
						</div>	
						<div id="jsjobs_appliedresume_action">
							<img src="../components/com_jsjobs/images/shrotdetail.png" />
							<span id="resume_action_style" onclick='getjobdetail("resumedetail_<?php echo $row->appid; ?>",<?php echo $row->id; ?>,<?php echo $row->appid; ?>)' ><?php echo JText::_('JS_DETAILS'); ?></span>
						</div>
					</div>	
					
					
				<div id="resumeaction_<?php echo $row->jobapplyid; ?>"></div>
					
				</div>
			</div>
				<?php
				$k = 1 - $k;
			}
			?>
			
			<div><?php echo $this->pagination->getListFooter(); ?></div>
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task"  id="task" value="actionresume" />
			<input type="hidden" name="jobid" id="jobid" value="<?php echo $this->oi; ?>" />
			<input type="hidden" name="resumeid" id="resumeid" value="<?php echo $row->appid; ?>" />
			<input type="hidden" name="id" id="id" value="" />
			<input type="hidden" name="action" id="action" value="" />
			<input type="hidden" name="action_status" id="action_status" value="" />
			<input type="hidden" name="tab_action" id="tab_action" value="" />
			
			<input type="hidden" name="boxchecked" value="0" />
			</form>
			<div style="float:left;"><?php echo eval(base64_decode('CQkJZWNobyAnPHRhYmxlIHdpZHRoPSIxMDAlIiBzdHlsZT0idGFibGUtbGF5b3V0OmZpeGVkOyI+DQo8dHI+PHRkIGhlaWdodD0iMTUiPjwvdGQ+PC90cj4NCjx0cj4NCjx0ZCBzdHlsZT0idmVydGljYWwtYWxpZ246bWlkZGxlOyIgYWxpZ249ImNlbnRlciI+DQo8YSBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIiB0YXJnZXQ9Il9ibGFuayI+PGltZyBzcmM9Imh0dHA6Ly93d3cuam9vbXNreS5jb20vbG9nby9qc2pvYnNjcmxvZ28ucG5nIiA+PC9hPg0KPGJyPg0KQ29weXJpZ2h0ICZjb3B5OyAyMDA4IC0gJy4gZGF0ZSgnWScpIC4nLCA8YSBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSIgdGFyZ2V0PSJfYmxhbmsiPkJ1cnVqIFNvbHV0aW9uczwvYT4gDQo8L3RkPg0KPC90cj4NCjwvdGFibGU+JzsNCg=='));	?>	</div>
	
</table>
<script language="texxt/Javascript">

</script>
