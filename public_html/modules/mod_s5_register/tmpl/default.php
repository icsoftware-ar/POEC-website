<?php
/**
* @title		Shape 5 Register Module
* @version		1.0
* @package		Joomla
* @website		http://www.shape5.com
* @copyright	Copyright (C) 2009 Shape 5 LLC. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

									
<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users'); ?>" method="post" class="form-validate">

<div style="width:108px;line-height:31px;float:left" class="s5_regfloatleft">
	<label for="jform_name" id="jform_name-lbl">
			Name:
	</label>
</div>		
<div class="s5_regfloatleft" style="float:left">
	<input type="text" maxlength="50" class="inputbox required" value="" size="40" id="jform_name" name="jform[name]"/> *
</div>
<div style="clear:both;"></div>

<div style="width:108px;line-height:31px;float:left" class="s5_regfloatleft">
		<label for="jform_username" id="jform_username-lbl">
			Username:
		</label>
</div>
<div class="s5_regfloatleft" style="float:left">
		<input type="text" maxlength="25" class="inputbox required validate-username" value="" size="40" name="jform[username]" id="jform_username"/> *
</div>
<div style="clear:both;"></div>


<div style="width:108px;line-height:31px;float:left" class="s5_regfloatleft"> 
		<label for="jform_email1" id="jform_email1-lbl">
			E-mail:
		</label>
</div>
<div class="s5_regfloatleft" style="float:left">
		<input type="text" maxlength="100" class="inputbox required validate-email" value="" size="40" name="jform[email1]" id="jform_email1"/> *
</div>
<div style="clear:both;"></div>

<div style="width:108px;line-height:31px;float:left" class="s5_regfloatleft">
		<label for="jform_email2" id="jform_email2-lbl">
			Verify E-mail:
		</label>
</div>
<div class="s5_regfloatleft" style="float:left">
		<input type="text" maxlength="100" class="inputbox required validate-email" value="" size="40" name="jform[email2]" id="jform_email2"/> *
</div>
<div style="clear:both;"></div>


<div style="width:108px;line-height:31px;float:left" class="s5_regfloatleft">
		<label for="jform_password1" id="jform_password1-lbl">
			Password:
		</label>
</div>
<div class="s5_regfloatleft" style="float:left">
  		<input type="password" value="" size="40" name="jform[password1]" id="jform_password1" class="inputbox required validate-password"/> *
</div>
<div style="clear:both;"></div>

<div style="width:108px;line-height:31px;float:left" class="s5_regfloatleft">
		<label for="jform_password2" id="jform_password2-lbl">
			Verify Password:
		</label>
</div>
<div class="s5_regfloatleft" style="float:left">
  		<input type="password" value="" size="40" name="jform[password2]" id="jform_password2" class="inputbox required validate-password"/> *
</div>
<div style="clear:both;"></div>

<br/>
	Fields marked with an asterisk (*) are required.	
<br/><br/>
<?php 
$version = new JVersion();
?>
	<button type="submit" class="<?php if($version->RELEASE >= '3.0') { ?>btn btn-primary <?php } else { ?>button <?php } ?>validate">Register</button>
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="registration.register" />
	<?php echo JHtml::_('form.token');?>
</form>
					
