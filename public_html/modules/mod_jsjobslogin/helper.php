<?php
/**
 * @Copyright Copyright (C) 2009-2010 Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
 * Created on:	Nov 22, 2010
 ^
 + Project: 	JS Jobs
 ^ 
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class modJSJobsLoginHelper
{
	function getReturnURL($params, $type)
	{
		if($itemid =  $params->get($type)){  
			if ($type == 'login'){ // login
				$redirectpage = $params->get('login');
				if ( $redirectpage == 'jsjobs'){ // jsjobs
					$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=successfullogin';
					$url = JRoute::_($link.'&Itemid='.$itemid, false);
				}else{ // joomla
					$menu =& JSite::getMenu();  
					$default = $menu->getDefault();
					$uri = JFactory::getURI( $default->link.'&Itemid='.$default->id );
					$url = $uri->toString(array('path', 'query', 'fragment'));
				}
			}else{ // logout
				$menu =& JSite::getMenu();  
				$item = $menu->getItem($itemid); //var_dump($menu);die;
				if ($item)
					$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
				else{ // stay on the same page
					$uri = JFactory::getURI();
					$url = $uri->toString(array('path', 'query', 'fragment'));
				}
			}	
		}
		else{ // stay on the same page
			$uri = JFactory::getURI();
			$url = $uri->toString(array('path', 'query', 'fragment'));
		}

		return base64_encode($url);
	}

	function getType()
	{
		$user = & JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
}
