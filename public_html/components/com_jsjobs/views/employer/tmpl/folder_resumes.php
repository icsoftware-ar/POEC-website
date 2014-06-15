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
 * File Name:	views/employer/tmpl/job_appliedapplications.php
 ^
 * Description: template view for my job applied application
 ^
 * History:		NONE
 ^
 */

  defined('_JEXEC') or die('Restricted access');

 global $mainframe;
 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
 $document->addStyleSheet('components/com_jsjobs/css/jsjobsresumerating.css');
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
					echo JText::_('JS_CUR_LOC'); ?> :<a href="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders');?>" class="curloclnk" > <?php echo JText::_('JS_MY_FOLDERS'); ?> </a> > <?php echo JText::_('JS_MY_FOLDERS_RESUMES');
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
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_MY_FOLDER');
			$count = 0;
			if (sizeof($this->employerlinks) != 0){
				foreach($this->employerlinks as $lnk)	{
					if ($count == 1) { ?>
						<a href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
					<?php }	?>
						<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php $count++;
				}
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_MY_FOLDERS_RESUMES');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->resume){
if ($this->userrole->rolefor == 1) { // employer
?>
<div id="folder_resume">
<table cellpadding="1" cellspacing="0" border="0" width="100%">
	<?php
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
                $count = 0;
                    foreach($this->resume as $app)	{
                            $count++;
                            $isodd = 1 - $isodd;
                            //$resumelink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=5&rd='.$app->appid.'&fd='.$this->fd;
                            $resumelink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=5&rd='.$app->aliasid.'&fd='.$this->fd;
                            ?>
						<tr>
                            <td ><?php  echo '<strong>'; ?><?php echo $app->first_name.' '.$app->last_name; ?><?php  echo '</strong>'; ?></td>
                            <td ><?php if($app->gender==1) echo JText::_('JS_MALE');
                                                    elseif($app->gender==2) echo JText::_('JS_FEMALE');
                                                    else  echo JText::_('JS_DOES_NOT_MATTER')?></td>
                            <td ><?php echo $app->symbol . $app->rangestart . ' - ' . $app->symbol . $app->rangeend ?></td>
                            <td ><?php echo date($this->config['date_format'],strtotime($app->apply_date)); ?></td>
                            <td align="center"><?php echo $app->total_experience;  ?></td>
                            <td>
                                    <a id="button" class="button minpad" href="<?php echo $resumelink?>"><?php echo JText::_('JS_RESUME');?></a>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td >
                                <strong><?php echo JText::_('JS_COMMENTS'); ?></strong>
                            </td>
                            <td colspan="4">
                                <div>
                                    <?php echo  $app->comments; ?>
                                </div>
                            </td>
                            <td colspan="3">
                                <div>
                                <?php
                                    $id = $app->id;
                                    $percent = 0;
                                    $stars = '';
                                    $percent = $app->rating * 20;
                                    $stars = '-small';
                                    $html="
                                        <div class=\"jsjobs-container".$stars."\"".( " style=\"margin-top:5px;\"" ).">
                                        <ul class=\"jsjobs-stars".$stars."\">
                                        <li id=\"rating_".$id."\" class=\"current-rating\" style=\"width:".(int)$percent."%;\"></li>
                                        <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',1,".(int)$app->ratingid.",".$app->jobid.",".$app->appid.");\" title=\"".JTEXT::_('Very Poor')."\" class=\"one-star\">1</a></li>
                                        <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',2,".(int)$app->ratingid.",".$app->jobid.",".$app->appid.");\" title=\"".JTEXT::_('Poor')."\" class=\"two-stars\">2</a></li>
                                        <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',3,".(int)$app->ratingid.",".$app->jobid.",".$app->appid.");\" title=\"".JTEXT::_('Regular')."\" class=\"three-stars\">3</a></li>
                                        <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',4,".(int)$app->ratingid.",".$app->jobid.",".$app->appid.");\" title=\"".JTEXT::_('Good')."\" class=\"four-stars\">4</a></li>
                                        <li><a href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_".$id."',5,".(int)$app->ratingid.",".$app->jobid.",".$app->appid.");\" title=\"".JTEXT::_('Very Good')."\" class=\"five-stars\">5</a></li>
                                        </ul>
                                        </div>
                                    ";
                                    $html .="</small></span>";
                                    echo $html;
                                ?>
                                </div>
                            </td>


                        </tr>
                        <tr>
                            <td>
                                <strong><?php echo JText::_('JS_LOCATION'); ?></strong>
                            </td>
                            <td colspan="7" align="left">
                                <?php
									$comma="";
                                    if ($app->cityname) { echo $app->cityname; $comma = 1; }
                                    elseif ($app->address_city) { echo $app->address_city; $comma = 1; }
                                    //if ($app->countyname) { if($comma) echo', '; echo $app->countyname; $comma = 1; }
                                    //elseif ($app->address_county) { if($comma) echo', '; echo $app->address_county; $comma = 1; }
                                    if ($app->statename) { if($comma) echo', '; echo $app->statename; $comma = 1; }
                                    elseif ($app->address_state) { if($comma) echo', '; echo $app->address_state; $comma = 1; }
                                    if ($app->countryname) { if($comma) echo', '; echo $app->countryname; $comma = 1; }
                                 ?>
                            </td>
                        </tr>
                    <tr><td height="15"></td></tr>

                    <?php
                    }
		?>
		</table>
		</div>
			<input type="hidden" name="layout" value="folder_resumes" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="folderid" id="folderid" value="<?php echo $this->fd; ?>" />
			<input type="hidden" name="resumeid" id="resumeid" value="<?php echo $app->appid; ?>" />
			<input type="hidden" name="id" id="id" value="" />
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
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=folder_resumes&fd='.$this->fd.'&Itemid='.$this->Itemid); ?>" method="post">
	<div id="jl_pagination">
		<div id="jl_pagination_pageslink">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<div id="jl_pagination_box">
			<?php	
				echo JText::_('JS_DISPLAY_#');
				echo $this->pagination->getLimitBox();
			?>
		</div>
		<div id="jl_pagination_counter">
			<?php echo $this->pagination->getResultsCounter(); ?>
		</div>
	</div>
</form>
<?php
}else{ // no result found in this category ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND');?></b></div>
	</div>
<?php
	
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
