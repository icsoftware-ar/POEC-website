<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if($this->result != false){ 
header("Content-Type: application/xml; charset=utf-8",true);
$link = JURI::root();
$results = $this->result;
$jobs = $results[0];
$listconfig = $results[1];

echo '<rss version="2.0">';
echo '<channel>';
echo '<title>'.$this->config['rss_job_title'].'</title>';
echo '<link>'.$link.'</link>';
echo '<ttl>'.$this->config['rss_job_ttl'].'</ttl>';
echo '<description>'.$this->config['rss_job_description'].'</description>';
if($this->config['rss_job_copyright'] != ''){
    echo '<copyright>'.$this->config['rss_job_copyright'].'</copyright>';
}
if($this->config['rss_job_webmaster'] != ''){
    echo '<webmaster>'.$this->config['rss_job_webmaster'].'</webmaster>';
}
if($this->config['rss_job_editor'] != ''){
    echo '<editor>'.$this->config['rss_job_editor'].'</editor>';
}
foreach($jobs AS $job){
	if(!empty($job->title)){
		$item ='<item><title>';
		$item .= htmlspecialchars($job->title).'</title>';
		$description = '';
		if($listconfig['lj_category'] == 1) $description .= JText::_('JS_CATEGORY').':- '.$job->cat_title.'<br/>';
		if($listconfig['lj_company'] == 1) $description .= JText::_('JS_COMPANY').':- '.$job->comp_title.'<br/>';
		if($listconfig['lj_jobtype'] == 1) $description .= JText::_('JS_JOB_TYPE').':- '.$job->jobtype.'<br/>';
		if($listconfig['lj_jobstatus'] == 1) $description .= JText::_('JS_JOB_STATUS').':- '.$job->jobstatus.'<br/>';
		if($listconfig['lj_noofjobs'] == 1) $description .= JText::_('JS_NOOFJOBS').':- '.$job->noofjobs.'<br/>';
		if(!empty($job->jobsalaryfrom) && !empty($job->jobsalaryto))
			if($listconfig['lj_salary'] == 1) $description .= JText::_('JS_SALARY_RANGE').':- '.$job->currency.$job->jobsalaryfrom.'-'.$job->currency.$job->jobsalaryto.'<br/>';
		$item .= '<description><![CDATA['.$description.']]></description>';
		if($this->config['rss_job_categories'] == 1)
			$item .= '<category>'.$result->cat_title.'</category>';
		if($this->config['rss_job_image'] == 1)
		   if($job->logofilename != '' AND !empty($job->logofilename)){
				   $imagelink = $link.'jsjobsdata/data/employer/comp_'.$job->companyid.'/logo/'.$job->logofilename;
				$item .= '<image><url>'.htmlspecialchars($imagelink).'</url>
								 <title>'.$job->logofilename.'</title>
								 <link>'.$link.'</link></image>';
		   }
		   $itemlink = $link.'index.php?option=com_jsjobs&amp;c=jsjobs&amp;view=employer&amp;layout=view_job&amp;vj=5&amp;oi='.$job->aliasid;
		$item .= '<link>'.htmlspecialchars($itemlink).'</link></item>';
		echo $item;
		
	}
}
echo '</channel>';
echo '</rss>';
}
?>
