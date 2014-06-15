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
 * File Name:	router.php
 ^ 
 * Description: for Joomla SEF
 ^ 
 * History:		NONE
 ^ 
 */
 
 
function JSJobsBuildRoute( &$query )
{
       $segments = array();
	   $router = new jsjobsRouter;

       if(isset( $query['c'] )) { $segments[] = $query['c']; unset( $query['c'] );}
       if(isset( $query['view'] )) { 
		   //$segments[] = $query['view']; 
		   $view = $query['view']; 
		   unset( $query['view'] ); 
	   };
       if(isset( $query['layout'] )) { 
		  //echo '<pre>'; print_r($query);echo '</pre>';
			$value = $router->buildLayout($query['layout'],$view);
			$layout = $query['layout']; 
			$segments[] = $value; unset( $query['layout'] );
		   //$segments[] = $query['layout']; unset( $query['layout'] );
	   };
		if(isset( $query['task'] )) { 
			if(count($segments) == 1) $segments[] = 'tk';
			$segments[] = $query['task']; unset( $query['task'] );
		}
	
		//resume by category 
       if(isset( $query['cat'] )) { 
		   if(isset($layout) AND $layout=="resume_bycategory"){
				//$segments[] = "resumecategory-".$router->getCategoryTitle($query['cat'])."-".$query['cat']; 
				$segments[] = "resumecategory-".$query['cat']; 
				unset( $query['cat'] );
			}	
			//$segments[] = $query['cat']; unset( $query['cat'] );
       }
		//resume by subcategory 
       if(isset( $query['resumesubcat'] )) { 
		   if(isset($layout) AND $layout=="resume_bysubcategory"){
				//$segments[] = "resumesubcategory-".$router->getSubCategoryTitle($query['resumesubcat'])."-".$query['resumesubcat']; 
				$segments[] = "resumesubcategory-".$query['resumesubcat']; 
				unset( $query['resumesubcat'] );
			}	
			//$segments[] = $query['cat']; unset( $query['cat'] );
       }
	   // my companies
       if(isset( $query['md'] )) { 
		   if(isset($layout) AND $layout=="formcompany"){
				$segments[] = "ecompany-".$query['md']; 
				unset( $query['md'] );
			}elseif(isset($layout) AND $layout=="view_company"){
				if(isset($query['vm'])){
					switch($query['vm']){
						case 1:
							$segments[] = "vcompany-".$query['md']; 
							unset( $query['md'] );
						break;
						case 3:
							$segments[] = "searchcompany-".$query['md']; 
							unset( $query['md'] );
						break;
						case 4:
							$segments[] = "appliedcompany-".$query['md']; 
							unset( $query['md'] );
						break;
						case 5:
							$segments[] = "viewcompany-".$query['md']; 
							unset( $query['md'] );
						break;
						case 6://jobseeker message view company
							$segments[] = "jmesviewcompany-".$query['md']; 
							unset( $query['md'] );
						break;
						case 7://employer message view company
							$segments[] = "emesviewcompany-".$query['md']; 
							unset( $query['md'] );
						break;
						case 2:
							$segments[] = "catcompany-".$query['md']; 
							unset( $query['md'] );
						break;
						case 8:
							$segments[] = "jobsbycompany-".$query['md']; 
							unset( $query['md'] );
						break;
					}
				}
			}elseif(isset($segments[2]) AND $segments[2]=="deletecompany"){
				$segments[] = "dcompany-".$query['md']; 
				unset( $query['md'] );
			}elseif(isset($segments[2]) AND $segments[2]=="addtogoldcompany"){
				$segments[] = "goldcompany-".$query['md']; 
				unset( $query['md'] );
			}elseif(isset($segments[2]) AND $segments[2]=="addtofeaturedcompany"){
				$segments[] = "featuredcompany-".$query['md']; 
				unset( $query['md'] );
			}
		   //$segments[] = $query['md']; unset( $query['md'] );
	   }
	   // form resume
       if(isset( $query['rd'] )) { 
			if(isset($layout) AND $layout=="view_resume"){
				if(isset($query['vm'])){
					switch($query['vm']){
						case 5:
							//$segments[] = "vfresume-".$router->getResumeTitle($query['rd'])."-".$query['rd']; 
							$segments[] = "vfresume-".$query['rd']; 
							unset( $query['rd'] );
						break;
						case 2:
							//$segments[] = "appliedresume-".$router->getResumeTitle($query['rd'])."-".$query['rd']; 
							$segments[] = "appliedresume-".$query['rd']; 
							unset( $query['rd'] );
						break;
						case 3:
							//$segments[] = "searchresume-".$router->getResumeTitle($query['rd'])."-".$query['rd']; 
							$segments[] = "searchresume-".$query['rd']; 
							unset( $query['rd'] );
						break;
						case 10:
							//$segments[] = "vresumecategory-".$router->getResumeTitle($query['rd'])."-".$query['rd']; 
							$segments[] = "vresumecategory-".$query['rd']; 
							unset( $query['rd'] );
							if(isset($query['cat'])) { $segments[] = $query['cat']; unset( $query['cat'] );} 
							
						break;
						case 11:
							//$segments[] = "vresumesubcategory-".$router->getResumeTitle($query['rd'])."-".$query['rd']; 
							$segments[] = "vresumesubcategory-".$query['rd']; 
							unset( $query['rd'] );
							if(isset($query['cat'])) { $segments[] = $query['cat']; unset( $query['cat'] );} 
						break;
						case 1:
							//$segments[] = "viewresume-".$router->getResumeTitle($query['rd'])."-".$query['rd']; 
							$segments[] = "viewresume-".$query['rd']; 
							unset( $query['rd'] );
						break;
					}
				
				}
			}elseif(isset($layout) AND $layout=="send_message"){
				if(isset($query['vm'])){
					switch($query['vm']){
						case 1:
						case 2:
							$segments[] = "employersendmessage-".$query['rd']; 
							unset( $query['rd'] );
						break;
						case 3:
							//$segments[] = "jobseekersendmessage-".$router->getResumeTitle($query['rd'])."-".$query['rd']; 
							$segments[] = "jobseekersendmessage-".$query['rd']; 
							unset( $query['rd'] );
						break;
					}
				}	
				
			}elseif(isset($layout) AND $layout=="resumepdf"){
				//$segments[] = "pdfresume-".$router->getResumeTitle($query['rd'])."-".$query['rd']; 
				$segments[] = "pdfresume-".$query['rd']; 
				unset( $query['rd'] );
			}elseif(isset($layout) AND $layout=="formresume"){
				$segments[] = "eresume-".$query['rd']; 
				unset( $query['rd'] );
			}elseif(isset($segments[2]) AND $segments[2]=="deleteresume"){
				$segments[] = "dresume-".$query['rd']; 
				unset( $query['rd'] );
			}elseif(isset($segments[2]) AND $segments[2]=="addtogoldresumes"){
				$segments[] = "goldresume-".$query['rd']; 
				unset( $query['rd'] );
			}elseif(isset($segments[2]) AND $segments[2]=="addtofeaturedresumes"){
				$segments[] = "featuredresume-".$query['rd']; 
				unset( $query['rd'] );
			}elseif(isset($segments[2]) AND $segments[2]=="exportresume"){
				$segments[] = "exportresume-".$query['rd']; 
				unset( $query['rd'] );
			}	
		   
		   //$segments[] = $query['rd']; unset( $query['rd'] );
	   }
	
	   // view company
       if(isset( $query['vm'] )) { $segments[] = $query['vm']; unset( $query['vm'] );};

	   // form job
       if(isset( $query['bd'] )) { 
			if(isset($segments[2]) AND $segments[2]=="deletejob"){
					//$segments[] = "djob-".$router->getJobTitle($query['bd'])."-".$query['bd']; 
					$segments[] = "djob-".$query['bd']; 
					unset( $query['bd'] );
			}elseif(isset($segments[2]) AND $segments[2]=="exportresume"){
				$segments[] = $query['bd']; 
				unset( $query['bd'] );
			}elseif(isset($layout) AND $layout=="job_appliedapplications"){
				//$segments[] = "jobappliedapplications-".$router->getJobTitle($query['bd'])."-".$query['bd']; 
				$segments[] = "jobappliedapplications-".$query['bd']; 
				unset( $query['bd'] );
			}elseif(isset($layout) AND $layout=="job_messages"){
				//$segments[] = "employerjobmessage-".$router->getJobTitle($query['bd'])."-".$query['bd']; 
				$segments[] = "employerjobmessage-".$query['bd']; 
				unset( $query['bd'] );
			}elseif(isset($layout) AND $layout=="send_message"){
				$segments[] = $query['bd']; 
				unset( $query['bd'] );
			}elseif(isset($layout) AND $layout=="resumepdf"){
				$segments[] = $query['bd']; unset( $query['bd'] );
			}elseif(isset($layout) AND $layout=="formjob"){
				//$segments[] = "ejob-".$router->getJobTitle($query['bd'])."-".$query['bd']; 
				$segments[] = "ejob-".$query['bd']; 
				unset( $query['bd'] );
			}elseif(isset($layout) AND $layout=="view_resume"){
				$segments[] = $query['bd']; unset( $query['bd'] );
			}
				//$segments[] = $query['bd']; unset( $query['bd'] );
	   }

	   // form job visitor
       if(isset( $query['email'] )) { 
			if(isset($layout) AND $layout=="jobalertunsubscribe"){
			   $segments[] = "unsubjobalert-".$query['email']."-".$query['email']; 
			   unset( $query['email'] );
			}elseif(isset($layout) AND $layout=="formjob_visitor"){
			   $segments[] = "visitorjob-".$query['email']."-".$query['email']; 
			   unset( $query['email'] );
			}elseif(isset($segments[2]) AND $segments[2]=="deletejob"){
					$segments[] = "dvisitorjob-".$query['email']."-".$query['email']; 
					unset( $query['email'] );
			}else{
			   $segments[] = $query['email']; 
			   unset( $query['email'] );
			}
	   }

	   // view job
       if(isset( $query['oi'] )) { 
			if(isset($layout) AND $layout=="view_job"){
				if(isset($query['vj'])){
					switch($query['vj']){
						case 1:
							//$segments[] = "vjob-".$router->getJobTitle($query['oi'])."-".$query['oi']; 
							$segments[] = "vjob-".$query['oi']; 
							unset( $query['oi'] );
						break;
						case 2:
							//$segments[] = "categoryjob-".$router->getJobTitle($query['oi'])."-".$query['oi']; 
							$segments[] = "categoryjob-".$query['oi']; 
							unset( $query['oi'] );
						break;
						case 3:
							//$segments[] = "searchjob-".$router->getJobTitle($query['oi'])."-".$query['oi']; 
							$segments[] = "searchjob-".$query['oi']; 
							unset( $query['oi'] );
						break;
						case 4:
							$segments[] = "appliedjob-".$query['oi']; 
							unset( $query['oi'] );
						break;
						case 5:
							$segments[] = "newestjobs-".$query['oi']; 
							unset( $query['oi'] );
						break;
						case 6:
							$segments[] = "viewcompanyjob-".$query['oi']; 
							unset( $query['oi'] );
						break;
					}
				}
			}elseif(isset($segments[2]) AND $segments[2]=="addtogoldjobs"){
				//$segments[] = "agjob-".$router->getJobTitle($query['oi'])."-".$query['oi']; 
				$segments[] = "agjob-".$query['oi']; 
				unset( $query['oi'] );
		   }elseif(isset($segments[2]) AND $segments[2]=="addtofeaturedjobs"){
				//$segments[] = "afjob-".$router->getJobTitle($query['oi'])."-".$query['oi']; 
				$segments[] = "afjob-".$query['oi']; 
				unset( $query['oi'] );
			}	

	   }
       if(isset( $query['vj'] )) { $segments[] = $query['vj']; unset( $query['vj'] );};

	   // view resume search
       if(isset( $query['rs'] )) { 
		   if(isset($layout) AND $layout=="viewresumesearch"){
				$segments[] = "myresumesearch"; 
			}	
		   $segments[] = $query['rs']; unset( $query['rs'] );
		   
	   }


	   // view cover letter
       if(isset( $query['cl'] )) { 
			if(isset($layout) AND $layout=="formcoverletter"){
				//$segments[] = "ecoverletter-".$router->getCoverLetterTitle($query['cl'])."-".$query['cl']; 
				$segments[] = "ecoverletter-".$query['cl']; 
				unset( $query['cl'] );
			}elseif(isset($layout) AND $layout=="view_coverletter"){
				//$segments[] = "vcoverletter-".$router->getCoverLetterTitle($query['cl'])."-".$query['cl']; 
				$segments[] = "vcoverletter-".$query['cl']; 
				unset( $query['cl'] );
			}elseif(isset($segments[2]) AND $segments[2]=="deletecoverletter"){
				//$segments[] = "dcoverletter-".$router->getCoverLetterTitle($query['cl'])."-".$query['cl']; 
				$segments[] = "dcoverletter-".$query['cl']; 
				unset( $query['cl'] );
			}
		   //$segments[] = $query['cl']; unset( $query['cl'] );
	   }
       if(isset( $query['vct'] )) { $segments[] = $query['vct']; unset( $query['vct'] );};

	   // view job search
       if(isset( $query['js'] )) {
		   if(isset($layout) AND $layout=="viewjobsearch"){
				//$segments[] = "resumecategory-".$router->getCategoryTitle($query['cat'])."-".$query['cat']; 
				$segments[] = "viewjobsearchmanual"; 
			}	
			$segments[] = $query['js']; unset( $query['js'] ); 
		    
		}

	   // apply now
       if(isset( $query['bi'] )) { 
		   if(isset($layout) AND $layout=="job_apply"){
				if(isset($query['aj'])){
					switch($query['aj']){
						case 1:
							$segments[] = "jobcatapply-".$query['bi']; 
							unset( $query['bi'] );
						break;
						case 2:
							$segments[] = "searchapply-".$query['bi']; 
							unset( $query['bi'] );
						break;
						case 3:
							$segments[] = "newapply-".$query['bi']; 
							unset( $query['bi'] );
						break;
						case 4:
							$segments[] = "companyapply-".$query['bi']."-".$query['bi']; 
							unset( $query['bi'] );
						break;
						case 5:
							$segments[] = "jobsubcatapply-".$query['bi']."-".$query['bi']; 
							unset( $query['bi'] );
						break;
					}
				}	
			}   
		   //$segments[] = $query['bi']; unset( $query['bi'] );
	   }
       if(isset( $query['aj'] )) { $segments[] = $query['aj']; unset( $query['aj'] );};
	   
	   // view cover letters
       if(isset( $query['vts'] )) { $segments[] = $query['vts']; unset( $query['vts'] );};
       if(isset( $query['clu'] )) { $segments[] = $query['clu']; unset( $query['clu'] );};

       //view resume 2
       if(isset( $query['ms'] )) { $segments[] = $query['ms']; unset( $query['ms'] );};

       //view package
		if(isset( $query['gd'] )) { $segments[] = "p-".$query['gd']; unset( $query['gd'] );};

       //package buy now
       if(isset( $query['pb'] )) { $segments[] = $query['pb']; unset( $query['pb'] );};

       //form department
       if(isset( $query['pd'] )) { 
		   if(isset($layout) AND $layout=="formdepartment"){
				//$segments[] = "edepartment-".$router->getDepartmentTitle($query['pd'])."-".$query['pd']; 
				$segments[] = "edepartment-".$query['pd']; 
				unset( $query['pd'] );
			}elseif(isset($layout) AND $layout=="view_department"){
				$segments[] = "vdepartment-".$query['pd']; 
				unset( $query['pd'] );
			}elseif(isset($segments[2]) AND $segments[2]=="deletedepartment"){
				$segments[] = "ddepartment-".$query['pd']; 
				unset( $query['pd'] );
			}
		   
		   
		   //$segments[] = $query['pd']; unset( $query['pd'] );
	   }

       //view department
       if(isset( $query['vp'] )) { $segments[] = $query['vp']; unset( $query['vp'] );};

       //view jobs by sub category
       if(isset( $query['jobsubcat'] )) { 
		   if(isset($layout) AND $layout=="list_subcategoryjobs"){
				$segments[] = "jobssubcategory-".$query['jobsubcat']; 
				unset( $query['jobsubcat'] );
			}	
		   //$segments[] = $query['jobsubcat']; unset( $query['jobsubcat'] );
	   }

        //resume pdf output
       if(isset( $query['format'] )) { 
		   if(isset($layout) AND $layout=="rssresumes"){
				$segments[] = "resumerssformat-".$query['format']; unset( $query['format'] );
		   }elseif(isset($layout) AND $layout=="rssjobs"){
				$segments[] = "jobsrssformat-".$query['format']; unset( $query['format'] );
		   }else{
				$segments[] = $query['format']; unset( $query['format'] );
		   }
		   
	   }

        //folder
       if(isset( $query['fd'] )) { 
		   if(isset($layout) AND $layout=="formfolder"){
				//$segments[] = "efolder-".$router->getFolderTitle($query['fd'])."-".$query['fd']; 
				$segments[] = "efolder-".$query['fd']; 
				unset( $query['fd'] );
			}elseif(isset($layout) AND $layout=="folder_resumes"){
				$segments[] = "folderresume-".$query['fd']; 
				unset( $query['fd'] );
			}elseif(isset($layout) AND $layout=="viewfolder"){
				$segments[] = "vfolder-".$query['fd']; 
				unset( $query['fd'] );
			}elseif(isset($layout) AND $layout=="view_resume"){
				$segments[] = $query['fd']; unset( $query['fd'] );
				//unset( $query['fd'] );
			}elseif(isset($segments[2]) AND $segments[2]=="deletefolder"){
				//$segments[] = "dfolder-".$router->getFolderTitle($query['fd'])."-".$query['fd']; 
				$segments[] = "dfolder-".$query['fd']; 
				unset( $query['fd'] );
			}
		   //$segments[] = $query['fd']; unset( $query['fd'] );
	   }

        //country
		if(isset( $query['country'] )) { $segments[] = "cn-".$query['country']; unset( $query['country'] );};
		if(isset( $query['state'] )) { $segments[] = "st-".$query['state']; unset( $query['state'] );};
       if(isset( $query['county'] )) { $segments[] = $query['county']; unset( $query['county'] );};
    	if(isset( $query['city'] )) { $segments[] = "cy-".$query['city']; unset( $query['city'] );};


	   // list_jobs
       if(isset( $query['jobcat'] )) {
		   if(isset($layout) AND $layout=="list_jobs"){
				//$segments[] = "jobcategory-".$router->getCategoryTitle($query['jobcat'])."-".$query['jobcat']; 
				$segments[] = "jobcategory-".$query['jobcat']; 
				unset( $query['jobcat'] );
				//$segments[] = $query['jobcat']; unset( $query['jobcat'] );
		   }elseif(isset($layout) AND $layout=="view_company"){
				$segments[] = $query['jobcat']; unset( $query['jobcat'] );
				unset( $query['jobcat'] );
		   }elseif(isset($layout) AND $layout=="job_apply"){
				$segments[] = $query['jobcat']; unset( $query['jobcat'] );
				unset( $query['jobcat'] );
				//$segments[] = $query['jobcat']; unset( $query['jobcat'] );
			}
		}
       if(isset( $query['cn'] )) { $segments[] = $query['cn']; unset( $query['cn'] );};
       if(isset( $query['cd'] )) { 
		   if(isset($layout) AND $layout=="company_jobs"){
				$segments[] = "companyjobs-".$query['cd']; 
				unset( $query['cd'] );
			}
		   //$segments[] = $query['cd']; unset( $query['cd'] );
	   }
       if(isset( $query['cm'] )) { $segments[] = $query['cm']; unset( $query['cm'] );};


	//list type       
	if(isset( $query['lt'] )) { $segments[] = $query['lt']; unset( $query['lt'] );};
	if(isset( $query['fr'] )) { $segments[] = $query['fr']; unset( $query['fr'] );};

   if(isset( $query['sortby'] )) { 
						$segments[] = "sortby-".$query['sortby']; 						
						unset( $query['sortby'] );
				/*if(isset( $query['sortby'] )) {	
					echo '<br>sortby'.$layout;exit;
				}
		   echo $layout;exit;
		   if(isset($layout)){
			   switch ($layout){
				   case "myresumes";
						$segments[] = "sortresume-".$query['sortby']; 
						unset( $query['sortby'] );
				   break;
				   default:
						$segments[] = $query['sortby']; 
						unset( $query['sortby'] );
				   break;
			   }
			}*/
	   }


	   // form job visitor
       if(isset( $query['jobid'] )) { $segments[] = $query['jobid']; unset( $query['jobid'] );};

	   // form user registration
       if(isset( $query['userrole'] )) { 
		   if($query['userrole']=="2"){
				$segments[] = "jobseekerregistration-".$query['userrole']."-".$query['userrole']; 
				unset( $query['userrole'] );
		   }elseif($query['userrole']=="3"){
				$segments[] = "employerregistration-".$query['userrole']."-".$query['userrole']; 
				unset( $query['userrole'] );
			}
		   
		   //$segments[] = $query['userrole']; unset( $query['userrole'] );
	   }
       if(isset( $query['ta'] )) { $segments[] = $query['ta']; unset( $query['ta'] );}; /* applied application tab */
       if(isset( $query['jacl'] )) { $segments[] = $query['jacl']; unset( $query['jacl'] );}; /* job applied application call  */

	   //  echo '<br> item '.$query['Itemid'];
       if(isset( $query['Itemid'] )) { 
		$_SESSION['JSItemid'] = $query['Itemid'];
	   };
	   
       return $segments;
}

