<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

	$inc_dir = realpath(dirname(__FILE__));
	require_once($inc_dir . '/fdispatcher.php');

	class FAdminMailer extends FDispatcher
	{

		public function __construct(&$params, FoxMessageBoard &$messageboard, &$fieldsbuilder)
		{
			parent::__construct($params, $messageboard, $fieldsbuilder);
		}


		protected function LoadFields()
		{
		}


		public function Process()
		{
			$mail = JFactory::getMailer();

			$this->set_from($mail);
			$this->set_to($mail, "to_address", "addRecipient");
			$this->set_to($mail, "cc_address", "addCC");
			$this->set_to($mail, "bcc_address", "addBCC");

			$mail->setSubject(JMailHelper::cleanSubject($this->Params->get("email_subject", "")));

			$body = $this->body();
			$body .= $this->attachments($mail);
			$body .= PHP_EOL;

			// Info about url
			$body .= $this->Application->getCfg("sitename") . " - " . $this->CurrentURL() . PHP_EOL;

			// Info about client
			$body .= "Client: " . $this->ClientIPaddress() . " - " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;

			$body = JMailHelper::cleanBody($body);
			$mail->setBody($body);

			$this->Logger->Write("---------------------------------------------------" . PHP_EOL . $body);

			return $this->send($mail);
		}


		private function set_from(&$mail)
		{
			$emailhelper = new FoxEmailHelper($this->Params);
			$config = JComponentHelper::getParams("com_foxcontact");

			$adminemailfrom = $config->get("adminemailfrom");
			$from = $emailhelper->convert($adminemailfrom);
			$mail->setSender($from);

			$adminemailreplyto = $config->get("adminemailreplyto");
			$replyto = $emailhelper->convert($adminemailreplyto);
			// In Joomla 1.7 From and Reply-to fields is set by default to the Global admin email
			// but a call to setSender() won't change the Reply-to field
			$mail->ClearReplyTos();
			$mail->addReplyTo($replyto);
		}


		// $param_name | $method
		// ------------+-------------
		// to_address  | addRecipient
		// cc_address  | addCC
		// bcc_address | addBCC
		private function set_to(&$mail, $param_name, $method)
		{
			if ($this->Params->get($param_name, NULL))
				$recipients = explode(",", $this->Params->get($param_name, ""));
			else
				$recipients = array();

			// http://docs.joomla.org/How_to_send_email_from_components
			foreach ($recipients as $recipient)
			{
				// Avoid to call $mail->add..() with an empty string, since explode(",", $string) returns al least 1 item, even if $string is empty
				if (empty($recipient)) continue;
				$mail->$method($recipient);
			}
		}


		protected function attachments(&$mail)
		{
			$result = "";
			// this email is for the webmaster
			$uploadmethod = intval($this->Params->get("uploadmethod", "1"));  // How the webmaster wants to receive attachments

			if (count($this->FileList) && ($uploadmethod & 1)) $result .= JText::_($GLOBALS["COM_NAME"] . "_ATTACHMENTS") . PHP_EOL;
			foreach ($this->FileList as &$file)
			{  // binary 01: http link, binary 10: attach, binary 11: both
				$filename = 'components/' . $GLOBALS["com_name"] . '/uploads/' . $file;
				if ($uploadmethod & 1) $result .= JUri::base() . $filename . PHP_EOL;
				if ($uploadmethod & 2) $mail->addAttachment(JPATH_SITE . "/" . $filename);
			}

			return $result;
		}

	}
?>
