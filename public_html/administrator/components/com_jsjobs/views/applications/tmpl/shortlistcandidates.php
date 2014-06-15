<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/jobappliedresumes.php
 ^ 
 * Description: Default template for job applied resumes view
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

JRequest :: setVar('layout', 'shortlistcandidates');
$_SESSION['cur_layout']='shortlistcandidates';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}
$document =& JFactory::getDocument();
$document->addStyleSheet('../components/com_jsjobs/css/jsjobsresumerating.css');


$status = array(
	'1' => JText::_('JOB_APPROVED'),
	'-1' => JText::_('JOB_REJECTED'));

?>
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

			<form action="index.php" method="post" name="adminForm" id="adminForm">
			<table>
				<tr>
					<td width="100%">
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
					<td nowrap="nowrap">
						<?php echo JText::_( 'JS_NAME' ); ?> :
						<input type="text" name="searchname" id="searchname" value="<?php if(isset($this->lists['searchname'])) echo $this->lists['searchname'];?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					&nbsp;&nbsp;&nbsp;</td>
					<td nowrap="nowrap">
						<?php echo $this->lists['jobtype'];?>
					&nbsp;&nbsp;&nbsp;</td>
					<td>
						<button onclick="document.getElementById('searchname').value='';this.form.getElementById('searchjobtype').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
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
						<th class="title"><?php echo JText::_('JS_NAME'); ?></th>
						<th><?php echo JText::_('JS_CATEGORY'); ?></th>
						<th><?php echo JText::_('JS_PREFFERD'); ?></th>
						<th><?php echo JText::_('JS_SALARY_RANGE'); ?></th>
						<th><?php echo JText::_('JS_APPLIED_DATE'); ?></th>
						<th><?php echo JText::_('JS_CONTACT_EMAIL'); ?></th>
						<th></th>
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
				$resumelink = 'index.php?option=com_jsjobs&view=application&layout=view_resume&rd='.$row->appid.'&oi='.$this->oi;
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $resumelink; ?>">
						<?php echo $row->first_name.' '.$row->last_name; ?></a>
					</td>
					<td>
						<?php 
						echo $row->cat_title;
						?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->jobtypetitle; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $this->config['currency'].$row->rangestart.' - '.$this->config['currency'].$row->rangeend; ?>
					</td>
					<td style="text-align: center;">
						<?php echo date( $this->config['date_format'],strtotime($row->apply_date)); ?>
					</td>
					<td style="text-align: center;">
						<?php echo  $row->email_address; ?>
					</td>
					<td style="text-align: center;">
						<?php 
							echo "<a href='".$resumelink ."'>".JText::_('JS_RESUME').$row->totalresume."  </a>";
						?>
					</td>
				</tr>
                        <tr valign="top" class="<?php echo "row$k"; ?>">
                            <td>
                                <strong><?php echo JText::_('JS_COMMENTS'); ?></strong>
                            </td>
                            <td colspan="4">
                                <div>
                                    <?php echo  $row->comments; ?>
                                </div>
                            </td>
                                    <td colspan="3">
                                        <div>
                                        <?php
                                            $id = $row->jobapplyid;
											$percent = 0;
                                            $stars = '';
											$percent = $row->rating * 20;
                                            $stars = '-small';
                                            $html="
                                                <div class=\"jsjobs-container".$stars."\"".( " style=\"margin-top:5px;\"" ).">
                                                <ul class=\"jsjobs-stars".$stars."\">
                                                <li id=\"rating_".$id."\" class=\"current-rating\" style=\"width:".(int)$percent."%;\"></li>
                                                <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',1,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Very Poor')."\" class=\"one-star\">1</a></li>
                                                <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',2,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Poor')."\" class=\"two-stars\">2</a></li>
                                                <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',3,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Regular')."\" class=\"three-stars\">3</a></li>
                                                <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',4,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Good')."\" class=\"four-stars\">4</a></li>
                                                <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',5,".(int)$row->ratingid.",".$row->id.",".$row->appid.");\" title=\"".JTEXT::_('Very Good')."\" class=\"five-stars\">5</a></li>
                                                </ul>
                                                </div>
                                            ";
                                            $html .="</small></span>";
                                            echo $html;
                                        ?>
                                        </div>
                                    </td>

                        </tr>
                        <tr valign="top" class="<?php echo "row$k"; ?>">
                            <td>
                                <strong><?php echo JText::_('JS_LOCATION'); ?></strong>
                            </td>
                            <td colspan="8" align="left">
                                <?php
                                    if ($row->cityname) { echo $row->cityname; $comma = 1; }
                                    elseif ($row->address_city) { echo $row->address_city; $comma = 1; }
                                    if ($row->countyname) { if($comma) echo', '; echo $row->countyname; $comma = 1; }
                                    elseif ($row->address_county) { if($comma) echo', '; echo $row->address_county; $comma = 1; }
                                    if ($row->statename) { if($comma) echo', '; echo $row->statename; $comma = 1; }
                                    elseif ($row->address_state) { if($comma) echo', '; echo $row->address_state; $comma = 1; }
                                    if ($row->countryname) { if($comma) echo', '; echo $row->countryname; $comma = 1; }
                                 ?>
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
			<input type="hidden" name="oi" value="<?php echo $this->oi; ?>" />
			<input type="hidden" name="id" id="id" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>		
<script>
function setrating(src,newrating,ratingid,jobid,resumeid){
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
               if(xhr.responseText == 1)
                document.getElementById(src).style.width=parseInt(newrating*20)+'%';

      }
    }
	xhr.open("GET","index.php?option=com_jsjobs&task=saveresumerating&ratingid="+ratingid+"&jobid="+jobid+"&resumeid="+resumeid+"&newrating="+newrating,true);
	xhr.send(null);


}

</script>
		