function JSJobsParseRoute( $segments )
{
       $vars = array();
	   $count = count($segments);
	   //echo '<br> count '.$count;
       $menu = &JMenu::getInstance('site');
		$router = new jsjobsRouter;
       
//       $item = &$menu->getActive();
		$menu	= &JSite::getMenu();

		$item	= &$menu->getActive();
		if(isset($segments[1])){
			$layout = $segments[1];
		}else $layout="";
		if($layout == 'tk'){
			$vars['task'] = $segments[2];
			if($segments[2]=='exportresume'){
				array_shift($segments);
			}
		}else{
			$lresult = $router->parseLayout($layout);
			//echo '<pre>';print_r($lresult);echo '</pre>';
			$vars['c'] = 'jsjobs';
			$vars['view'] = $lresult["view"];
			$vars['layout'] = $lresult["layout"];
		}
		//echo '<pre>';print_r($segments);echo '</pre>';
		//$index1 = explode(":",$segments[2]);
		//echo '<pre>';print_r($index1);echo '</pre>';
		//exit;
		$i=0;
		foreach($segments AS $seg){
			if($i >= 2){
				$array = explode(":",$seg);
				$index = $array[0];
				//unset the current index
				unset($array[0]);
				if(isset($array[1])) $value = implode("-",$array);
				switch($index){
					/*job  */
					case "ejob":$vars['bd'] = $router->parseId($value);break;
					case "djob":$vars['bd'] = $router->parseId($value);break;
					case "vjob":
						$vars['oi'] = $router->parseId($value);
						$vars['vj']=$segments[3];
					break;
					case "agjob":$vars['oi'] = $router->parseId($value);break;
					case "afjob":$vars['oi'] = $router->parseId($value);break;

					/*Company  */
					
					case "ecompany":$vars['md'] = $router->parseId($value);break;
					case "vcompany":
						$vars['md'] = $router->parseId($value);
						$vars['vm']=$segments[3];
					break;
					case "dcompany":$vars['md'] = $router->parseId($value);break;
					case "goldcompany":$vars['md'] = $router->parseId($value);break;
					case "featuredcompany":$vars['md'] = $router->parseId($value);break;

					/*Department */
					case "edepartment":$vars['pd'] = $router->parseId($value);break;
					case "vdepartment":
						$vars['pd'] = $router->parseId($value);
						$vars['vp']=$segments[3];
					break;
					case "ddepartment":$vars['pd'] = $router->parseId($value);break;
					/*Folders */
					case "efolder":$vars['fd'] = $router->parseId($value);break;
					case "vfolder":	$vars['fd'] = $router->parseId($value);	break;
					case "dfolder":$vars['fd'] = $router->parseId($value);break;
					case "folderresume":$vars['fd'] = $router->parseId($value);break;
					/*Resume */
					case "vfresume": // view folder resume 
						$vars['rd'] = $router->parseId($value);
						$vars['vm']=$segments[3];
						$vars['fd']=$segments[4];
					break;
					case "appliedresume": // Applied resume 
						$vars['rd'] = $router->parseId($value);
						$vars['vm']=$segments[3];
						$vars['bd']=$segments[4];
					break;
					case "searchresume": // resume search result 
						$vars['rd'] = $router->parseId($value);
						$vars['vm']=$segments[3];
						if(isset($segments[4])) $vars['bd']=$segments[4];
					break;
					case "vresumecategory": // resume by category
						$vars['rd'] = $router->parseId($value);
						$vars['vm']=$segments[4];
						$vars['cat']=$segments[3];
					break;
					case "vresumesubcategory": // resume by subcategory
						$vars['rd'] = $router->parseId($value);
						$vars['vm']=$segments[4];
						$vars['cat']=$segments[3];
					break;
					case "pdfresume":
						$vars['rd'] = $router->parseId($value);
						$vars['bd'] = $segments[3];
						$vars['ms'] = $segments[4];
						$vars['format'] = $segments[5];
					break;
					/*employer job message*/
					case "employerjobmessage":$vars['bd'] = $router->parseId($value);break;
					case "employersendmessage":
						$vars['rd'] = $router->parseId($value);
						$vars['vm'] = $segments[3];
						$vars['bd'] = $segments[4];
					break;
					/* employer job_appliedapplications*/
					case "jobappliedapplications":
						$vars['bd'] = $router->parseId($value);
						if(isset($segments[3])) $vars['ta'] = $segments[3];
						if(isset($segments[4])) $vars['jacl'] = $segments[4];
					break;
					/* resume save searches */
					case "myresumesearch":$vars['rs']=$segments[3];break;		
					/* delete resume save searches */
					case "deleteresumesearch":$vars['rs']=$segments[3];break;		
					/* resume by category */
					case "resumecategory":$vars['cat'] = $router->parseId($value);break;			
					/* resume by resume subcategory */
					case "resumesubcategory":$vars['resumesubcat'] = $router->parseId($value);break;			
					/* Rss Resume */
					case "resumerssformat":$vars['format'] = "rss";break;break;			
					/* Package details */
					case "p":
						$vars['gd'] = $router->parseId($value);
						if(isset($segments[3])) $vars['pb'] = $segments[3];
					break;
					
					
					
					
					
					
					/* jobseeker Router */
					
					/*Resume */
					case "eresume":
						$vars['rd'] = $router->parseId($value);
						$vars['vm']=$segments[3];
					break;
					case "viewresume":
						$vars['rd'] = $router->parseId($value);
						$vars['vm']=$segments[3];
					break;
					case "dresume":$vars['rd'] = $router->parseId($value);break;
					case "goldresume":$vars['rd'] = $router->parseId($value);break;
					case "featuredresume":$vars['rd'] = $router->parseId($value);break;
					case "exportresume":
						$vars['rd'] = $router->parseId($value);
						$vars['bd'] = $segments[3];
					break;
					/* cover letters */
					case "ecoverletter":$vars['cl'] = $router->parseId($value);break;
					case "vcoverletter":
						$vars['cl'] = $router->parseId($value);
						$vars['vct'] = $segments[3];
					break;
					case "dcoverletter":$vars['cl'] = $router->parseId($value);break;
					/* Messages */
					case "jobseekersendmessage":
						$vars['rd'] = $router->parseId($value);
						$vars['vm'] = $segments[3];
						$vars['bd'] = $segments[4];
					break;
					/* list newest jobs */
					case "newestjobs":
						$vars['oi'] = $router->parseId($value);
						$vars['vj']=$segments[3];
					break;
					case "categoryjob":
						$vars['oi'] = $router->parseId($value);
						$vars['vj']=$segments[3];
					break;
					case "appliedjob":
						$vars['oi'] = $router->parseId($value);
						$vars['vj']=$segments[3];
					break;
					case "searchjob":
						$vars['oi'] = $router->parseId($value);
						$vars['vj']=$segments[3];
					break;
					case "viewcompany":
						$vars['md'] = $router->parseId($value);
						$vars['vm']=$segments[3];
						if(isset($segments[4])) $vars['jobcat']=$segments[4];
					break;
					case "jmesviewcompany":
						$vars['md'] = $router->parseId($value);
						$vars['vm']=$segments[3];
					break;
					case "emesviewcompany":
						$vars['md'] = $router->parseId($value);
						$vars['vm']=$segments[3];
					break;
					case "catcompany":
						$vars['md'] = $router->parseId($value);
						$vars['vm']=$segments[3];
						if(isset($segments[4])) $vars['jobcat']=$segments[4];
					break;
					case "jobsbycompany":
						$vars['md'] = $router->parseId($value);
						$vars['vm']=$segments[3];
						if(isset($segments[4])) $vars['jobcat']=$segments[4];
					break;
					case "viewcompanyjob":
						$vars['oi'] = $router->parseId($value);
						$vars['vj']=$segments[3];
					break;
					case "companyjobs":
						$vars['cd'] = $router->parseId($value);
					break;
					case "newapply":
						$vars['bi'] = $router->parseId($value);
						$vars['aj']=$segments[3];
					break;
					case "companyapply":
						$vars['bi'] = $router->parseId($value);
						$vars['aj']=$segments[3];
					break;
					case "jobcatapply":
						$vars['bi'] = $router->parseId($value);
						$vars['aj']=$segments[3];
						$vars['jobcat']=$segments[4];
					break;
					case "jobsubcatapply":
						$vars['bi'] = $router->parseId($value);
						$vars['aj']=$segments[3];
						$vars['jobcat']=$segments[4];
					break;
					case "searchapply":
						$vars['bi'] = $router->parseId($value);
						$vars['aj']=$segments[3];
					break;
					case "jobcategory": // jobs by category
						$vars['jobcat'] = $router->parseId($value);
					break;
					/* job by subcategory */
					case "jobssubcategory":$vars['jobsubcat'] = $router->parseId($value);break;			
					case "appliedcompany":
						$vars['md'] = $router->parseId($value);
						$vars['vm']=$segments[3];
						$vars['jobcat']=$segments[4];
					break;
					case "searchcompany":
						$vars['md'] = $router->parseId($value);
						$vars['vm']=$segments[3];
						$vars['jobcat']=$segments[4];
					break;
					case "viewjobsearchmanual":$vars['js']=$segments[3];break;
					case "viewjobsearch":$vars['js']=$segments[2];break;		
					/* delete job save searches */
					case "deletejobsearch":$vars['js']=$segments[3];break;		
					/* unsubscribe job alerts */
					case "unsubjobalert":$vars['email']=$router->parseEmail($value);break;		
					/* visitor job  */
					case "visitorjob":
						$vars['email']=$router->parseEmail($value);
						$vars['jobid']=$segments[3];
					break;		
					case "dvisitorjob":
						$vars['email']=$router->parseEmail($segments[3]);
						$vars['jobid']=$segments[4];
					break;		
					/* Rss JOBS */
					case "jobsrssformat":$vars['format'] = "rss";break;break;			
					
					/*Registration */
					case "jobseekerregistration":$vars['userrole']=$router->parseId($value);break;
					case "employerregistration":$vars['userrole']=$router->parseId($value);break;
					
					/*Sorting */
					case "sortby": 
						$vars['sortby']=$value;
					break;
					
					/* jobs by city */
					case "cy":
						$vars['city'] = $router->parseId($value);
						$vars['lt']=$segments[3];
					break;
					/* jobs by States */
					case "st":
						$vars['state'] = $router->parseId($value);
						$vars['lt']=$segments[3];
					break;
					/* jobs by cOUNTRY */
					case "cn":
						$vars['country'] = $router->parseId($value);
						$vars['lt']=$segments[3];
					break;
					
				}
			}
			$i++;
		}
       if(isset( $_SESSION['JSItemid'] )) { 
		$vars['Itemid'] = $_SESSION['JSItemid'];
		}
       return $vars;

}
class jsjobsRouter{
	
