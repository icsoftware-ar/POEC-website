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
 * File Name:	views/employer/tmpl/viewjob.php
 ^ 
 * Description: template view for a company
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
  global $mainframe;
$comma = 0;	
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
						$vm =  $this->vm;
						if ($vm == '1'){ //my companies
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_MY_COMPANIES'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO');
						}elseif ($vm == '2'){ //list jobs
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_CATEGORIES'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&cn=&jobcat=<?php echo $_GET['jobcat']; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOBS_LIST_BY_CATEGORY'); ?></a> ><?php echo JText::_('JS_COMPANY_INFO');
						}elseif ($vm == '3'){ //job search
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_JOB'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_SEARCH_RESULT'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO');
						}else if ($vm == '4'){ $vm=2; //my applied jobs
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_APPLIED_JOBS'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO');
						}else if ($vm == '5'){ $vm=2; //newest jobs
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_NEWEST_JOBS'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO');
						}else if ($vm == '6'){  //jsmessages jobseeker
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jsmessages&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MESSAGES'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO');
						}else if ($vm == '7'){  //empmessages employer
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=empmessages&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MESSAGES'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO');
						}else if ($vm == '8'){  //COMPANY JOBS
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=company_jobs&cd=<?php echo $this->company->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo $this->company->name ; ?></a> > <?php echo JText::_('JS_COMPANY_INFO');
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
				foreach($this->employerlinks as $lnk)	{ ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_COMPANY_INFO');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php 
	if(isset($this->company)){ ?>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
		<?php
		$trclass = array("odd", "even");
		$i = 0;
		$isodd = 1;
		
		?>
	  </table>
	  <div id="comp_info_outer">
		  <div id="comp_detail">
			<table id="comp_table" cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
				<?php
					$trclass = array("odd", "even");
					$isodd = 0; ?>
									<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]];$isodd = 1 - $isodd; ?>">
										<td colspan="2" align="center"><b><?php echo $this->company->name;?></b></td>
									</tr>
					<?php 		if ($this->company->url) {
								if($this->config['comp_show_url'] == 1) { ?>
									  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]];$isodd = 1 - $isodd; ?>">
										<td width="50%"><b><?php echo JText::_('JS_URL'); ?></b></td>
										<!--<td><span id="anchor"><a class="anchor" href="<?php $chkprotocol = isURL($this->company->url);if($chkprotocol == true) echo $this->company->url;else echo 'http://'.$this->company->url;?>" target="_blank"><?php //echo $this->company->url; ?></a></span></td>-->
										<td><span id="anchor"><a class="anchor" href="<?php echo $this->company->url;?>" target="_blank"><?php echo $this->company->url; ?></a></span></td>
									  </tr>
					<?php		}
							}
							if ($this->config['comp_name'] == 1) { ?>
								  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]];$isodd = 1 - $isodd; ?>">
									<td><b><?php echo JText::_('JS_CONTACTNAME'); ?></b></td>
									<td><?php echo $this->company->contactname; ?></td>
								  </tr>
				  <?php 	}
							if ($this->config['comp_email_address'] == 1) { ?>
								  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]];$isodd = 1 - $isodd; ?>">
									<td><b><?php echo JText::_('JS_CONTACTEMAIL'); ?></b></td>
									<td><?php echo $this->company->contactemail; ?></td>
								  </tr>
				  <?php 	} ?>
								  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]];$isodd = 1 - $isodd; ?>">
									<td><b><?php echo JText::_('JS_CONTACTPHONE'); ?></b></td>
									<td><?php echo $this->company->contactphone; ?></td>
								  </tr>
				  <?php 	if ($this->company->address1) { ?>
								  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]];$isodd = 1 - $isodd; ?>">
									<td><b><?php echo JText::_('JS_ADDRESS1'); ?></b></td>
									<td><?php echo $this->company->address1; ?></td>
								  </tr>
				  <?php 	}
							if ($this->company->address2) { ?>
								  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]];$isodd = 1 - $isodd; ?>">
									<td><b><?php echo JText::_('JS_ADDRESS2'); ?></b></td>
									<td><?php echo $this->company->address2; ?></td>
								  </tr>
				  <?php 	} ?>
								  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]];$isodd = 1 - $isodd; ?>">
									<td><b><?php echo JText::_('JS_LOCATION'); ?></b></td>
									<td>
									  <?php 
									   if($this->company->multicity != '') echo $this->company->multicity; ;
									   
									   ?>
									</td>
								  </tr>
			</table>
			<div id="data_diff_bar"></div>
			<table id="comp_table" cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
				<?php
				$i = 0;
				$isodd = 0;
				foreach($this->fieldsordering as $field){ 
					//echo '<br> uf'.$field->field;
					switch ($field->field) {
						case "jobcategory": $isodd = 1 - $isodd; ?>
							  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="50%"><b><?php echo JText::_('JS_CATEGORIES'); ?></b></td>
								<td><?php echo $this->company->cat_title; ?></td>
							  </tr>
						<?php break;
						case "contactphone":  ?>
							  <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd; ?>
							  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td><b><?php echo JText::_('JS_CONTACTPHONE'); ?></b></td>
								<td><?php echo $this->company->contactphone; ?></td>
							  </tr>
							  <?php } ?>
						<?php break;
						case "contactfax":  ?>
							  <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
							  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td><b><?php echo JText::_('JS_CONTACTFAX'); ?></b></td>
								<td><?php echo $this->company->companyfax; ?></td>
							  </tr>
							  <?php } ?>
						<?php break;
						case "since":  ?>
							  <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
							  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td><b><?php echo JText::_('JS_SINCE'); ?></b></td>
								<td><?php echo date($this->config['date_format'],strtotime($this->company->since)); ?></td>
							  </tr>
						  <?php } ?>
						<?php break;
						case "companysize":  ?>
							  <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
							  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td><b><?php echo JText::_('JS_COMPANY_SIZE'); ?></b></td>
								<td><?php echo $this->company->companysize; ?></td>
							  </tr>
						  <?php } ?>
						<?php break;
						case "income":  ?>
							  <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
							  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td><b><?php echo JText::_('JS_INCOME'); ?></b></td>
								<td><?php echo $this->company->income; ?></td>
							  </tr>
							  <?php } ?>
						<?php break;
						case "description":  ?>
							  <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
							  <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td><b><?php echo JText::_('JS_DESCRIPTION'); ?></b></td>
								<td><?php echo $this->company->description; ?></td>
							  </tr>
							  <?php } ?>
						<?php break;
						case "aboutcompany": ?>
							  <?php if ( $field->published == 1 ) { ?>
								<?php if (isset($this->company)) 
											if($this->company->aboutcompanyfilename != '') {?>
												<tr><td></td><td><input type='checkbox' name='deleteaboutcompany' value='1'><?php echo JText::_('JS_DELETE_ABOUT_COMPANY_FILE') .'['.$this->company->aboutcompanyfilename.']'; ?></td></tr>
								<?php } ?>				
								<tr>
									<td align="right" >	<label id="aboutcompanymsg" for="aboutcompany"><?php echo JText::_('JS_ABOUT_COMPANY'); ?>	</label></td>
									<td><input type="file" class="inputbox" name="aboutcompany" size="20" maxlenght='30'/></td>
								</tr>
							  <?php } ?>
						<?php break;
						  
						default:
						if ( $field->published == 1 ) { 
						}	
					}
					
				} 
				if($this->isjobsharing){
					if(isset($this->userfields)){
						foreach($this->userfields as $ufield){
								$isodd = 1 - $isodd; 
								echo '<tr id="mc_field_row" class="'.$this->theme[$trclass[$isodd]] .'">';
								echo '<td><b>'. $ufield['field_title'] .'</b></td>';
								echo '<td>'.$ufield['field_value'].'</td>';	
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
								echo '<td><b>'. $userfield->title .'</b></td>';
								if ($userfield->type != "select"){
									if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								}elseif ($userfield->type == "select"){
									if(isset($ufield[2])){ $fvalue = $ufield[2]->fieldtitle; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								}
								echo '<td>'.$fvalue.'</td>';	
								echo '</tr>';
							}
						}	 
						
					
					}
				
				?>
			</table>
		  </div>
		  <div id="comp_btn_img">
				<div id="comp_btn">
					<?php if (isset($vm)) $vm = $vm; else $vm='';
					if ($vm != '1'){?>
						<!--<a id="button" class="button" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=company_jobs&cd=<?php echo $this->company->id; ?>&Itemid=<?php echo $this->Itemid; ?>" >-->
						<a id="button" class="button" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=company_jobs&cd=<?php echo $this->company->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" >
						<?php echo JText::_('JS_VIEW_ALL_JOBS'); ?></a>
					<?php } ?>	
				</div>
				<div id="comp_image">
					<?php if($this->isjobsharing) { ?>
						<?php if ($this->company->logoisfile !=-1) { ?>
								<img width="200"  src="<?php echo $this->company->company_logo;?>" />
						<?php }else { ?>
								<img width="200" height="54" src="components/com_jsjobs/images/blank_logo.png" />
						<?php } ?>
					<?php }else{ ?>
						<?php if ($this->company->logoisfile !=-1) { ?>
								<img width="200"  src="<?php echo $this->config['data_directory'];?>/data/employer/comp_<?php echo $this->company->id;?>/logo/<?php echo $this->company->logofilename;?>" />
						<?php }else { ?>
								<img width="200" height="54" src="components/com_jsjobs/images/blank_logo.png" />
						<?php } ?>
					<?php } ?>
				</div>
		  </div>
	  </div>


	<?php }else { ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND'); ?></b></div>
	</div>
	
<?php }
}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
<?php 

function isURL($url = NULL) {
        if($url==NULL) return false;

        $protocol = '(http://|https://)';
        if(ereg($protocol, $url)==true) return true;
        else return false;
}

?>
