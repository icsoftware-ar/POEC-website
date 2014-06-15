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
 * File Name:	admin-----/views/applications/tmpl/jobstatus.php
 ^ 
 * Description: Default template for job status
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'goldresumes');
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

$_SESSION['cur_layout']='goldresumes';
$status = array(
	'1' => JText::_('JS_APPROVED'),
	'-1' => JText::_('JS_REJECTED'));
$version = new JVersion;
$jversion = $version->getShortVersion();
if(substr($jversion,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}
JHTML::_('behavior.calendar');
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
<table width="100%">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_JOB_SHARE_LOG'); ?></div>
			
			<form action="index.php" method="post" name="adminForm" id="adminForm">
			
			<table>
				<tr>
					<td width="10%">
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
					<td nowrap>
						<?php echo JText::_( 'JS_UID' ); ?>:
						<input type="text" name="searchuid" id="searchuid" size="15" value="<?php if(isset($this->lists['uid'])) echo $this->lists['uid'];?>" class="text_area" style="width:30px;" />
					&nbsp;</td>
					<td nowrap>
						<?php echo JText::_( 'JS_REF_NUMBER' ); ?>:
						<input type="text" name="searchrefnumber" id="searchrefnumber" size="15" value="<?php if(isset($this->lists['refnumber'])) echo $this->lists['refnumber'];?>" class="text_area"  style="width:30px;" />
					&nbsp;</td>
					<td nowrap >
						<?php echo JText::_( 'JS_USER_NAME' ); ?>:
						<input type="text" name="searchusername" id="searchusername" size="15" value="<?php if(isset($this->lists['username'])) echo $this->lists['username'];?>" class="text_area"  style="width:150px;" />						
					&nbsp;</td>
                                        <td>
					<?php echo JText::_( 'JS_START' ); ?>:
					<?php if($jversion == '1.5') { ?> 
						<input type="reset" class="button" value="..." onclick="return showCalendar('searchstartdate','<?php echo $js_dateformat; ?>');" style="width:80px;" />
					<?php }else{
                                                $startdate = !empty($this->lists['startdate']) ? date(str_replace('%', '', $js_dateformat),  strtotime($this->lists['startdate'])) : '';
                                                echo JHTML::_('calendar', $startdate,'searchstartdate', 'searchstartdate',$js_dateformat,array('class'=>'inputbox', 'style'=>'width:80px;',  'maxlength'=>'19'));
					 } ?>
					</td>&nbsp;
					<td nowrap>
					<?php echo JText::_( 'JS_END' ); ?>:
					<?php if($jversion == '1.5') { ?> 
						<input class="button" value="..." onclick="return showCalendar('searchenddate','<?php echo $js_dateformat; ?>');" type="reset" style="width:80px;" >					
					<?php }else{
                                                $enddate = !empty($this->lists['enddate']) ? date(str_replace('%', '', $js_dateformat),  strtotime($this->lists['enddate'])) : '';
                                                echo JHTML::_('calendar', $enddate,'searchenddate', 'searchenddate',$js_dateformat,array('class'=>'inputbox', 'style'=>'width:80px;',  'maxlength'=>'19'));
					 } ?>
                                        </td>
					<td>
                                                <button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
                                                <button onclick="document.getElementById('searchuid').value='';document.getElementById('searchusername').value='';document.getElementById('searchrefnumber').value='';this.form.getElementById('searchstartdate').value='';this.form.getElementById('searchenddate').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
			<table class="adminlist" border="0">
			<?php
			jimport('joomla.filter.output');
			$k = 0;
				for ($i=0, $n=count( $this->servicelog); $i < $n; $i++)
				{
				$row = $this->servicelog[$i];
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
                                            <div id="log_wrapper">
                                                <span id="log_three_col"><?php echo JText::_('JS_UID');?><span id="log_three_col_value"><?php echo $row->uid;?></span></span>
                                                <span id="log_three_col"><?php echo JText::_('JS_USER_NAME');?><span id="log_three_col_value"><?php echo $row->username;?></span></span>
                                                <span id="log_three_col"><?php echo JText::_('JS_REF_NUMBER');?><span id="log_three_col_value"><?php echo $row->referenceid;?></span></span>
                                                <span id="log_three_col"><?php echo JText::_('EVENT');?><span id="log_three_col_value"><?php echo $row->event;?></span></span>
                                                <span id="log_three_col"><?php echo JText::_('EVENT_TYPE');?><span id="log_three_col_value"><?php echo $row->eventtype;?></span></span>
                                                <span id="log_three_col"><?php echo JText::_('DATE');?><span id="log_three_col_value"><?php echo date(str_replace('%', '', $js_dateformat),strtotime($row->datetime));?></span></span>
                                                <span id="log_message"><?php echo JText::_('MESSAGE');?><span id="log_message_value"><?php echo $row->message;?></span></span>                                                
                                            </div>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</table>
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                        <input type="hidden" name="layout" value="jobsharelog" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>							
