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
 * File Name:	views/jobseeker/tmpl/filters.php
 ^ 
 * Description: template view for filters
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');

 global $mainframe;
  $document =& JFactory::getDocument();
   $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
?>
<?php if ($this->searchresumeconfig['resume_subcategories'] == 1) {  ?>
                    <div style="max-height:<?php echo $this->listjobconfig['resume_subcategoeis_max_hight'] ?>;px;overflow-y: auto;width:100%;">
                        <table cellpadding="3" cellspacing="0" border="0" width="100%" >
                                <?php
                                $sctrclass = array($this->theme['odd'], $this->theme['even']);
                                $count =1;
                                $isodd =1;
                                $noofcols =$this->searchresumeconfig['resume_subcategories_colsperrow'];
                                $allcategories = $this->searchresumeconfig['resume_subcategories_all'];
                                $colwidth = round(100 / $noofcols);

                                if ( isset($this->subcategories) ){
                                        foreach($this->subcategories as $category)	{
                                            if ($allcategories == 0){ // show only those categories who have jobs
                                                    if($category->resumeinsubcat > 0 ) $printrecord = 1; else $printrecord = 0;
                                            }else $printrecord = 1;
                                            if ($printrecord == 1){
                                                if ($count == 1){
                                                        $isodd = 1 - $isodd;
                                                        echo '<tr id="mc_field_row" class="'.$sctrclass[$isodd].'">';
                                                }
                                                $lnks = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_bysubcategory&resumesubcat='. $category->aliasid .'&Itemid='.$this->Itemid;
                                                ?>
                                                        <td width="<?php echo $colwidth; ?>%" align="left"><span id="themeanchor"><a class="anchor" href="<?php echo $lnks; ?>" ><?php echo $category->title; ?> (<?php echo $category->resumeinsubcat; ?>)</a></span></td>
                                                <?php
                                                if ($count == $noofcols){
                                                        echo '</tr>';
                                                        $count = 0;
                                                }
                                                $count++;
                                            }
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
                    </div>
<?php } ?>
