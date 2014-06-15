<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.event.plugin' );
if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

/**
 * Joomla! Jsjobs Login Redirect
 * Version 1.7.2
 * @author		Ahmed
 * @package		Joomla
 * @subpackage	System
 */
class  plgUserJsjobsloginredirect extends JPlugin
{

	/**
	 * Object Constructor.
	 *
	 * @access	public
	 * @param	object	The object to observe -- event dispatcher.
	 * @param	object	The configuration object for the plugin.
	 * @return	void
	 * @since	1.0
	 */
	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	function onLoginUser($user, $options ){ //1.5 
		$this->redirectByUserRole($user['username']);
	}
	function onUserLogin($user,$options) {  // 1.6
		$this->redirectByUserRole($user['username']);
	}	
	
	

	function redirectByUserRole($username){
			global $mainframe;
		
			$version = new JVersion;
			$joomla = $version->getShortVersion();
			$jversion = substr($joomla,0,3);
		
			$app= JFactory::getApplication();
			$router = $app->getRouter();		
			$db = &JFactory::getDBO();
			$query = 'SELECT role.role AS userrole 
				FROM #__users AS user
				JOIN #__js_job_userroles AS role on user.id=role.uid
				WHERE user.username ='.$db->Quote($username);
			$db->setQuery( $query );
			$urole=$db->loadResult();
			$params= $this->params;
			$isloginredirect=$params->get('isredirect',0);
			if($isloginredirect==1){
				if($urole==1){ // for employer
					$emplinkid=$params->get('em_default_login_redirect','');
					if($emplinkid){
						 /*$menu = $app->getMenu();
						 $item = $menu->getItem( $emplinkid );						
						 $finallink=$item->link;*/
						 $finallink=$this->get_link_from_menuitem($emplinkid);
						if($finallink){
							JFactory::getApplication()->setUserState('users.login.form.return',$finallink);                
							JFactory::getApplication()->setUserState('return',$finallink); 					
							if($jversion == '1.5'){
								$mainframe->redirect( $finallink );
							}else return true;
							/*$session =& JFactory::getSession();
							$session->set('jsjobsloginredirect',$finallink);						
							return true;*/
						}	
						
							//$app->redirect(JRoute::_($finallink,false),'');
					}else{
						if (empty($emplinkid)) 
						{
							$app->redirect('index.php','Could Not Redirect! You did not specify a valid URL in the fileld Redirect URL of plugin setting!');
							return false;
						}

					}
				
				}elseif($urole==2){ // for jobseeker
					$jblinkid=$params->get('jb_default_login_redirect','');
					if($jblinkid){
						 /*$menu = $app->getMenu();
						 $item = $menu->getItem( $jblinkid );						
						 $finallink=$item->link;*/
						 $finallink=$this->get_link_from_menuitem($jblinkid);
						 
						 if($finallink){
							JFactory::getApplication()->setUserState('users.login.form.return',$finallink);                
							JFactory::getApplication()->setUserState('return',$finallink); 			
							if($jversion == '1.5'){
								$mainframe->redirect( $finallink );
							}else return true;
						 
							/*$session =& JFactory::getSession();
							$session->set('jsjobsloginredirect',$finallink);						
							return true;*/
							//echo $finallink;exit;
							//return $finallink; 
							//$app->redirect($finallink);
							//$app->redirect(JRoute::_($finallink,false),'');
						  }		
					}else{
						if (empty($jblinkid)) 
						{
							$app->redirect('index.php','Could Not Redirect! You did not specify a valid URL in the fileld Redirect URL of plugin setting!');
							return false;
						}
					
					}
				}
				
			}else{
				return true;
			}
	}
	
	function get_link_from_menuitem($menu_id){

		$database = JFactory::getDBO();	
		$app = JFactory::getApplication();
		$router = $app->getRouter();		
		
		$url = '';
		if($menu_id!=''){
			
			$database->setQuery("SELECT link, type, params "
			." FROM #__menu "
			." WHERE id='$menu_id' "
			." limit 1 "
			);
			$rows = $database->loadObjectList();
			$link = '';
			$type = '';
			$params = '';
			foreach($rows as $row){	
				$link = $row->link;	
				$type = $row->type;
				$params = $row->params;				
			}
			if($link!='') {
				if($router->getMode() == JROUTER_MODE_SEF) {
					$url = 'index.php?Itemid='.$menu_id;
				}else{
					$url = $link.'&Itemid='.$menu_id;
				}				
			}
			
		}	
		
		$url = JRoute::_($url);
		$url = str_replace('&amp;','&',$url);
		
		if($type=='alias'){
			//get the menu-item-id this alias points to			
			$registry = new JRegistry;
			$registry->loadString($params);
			$result = $registry->toArray();	
			$alias_menu_id = $result['aliasoptions'];		
			$url = $this->get_url_from_alias($alias_menu_id);			
		}	
		return $url;		
	}
	
	function get_url_from_alias($menu_id){
		//to recurse if menuitemtype is alias
		$url = $this->get_link_from_menuitem($menu_id);
		return $url;		
	}

	
	
		
		
}