	function buildLayout($value,$view){
		$returnvalue = "";
		switch($value){
			case "controlpanel": 
				if($view == 'jobseeker') $returnvalue = "controlpanel";
				else $returnvalue = "controlpannel";
			break;	
			case "formjob":$returnvalue = "formjob";break;
			case "myjobs":$returnvalue = "myjobs";break;
			case "mycompanies":$returnvalue = "mycompanies";break;
			case "formcompany":$returnvalue = "formcompany";break;
			case "alljobsappliedapplications":$returnvalue = "appliedresume";break;
			case "formdepartment":$returnvalue = "formdepartment";break;
			case "mydepartments":$returnvalue = "mydepartments";break;
			case "formfolder":$returnvalue = "formfolder";break;
			case "myfolders":$returnvalue = "myfolders";break;
			case "empmessages":$returnvalue = "employermessages";break;
			case "resumesearch":$returnvalue = "resumesearch";break;
			case "my_resumesearches":$returnvalue = "resumesavesearch";break;
			case "resumebycategory":$returnvalue = "resumebycategory";break;
			case "rssresumes":$returnvalue = "rssresumes";break;
			case "packages":
				if($view == 'jobseeker') $returnvalue = "jobseekerpackages";
				else $returnvalue = "packages";
			break;
			case "purchasehistory":
				if($view == 'jobseeker') $returnvalue = "jobseekerpurchasehistory";
				else $returnvalue = "purchasehistory";
			break;
			case "my_stats":
				if($view == 'jobseeker') $returnvalue = "jobseekerstats";
				else $returnvalue = "stats";
			break;
			case "package_details":
				if($view == 'jobseeker') $returnvalue = "jobseekerpackagedetails";
				else $returnvalue = "employerpackagedetails";
			break;
			case "package_buynow":
				if($view == 'jobseeker') $returnvalue = "jobseekerbuynow";
				else $returnvalue = "employerbuynow";
			break;
			case "view_job":$returnvalue="viewjob"; break;
			case "view_company":$returnvalue="viewcompany"; break;
			case "view_department":$returnvalue="viewdepartment"; break;
			case "viewfolder":$returnvalue="viewfolder"; break;
			case "folder_resumes":$returnvalue="folderresumes"; break;
			case "job_messages":$returnvalue="employerjobmessages"; break;
			case "send_message":$returnvalue = "employersendmessages";break;
			case "job_appliedapplications":$returnvalue="jobappliedapplications"; break;
			case "resume_searchresults":$returnvalue="resumesearchresults"; break;
			case "viewresumesearch":$returnvalue="viewresumesearch"; break;
			case "resume_bycategory":$returnvalue="resumecategory"; break;
			case "resume_bysubcategory":$returnvalue="resumesubcategory"; break;
			case "formjob_visitor":$returnvalue="formjobvisitor"; break;

			/* Jobseeker layout start  */
			case "formresume":$returnvalue = "formresume";break;
			case "myresumes":$returnvalue = "myresumes";break;
			case "formcoverletter":$returnvalue = "formcoverletter";break;
			case "mycoverletters":$returnvalue = "mycoverletters";break;
			case "jsmessages":$returnvalue = "jobseekermessages";break;
			case "jobcat":$returnvalue = "jobcategory";break;
			case "listnewestjobs":$returnvalue = "newestjobs";break;
			case "myappliedjobs":$returnvalue = "myappliedjobs";break;
			case "jobsearch":$returnvalue = "searchjob";break;
			case "my_jobsearches":$returnvalue = "jobsearches";break;
			case "jobalertsetting":$returnvalue = "jobalert";break;
			case "rssjobs":$returnvalue = "rssjobs";break;
			case "view_resume":$returnvalue = "viewresume";break;
			case "view_coverletters":$returnvalue = "viewcoverletters";break;
			case "view_coverletter":$returnvalue = "viewcoverletter";break;
			case "resumepdf":$returnvalue = "resumepdf";break;
			case "company_jobs":$returnvalue = "companyjobs";break;
			case "job_apply":$returnvalue = "jobapply";break;
			case "list_jobs":$returnvalue = "listjobs";break;
			case "list_subcategoryjobs":$returnvalue = "listsubcategoryjobs";break;
			case "job_searchresults":$returnvalue = "jobsearchresults";break;
			case "viewjobsearch":$returnvalue = "viewjobsearch";break;
			case "jobalertunsubscribe":$returnvalue = "jobalertunsubscribe";break;
			case "rssjobs":$returnvalue = "rssjobs";break;
			case "userregister":$returnvalue = "registration";break;

			case "successfullogin":$returnvalue = "successfullogin";break;
			case "new_injsjobs":$returnvalue = "newinjsjobs";break;
			
			
		}
		return $returnvalue;
	}	
	function parseLayout($value){
	//	$returnvalue = "";
		switch($value){
			case "controlpanel": $returnvalue["layout"]="controlpanel"; $returnvalue["view"]="jobseeker";break;
			case "controlpannel": $returnvalue["layout"]="controlpanel"; $returnvalue["view"]="employer";break;
			case "formjob": $returnvalue["layout"]="formjob"; $returnvalue["view"]="employer";break;
			case "myjobs": $returnvalue["layout"]="myjobs"; $returnvalue["view"]="employer";break;
			case "mycompanies": $returnvalue["layout"]="mycompanies"; $returnvalue["view"]="employer";break;
			case "formcompany": $returnvalue["layout"]="formcompany"; $returnvalue["view"]="employer";break;
			case "appliedresume": $returnvalue["layout"]="alljobsappliedapplications"; $returnvalue["view"]="employer";break;
			case "formdepartment": $returnvalue["layout"]="formdepartment"; $returnvalue["view"]="employer";break;
			case "mydepartments": $returnvalue["layout"]="mydepartments"; $returnvalue["view"]="employer";break;
			case "formfolder": $returnvalue["layout"]="formfolder"; $returnvalue["view"]="employer";break;
			case "myfolders": $returnvalue["layout"]="myfolders"; $returnvalue["view"]="employer";break;
			case "employermessages": $returnvalue["layout"]="empmessages"; $returnvalue["view"]="employer";break;
			case "resumesearch": $returnvalue["layout"]="resumesearch"; $returnvalue["view"]="employer";break;
			case "resumesavesearch": $returnvalue["layout"]="my_resumesearches"; $returnvalue["view"]="employer";break;
			case "resumebycategory": $returnvalue["layout"]="resumebycategory"; $returnvalue["view"]="employer";break;
			case "rssresumes": $returnvalue["layout"]="rssresumes"; $returnvalue["view"]="rss";break;
			case "packages": $returnvalue["layout"]="packages"; $returnvalue["view"]="employer";break;
			case "purchasehistory": $returnvalue["layout"]="purchasehistory"; $returnvalue["view"]="employer";break;
			case "stats": $returnvalue["layout"]="my_stats"; $returnvalue["view"]="employer";break;
			case "viewjob": $returnvalue["layout"]="view_job"; $returnvalue["view"]="employer";break;
			case "viewcompany": $returnvalue["layout"]="view_company"; $returnvalue["view"]="employer";break;
			case "viewdepartment": $returnvalue["layout"]="view_department"; $returnvalue["view"]="employer";break;
			case "viewfolder": $returnvalue["layout"]="viewfolder"; $returnvalue["view"]="employer";break;
			case "folderresumes": $returnvalue["layout"]="folder_resumes"; $returnvalue["view"]="employer";break;
			case "employerjobmessages": $returnvalue["layout"]="job_messages"; $returnvalue["view"]="employer";break;
			case "employersendmessages": $returnvalue["layout"]="send_message"; $returnvalue["view"]="employer";break;
			case "jobappliedapplications": $returnvalue["layout"]="job_appliedapplications"; $returnvalue["view"]="employer";break;
			case "resumesearchresults": $returnvalue["layout"]="resume_searchresults"; $returnvalue["view"]="employer";break;
			case "viewresumesearch": $returnvalue["layout"]="viewresumesearch"; $returnvalue["view"]="employer";break;
			case "resumecategory": $returnvalue["layout"]="resume_bycategory"; $returnvalue["view"]="employer";break;
			case "resumesubcategory": $returnvalue["layout"]="resume_bysubcategory"; $returnvalue["view"]="employer";break;
			case "employerpackagedetails": $returnvalue["layout"]="package_details"; $returnvalue["view"]="employer";break;
			case "jobseekerpackagedetails": $returnvalue["layout"]="package_details"; $returnvalue["view"]="jobseeker";break;
			case "employerbuynow": $returnvalue["layout"]="package_buynow"; $returnvalue["view"]="employer";break;
			case "jobseekerbuynow": $returnvalue["layout"]="package_buynow"; $returnvalue["view"]="jobseeker";break;
			case "formjobvisitor": $returnvalue["layout"]="formjob_visitor"; $returnvalue["view"]="employer";break;

			/* Jobseeker layout start  */
			case "formresume": $returnvalue["layout"]="formresume"; $returnvalue["view"]="jobseeker";break;
			case "myresumes": $returnvalue["layout"]="myresumes"; $returnvalue["view"]="jobseeker";break;
			case "formcoverletter": $returnvalue["layout"]="formcoverletter"; $returnvalue["view"]="jobseeker";break;
			case "mycoverletters": $returnvalue["layout"]="mycoverletters"; $returnvalue["view"]="jobseeker";break;
			case "jobseekermessages": $returnvalue["layout"]="jsmessages"; $returnvalue["view"]="jobseeker";break;
			case "jobcategory": $returnvalue["layout"]="jobcat"; $returnvalue["view"]="jobseeker";break;
			case "newestjobs": $returnvalue["layout"]="listnewestjobs"; $returnvalue["view"]="jobseeker";break;
			case "myappliedjobs": $returnvalue["layout"]="myappliedjobs"; $returnvalue["view"]="jobseeker";break;
			case "searchjob": $returnvalue["layout"]="jobsearch"; $returnvalue["view"]="jobseeker";break;
			case "jobsearches": $returnvalue["layout"]="my_jobsearches"; $returnvalue["view"]="jobseeker";break;
			case "jobalert": $returnvalue["layout"]="jobalertsetting"; $returnvalue["view"]="jobseeker";break;
			case "rssjobs": $returnvalue["layout"]="rssjobs"; $returnvalue["view"]="rss";break;
			case "jobseekerpackages": $returnvalue["layout"]="packages"; $returnvalue["view"]="jobseeker";break;
			case "jobseekerpurchasehistory": $returnvalue["layout"]="purchasehistory"; $returnvalue["view"]="jobseeker";break;
			case "jobseekerstats": $returnvalue["layout"]="my_stats"; $returnvalue["view"]="jobseeker";break;
			case "viewresume": $returnvalue["layout"]="view_resume"; $returnvalue["view"]="jobseeker";break;
			case "viewcoverletters": $returnvalue["layout"]="view_coverletters"; $returnvalue["view"]="jobseeker";break;
			case "viewcoverletter": $returnvalue["layout"]="view_coverletter"; $returnvalue["view"]="jobseeker";break;
			case "resumepdf": $returnvalue["layout"]="resumepdf"; $returnvalue["view"]="output";break;
			case "companyjobs": $returnvalue["layout"]="company_jobs"; $returnvalue["view"]="jobseeker";break;
			case "jobapply": $returnvalue["layout"]="job_apply"; $returnvalue["view"]="jobseeker";break;
			case "listjobs": $returnvalue["layout"]="list_jobs"; $returnvalue["view"]="jobseeker";break;
			case "listsubcategoryjobs": $returnvalue["layout"]="list_subcategoryjobs"; $returnvalue["view"]="jobseeker";break;
			case "jobsearchresults": $returnvalue["layout"]="job_searchresults"; $returnvalue["view"]="jobseeker";break;
			case "viewjobsearch": $returnvalue["layout"]="viewjobsearch"; $returnvalue["view"]="jobseeker";break;
			case "jobalertunsubscribe": $returnvalue["layout"]="jobalertunsubscribe"; $returnvalue["view"]="jobseeker";break;
			case "rssjobs": $returnvalue["layout"]="rssjobs"; $returnvalue["view"]="rss";break;
			case "registration": $returnvalue["layout"]="userregister"; $returnvalue["view"]="jobseeker";break;
			case "successfullogin": $returnvalue["layout"]="successfullogin"; $returnvalue["view"]="employer";break;
			case "newinjsjobs": $returnvalue["layout"]="new_injsjobs"; $returnvalue["view"]="jobseeker";break;
			
			
		}
		if(isset($returnvalue)) return $returnvalue;
	}	

