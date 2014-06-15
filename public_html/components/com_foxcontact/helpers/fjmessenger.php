<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

$inc_dir = realpath(dirname(__FILE__));
require_once($inc_dir . '/fdispatcher.php');

class FJMessenger extends FDispatcher
{
	public function __construct(&$params, FoxMessageBoard &$messageboard, &$fieldsbuilder)
	{
		parent::__construct($params, $messageboard, $fieldsbuilder);

		$this->isvalid = true;
	}


	public function Process()
	{
		$uid = $this->Params->get("jmessenger_user", NULL);
		// No user selected for Joomla messenger
		if (!$uid)
		{
			//JLog::add("No recipient selected in Joomla Messenger dispatcher. Private message was not send.", JLog::INFO, get_class($this));
			// It's not a problem. Maybe it's even wanted. Return succesful.
			return true;
		}

		$body = $this->body();
		$body .= $this->attachments();
		$body .= PHP_EOL;
		// Info about url
		$body .= $this->Application->getCfg("sitename") . " - " . $this->CurrentURL() . PHP_EOL;
		// Info about client
		$body .= "Client: " . $this->ClientIPaddress() . " - " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->insert($db->quoteName("#__messages"));
		$query->set($db->quoteName("user_id_from") . "=" . $db->quote($uid));
		$query->set($db->quoteName("user_id_to") . "=" . $db->quote($uid));
		$query->set($db->quoteName("date_time") . "=" . $db->quote(JFactory::getDate()->toSql()));
		$query->set($db->quoteName("subject") . "=" . $db->quote($this->submittername() . " (" . $this->submitteraddress() . ")"));
		$query->set($db->quoteName("message") . "=" . $db->quote(JMailHelper::cleanBody($body)));

		$db->setQuery((string)$query);

		if (!$db->query())
		{
			//JLog::add($msg, JLog::ERROR, get_class($this));
			$this->MessageBoard->Add(JText::_($GLOBALS["COM_NAME"] . "_ERR_SENDING_MESSAGE"), FoxMessageBoard::error);
			// Database problems. Return error.
			return false;
		}

		//JLog::add("Private message sent to Joomla messenger.", JLog::INFO, get_class($this));
		return true;

	}


	protected function attachments()
	{
		$result = "";
		// this message is for the webmaster
		if (count($this->FileList)) $result .= JText::_($GLOBALS["COM_NAME"] . "_ATTACHMENTS") . PHP_EOL;
		foreach ($this->FileList as &$file)
		{
			$result .= JUri::base() . 'components/' . $GLOBALS["com_name"] . '/uploads/' . $file . PHP_EOL;
		}

		return $result;
	}

}

?>
