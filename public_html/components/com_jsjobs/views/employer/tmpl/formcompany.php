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
JHTML::_('behavior.formvalidation');  

 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
	$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
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
	$js_scriptdateformat = $firstvalue.$dash.$secondvalue.$dash.$thirdvalue;

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
function checkUrl(obj) {
		if(!obj.value.match(/^http[s]?\:\/\//))
			obj.value='http://'+obj.value;
}
window.addEvent('domready', function(){
   document.formvalidator.setHandler('url', function(value) {
		if(value.match(/^(http|https|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i) ||
		value.match(/^mailto\:\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w{2,4}$/i))
		{
			return true;
		}
		else
		{
		return false;
		}	   
	   
   });
});	
window.addEvent('domready', function(){
   document.formvalidator.setHandler('since', function(value) {
		var date_since_make = new Array();
		var split_since_value=new Array();
	   
		f = document.adminForm;
		var returnvalue = true;
		var today=new Date();
			today.setHours(0,0,0,0);				
		
		/*if ((today.getMonth()+1) < 10)
			var tomonth = "0"+(today.getMonth()+1);
		else
			var tomonth = (today.getMonth()+1);
		
		if ((today.getDate()) < 10)
			var day = "0"+(today.getDate());
		else
			var day = (today.getDate());

			var todate = (today.getYear()+1900)+"-"+tomonth+"-"+day;*/
			
					var since_string = document.getElementById("since").value;
					var format_type = document.getElementById("j_dateformat").value;
					if(format_type=='d-m-Y'){
						split_since_value=since_string.split('-');

						date_since_make['year']=split_since_value[2];
						date_since_make['month']=split_since_value[1];
						date_since_make['day']=split_since_value[0];


					}else if(format_type=='m/d/Y'){
						split_since_value=since_string.split('/');
						date_since_make['year']=split_since_value[2];
						date_since_make['month']=split_since_value[0];
						date_since_make['day']=split_since_value[1];


					}else if(format_type=='Y-m-d'){

						split_since_value=since_string.split('-');

						date_since_make['year']=split_since_value[0];
						date_since_make['month']=split_since_value[1];
						date_since_make['day']=split_since_value[2];
					}
					var sincedate = new Date(date_since_make['year'],date_since_make['month']-1,date_since_make['day']);		
						
					if (sincedate > today ){
						returnvalue = false;
					}
					return returnvalue;
			
		
   });
});	

	
	
	
function myValidate(f) {
		var msg = new Array();
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
        }
        else {
			msg.push('<?php echo JText::_( 'JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_RETRY');?>');
			var element_since = document.getElementById('since');                
			if(hasClass(element_since,'invalid')){
					msg.push('<?php echo JText::_('JS_COMPANY_START_DATE_MUST_BE_LESS_THEN_TODAY'); ?>');
            }
            alert (msg.join('\n'));			
			return false;
        }
		var comdescription = tinyMCE.get('description').getContent();
		if(comdescription == ''){
				msg.push('<?php echo JText::_('JS_PLEASE_ENTER_COMPANY_DESCRIPTION'); ?>');
				alert (msg.join('\n'));			
				return false;
		}
		return true;
}
function hasClass(el, selector) {
   var className = " " + selector + " ";
  
   if ((" " + el.className + " ").replace(/[\n\t]/g, " ").indexOf(className) > -1) {
    return true;
   }
   return false;
  }
  


</script>

	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
			<span id="tp_curloc">
				<?php if ($this->config['cur_location'] == 1) {
					if (isset($this->company)){
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_COMPANIES'); ?></a> > <?php echo JText::_('JS_COMPNAY_INFO');
					}else{
						echo JText::_('JS_CUR_LOC'); ?> :  <?php echo JText::_('JS_NEW_JOB_INFO');
					}
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
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_COMPNAY_INFO');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->userrole->rolefor == 1) { // employer
if ($this->canaddnewcompany == 1) { // add new company, in edit case always 1

?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"  onSubmit="return myValidate(this);">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php
		$i = 0;
		foreach($this->fieldsordering as $field){ 
			switch ($field->field) {
				case "jobcategory": ?>
				      <tr>
				        <td valign="top" align="right"><label  id="jobcategorymsg" for="jobcategory"><?php echo JText::_('JS_CATEGORIES'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><?php echo $this->lists['jobcategory']; ?></td>
				      </tr>
				<?php break;
				case "name": ?>
				      <tr>
				        <td width="20%" align="right"><label id="namemsg" for="name"><?php echo JText::_('JS_COMPANYNAME'); ?></label>&nbsp;<font color="red">*</font></td>
				          <td width="60%"><input class="inputbox required" type="text" name="name" id="name" size="40" maxlength="255" value="<?php if(isset($this->company)) echo $this->company->name; ?>" />
				        </td>
				      </tr>
				<?php break;
				case "url": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="urlmsg" for="url"><?php echo JText::_('JS_URL'); ?></label></td>
						<td><input class="inputbox validate-url" type="text" name="url" size="40" maxlength="100" onblur="checkUrl(this);" value="<?php if(isset($this->company)) echo trim ($this->company->url); ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "contactname": ?>
				      <tr>
				        <td align="right"><label id="contactnamemsg" for="contactname"><?php echo JText::_('JS_CONTACTNAME'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required" type="text" name="contactname" id="contactname" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactname; ?>" />
				        </td>
				      </tr>
				<?php break;
				case "contactphone": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="contactphonemsg" for="contactphone"><?php echo JText::_('JS_CONTACTPHONE'); ?></label></td>
				        <td><input class="inputbox" type="text" name="contactphone" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactphone; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "contactfax": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="companyfaxmsg" for="companyfax"><?php echo JText::_('JS_CONTACTFAX'); ?></label></td>
				        <td><input class="inputbox" type="text" name="companyfax" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->companyfax; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "contactemail": ?>
				      <tr>
				        <td align="right"><label id="contactemailmsg" for="contactemail"><?php echo JText::_('JS_CONTACTEMAIL');?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required validate-email" type="text" name="contactemail" id="contactemail" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactemail; ?>" />
				        </td>
				      </tr>
				<?php break;
				case "since": ?>
					<?php if ( $field->published == 1 ) { ?>
						<?php 
							$startdatevalue = '';
							if(isset($this->company)) $startdatevalue = date($this->config['date_format'],strtotime($this->company->since));
							?>
						<tr>
							<td  valign="top" align="right"><?php echo JText::_('JS_SINCE'); ?>:</td>
							<td>
								<?php if($jversion == '1.5'){ ?><input class="inputbox validate-since" type="text" name="since" id="since" readonly size="10" maxlength="10" value = "<?php if (isset($this->company)) echo date($this->config['date_format'],strtotime($this->company->since));?>" />
								<input type="reset" class="button" value="..." onclick="return showCalendar('since','<?php echo $js_dateformat; ?>');"  />
								<?php 
								}elseif(isset($this->company)){
									 	echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->company->since)),'since', 'since',$js_dateformat,array('class'=>'inputbox validate-since', 'size'=>'10',  'maxlength'=>'19')); }
								else 	echo JHTML::_('calendar','','since', 'since',$js_dateformat,array('class'=>'inputbox validate-since', 'size'=>'10',  'maxlength'=>'19'));
								
                                                                ?>
							</td>						
						</tr>
				  <?php } ?>
				<?php break;
				case "companysize": ?>
					  <?php if ( $field->published == 1 ) { ?>
			       <tr>
			        <td valign="top" align="right"><label id="companysize" for="companysize"><?php echo JText::_('JS_COMPANY_SIZE'); ?></label></td>
			        <td><input class="inputbox validate-numeric" maxlength="6" type="text" name="companysize" id="companysize" size="20" maxlength="20" value="<?php if(isset($this->company)) echo $this->company->companysize; ?>" />
			        </td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "income": ?>
					  <?php if ( $field->published == 1 ) { ?>
				       <tr>
				        <td valign="top" align="right"><label id="incomemsg" for="income"><?php echo JText::_('JS_INCOME'); ?></label></td>
				        <td><input class="inputbox validate-numeric" maxlength="6" type="text" name="income" id="income" size="20" maxlength="10" value="<?php if(isset($this->company)) echo $this->company->income; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "description": ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if ( $this->config['comp_editor'] == '1' ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->company))
										echo $editor->display('description', $this->company->description, '100%', '100%', '60', '20', false);
									else
										echo $editor->display('description', '', '100%', '100%', '60', '20', false);
								?>	
								</td>
							</tr>
						<?php } else {?>
				       <tr>
				        <td valign="top" align="right"><label id="descriptionmsg" for="description"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td>
							<textarea class="inputbox required" name="description" id="description" cols="60" rows="5"><?php if(isset($this->company)) echo $this->company->description; ?></textarea>
						</td>
				      </tr>
						<?php } ?>
					  <?php } ?>
				<?php break;
				case "city": ?>
					  <?php if ($this->config['comp_city'] == 1) { ?>
					  <?php if ( $field->published == 1 ) { ?>
						  <tr>
							<td align="right"><label id="citymsg" for="city"><?php echo JText::_('JS_CITY'); ?></label></td>
							<td id="company_city"> 
								<input class="inputbox" type="text" name="city" id="city" size="40" maxlength="100" value="" />
								<input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="<?php if(isset($this->multiselectedit)) echo $this->multiselectedit; ?>" />
							</td>
						  </tr>
					  <?php } ?>
					  <?php } ?>
				<?php break;
				case "zipcode": ?>
					  <?php if ($this->config['comp_zipcode'] == 1) { ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="zipcodemsg" for="zipcode"><?php echo JText::_('JS_ZIPCODE'); ?></label></td>
				        <td><input class="inputbox validate-numeric" maxlength="6" type="text" name="zipcode" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->zipcode; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
					  <?php } ?>
				<?php break;
				case "address1": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="address1msg" for="address1"><?php echo JText::_('JS_ADDRESS1'); ?></label></td>
				        <td><input class="inputbox" type="text" name="address1" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->address1; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "address2": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="address2msg" for="address2"><?php echo JText::_('JS_ADDRESS2'); ?></label></td>
				        <td><input class="inputbox" type="text" name="address2" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->address2; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "logo": ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if (isset($this->company)){ 
									if($this->company->logofilename != '') {?>
										<tr><td></td><td><input type='checkbox' name='deletelogo' value='1'><?php echo JText::_('JS_DELETE_LOGO_FILE') .'['.$this->company->logofilename.']'; ?></td></tr>
						<?php }
							}?>				
						<tr>
							<td align="right" ><label id="logomsg" for="logo">	<?php echo JText::_('JS_COMPANY_LOGO'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="logo" size="20" maxlenght='3'/>
							<br><small><?php echo JText::_('JS_MAXIMUM_WIDTH');?> : 200px)</small>
							<br><small><?php echo JText::_('JS_MAXIMUM_FILE_SIZE').' ('.$this->config['company_logofilezize']; ?>KB)</small></td>
						</tr>
					  <?php } ?>
				<?php break;
				case "smalllogo": ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if (isset($this->company)) 
									if($this->company->smalllogofilename != '') {?>
										<tr><td></td><td><input type='checkbox' name='deletesmalllogo' value='1'><?php echo JText::_('JS_DELETE_SMALL_LOGO_FILE') .'['.$this->company->smalllogofilename.']'; ?></td></tr>
						<?php } ?>				
						<tr>
							<td align="right" >	<label id="smalllogomsg" for="smalllogo"><?php echo JText::_('JS_COMPANY_SMALL_LOGO'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="smalllogo" size="20" maxlenght='30'/></td>
						</tr>
					  <?php } ?>
				<?php break;
				case "aboutcompany": ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if (isset($this->company)) 
									if($this->company->aboutcompanyfilename != '') {?>
										<tr><td></td><td><input type='checkbox' name='deleteaboutcompany' value='1'><?php echo JText::_('JS_DELETE_ABOUT_COMPANY_FILE') .'['.$this->company->aboutcompanyfilename.']'; ?></td></tr>
						<?php } ?>				
						<tr>
							<td align="right" >	<label id="aboutcompanymsg" for="aboutcompany"><?php echo JText::_('JS_ABOUT_COMPANY'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="aboutcompany" size="20" maxlenght='30'/></td>
						</tr>
					  <?php } ?>
				<?php break;
				  
				default:
					if ( $field->published == 1 ) {
						if (isset($this->userfields))
						foreach($this->userfields as $ufield){ 
							if($field->field == $ufield[0]->id) {
								$userfield = $ufield[0];
								$i++;
								echo "<tr><td valign='top' align='right'>";
								if($userfield->required == 1){
									echo "<label id=".$userfield->name."msg for='userfields_$i'>$userfield->title</label>&nbsp;<font color='red'>*</font>";
									if($userfield->type == 'emailaddress')
                                                                            $cssclass = "class ='inputbox required validate-email' ";
                                                                        else
                                                                            $cssclass = "class ='inputbox required' ";

								}else{
									echo $userfield->title; $cssclass = "class='inputbox' ";
								}
								echo "</td><td>"	;
									
								$readonly = $userfield->readonly ? ' readonly="readonly"' : '';
		   						$maxlength = $userfield->maxlength ? 'maxlength="'.$userfield->maxlength.'"' : '';
								if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								echo '<input type="hidden" id="userfields_'.$i.'_id" name="userfields_'.$i.'_id"  value="'.$userfield->id.'"  />';
								echo '<input type="hidden" id="userdata_'.$i.'_id" name="userdata_'.$i.'_id"  value="'.$userdataid.'"  />';
								switch( $userfield->type ) {
									case 'text':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'emailaddress':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'date':
                                                                                if($jversion == '1.5'){
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
									    ?><input type="reset" class="button" value="..." onclick="return showCalendar('userfields_<?php echo $i; ?>','%Y-%m-%d');" /><?php
                                                                                }else{
                                                                                    if($cssclass == "class ='inputbox required' ") $css = 'inputbox required';else $css = 'inputbox ';
                                                                                    echo JHTML::_('calendar', $fvalue,'userfields_'.$i, 'userfields_'.$i,'%Y-%m-%d',array('class'=>$css, 'size'=>'10',  'maxlength'=>$maxlength));
                                                                                }
										break;
									case 'textarea':
										echo '<textarea name="userfields_'.$i.'" id="userfields_'.$i.'_field" cols="'.$userfield->cols.'" rows="'.$userfield->rows.'" '.$readonly.$cssclass.'>'.$fvalue.'</textarea>';
										break;	
									case 'checkbox':
										echo '<input type="checkbox" name="userfields_'.$i.'" id="userfields_'.$i.'_field" value="1" '.  'checked="checked"' .'/>';
										break;	
									case 'select':
										$htm = '<select name="userfields_'.$i.'" id="userfields_'.$i.'" >';
										if (isset ($ufield[2])){
											foreach($ufield[2] as $opt){
												if ($opt->id == $fvalue)
													$htm .= '<option value="'.$opt->id.'" selected="yes">'. $opt->fieldtitle .' </option>';
												else
													$htm .= '<option value="'.$opt->id.'">'. $opt->fieldtitle .' </option>';
											}
										}
										$htm .= '</select>';	
										echo $htm;
										break;	
									case 'editortext':
										$editor =& JFactory::getEditor();
										if(isset($this->company))
											echo $editor->display("userfields_$i", $fvalue, '100%', '100%', '60', '20', false);
										else
											echo $editor->display("userfields_$i", '', '100%', '100%', '60', '20', false);
									
								}
								echo '</td></tr>';
							}
						}
						
					}
			}
			
		} 
		echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="'.$i.'"  />';
		?>

    <tr>
        <td colspan="2" height="10"></td>
      <tr>
	<tr>
		<td colspan="2" align="center">
			<input id="button" class="button" type="submit" name="submit_app" value="<?php echo JText::_('JS_SAVE_COMPANY'); ?>" />
		</td>
	</tr>
    </table>


	<?php 
				if(isset($this->company)) {
					if (($this->company->created=='0000-00-00 00:00:00') || ($this->company->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->company->created;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="savecompany" />
			<input type="hidden" name="check" value="" />
			<?php if(isset($this->packagedetail[0])) echo '<input type="hidden" name="packageid" value="'.$this->packagedetail[0].'" />';?>
			<?php if(isset($this->packagedetail[1])) echo '<input type="hidden" name="paymenthistoryid" value="'.$this->packagedetail[1].'" />'; ?>
			<input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php  echo $js_scriptdateformat; ?>" />
			
		  <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  <input type="hidden" id="id" name="id" value="<?php if(isset($this->company)) echo $this->company->id; ?>" />
		  
		  
<script language=Javascript>
	
        jQuery(document).ready(function() {
            var cityname = jQuery("#citynameforedit").val();
            if(cityname != ""){
                jQuery("#city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
                    noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
                    searchingText: "<?php echo JText::_('SEARCHING...');?>",
                    //tokenLimit: 1,
                    prePopulate: <?php if(isset($this->multiselectedit)) echo $this->multiselectedit;else echo "''"; ?>

                    
                });
            }else{
                jQuery("#city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
                    noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
                    searchingText: "<?php echo JText::_('SEARCHING...');?>",
                    //tokenLimit: 1

                });
            }
        });

	
function dochange(src, val){
	var pagesrc = 'company_'+src;
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
				//document.getElementById('company_county').innerHTML=countyhtml; //retuen value
				document.getElementById('company_city').innerHTML=cityhtml; //retuen value
			}
			/*else if(src=='county'){
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('company_city').innerHTML=cityhtml; //retuen value
			}*/
      }
    }
 
	xhr.open("GET","index.php?option=com_jsjobs&task=listaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
}