	function getJobTitle($jobid){
		$db = &JFactory::getDBO();
		$query = "SELECT alias FROM `#__js_job_jobs` WHERE id = ".(int)$jobid;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	function getCompanyTitle($companyid){
		$db = &JFactory::getDBO();
		$query = "SELECT alias FROM `#__js_job_companies` WHERE id = ".(int)$companyid;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	function getDepartmentTitle($depid){
		$db = &JFactory::getDBO();
		$query = "SELECT alias FROM `#__js_job_departments` WHERE id = ".(int)$depid;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	function getFolderTitle($id){
		$db = &JFactory::getDBO();
		$query = "SELECT alias FROM `#__js_job_folders` WHERE id = ".(int)$id;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	function getResumeTitle($id){
		$db = &JFactory::getDBO();
		
		if(preg_match('/shr/',$id)){ // for sharing 
			//$serverid=substr($id,0,-3);
			$serverid = (int)$id;
			$query = "SELECT alias FROM `#__js_job_resume` WHERE serverid = ".(int)$serverid;
			$db->setQuery($query);
			$result = $db->loadResult();
		}else{
			$query = "SELECT alias FROM `#__js_job_resume` WHERE id = ".(int)$id;
			$db->setQuery($query);
			$result = $db->loadResult();
		}
		return $result;
	}
	function getCategoryTitle($id){
		$db = &JFactory::getDBO();
		if(preg_match('/shr/',$id)){ // for sharing 
			//$serverid=substr($id,0,-3);
			$serverid = (int)$id;
			$query = "SELECT alias FROM `__js_job_categories` WHERE serverid = ".(int)$serverid;
			$db->setQuery($query);
			$result = $db->loadResult();
		}else{
			
			$query = "SELECT alias FROM `#__js_job_categories` WHERE id = ".(int)$id;
			$db->setQuery($query);
			$result = $db->loadResult();
		}	
		return $result;
	}
	function getSubCategoryTitle($id){
		$db = &JFactory::getDBO();
		$query = "SELECT alias FROM `#__js_job_subcategories` WHERE id = ".(int)$id;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	function getCoverLetterTitle($id){
		$db = &JFactory::getDBO();
		$query = "SELECT alias FROM `#__js_job_coverletters` WHERE id = ".(int)$id;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}	
	function parseId($value){
		$id = explode("-",$value);
		$count = count($id);
		$id = (int)$id[($count-1)];
		return $id;
	}
	function parseSortValue($value){
		$sort = explode("-",$value);
		$count = count($sort);
		$sort_value = $sort[($count-1)];
		return $sort_value;
	}
	function parseEmail($value){
		$email = explode("-",$value);
		$count = count($email);
		$eaddress = $email[($count-1)];
		return $eaddress;
	}
	
	
	
}

