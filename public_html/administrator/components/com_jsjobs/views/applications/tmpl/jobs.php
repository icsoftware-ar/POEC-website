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
JRequest :: setVar('layout', 'jobs');
$_SESSION['cur_layout']='jobs';
$version = new JVersion;
$joomla = $version->getShortVersion();
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

if(substr($joomla,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}

$status = array(
	'1' => JText::_('JOB_APPROVED'),
	'-1' => JText::_('JOB_REJECTED'));

?>
<script language=Javascript>
    function confirmdeletejob(id,task){
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
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_JOBS'); ?></div>

			<form action="index.php" method="post" name="adminForm" id="adminForm">
			<table>
				<tr>
					<td width="100%">
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
					<td nowrap>
						<?php echo JText::_( 'JS_TITLE' ); ?>:
						<input type="text" name="searchtitle" id="searchtitle" size="15" value="<?php if(isset($this->lists['searchtitle'])) echo $this->lists['searchtitle'];?>" class="text_area" onchange="document.adminForm.submit();" />
					&nbsp;</td>
					<td nowrap >
						<?php echo JText::_( 'JS_COMPANY' ); ?>:
						<input type="text" name="searchcompany" id="searchcompany" size="15" value="<?php if(isset($this->lists['searchcompany'])) echo $this->lists['searchcompany'];?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					&nbsp;</td>
					<td >
						<?php echo $this->lists['jobcategory'];?>
					</td>
					<td>
						<?php echo $this->lists['jobtype'];?>
					</td>
					
					<td>
						<button onclick="document.getElementById('searchtitle').value='';document.getElementById('searchcompany').value='';this.form.getElementById('searchjobcategory').value='';this.form.getElementById('searchjobtype').value='';this.form.getElementById('searchjobstatus').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
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
						<th class="title"><?php echo JText::_('JS_TITLE'); ?></th>
						<th><?php echo JText::_('JS_COMPANYNAME'); ?></th>
						<th><?php echo JText::_('JOB_CATEGORY'); ?></th>
						<th><?php echo JText::_('JS_JOBTYPE'); ?></th>
						<th><?php echo JText::_('CREATED'); ?></th>
						<th><?php echo JText::_('JS_STATUS'); ?></th>
						<th><?php echo JText::_('SHORT_LIST'); ?></th>
						<th><?php echo JText::_('JS_ACTION'); ?></th>
						<th><?php echo JText::_('JS_COPY_JOB'); ?></th>
						<th width="17"><?php echo JText::_('JS_ENFORCE_DELETE'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
			
				$jobdeletetask 	= 'jobenforcedelete';
				$deleteimg 	= 'publish_x.png';
				$deletealt 	= JText::_( 'Delete' );
			
			
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
				$row =& $this->items[$i];
				$checked = JHTML::_('grid.id', $i, $row->id);
				$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=edit&cid[]='.$row->id);
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>">
						<?php echo $row->title; ?></a>
					</td>
					<td>
						<?php 
						echo $row->companyname;
						?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->cat_title; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->jobtypetitle; ?>
					</td>
					
					<td style="text-align: center;">
						<?php echo  date( $this->config['date_format'],strtotime($row->created)); ?>
					</td>
					<td style="text-align: center;">
						<?php 
							if($row->status == 1) echo "<font color='green'>".$status[$row->status]."</font>";
							else echo "<font color='red'>".$status[$row->status]."</font>";
						?>
					</td>
					<td style="text-align: center;">
						<a href="index.php?option=com_jsjobs&c=jsjobs&view=applications&layout=jobappliedresume&oi=<?php echo $row->id; ?>&ta=5"><?php echo JText::_('JS_CANDIDATES'); ?></a>
					</td>
					<td style="text-align: center;">
						<?php if($row->isgoldjob==1) { ?>
							<img src="../components/com_jsjobs/images/gold.png" width="12" height="12" border="0" title="<?php echo JText::_('JS_GOLD_JOB'); ?>" />
						<?php }else{?>	
							<a href="index.php?option=com_jsjobs&task=savegoldjob&id=<?php echo $row->id; ?>">
								<img src="../components/com_jsjobs/images/addgold.png" width="12" height="12" border="0" title="<?php echo JText::_('JS_ADD_TO_GOLD_JOBS'); ?>" /></a>
							</a>
						<?php }?>	

						<?php if($row->isfeaturedjob==1) { ?>
							<img src="../components/com_jsjobs/images/featured.png" width="12" height="12" border="0" title="<?php echo JText::_('JS_FEATURED_JOB'); ?>" />
						<?php }else{?>	
							<a href="index.php?option=com_jsjobs&task=savefeaturedjob&id=<?php echo $row->id; ?>">
								<img src="../components/com_jsjobs/images/addfeatured.png" width="12" height="12" border="0" title="<?php echo JText::_('JS_ADD_TO_FEATURED_JOBS'); ?>" /></a>
							</a>
						<?php }?>	



					</td>
					<td style="text-align: center;">
						<a href="javascript:void(0);" onclick=" return copyjob('<?php echo $row->id;?>');">
						<img src="../components/com_jsjobs/images/copy.png" width="16" height="16" border="0" alt="<?php echo JText::_('JS_COPY_JOB'); ?>" /></a>
					</td>
					<td style="text-align: center;">
							<a href="javascript:void(0);" onclick=" return confirmdeletejob('cb<?php echo $i;?>','<?php echo $jobdeletetask;?>');">
							<img src="../components/com_jsjobs/images/<?php echo $deleteimg;?>" width="16" height="16" border="0" alt="<?php echo $deletealt; ?>" /></a>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tr>
				<td colspan="10">
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
<script type="text/javascript" src="<?php echo JURI::root();?>components/com_jsjobs/js/tinybox.js"></script>
<link media="screen" rel="stylesheet" href="<?php echo JURI::root();?>components/com_jsjobs/js/style.css" />
<script type="text/javascript" language="Javascript">
	function copyjob(val){
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
					TINY.box.show({html:"<?php echo JText::_('JS_JOB_HAS_BEEN_COPIED');?>",animate:true,boxid:'frameless',close:true});
					setTimeout(function(){window.location.reload();},3000);
				}
			}

		xhr.open("GET","index.php?option=com_jsjobs&task=getcopyjob&val="+val,true);
		xhr.send(null);
	}
</script>
