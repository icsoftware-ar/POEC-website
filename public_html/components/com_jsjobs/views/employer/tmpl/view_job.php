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
 * File Name:	views/employer/tmpl/view_job.php
 ^ 
 * Description: template view for a job
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
  global $mainframe;
  $document =& JFactory::getDocument();
  $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);

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
						$vj = $this->vj; 
						if ($vj == '1'){ $vm=1;//my jobs
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_MY_JOBS'); ?></a> > <?php echo JText::_('JS_VIEW_JOB');
						}else if ($vj == '2'){ $vm=2; $aj=1;//job cat
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_CATEGORIES'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&jobcat=<?php echo $this->job->jobcategory; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOBS_LIST_BY_CATEGORY'); ?></a> ><?php echo JText::_('JS_VIEW_JOB');
						}else if ($vj == '3'){ $vm=3; $aj=2;//job search
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_JOB'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_SEARCH_RESULT'); ?></a> > <?php echo JText::_('JS_VIEW_JOB');
						}else if ($vj == '4'){ $vm=4; //my applied jobs
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_APPLIED_JOBS'); ?></a> > <?php echo JText::_('JS_VIEW_JOB');
						}else if ($vj == '5'){ $vm=5; $aj=3;//newest jobs
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_NEWEST_JOBS'); ?></a> > <?php echo JText::_('JS_VIEW_JOB');
						}else if ($vj == '6'){ $vm=8; $aj=4;//company jobs jobs
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=company_jobs&cd=<?php echo $this->job->companyid; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo $this->job->companyname.' '.JText::_('JS_JOBS'); ?></a> > <?php echo JText::_('JS_VIEW_JOB');
						}
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
			if (sizeof($this->employerlinks) != 0){
				foreach($this->employerlinks as $lnk)	{?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center">
                <?php 
					if ($this->listjobconfig['lj_title'] == '1') {   
						if(isset($this->job)) echo '[ '.$this->job->title;
						$days = $this->config['newdays'];
						$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
						if(isset($this->job)){
							if ($this->job->created > $isnew)
								echo "<font color='red'> ".JText::_('JS_NEW')." </font>";
							echo ' ] ';
						 }	
					}
					echo JText::_('JS_JOB_INFO');  ?>
				</span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php 
	if( isset($this->job)){//job summary table ?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
        <?php   $trclass = array("odd", "even");
		$i = 0;
                $j = 0; // for two column
                $k = 2;
		$isodd = 1;?>
            <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
          <?php foreach($this->fieldsordering as $field){
                 switch ($field->field) {
                        case "company":
                         if ($this->listjobconfig['lj_company'] == '1') {     ?>
                                <td class="ji_title"><?php echo JText::_('JS_COMPANY'); ?></td>
                                        <td class="ji_data">
                                            <?php if (isset($_GET['jobcat'])) $jobcat = $_GET['jobcat']; else $jobcat=null;
                                            $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm='.$vm.'&md='.$this->job->companyaliasid.'&jobcat='.$this->job->jobcategory.'&Itemid='.$this->Itemid; ?>
                                            <span id="anchor">
												<a class="anchor" href="<?php echo $link; ?>">
													<?php echo $this->job->companyname; ?>
												</a>
											</span>
                                        </td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                          <?php } ?>
                        <?php break;
                        case "department": ?>
                                <td class="ji_title"><?php echo JText::_('JS_DEPARTMENT'); ?></td>
                                        <td class="ji_data">
                                                <?php echo $this->job->departmentname; ?>
                                        </td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                        <?php break; 
                        case "jobcategory": 
                              if ($this->listjobconfig['lj_category'] == '1') { ?>
                                        <td class="ji_title"><?php echo JText::_('JS_CATEGORY'); ?></td>
                                        <td class="ji_data"><?php echo $this->job->cat_title; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                                <?php } ?>
                        <?php break;
                        case "subcategory":  ?>
                                        <td class="ji_title"><?php echo JText::_('JS_SUB_CATEGORY'); ?></td>
                                        <td class="ji_data"><?php echo $this->job->subcategory; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                        <?php break;
                        case "jobtype": 
                              if ($this->listjobconfig['lj_jobtype'] == '1') { ?>
                                <td class="ji_title"><?php echo JText::_('JS_JOBTYPE'); ?></td>
								<td class="ji_data"><?php echo $this->job->jobtypetitle; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                                <?php } ?>
                        <?php break;
                        case "jobstatus":
                              if ($this->listjobconfig['lj_jobstatus'] == '1') { ?>
                                <td class="ji_title"><?php echo JText::_('JS_JOBSTATUS'); ?></td>
								<td class="ji_data"><?php echo $this->job->jobstatustitle; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                            <?php } ?>
                        <?php break;
                        case "jobshift": ?>
                      <?php if ( $field->published == 1 ) { ?>
                                <td class="ji_title"><?php echo JText::_('JS_SHIFT'); ?></td>
								<td class="ji_data"><?php echo $this->job->shifttitle; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                          <?php } ?>
                        <?php break;
                        case "jobsalaryrange":  ?>
                      <?php if ( $field->published == 1 ) { ?>
                                <?php if ( $this->job->hidesalaryrange != 1 ) { // show salary ?>
                                <td class="ji_title"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
								<td class="ji_data"><?php
                                                if ($this->job->salaryfrom) echo JText::_('JS_S_FROM') .' ' .$this->job->symbol. $this->job->salaryfrom;
                                                if ($this->job->salaryto) echo ' - ' . JText::_('JS_S_TO'). ' '.$this->job->symbol . $this->job->salaryto;
                                                if ($this->job->salarytype) echo ' ' . $this->job->salarytype;;
                                                //echo $salaryrange; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                                <?php } ?>
                          <?php } ?>
                        <?php break;
                        case "heighesteducation":?>
                      <?php if ( $field->published == 1 ) { ?>
                                <?php
                                        if($this->job->iseducationminimax == 1){
                                                if($this->job->educationminimax == 1) $title = JText::_('JS_MINIMUM_EDEDUCATION');
                                                else $title = JText::_('JS_MAXIMUM_EDEDUCATION');
                                                $educationtitle = $this->job->educationtitle;
                                        }else {
                                                $title = JText::_('JS_EDEDUCATION');
                                                $educationtitle = $this->job->mineducationtitle.' - '.$this->job->maxeducationtitle;
                                        }
                                ?>
                                <td class="ji_title"><?php echo $title; ?></td>
								<td class="ji_data"><?php echo $educationtitle; ?></td>
                             <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                  </tr>
                                  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                              <?php }?>
                                <td class="ji_title"><?php echo JText::_('JS_DEGREE_TITLE'); ?></td>
								<td class="ji_data"><?php echo $this->job->degreetitle; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                                <?php } ?>
                        <?php break;
                        case "noofjobs":?>
                                <td class="ji_title"><?php echo JText::_('JS_NOOFJOBS'); ?></td>
								<td class="ji_data"><?php echo $this->job->noofjobs; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                        <?php break;
                        case "experience": ?>
                      <?php if ( $field->published == 1 ) { ?>
                                <?php
                                        if($this->job->isexperienceminimax == 1){
                                                if($this->job->experienceminimax == 1) $title = JText::_('JS_MINIMUM_EXPERIENCE');
                                                else $title = JText::_('JS_MAXIMUM_EXPERIENCE');
                                                $experiencetitle = $this->job->experiencetitle;
                                        }else {
                                                $title = JText::_('JS_EXPERIENCE');
                                                $experiencetitle = $this->job->minexperiencetitle.' - '.$this->job->maxexperiencetitle;
                                        }
                                        if($this->job->experiencetext) $experiencetitle .= ' ('.$this->job->experiencetext.')';
                                ?>
                                <td class="ji_title"><?php echo $title; ?></td>
								<td class="ji_data"><?php echo $experiencetitle; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                          <?php } ?>
                        <?php break;
                        case "duration": ?>
                      <?php if ( $field->published == 1 ) { ?>
                                <td class="ji_title"><?php echo JText::_('JS_DURATION'); ?></td>
								<td class="ji_data"><?php echo $this->job->duration; ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                          <?php } ?>
                        <?php break;
                        case "startpublishing": ?>
                              <?php //if ($vj == '1'){ //my jobs ?>
                                        <td class="ji_title"><?php echo JText::_('JS_START_PUBLISHING'); ?></td>
										<td class="ji_data"><?php echo date($this->config['date_format'],strtotime($this->job->startpublishing)); ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                                  <?php //} ?>
                        <?php break;
                        case "stoppublishing": ?>
                              <?php //if ($vj == '1'){ //my jobs ?>
                                                <td class="ji_title"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></td>
												<td class="ji_data"><?php echo  date($this->config['date_format'],strtotime($this->job->stoppublishing)); ?></td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                        <?php break;
                        case "city":
                          if ($this->listjobconfig['lj_city'] == '1') { ?>
                                <td colspan="4">
                                <div style="float:left;width:100%;">
									<span style="font-weight:bold;padding-left:4px;width:20%;"><?php echo JText::_('JS_LOCATION').":"; ?></span>
									<span style="padding-left:4%;width:70%;"><?php if($this->job->multicity != '') echo $this->job->multicity; elseif($this->job->multicity != '') echo $this->job->multicity;?></span>
								<div>	
								</td>
                                 <?php $j++; if($j%$k == 0){ $isodd = 1 - $isodd; ?>
                                      </tr>
                                      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
                                  <?php }?>
                                <?php } ?>
                        <?php break;
                }?>

          <?php  }
          if($j%$k == 0){
			  echo '</tr>';
		  }else{
			  echo '<td></td><td></td></tr>';
		  }?>
          
        </table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
      <tr>
        <td colspan="2" height="5"></td>
      </tr>
		<?php
		$trclass = array("odd", "even");
		$i = 0;
		$isodd = 1;
		foreach($this->fieldsordering as $field){ 
			switch ($field->field) {
				
				case "video": $isodd = 1 - $isodd;
						if ($this->job->video) { ?>
				      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
				        <td class="ji_title2"><?php echo JText::_('JS_VIDEO'); ?></td>
						<td class="ji_data2">
						<iframe title="YouTube video player" width="480" height="390" 
                                                        src="http://www.youtube.com/embed/<?php echo $this->job->video; ?>" frameborder="0" allowfullscreen>
                                                </iframe>
						</td>
				      </tr>
					  <?php } ?>
				<?php  break;
				case "map": $isodd = 1 - $isodd; ?>
				  <tr>
			        <td colspan="2">
						<div id="map"><div id="map_container"></div></div>
			        </td>
			      </tr>
						<input type="hidden" id="longitude" name="longitude" value="<?php if(isset($this->job)) echo $this->job->longitude;?>"/>
						<input type="hidden" id="latitude" name="latitude" value="<?php if(isset($this->job)) echo $this->job->latitude;?>"/>
				<?php break;
				case "agreement": $isodd = 1 - $isodd; ?>
				    <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
				        <td colspan="2" class="ji_title2"><?php echo JText::_('JS_AGREEMENT'); ?></td>
					</tr>
				    <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
						<td colspan="2" class="ji_data2"><?php echo $this->job->agreement; ?></td>
					</tr>

				<?php break;
				case "description": $isodd = 1 - $isodd; ?>
				    <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
				        <td colspan="2" class="ji_title2"><?php echo JText::_('JS_DESCRIPTION'); ?></td>
					</tr>
				    <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
						<td colspan="2" class="ji_data2"><?php echo $this->job->description; ?></td>
					</tr>
				<?php break;
				case "qualifications": $isodd = 1 - $isodd; ?>
				    <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
				        <td colspan="2" class="ji_title2"><?php echo JText::_('JS_QUALIFICATIONS'); ?></td>
					</tr>
				    <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
						<td colspan="2" class="ji_data2"><?php echo $this->job->qualifications; ?></td>
					</tr>
				<?php break;
				case "prefferdskills": $isodd = 1 - $isodd; ?>
				    <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
				        <td colspan="2" class="ji_title2"><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></td>
					</tr>
				    <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
						<td colspan="2" class="ji_data2"><?php echo $this->job->prefferdskills; ?></td>
					</tr>
				<?php break;
				default:

				?>
			<?php }
		}
		?>
		<?php 
			if ( $field->published == 1 ) { 
				if($this->isjobsharing!="") {
					if(is_array($this->userfields)) {
						for($k = 0; $k < 15;$k++){
								$isodd = 1 - $isodd; 
								$field_title='fieldtitle_'.$k;
								$field_value='fieldvalue_'.$k;
								echo '<tr id="mc_field_row" class="'.$this->theme[$trclass[$isodd]] .'">';
								echo '<td class="ji_title2">'. $this->userfields[$field_title].'</td>';
								echo '<td class="ji_data2">'.$this->userfields[$field_value].'</td>';	
								echo '</tr>';
						}
					}
				}else{
					foreach($this->userfields as $ufield){ 
						if($ufield[0]->published==1) {
							$isodd = 1 - $isodd; 
							$userfield = $ufield[0];
							$i++;
							echo '<tr id="mc_field_row" class="'.$this->theme[$trclass[$isodd]] .'">';
							echo '<td class="ji_title2">'. $userfield->title .'</td>';
							if ($userfield->type == "checkbox"){
								if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								if ($fvalue == '1') $fvalue = "True"; else $fvalue = "false";
							}elseif ($userfield->type == "select"){
								if(isset($ufield[2])){ $fvalue = $ufield[2]->fieldtitle; $userdataid = $ufield[2]->id;} else {$fvalue=""; $userdataid = ""; }
							}else{
								if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
							}
							echo '<td class="ji_data2">'.$fvalue.'</td>';	
							echo '</tr>';
						}
					}	 
					
				}
			}	
		
		?>
		<?php $isodd = 1 - $isodd; ?>	
		
      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
        <td class="ji_title2"><?php echo JText::_('JS_DATEPOSTED'); ?></td>
		<td class="ji_data2"><?php echo date($this->config['date_format'].' H:i:s',strtotime($this->job->created));  ?></td>
      </tr>

      <tr>
        <td colspan="2" height="5"></td>
      </tr>
	<?php if (($vj == '2') || ($vj == '3') || ($vj == '5') || ($vj == '6')){ ?>
      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>"> 
        <td colspan="2" align="center">
			<div id="pkg_bn_btn">
				<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&aj='.$aj.'&jobcat='.$this->job->jobcategory.'&bi='.$this->job->id.'&Itemid='.$this->Itemid; ?>
				<a id="button" href="<?php echo $link?>" class="button"><strong><?php echo JText::_('JS_APPLYNOW'); ?></strong></a>		   
			</div>
		</td>
      </tr>
	</table>
	
      <div id="jsjobs_share_pannel" >
	  <?php 
		  if($this->socailsharing['jobseeker_share_google_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a href="#" onclick="window.open('https://m.google.com/app/plus/x/?v=compose&content='+location.href,'gplusshare','width=450,height=300,left='+(screen.availWidth/2-225)+',top='+(screen.availHeight/2-150)+'');return false;"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_google.png";?>" alt="Share on Google+" /></a>			
					</div>
			<?php }
		  if($this->socailsharing['jobseeker_share_friendfeed_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a target="_blank" href="http://www.friendfeed.com/share?title=<?php echo $document->title;?> - <?php echo JURI::current();?>" title="Share to FriendFeed"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_ff.png";?>" alt="Friend Feed" /></a>
					</div>
			<?php }
		  if($this->socailsharing['jobseeker_share_blog_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a href="#" onclick="window.open('http://www.blogger.com/blog_this.pyra?t&u='+location.href+'&n='+document.title, '_blank', 'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0');return false" title="BlogThis!"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_blog.png";?>" alt="Share on Blog" /></a>
					</div>
		<?php }
		if($this->socailsharing['jobseeker_share_linkedin_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo JURI::current();?>&title=<?php echo $document->title;?>" title="Share to Linkedin"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_linkedin.png";?>" alt="Linkedid" /></a>
					</div>
		<?php }
		if($this->socailsharing['jobseeker_share_myspace_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a target="_blank" href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?php echo JURI::current();?>&t=<?php echo $document->title;?>" title="Share to MySpace"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_myspace.png";?>" alt="MySpace" /></a>
					</div>
		<?php }
		if($this->socailsharing['jobseeker_share_twiiter_share'] == 1) { ?>
					<div id="jsjobs_share_content">
						<a href="#" onclick="window.open('http://twitter.com/share?text=<?php echo $document->title;?>&url=<?php echo JURI::current();?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0');return false" title="Share to Twitter"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_twitter.png";?>" alt="Twitter" /></a>
					</div>
		<?php }
		if($this->socailsharing['jobseeker_share_yahoo_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a target="_blank" href="http://bookmarks.yahoo.com/toolbar/savebm?u=<?php echo JURI::current();?>&t=<?php echo $document->title;?>" title="Save to Yahoo! Bookmarks"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_yahoo.png";?>" alt="Yahoo" /></a>
					</div>

		<?php }
		if($this->socailsharing['jobseeker_share_digg_share'] == 1) { ?>
					<div id="jsjobs_share_content">
						<a href="#" onclick="window.open('http://digg.com/submit?url='+location.href);return false" title="Share to Digg" ><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_digg.png";?>" alt="Share on Digg" /></a>
					</div>
		<?php }
		if($this->socailsharing['jobseeker_share_fb_share'] == 1) { ?>
					<div id="jsjobs_share_content" class="jsjobs_sharelast">
						<a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u='+location.href+'&t='+document.title, '_blank', 'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0');return false"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_fb.png";?>" alt="Share on facebook" /></a>
					</div>
		<?php } ?>
		</div>
      <div id="jsjobs_share_pannel" >
		<?php if($this->socailsharing['jobseeker_share_fb_like'] == 1){?>
				<div id="share_content">
					  <div id="fb-root"></div>
					  <script>
						// Load the SDK Asynchronously
						(function(d){
						   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
						   if (d.getElementById(id)) {return;}
						   js = d.createElement('script'); js.id = id; js.async = true;
						   js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
						   ref.parentNode.insertBefore(js, ref);
						 }(document));
					  </script>
					  <div class="fb-like"></div>
				</div>
			<?php } 
			  if($this->socailsharing['jobseeker_share_google_like'] == 1){ ?>
					<div id="share_content">
						<script src="https://apis.google.com/js/plusone.js"></script>
						<g:plus action="share" href="<?php echo JURI::current();?>"></g:plus>
					</div>
			<?php }?>
		</div>	

		<?php if($this->socailsharing['jobseeker_share_fb_comments'] == 1){ ?>				
					<div id="jsjobs_fbcommentparent">
						<span id="jsjobs_fbcommentheading"><?php echo JText::_('Comments');?></span>
						<div id="jsjobs_fbcomment">
							<iframe id="jobseeker_fb_comments" src="" scrolling="yes" frameborder="0" style="border:none; overflow:hidden; width:100%; height:400px;" allowTransparency="true"></iframe>
							<script>//window.onload = function() {}</script>
						</div>
					</div>
		<?php } ?>



      
	
	<?php }else{ ?>  
	</table>
      <div id="jsjobs_share_pannel" >
	  <?php 
		  if($this->socailsharing['employer_share_google_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a href="#" onclick="window.open('https://m.google.com/app/plus/x/?v=compose&content='+location.href,'gplusshare','width=450,height=300,left='+(screen.availWidth/2-225)+',top='+(screen.availHeight/2-150)+'');return false;"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_google.png";?>" alt="Share on Google+" /></a>			
					</div>
			<?php }
		  if($this->socailsharing['employer_share_friendfeed_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a target="_blank" href="http://www.friendfeed.com/share?title=<?php echo $document->title;?> - <?php echo JURI::current();?>" title="Share to FriendFeed"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_ff.png";?>" alt="Friend Feed" /></a>
					</div>
			<?php }
		  if($this->socailsharing['employer_share_blog_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a href="#" onclick="window.open('http://www.blogger.com/blog_this.pyra?t&u='+location.href+'&n='+document.title, '_blank', 'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0');return false" title="BlogThis!"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_blog.png";?>" alt="Share on Blog" /></a>
					</div>
		<?php }
		if($this->socailsharing['employer_share_linkedin_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo JURI::current();?>&title=<?php echo $document->title;?>" title="Share to Linkedin"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_linkedin.png";?>" alt="Linkedid" /></a>
					</div>
		<?php }
		if($this->socailsharing['employer_share_myspace_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a target="_blank" href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?php echo JURI::current();?>&t=<?php echo $document->title;?>" title="Share to MySpace"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_myspace.png";?>" alt="MySpace" /></a>
					</div>
		<?php }
		if($this->socailsharing['employer_share_twitter_share'] == 1) { ?>
					<div id="jsjobs_share_content">
						<a href="#" onclick="window.open('http://twitter.com/share?text=<?php echo $document->title;?>&url=<?php echo JURI::current();?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0');return false" title="Share to Twitter"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_twitter.png";?>" alt="Twitter" /></a>
					</div>
		<?php }
		if($this->socailsharing['employer_share_yahoo_share'] == 1){ ?>
					<div id="jsjobs_share_content">
						<a target="_blank" href="http://bookmarks.yahoo.com/toolbar/savebm?u=<?php echo JURI::current();?>&t=<?php echo $document->title;?>" title="Save to Yahoo! Bookmarks"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_yahoo.png";?>" alt="Yahoo" /></a>
					</div>

		<?php }
		if($this->socailsharing['employer_share_digg_share'] == 1) { ?>
					<div id="jsjobs_share_content">
						<a href="#" onclick="window.open('http://digg.com/submit?url='+location.href);return false" title="Share to Digg" ><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_digg.png";?>" alt="Share on Digg" /></a>
					</div>
		<?php }
		if($this->socailsharing['employer_share_fb_share'] == 1) { ?>
					<div id="jsjobs_share_content" class="jsjobs_sharelast">
						<a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u='+location.href+'&t='+document.title, '_blank', 'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0');return false"><img src="<?php echo JURI::root()."components/com_jsjobs/themes/images/share_fb.png";?>" alt="Share on facebook" /></a>
					</div>
		<?php } ?>
		</div>
      <div id="jsjobs_share_pannel" >
		
		<?php if($this->socailsharing['employer_share_fb_like'] == 1){?>
			<div id="share_content">
					  <div id="fb-root"></div>
					  <script>
						// Load the SDK Asynchronously
						(function(d){
						   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
						   if (d.getElementById(id)) {return;}
						   js = d.createElement('script'); js.id = id; js.async = true;
						   js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
						   ref.parentNode.insertBefore(js, ref);
						 }(document));
					  </script>
					  <div class="fb-like"></div>
					</div>
			<?php } 
			  if($this->socailsharing['employer_share_google_like'] == 1){ ?>
					<div id="share_content">
						<script src="https://apis.google.com/js/plusone.js"></script>
						<g:plus action="share" href="<?php echo JURI::current();?>"></g:plus>
					</div>
			<?php }?>
		</div>	
		
		<?php  if($this->socailsharing['employer_share_fb_comments'] == 1){ ?>				
					<div id="jsjobs_fbcommentparent">
						<span id="jsjobs_fbcommentheading"><?php echo JText::_('Comments');?></span>
						<div id="jsjobs_fbcomment">
							<iframe id="employer_fb_comments" src="" scrolling="yes" frameborder="0" style="border:none; overflow:hidden; width:100%; height:400px;" allowTransparency="true"></iframe>
							<script>//window.onload = function() {}</script>
						</div>
					</div>
		<?php } ?>
			
	  
	  
      
	<?php } ?>  
	
	<?php }else { ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND'); ?></b></div>
	</div>
<?php
		 
		 } ?>
<?php 
}//ol
?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
<style type="text/css">
div#map_container{
	width:100%;
	height:350px;
}
</style>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
window.onload = loadMap();
  function loadMap() {
		var latedit=[];
		var longedit=[];
		var longitude = document.getElementById('longitude').value;
		var latitude = document.getElementById('latitude').value;
		latedit=latitude.split(",");
		longedit=longitude.split(",");
		if(latedit != '' && longedit != ''){ 
			for (var i = 0; i < latedit.length; i++) {
				var latlng = new google.maps.LatLng(latedit[i], longedit[i]); zoom = 4;
				var myOptions = {
				  zoom: zoom,
				  center: latlng,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				if(i==0) var map = new google.maps.Map(document.getElementById("map_container"),myOptions);
				/*var lastmarker = new google.maps.Marker({
					postiion:latlng,
					map:map,
				});*/
					var marker = new google.maps.Marker({
					  position: latlng, 
					  zoom: zoom,
					  map: map, 
					  visible: true,					  
					});
					marker.setMap(map);
			}			
		}
}
	window.onload = function() {
		
		if(document.getElementById('jobseeker_fb_comments') != null){
			var myFrame = document.getElementById('jobseeker_fb_comments');
			if(myFrame != null)
			myFrame.src='http://www.facebook.com/plugins/comments.php?href='+location.href;
		}
		if(document.getElementById('employer_fb_comments') != null){
			var myFrame = document.getElementById('employer_fb_comments');
			if(myFrame != null)
			myFrame.src='http://www.facebook.com/plugins/comments.php?href='+location.href;
		}
	}
</script>
