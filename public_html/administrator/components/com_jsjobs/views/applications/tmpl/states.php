<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/states.php
 ^ 
 * Description: Default template for states
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'states');
$_SESSION['cur_layout']='states';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}

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
			<form action="index.php?option=com_jsjobs" method="post" name="adminForm" id="adminForm">
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
					<tr ><td colspan="3">
						<?php 
							$session = JFactory::getSession();
							$countryid=$session->get('countryid');	
							$statecode=$session->get('statecode');	
						?>
						<a href="index.php?option=com_jsjobs&task=view&layout=countries"><?php echo JText::_('JS_COUNTRIES'); ?></a> > <?php echo JText::_('JS_STATES'); ?> 
					</td></tr>
					
					<tr>
						<th width="20">
							<?php if(substr($joomla,0,3) < '3'){ ?>
								<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
							<?php }else{ ?>
								<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
							<?php } ?>
						</th>
						<th  width="60%" class="title"><?php echo JText::_('NAME'); ?></th>
						<th><?php echo JText::_('JS_PUBLISHED'); ?></th>
						<th><?php echo JText::_('JS_CITIES'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
				$row =& $this->items[$i];
				$checked = JHTML::_('grid.id', $i, $row->id);
				$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=editjobstate&cid[]='.$row->id);
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>">
						<?php echo $row->name; ?></a>
					</td>
					<td align="center">
						<?php
							if ($row->enabled == '1')$img 	= 'tick.png'; else $img 	= 'publish_x.png';
						?>
						<img src="../components/com_jsjobs/images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
					</td>
					<td><a href="index.php?option=com_jsjobs&task=view&layout=cities&sd=<?php echo $row->id; ?>&ct=<?php echo $this->ct;?>"><?php echo JText::_('JS_CITIES'); ?></a></td>
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
			<input type="hidden" name="layout" value="states" />
			<input type="hidden" name="task" value="view" />
			<input type="hidden" name="boxchecked" value="0" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>							
