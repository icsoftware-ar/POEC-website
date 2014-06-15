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
 * File Name:	views/jobseeker/tmpl/filters.php
 ^ 
 * Description: template view for filters
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access'); ?>
<div id="tellafriend" class="tellafriend">
	<form action="index.php" method="POST">
		<div id="tellafriend_headline">
			<div id="tp_heading">
				<span id="tp_headingtext">
					<span id="tp_headingtext_left"></span>
					<span id="tp_headingtext_center"><?php echo JText::_('JS_TELL_A_FRIEND');?></span>
					<span id="tp_headingtext_right"></span>				
				</span>
			</div>
		</div>
			<table>
				<tr>
					<td colspan="4" height="5"></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_('JS_YOUR_NAME');?><font color="red">*</font></td>
					<td><input class="inputbox required" type="text" name="sendername" id="sendername" value=""/></td>
					<td align="right"><?php echo JText::_('JS_YOUR_EMAIL');?><font color="red">*</font></td>
					<td><input class="inputbox required" type="text" name="senderemail" id="senderemail" value=""/></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_('JS_JOB_TITILE');?></td>
					<td colspan="3"><input class="inputbox required" type="text" name="jobtitle" id="jobtitle" value="" disabled="disabled"/></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_('JS_FRIEND_EMAIL');?><font color="red">*</font></td>
					<td colspan="3"><input class="inputbox required validate-email" type="text" name="email1" id="email1" value=""/></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_('JS_FRIEND_EMAIL');?></td>
					<td colspan="3"><input class="inputbox validate-email" type="text" name="email2" id="email2" value="" /></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_('JS_FRIEND_EMAIL');?></td>
					<td colspan="3"><input class="inputbox validate-email" type="text" name="email3" id="email3" value="" /></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_('JS_FRIEND_EMAIL');?></td>
					<td colspan="3"><input class="inputbox validate-email" type="text" name="email4" id="email4" value=""/></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_('JS_FRIEND_EMAIL');?></td>
					<td colspan="3"><input class="inputbox validate-email" type="text" name="email5" id="email5" value=""/></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_('JS_MESSAGE');?><font color="red">*</font></td>
					<td colspan="3">
						<textarea class="inputbox required" name="message" id="message" cols="45" rows="3" maxlength="250" style="resize:none;"></textarea>
						<br/><?php echo JText::_('JS_MAX_LENGTH_IS_250_CHAR');?>
					</td>
				</tr>
				<tr><td colspan="4" height="5"></td></tr>
				<tr>
					<td align="right" colspan="4">
						<input id="button" class="button" type="button" onclick="Javascript: friendValidate();" value="<?php echo JText::_('JS_SEND_TO_FRIENDS');?>"/>&nbsp;&nbsp;
						<input id="button" class="close_button" type="button" onclick="Javascript: closetellafriend();" value="<?php echo JText::_('JS_CLOSE');?>" />
					</td>
				</tr>
			</table>
		<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
		<input type="hidden" name="task" value="savecompany" />
		<input type="hidden" name="jobid" id="jobid" value="" />
		</form>
</div>
<script type="text/javascript" src="<?php echo JURI::root();?>components/com_jsjobs/js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="<?php echo JURI::root();?>components/com_jsjobs/js/tinybox.js"></script>
<link media="screen" rel="stylesheet" href="<?php echo JURI::root();?>components/com_jsjobs/js/style.css" />
<script language="javascript">
	function closetellafriend(){
		(function($){
			$('#tellafriend').slideUp("slow");
		})(jQuery);
	}
	function showtellafriend(jobid,jobtitle){
		(function($){
			$('#tellafriend').slideDown("slow");
			document.getElementById('jobid').value = jobid;
			document.getElementById('jobtitle').value = jobtitle;
		})(jQuery);
	}
	
	function friendValidate(){
		var arr = new Array();
		if(document.getElementById('sendername').value == '' ){
			alert('<?php echo JText::_('JS_YOUR_NAME_IS_REQUIRED');?>');
			document.getElementById('sendername').focus();
			return false;
		}
		arr[0] = document.getElementById('sendername').value;
		if(document.getElementById('senderemail').value == '' ){
			alert('<?php echo JText::_('JS_YOUR_EMAIL_IS_REQUIRED');?>');
			document.getElementById('senderemail').focus();
			return false;
		}else{
			var result = echeck(document.getElementById('senderemail').value);
			if(result == false){
				alert('<?php echo JText::_('JS_INVALID_EMAIL');?>');
				document.getElementById('senderemail').focus();
				return false;
			}
		}
		arr[1] = document.getElementById('senderemail').value;
		if(document.getElementById('email1').value == '' ){
			alert('<?php echo JText::_('JS_ENTER_ATLEAST_ONE_FRIEND_EMAIL');?>');
			document.getElementById('email1').focus();
			return false;
		}else{
			var result = echeck(document.getElementById('email1').value);
			if(result == false){
				alert('<?php echo JText::_('JS_INVALID_EMAIL');?>');
				document.getElementById('email1').focus();
				return false;
			}
		}
		arr[2] = document.getElementById('email1').value;
		if(document.getElementById('email2').value != ''){
			var result = echeck(document.getElementById('email2').value);
			if(result == false){
				alert('<?php echo JText::_('JS_INVALID_EMAIL');?>');
				document.getElementById('email2').focus();
				return false;
			}
		}
		arr[3] = document.getElementById('email2').value;
		if(document.getElementById('email3').value != ''){
			var result = echeck(document.getElementById('email3').value);
			if(result == false){
				alert('<?php echo JText::_('JS_INVALID_EMAIL');?>');
				document.getElementById('email3').focus();
				return false;
			}
		}
		arr[4] = document.getElementById('email3').value;
		if(document.getElementById('email4').value != ''){
			var result = echeck(document.getElementById('email4').value);
			if(result == false){
				alert('<?php echo JText::_('JS_INVALID_EMAIL');?>');
				document.getElementById('email4').focus();
				return false;
			}
		}
		arr[5] = document.getElementById('email4').value;
		if(document.getElementById('email5').value != ''){
			var result = echeck(document.getElementById('email5').value);
			if(result == false){
				alert('<?php echo JText::_('JS_INVALID_EMAIL');?>');
				document.getElementById('email5').focus();
				return false;
			}
		}
		arr[6] = document.getElementById('email5').value;
		if(document.getElementById('message').value == '' ){
			alert('<?php echo JText::_('JS_INVALID_MESSAGE');?>');
			document.getElementById('message').focus();
			return false;
		}
		arr[7] = document.getElementById('message').value;
		arr[8] = document.getElementById('jobid').value;
		sendtofriend(arr);
	}
	function sendtofriend(vars){

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
					TINY.box.show({html:"<?php echo JText::_('JS_JOB_HAS_BEEN_SEND_TO_FRIENDS');?>",animate:true,boxid:'frameless',close:true});
					setTimeout(function(){window.location.reload();},3000);
			  }
		  }
		}
		xhr.open("GET","index.php?option=com_jsjobs&task=sendtofriend&val="+JSON.stringify(vars),true);
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
