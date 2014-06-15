<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Sep 21, 2010
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/paymentreport.php
 ^ 
 * Description: Default template for payment report
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'payment_report');
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

$_SESSION['cur_layout']='payment_report';
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
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_PAYMENT_REPORT'); ?></div>
			
			<form action="index.php" method="post" name="adminForm" id="adminForm">
			<table>
				<tr>
					<td width="100%">
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
                                        <?php /*
					<td nowrap>
					<?php echo JText::_( 'JS_NAME' ); ?>:
                                            <input type="text" name="buyername" id="buyername" value="<?php if(isset($this->lists['buyername'])) echo $this->lists['buyername'];?>" class="text_area" onchange="document.adminForm.submit();" />
					</td>&nbsp;
                                         */ ?>
					<td nowrap="nowrap">
						<?php echo $this->lists['paymentfor'];?>
					</td>&nbsp;
					<td>
						<?php echo $this->lists['paymentstatus'];?>
					</td>&nbsp;
					<td nowrap>
					<?php echo JText::_( 'JS_START' ); ?>:
					<!--<input type="text" name="prsearchstartdate" id="prsearchstartdate" value="<?php if(isset($this->lists['searchstartdate'])) echo $this->lists['searchstartdate'];?>" class="text_area" onchange="document.adminForm.submit();" />-->
					<?php if($jversion == '1.5') { ?> 
						<input type="reset" class="button" value="..." onclick="return showCalendar('prsearchstartdate','<?php echo $js_dateformat; ?>');"  />
					<?php }else{
								 echo JHTML::_('calendar', '','prsearchstartdate', 'prsearchstartdate',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19'));
					 } ?>
					</td>&nbsp;
					</td>
					<td nowrap>
					<?php echo JText::_( 'JS_END' ); ?>:
						<!--<input type="text" name="prsearchenddate" id="prsearchenddate" value="<?php if(isset($this->lists['searchenddate'])) echo $this->lists['searchenddate'];?>" class="text_area" onchange="document.adminForm.submit();" />-->
					<?php if($jversion == '1.5') { ?> 
						<input class="button" value="..." onclick="return showCalendar('prsearchenddate','<?php echo $js_dateformat; ?>');" type="reset">					
					<?php }else{
								 echo JHTML::_('calendar', '','prsearchenddate', 'prsearchenddate',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19'));
					 } ?>
					</td>&nbsp;
					<td>
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					</td>&nbsp;
					
					<td>
						<button onclick="this.form.getElementById('searchpaymentstatus').value='';document.getElementById('prsearchstartdate').value='';document.getElementById('prsearchenddate').value='';this.form.getElementById('paymentfor').value='both';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
			
			
			<table class="adminlist" border="0">
				<thead>
					<tr>
						<th><?php echo JText::_('JS_PACKAGE'); ?></th>
						<th width="10%"><?php echo JText::_('JS_PACKAGE_FOR'); ?></th>
						<th><?php echo JText::_('JS_NAME'); ?></th>
						<th><?php echo JText::_('JS_PAYER_NAME'); ?></th>
						<th><?php echo JText::_('JS_PAID_AMOUNT'); ?></th>
						<th><?php echo JText::_('JS_PAYMENT_STATUS'); ?></th>
						<th ><?php echo JText::_('JS_CREATED'); ?></th>
						
						
						
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
				
				
				//$alink = JFilterOutput::ampReplace('index.php?option='.$option.'&task=packageapprove&packageid='.$items->id);
				

				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
				$row =& $this->items[$i];
				//$checked = JHTML::_('grid.id', $i, $row->id);
				//$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=edit&cid[]='.$row->id);
				
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
						<!--<td><a href="index.php?option=com_jsjobs&task=view&layout=package_paymentreport&pk=<?php echo $row->packageid;?>&pf=<?php echo $row->packagefor;?>"><?php echo $row->packagetitle; ?></a></td>-->
					<td><?php echo $row->packagetitle; ?></td>
					<td align="center"><?php echo $row->packagefor; ?></td>
					<td align="center">
                                            <?php if($row->packagefor == 'Employer'){ ?>
                                                <a href="index.php?option=com_jsjobs&task=view&layout=userstate_companies&md=<?php echo $row->uid; ?>"><?php echo $row->buyername; ?></a>
                                            <?php }else if($row->packagefor == 'Job Seeker'){ ?>
                                                <a href="index.php?option=com_jsjobs&task=view&layout=userstate_resumes&ruid=<?php echo $row->uid; ?>"><?php echo $row->buyername; ?></a>
                                            <?php } ?>
                                        </td>
					<td align="center"><?php echo $row->payer_firstname; ?></td>
					<td align="center"><?php if($row->paidamount )echo $row->symbol .$row->paidamount; ?></td>
					<td align="center"><?php if($row->transactionverified == 1) echo JText::_('JS_VERIFIED'); else echo JText::_('JS_NOT_VERIFIED'); ?></td>
					<td align="center"><?php echo date($this->config['date_format'],strtotime($row->created)); ?></td>
					
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
