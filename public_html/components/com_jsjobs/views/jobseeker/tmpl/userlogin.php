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

$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);

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
				<?php if ($this->config['cur_location'] == 1) {
					if($this->userrole == 2){
						echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_JOBSEEKER_LOGIN');
					}elseif($this->userrole == 3){
						echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_EMPLOYER_LOGIN');
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
				<span id="tp_headingtext_center"><?php if($this->userrole == 2){
													echo JText::_('JS_JOBSEEKER_LOGIN');
												}elseif($this->userrole == 3){
													echo JText::_('JS_EMPLOYER_LOGIN');
												} ?>
				</span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>

<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" id="loginform" name="loginform">

	<div id="userform" class="userform">
		<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
			<tr>
				<td align="right" nowrap>
					<label id="name-lbl" for="name"><?php echo JText::_('JS_USER_NAME'); ?>: </label>* 
				</td>
				<td>
					<input id="username" class="validate-username" type="text" size="25" value="" name="username" >
				</td>
			</tr>
			<tr>
				<td align="right" nowrap>
					<label id="password-lbl" for="password"><?php echo JText::_('JS_PASSWORD'); ?>: </label>* 
				</td>
				<td>
					<input id="password" class="validate-password" type="password" size="25" value="" name="password">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
						<input id="button" class="button validate" type="button" onclick="return checkformlogin();" value="<?php echo JText::_('JS_LOGIN'); ?>"/>
					
						<!--<button  type="submit" class="button validate" onclick="return myValidate(this.loginform);"><?php echo JText::_('JLOGIN'); ?></button>-->
				</td>
			</tr>

		</table>
			<input type="hidden" name="return" value="<?php echo $this->loginreturn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>	
	</form>
<div>
	<ul>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('JS_COM_USERS_LOGIN_RESET'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('JS_COM_USERS_LOGIN_REMIND'); ?></a>
		</li>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_jsjobs&view=jobseeker&layout=userregister&userrole='.$this->userrole.'&Itemid=0'); ?>">
				<?php echo JText::_('JS_COM_USERS_LOGIN_REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul>
</div>
<script type="text/javascript" language="javascript">
	function checkformlogin(){
		var username = document.getElementById('username').value;
		var password = document.getElementById('password').value;
		if(username!="" && password!=""){
				document.loginform.submit();
		}else{
                alert('<?php echo JText::_( 'JS_FILL_REQ_FIELDS');?>');
		}
	}
</script>	


<?php } //ol ?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>

