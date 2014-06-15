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
 * File Name:	admin-----/views/applications/tmpl/category.php
 ^ 
 * Description: Default template for categories view
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'categories');
$_SESSION['cur_layout']='categories';
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) != '1.5') {
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}
if ($this->sort == 'asc') $img = "../components/com_jsjobs/images/sort1.png";
else $img = "../components/com_jsjobs/images/sort0.png";
$link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=categories';

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
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_CATEGORIES'); ?></div>
			
			<form action="index.php" method="post" name="adminForm" id="adminForm">
				<table>
				<tr>
					<td width="100%">
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
					<td nowrap>
						<?php echo JText::_( 'JS_NAME' ); ?>:
							<input type="text" name="searchname" id="searchname" value="<?php if(isset($this->lists['searchname'])) echo $this->lists['searchname'];?>" class="text_area" onchange="document.adminForm.submit();" />
					&nbsp;</td>
					<td nowrap >
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					&nbsp;</td>

					<td>
						<button onclick="document.getElementById('searchname').value='';document.getElementById('searchusername').value='';document.getElementById('searchcompany').value='';document.getElementById('searchresume').value='';document.getElementById('searchrole').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
			<table class="adminlist" border="0">
				<thead>
					<tr>
						<th width="20">
							<?php if(substr($joomla,0,3) < '3'){ ?>
								<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
							<?php }else{ ?>
								<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
							<?php } ?>
						</th>
						<th  width="60%" class="title">
							<a href="javascript: void();"onclick="document.getElementById('changesort').value = 1;document.adminForm.submit();"><?php echo JText::_('JS_CATEGORY_TITLE'); ?></a><img src="<?php echo $img ?>">
						</th>
						<th><?php echo JText::_('JS_PUBLISHED'); ?></th>
						<th><?php echo JText::_('SUB_CATEGORIES'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
				$row =& $this->items[$i];
				$checked = JHTML::_('grid.id', $i, $row->id);
				$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=edit&cid[]='.$row->id);
				$subcategories_link = 'index.php?option='.$this->option.'&view=applications&layout=subcategories&cd='.$row->id;
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>">
						<?php echo $row->cat_title; ?></a>
					</td>
					<td align="center">
                                            <?php if($row->isactive == 1){ // published ?>
                                                <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','unpublishcategories')">
                                                        <img src="../components/com_jsjobs/images/tick.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Published' ); ?>" /></a>
                                            <?php }else{ // published ?>
                                                <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','publishcategories')">
                                                        <img src="../components/com_jsjobs/images/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'Unpublishe' ); ?>" /></a>
                                            <?php } ?>
					</td>
					<td align="center">
						<a href="<?php echo $subcategories_link; ?>">	<?php echo JText::_('SUB_CATEGORIES'); ?></a>
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
			<input type="hidden" name="sortby" value="<?php echo $this->sort;?>" />
			<input type="hidden" name="changesort" id="changesort" value="0" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>							
