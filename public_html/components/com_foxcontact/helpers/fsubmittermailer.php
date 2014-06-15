<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

	$inc_dir = realpath(dirname(__FILE__));
	require_once($inc_dir . '/fdispatcher.php');

	class FSubmitterMailer extends FDispatcher
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
			$copy_to_submitter =
			(bool)$this->JInput->post->get($this->SafeName("copy_to_submitter" . $this->GetId()), NULL, "int") || // Checkbox is enabled
			($this->Params->get("copy_to_submitter", NULL) == 1);                            // always send a copy parameter

			if (!$copy_to_submitter || !isset($this->FieldsBuilder->Fields['sender1']) || empty($this->FieldsBuilder->Fields['sender1']['Value']))
			{
				$this->FSession->Clear('filelist');
				//JLog::add("Copy email for the submitter skipped.", JLog::INFO, get_class($this));
				return true;
			}

			$mail = JFactory::getMailer();

			$this->set_from($mail);
			$this->set_to($mail);
			$mail->setSubject(JMailHelper::cleanSubject($this->Params->get("email_copy_subject", "")));

			// Body
			$body = $this->Params->get("email_copy_text", "") . PHP_EOL;
			// a blank line
			$body .= PHP_EOL;

			if ($this->Params->get("email_copy_summary", NULL))
			{
				$body .= $this->body();
				$body .= $this->attachments();
				$body .= PHP_EOL;
			}

			// A null body will raise a mail error, so always add at least a signature.
			$body .= "------" . PHP_EOL . $this->Application->getCfg("sitename") . PHP_EOL;

			$body = JMailHelper::cleanBody($body);
			$mail->setBody($body);

			// Clear file list for the next submission of the same users
			$this->FSession->Clear('filelist');

			$this->send($mail);

			return true;
		}


		private function set_from(&$mail)
		{
			$emailhelper = new FoxEmailHelper($this->Params);
			$config = JComponentHelper::getParams("com_foxcontact");

			$submitteremailfrom = $config->get("submitteremailfrom");
			$from = $emailhelper->convert($submitteremailfrom);
			$mail->setSender($from);

			$submitteremailreplyto = $config->get("submitteremailreplyto");
			$replyto = $emailhelper->convert($submitteremailreplyto);
			// In Joomla 1.7 From and Reply-to fields is set by default to the Global admin email
			// but a call to setSender() won't change the Reply-to field
			$mail->ClearReplyTos();
			$mail->addReplyTo($replyto);
		}


		private function set_to(&$mail)
		{
			$addr = $this->FieldsBuilder->Fields['sender1']['Value'];
			$mail->addRecipient(JMailHelper::cleanAddress($addr));
		}


		protected function attachments()
		{
			$result = "";

			// this email is for the submitter
			// a further separation from signature above
			if (count($this->FileList)) $result .= JText::_($GLOBALS["COM_NAME"] . "_ATTACHMENTS") . PHP_EOL;
			foreach ($this->FileList as &$file)
			{
				$result .= substr($file, 14) . PHP_EOL;
			}

			return $result;
		}

	}
?>