</script>
			  

		</form>
<?php 
} else{ // can not add new company ?>
<?php
	$message = '';
	$e_p_link=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=packages&Itemid='.$this->Itemid);
	
	if(empty($this->packagedetail[0]->packageexpiredays)){ //$this->packagecombo == 2 means user have no package
		$message = "<strong><font color='orangered'>".JText::_('JS_COMPANY_LIMIT_EXCEED')." <a href=".$e_p_link.">".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";
	}elseif(empty($this->packagedetail[0]->id) && $this->packagecombo == 2){
		$message = "<strong><font color='orangered'>".JText::_('JS_JOB_NO_PACKAGE')." <a href=".$e_p_link.">".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";
	}else{
		$days="";
		if((isset($this->packagedetail[0]->packageexpiredays)) AND (isset($this->packagedetail[0]->packageexpireindays)))
			$days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
		if($days == 1) $days = $days.' '.JText::_('JS_DAY'); else $days = $days.' '.JText::_('JS_DAYS');
		$package_title="";
		if(isset($this->packagedetail[0]->packagetitle)) $package_title=$this->packagedetail[0]->packagetitle;
		$message = "<strong><font color='red'>".JText::_('JS_YOUR_PACKAGE').' &quot;'.$package_title.'&quot; '.JText::_('JS_HAS_EXPIRED').' '.$days.' ' .JText::_('JS_AGO')." <a href=".$e_p_link.">".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";
	}
	if($message != ''){ ?>
	<div id="errormessage" class="errormessage">
		<div id="message"><?php echo $message;?></div>
	</div>
<?php }	
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
