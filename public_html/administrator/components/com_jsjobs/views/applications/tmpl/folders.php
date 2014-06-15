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
 * File Name:	admin-----/views/applications/tmpl/jobs.php
 ^ 
 * Description: Default template for jobs view
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

JRequest :: setVar('layout', 'folders');
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

$_SESSION['cur_layout']='folders';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}

$status = array(
	'1' => JText::_('JS_APPROVED'),
	'-1' => JText::_('JS_REJECTED'));

?>
<script language=Javascript>
    function confirmdeletecompany(id,task){
        if(confirm("<?php echo JText::_('JS_ARE_YOU_SURE'); ?>") == true){
            return listItemTask(id,task);
        }else return false;
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
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_FOLDERS'); ?></div>

			<form action="index.php" method="post" name="adminForm" id="adminForm">
			<table class="adminlist">
				<thead>
					<tr>
						<th width="20">
							<?php if(substr($joomla,0,3) < '3'){ ?>
								<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
							<?php }else{ ?>
								<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
							<?php } ?>
						</th>
						<th><?php echo JText::_('JS_NAME'); ?></th>
						<th><?php echo JText::_('JS_DESCRIPTION'); ?></th>
						<th><?php echo JText::_('JS_COMPANY'); ?></th>
						<th><?php echo JText::_('JS_STATUS'); ?></th>
						<th><?php echo JText::_('JS_RESUME'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
			
			
				for ($i=0, $n=count( $this->items ); $i < $n; $i++){
                                    $row =& $this->items[$i];
                                    $checked = JHTML::_('grid.id', $i, $row->id);
                                    $link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=edit&cid[]='.$row->id);

                                    ?>
                                    <tr valign="top" class="<?php echo "row$k"; ?>">
                                        <td width="5%">
						<?php echo $checked; ?>
					</td>
                                        <td width="10%">
						<a href="<?php echo $link; ?>">
						<?php echo $row->name; ?></a>
					</td>
					<td style="text-align: center;">
                                            <?php if(strlen($row->decription) > 100) $dots = '...';
                                                echo substr($row->decription,0,50); ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->companyname; ?>
					</td>
					<td style="text-align: center;">
                                            <strong><?php if ($row->status == 1){ echo '<font color="green">'. JText::_('JS_APPROVED'). '</font>'; }
                                            else {echo '<font color="red">' . JText::_('JS_REJECTED'). '</font>';}
                                             ?></strong>
					</td>
                                        <td  style="text-align: center;" >
                                                <?php  $link = 'index.php?option=com_jsjobs&c=jsjobs&view=applications&layout=folder_resumes&fd='.$row->id; ?>
                                                <a class="pageLink" href="<?php echo $link ?>"><?php echo JText::_('JS_RESUME'); echo ' ('.$row->nor.')'; ?></a>
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
