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
 * File Name:	views/jobseeker/tmpl/jobcat.php
 ^ 
 * Description: template view for job categories 
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
  global $mainframe;
  $document =& JFactory::getDocument();
   $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);

	$link = "index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=".$this->Itemid;
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
				<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_RESUME_BY_CATEGORY'); ?>
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
				<?php 
				}
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_RESUME_BY_CATEGORY');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->categories != false) { 
?>

	<table cellpadding="3" cellspacing="0" border="0" width="100%" >
		<?php
		$trclass = array($this->theme['odd'], $this->theme['even']);
                $count =1;
                $isodd =1;
                $noofcols =$this->config['categories_colsperrow'];
                $colwidth = round(100 / $noofcols);
                
		if ( isset($this->categories)){
			foreach($this->categories as $category)	{
                                    if ($count == 1){
                                            $isodd = 1 - $isodd;
                                            echo '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
                                    }
                                    $lnks = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_bycategory&cat='. $category->aliasid .'&Itemid='.$this->Itemid;
                                    $lnks = JRoute::_($lnks);
                                    ?>
                                            <td width="<?php echo $colwidth; ?>%" align="left"><span id="themeanchor"><a class="anchor" href="<?php echo $lnks; ?>" ><?php echo $category->cattitle; ?> (<?php echo $category->total; ?>)</a></span></td>
                                    <?php
                                    if ($count == $noofcols){
                                            echo '</tr>';
                                            $count = 0;
                                    }
                                    $count++;
                        }
			
		}
                if ($count-1 < $noofcols){
                        for ($i = $count; $i <= $noofcols; $i++){
                            echo '<td></td>';
                        }
                        echo '</tr>';
                }
		
		?>		
		
	</table>
<?php
} else{ // not allowed job posting ?>
<?php
	$message = "<font color='red'><strong>" . JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW') . "</strong></font>"; ?>
<div id="errormessage" class="errormessage">
	<div id="message"><?php echo $message;?></div>
</div>
<?php
}
}//ol
?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
