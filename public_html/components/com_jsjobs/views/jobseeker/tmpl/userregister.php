<?php
/**
 * @version		$Id: register.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.parameter' );

JHTML::_('behavior.formvalidation'); 

global $mainframe;

		$regplugin = &JPluginHelper::getPlugin('system','jsjobsregister');
		if(!empty($regplugin)) {
			$regpluginParams= new JRegistry();
			$regpluginParams->loadString($regplugin->params);
			 $rolevalue = $regpluginParams->get('userregisterinrole'); 
			if(!empty($rolevalue)) $isplugininstalled=1;
			else $isplugininstalled=0;
		}else $isplugininstalled=0;


$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);

?>
<script type="text/javascript" language="javascript">
function myValidate(f) {
    if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
        }else {
                alert('<?php echo JText::_( 'JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_RETRY');?>');
				return false;
        }
		return true;
}
</script>
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
				<?php if ($this->config['cur_location'] == 1) {
					if($this->userrole == 1){
						echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_USER_REGISTARATION');
					}elseif($this->userrole == 2){
						echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_JOBSEEKER_REGISTRATION');
					}elseif($this->userrole == 3){
						echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_EMPLOYER_REGISTRATION');
					}
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			if($this->userrole==2):
				if (sizeof($this->jobseekerlinks) != 0){
					foreach($this->jobseekerlinks as $lnk)	{ ?>
						<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
					<?php }
				}
			endif; 
			if($this->userrole==3):
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid='.$this->Itemid;
				$employerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);
                
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$this->Itemid;
				$employerlinks [] = array($link, JText::_('JS_NEW_JOB'),0);
				
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$this->Itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_JOBS'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$this->Itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_COMPANIES'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid='.$this->Itemid;
				$employerlinks [] = array($link, JText::_('JS_APPLIED_RESUME'),-1);
				foreach($employerlinks as $lnk)	{ ?>
						<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			endif; 
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php if($this->userrole == 1){
													echo JText::_('JS_USER_REGISTARATION');
												}elseif($this->userrole == 2){
													echo JText::_('JS_JOBSEEKER_REGISTRATION');
												}elseif($this->userrole == 3){
													echo JText::_('JS_EMPLOYER_REGISTRATION');
												} ?>
				</span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>

<form action="<?php echo JRoute::_('index.php'); ?>" enctype="multipart/form-data" method="post" id="josForm" name="josForm" class="form-validate">
	<div id="userform" class="userform">
		<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
			<tr>
				<td align="right" nowrap>
					<label id="namemsg" for="name"><?php echo JText::_('JS_NAME'); ?>: </label>* 
				</td>
				<td>
					<input type="text" name="<?php echo ($jversion <>'15')?'jform[name]':'name'?>" id="name" size="30" value="" class="inputbox required" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td align="right" nowrap>
					<label id="usernamemsg" for="username"><?php echo JText::_('JS_USER_NAME'); ?>: </label>* 
				</td>
				<td>
					<input type="text" id="username" name="<?php echo ($jversion <>'15')?'jform[username]':'username'?>" size="30" value="" class="inputbox required validate-username" maxlength="25" onBlur="checkjsusername();"/>
					<div id="usernameerror"></div>
				</td>
			</tr>
			<tr>
				<td align="right" nowrap>
					<label id="emailmsg" for="email"><?php echo JText::_('JS_USER_EMAIL'); ?>: </label>
				</td>
				<td>
					<input type="text" id="email" name="<?php echo ($jversion <> '15')?'jform[email1]':'email'?>" size="30" value="" class="inputbox required validate-email" maxlength="100" onBlur="checkjsemail();"/>
					<div id="emailerror"></div>
				</td>
			</tr>
			<?php if($jversion <> '15'): ?>
			<tr>
				<td align="right" nowrap>
					<label id="email2msg" for="email2"><?php echo JText::_('JS_CONFIRM_EMAIL'); ?>: </label>
				</td>
				<td>
					<input type="text" id="email2" name="jform[email2]" size="30" value="" class="inputbox required validate-email" maxlength="100" onBlur="checkjsemailmatch();"/>
					<div id="emailmatcherror"></div>
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<td align="right" nowrap>
					<label id="pwmsg" for="password"><?php echo JText::_('JS_PASSWORD'); ?>: </label>
				</td>
				<td>
					<input class="inputbox required validate-password" type="password" id="password" name="<?php echo ($jversion <> '15')?'jform[password1]':'password'?>" size="30" value="" />
				</td>
			</tr>
			<tr>
				<td align="right" nowrap>
					<label id="pw2msg" for="password2"><?php echo JText::_('JS_VERIFY_PASSWORD'); ?>: </label>
				</td>
				<td>
					<input class="inputbox required validate-passverify" type="password" id="password2" name="<?php echo ($jversion <> '15')?'jform[password2]':'password2'?>" size="30" value="" onBlur="checkjspasswordmatch();"/>
					<div id="passwordmatcherror"></div>
				</td>
			</tr>
			<tr>
				<td align="right" nowrap>
					<label id="userrole" for="userrole"><?php echo JText::_('JS_USER_ROLE'); ?>: </label>
				</td>
				<td>
					<?php if($isplugininstalled==1) { ?>	
			
						<?php if($this->userrole == 1){//Both ?>
								<select name='userrole'>
									<option value='1'><?php echo JText::_('JS_EMPLOYER');?></option>
									<option value='2'><?php echo JText::_('JS_JOBSEEKER');?></option>
								</select>
						<?php }elseif($this->userrole == 2){ //jobseeker
								echo "<input type='hidden' name='userrole' value='2'>".JText::_('JS_JOBSEEKER');
							}elseif($this->userrole == 3){ // employer
								echo "<input type='hidden' name='userrole' value='1'>".JText::_('JS_EMPLOYER');
							} ?>
					<?php }else{ echo JText::_('JS_PLEASE_INSTALL_OR_ENABLE_JSJOBS_REGISTER_PLUGIN'); ?>
					<?php } ?>
				</td>
			</tr>
			<?php if ( $this->config['user_registration_captcha'] == 1 ){ ?>
				<tr>
					<td valign="top" align="right"><label id="captchamsg" for="captcha"><?php echo JText::_('JS_CAPTCHA'); ?></label><?php  echo '&nbsp;<font color="red">*</font>'; ?></td>
					<td colspan="3"><?php echo $this->captcha;  ?> </td>
				</tr>
			<?php } ?>


			<tr>
				<td colspan="2" align="center">
					<input id="button" class="button validate" type="button" onclick="return checkformagain();" value="<?php echo JText::_('JS_REGISTER'); ?>"/>
				</td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="option" value="<?php echo ($jversion <> '15')?'com_users':'com_user'?>" />
	<input type="hidden" name="task" value="<?php echo ($jversion <> '15')?'registration.register':'register'?>" />
	<input type="hidden" name="check" value="" />
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="gid" value="0" />
	<input type="hidden" name="jversion" id="jversion" value="<?php echo $jversion;?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<script type="text/javascript" language="javascript">
	function checkformagain(){
		var cansend = false;
		var username = document.getElementById('username').value;
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
					if(xhr.responseText != '0'){
						document.getElementById('username').className += " invalid";
						//document.getElementById('username').focus();
						document.getElementById('usernameerror').innerHTML = "<?php echo JText::_('JS_USERNAME_EXIST');?>"
						cansend = false;
					}else{
						document.getElementById('usernameerror').innerHTML = ""
						document.getElementById('username').className = "inputbox required";
						cansend = true;
					}
				}
			}

		xhr.open("GET","index.php?option=com_jsjobs&task=checkuserdetail&fr=username&val="+username,true);
		xhr.send(null);
		//for email validation
		var email = document.getElementById('email').value;
		var exhr;
		try {  exhr = new ActiveXObject('Msxml2.XMLHTTP');   }
		catch (e){
			try {   exhr = new ActiveXObject('Microsoft.XMLHTTP');    }
			catch (e2) {
			  try {  exhr = new XMLHttpRequest();     }
			  catch (e3) {  xhr = false;   }
			}
		 }
		exhr.onreadystatechange = function(){
				if(exhr.readyState == 4 && exhr.status == 200){
					if(exhr.responseText != '0'){
						document.getElementById('email').className += " invalid";
						//document.getElementById('email').focus();
						document.getElementById('emailerror').innerHTML = "<?php echo JText::_('JS_EMAIL_EXIST');?>"
					}else{
						document.getElementById('emailerror').innerHTML = ""
						document.getElementById('email').className = "inputbox required validate-email";
						if(cansend == true){
							var issend = myValidate(document.josForm);
							if(issend == true)
								document.josForm.submit();
						}
					}
				}
			}

		exhr.open("GET","index.php?option=com_jsjobs&task=checkuserdetail&fr=email&val="+email,true);
		exhr.send(null);
		
	}
	function checkjsusername(){
		var username = document.getElementById('username').value;
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
					if(xhr.responseText != '0'){
						document.getElementById('username').className += " invalid";
						//document.getElementById('username').focus();
						document.getElementById('usernameerror').innerHTML = "<?php echo JText::_('JS_USERNAME_EXIST');?>"
					}else{
						document.getElementById('usernameerror').innerHTML = ""
						document.getElementById('username').className = "inputbox required";
					}
				}
			}

		xhr.open("GET","index.php?option=com_jsjobs&task=checkuserdetail&fr=username&val="+username,true);
		xhr.send(null);
	}
	function checkjsemail(){
		var email = document.getElementById('email').value;
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
					if(xhr.responseText != '0'){
						document.getElementById('email').className += " invalid";
						//document.getElementById('email').focus();
						document.getElementById('emailerror').innerHTML = "<?php echo JText::_('JS_EMAIL_EXIST');?>"
					}else{
						document.getElementById('emailerror').innerHTML = ""
						document.getElementById('email').className = "inputbox required validate-email";
					}
				}
			}

		xhr.open("GET","index.php?option=com_jsjobs&task=checkuserdetail&fr=email&val="+email,true);
		xhr.send(null);
	}
	function checkjsemailmatch(){
		var email = document.getElementById('email').value;
		var email2 = document.getElementById('email2').value;
		if(email2 != email){
			document.getElementById('email2').className += " invalid";
			//document.getElementById('email2').focus();
			document.getElementById('emailmatcherror').innerHTML = "<?php echo JText::_('JS_EMAIL_DOES_NOT_MATCH');?>"
		}else{
			document.getElementById('emailmatcherror').innerHTML = ""
			document.getElementById('email2').className = "inputbox required validate-email";
		}
	}
	function checkjspasswordmatch(){
		var password = document.getElementById('password').value;
		var password2 = document.getElementById('password2').value;
		if(password2 != password){
			document.getElementById('password2').className += " invalid";
			//document.getElementById('password2').focus();
			document.getElementById('passwordmatcherror').innerHTML = "<?php echo JText::_('JS_PASSWORD_DOES_NOT_MATCH');?>"
		}else{
			document.getElementById('passwordmatcherror').innerHTML = ""
			document.getElementById('password2').className = "inputbox required";
		}
	}
	
</script>
<?php } //ol ?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>

