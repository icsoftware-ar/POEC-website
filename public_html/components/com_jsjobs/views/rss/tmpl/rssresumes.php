<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if($this->result !=false){ 
header("Content-Type: application/xml; charset=utf-8",true);
$link = JURI::root();
$results = $this->result;

echo '<rss version="2.0">';
echo '<channel>';
echo '<title>'.$this->config['rss_resume_title'].'</title>';
echo '<link>'.$link.'</link>';
echo '<ttl>'.$this->config['rss_resume_ttl'].'</ttl>';
echo '<description>'.$this->config['rss_resume_description'].'</description>';
if($this->config['rss_resume_copyright'] != ''){
    echo '<copyright>'.$this->config['rss_resume_copyright'].'</copyright>';
}
if($this->config['rss_resume_webmaster'] != ''){
    echo '<webmaster>'.$this->config['rss_resume_webmaster'].'</webmaster>';
}
if($this->config['rss_resume_editor'] != ''){
    echo '<editor>'.$this->config['rss_resume_editor'].'</editor>';
}
foreach($results AS $result){
	if(!empty($result->application_title)){
			$item ='<item><title>';
			$item .= htmlspecialchars($result->application_title).'</title>';
			
			if($result->gender == 1) $gender = JText::_('JS_MALE'); else $gender = JText::_('JS_FEMALE');
			$description = 'First Name:- '.$result->first_name.'<br/>Last Name:- '.$result->last_name.'<br/>Gender:- '.$gender.'<br/>Highest Education:- '.$result->education.'<br/>Total Experience:- '.$result->total_experience.'<br/>Email Address:- '.$result->email_address;
			$item .= '<description><![CDATA['.$description.']]></description>';
			if($this->config['rss_resume_categories'] == 1)
				$item .= '<category>'.$result->cat_title.'</category>';
			if($this->config['rss_resume_image'] == 1){
				/*if($this->isjobsharing){
					   if($result->photo != ''){
							   $imagelink = $result->image_path;
							$item .= '<image><url>'.htmlspecialchars($imagelink).'</url>
											 <title>Picture</title>
											 <link>'.$link.'</link></image>';
					   }
				}else{
				}*/   
					  if($result->photo != ''){
							   $imagelink = $link.'jsjobsdata/data/jobseeker/resume_'.$result->id.'/photo/'.$result->photo;
							$item .= '<image><url>'.htmlspecialchars($imagelink).'</url>
											 <title>Picture</title>
											 <link>'.$link.'</link></image>';
					   }
				
			}
			if($this->config['rss_resume_file'] == 1){
				/*if($this->isjobsharing){
				   if($result->filename != ''){
					   $filelink = $result->file_url;
					   $item .= '<enclosure url="'.$filelink.'" length="'.$result->filesize.'" type="docs/'.$result->filetype.'"/>';
				   }
					$itemlink = $link.'index.php?option=com_jsjobs&amp;c=jsjobs&amp;view=jobseeker&amp;layout=view_resume&amp;rd='.$result->id;
					$item .= '<link>'.htmlspecialchars($itemlink).'</link></item>';
				}else{
				}*/
				   if($result->filename != ''){
					   $filelink = $link.'jsjobsdata/data/jobseeker/resume_'.$result->id.'/resume/'.$result->filename;
					   $item .= '<enclosure url="'.$filelink.'" length="'.$result->filesize.'" type="docs/'.$result->filetype.'"/>';
				   }
					$itemlink = $link.'index.php?option=com_jsjobs&amp;c=jsjobs&amp;view=jobseeker&amp;layout=view_resume&amp;rd='.$result->id;
					$item .= '<link>'.htmlspecialchars($itemlink).'</link></item>';
			}
			echo $item;
		
	}
}
echo '</channel>';
echo '</rss>';
}
?>
