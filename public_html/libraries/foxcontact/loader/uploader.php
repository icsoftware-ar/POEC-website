<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

require_once JPATH_COMPONENT . "/helpers/fsession.php";
require_once JPATH_COMPONENT . "/helpers/flogger.php";
require_once JPATH_COMPONENT . "/helpers/fmimetype.php";
require_once "loader.php";

define('KB', 1024);

class uploaderLoader extends Loader
{
	protected function type()
	{
		return "uploader";
	}


	protected function http_headers()
	{
	}


	protected function content_header()
	{
	}


	protected function content_footer()
	{
	}


	protected function load()
	{

		switch (true)
		{
			case isset($_GET['qqfile']):
				$um = new XhrUploadManager();
				break;
			case isset($_FILES['qqfile']):
				$um = new FileFormUploadManager();
				break;
			default:
				// Malformed / malicious request, or attachment exceeds server limits
				$result = array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_NO_FILE'));
				exit(htmlspecialchars(json_encode($result), ENT_NOQUOTES));
		}
		$um->Params = & $this->Params;
		$result = $um->HandleUpload(JPATH_COMPONENT . '/uploads/');
		// to pass data through iframe you will need to encode all html tags
		echo(htmlspecialchars(json_encode($result), ENT_NOQUOTES));

	}
}


abstract class FUploadManager
{
	protected $Session;
	protected $Log;
	protected $DebugLog;


	abstract protected function save_file($path);


	abstract protected function get_file_name();


	abstract protected function get_file_size();


	function __construct()
	{
		$this->Log = new FLogger();
		$this->DebugLog = new FDebugLogger("file uploader");

		$this->Session = JFactory::getSession();
	}


	public function HandleUpload($uploadDirectory)
	{
		$this->DebugLog->Write("HandleUpload() started");

		// Security issue: when upload is disabled, stop here
		if (!(bool)$this->Params->get("uploaddisplay", 0))
		{
			$this->DebugLog->Write("Directory " . $uploadDirectory . " is not writable");
			return array('error' => " [upload disabled]");
		}

		if (!is_writable($uploadDirectory))
		{
			$this->DebugLog->Write("Directory " . $uploadDirectory . " is not writable");
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_DIR_NOT_WRITABLE'));
		}
		$this->DebugLog->Write("Directory " . $uploadDirectory . " is ok");

		// Check file size
		$size = $this->get_file_size();
		if ($size == 0) // It must be > 0
		{
			$this->DebugLog->Write("File size is 0");
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_FILE_EMPTY'));
		}
		$this->DebugLog->Write("File size is > 0");

		// uploadmax_file_size defaults to 0 to prevent hack attempts
		$max = $this->Params->get("uploadmax_file_size", 0) * KB; // and < max limit
		if ($size > $max)
		{
			$this->DebugLog->Write("File size too large ($size > $max)");
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_FILE_TOO_LARGE'));
		}
		$this->DebugLog->Write("File size ($size / $max) is ok");

		// Clean file name
		$filename = preg_replace("/[^\w\.-_]/", "_", $this->get_file_name());
		// Assign a random unique id to the file name, to avoid that lamers can force the server to execute their uploaded shit
		$filename = uniqid() . "-" . $filename;
		$full_filename = $uploadDirectory . $filename;

		if (!$this->save_file($full_filename))
		{
			$this->DebugLog->Write("Error saving file");
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_SAVE_FILE'));
		}
		$this->DebugLog->Write("File saved");

		$mimetype = new FMimeType();
		if (!$mimetype->Check($full_filename, $this->Params))
		{
			// Delete the file uploaded
			unlink($full_filename);
			$this->DebugLog->Write("File type [" . $mimetype->Mimetype . "] is not allowed. Allowed types are:" . PHP_EOL . print_r($mimetype->Allowed, true));
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_MIME') . " [" . $mimetype->Mimetype . "]");
		}
		$this->DebugLog->Write("File type [" . $mimetype->Mimetype . "] is allowed");

		// Security issue: block scripts based on their content
		$content = file_get_contents($full_filename);
		if (strpos($content, '<?php') !== false)
		{
			// contains php directive
			unlink($full_filename);
			$this->DebugLog->Write("File type [" . $mimetype->Mimetype . "] is not allowed. Allowed types are:" . PHP_EOL . print_r($mimetype->Allowed, true));
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_MIME') . " [forbidden content]");
		}

		// Security issue: block scripts based on their extension
		$forbidden_extensions = '/^ph(p[345st]?|t|tml|ar)$/'; // php|php3|php4|php5|phps|phpt|pht|phtml|phar
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		$m = array();
		if (preg_match($forbidden_extensions, $extension, $m))
		{
			// dangerous file extension
			unlink($full_filename);
			$this->DebugLog->Write("File type [" . $mimetype->Mimetype . "] is not allowed. Allowed types are:" . PHP_EOL . print_r($mimetype->Allowed, true));
			return array('error' => JFactory::getLanguage()->_($GLOBALS["COM_NAME"] . '_ERR_MIME') . " [forbidden extension]");
		}

		// Security issue: wrap the uploaded file in a zip shell to avoid script to be executed
		if (class_exists("ZipArchive"))
		{
			// Zip library is callable
			$zip = new ZipArchive();
			// Replace the extension with .zip
			$parts = pathinfo($full_filename);
			$zipname = $parts["dirname"] . "/" . $parts["filename"] . ".zip";
			// Create the zip archive
			if ($zip->open($zipname, ZIPARCHIVE::CREATE) && $zip->addFromString($filename, $content) && $zip->close())
			{
				unlink($full_filename);
				// Replace the file name used in the session list
				$filename = $parts["filename"] . ".zip";
			}
		}

		$cid = JFactory::getApplication()->input->get("cid", NULL);
		$mid = JFactory::getApplication()->input->get("mid", NULL);
		$owner = JFactory::getApplication()->input->get("owner", NULL);
		$id = JFactory::getApplication()->input->get("id", NULL);
		$jsession = JFactory::getSession();
		$fsession = new FSession($jsession->getId(), $cid, $mid);

		// Store the answer in the session

		// Read the list from the session
		$data = $fsession->Load('filelist');
		if ($data) $filelist = explode("|", $data);
		else $filelist = array();
		// Append this file to the list
		$filelist[] = $filename;
		$data = implode("|", $filelist);
		$fsession->Save($data, "filelist");

		$this->Log->Write("File " . $filename . " uploaded succesful.");
		$this->DebugLog->Write("File uploaded succesful.");
		return array("success" => true);
	}

}


// File uploads via XMLHttpRequest
class XhrUploadManager extends FUploadManager
{

	public function __construct()
	{
		parent::__construct();
	}


	protected function save_file($path)
	{
		$input = fopen("php://input", "r");
		$target = fopen($path, "w");

		// Todo: Check they are both valid strams before using them
		$realSize = stream_copy_to_stream($input, $target);

		fclose($input);
		fclose($target);

		return ($realSize == $this->get_file_size());
	}


	protected function get_file_name()
	{
		// Todo: usare il wrapper di Joomla per le get
		return $_GET['qqfile'];
	}


	protected function get_file_size()
	{
		if (isset($_SERVER["CONTENT_LENGTH"])) return (int)$_SERVER["CONTENT_LENGTH"];
		//else throw new Exception('Getting content length is not supported.');
		return 0;
	}

}


// File uploads via regular form post (uses the $_FILES array)
class FileFormUploadManager extends FUploadManager
{
	public function __construct()
	{
		parent::__construct();
	}


	protected function save_file($path)
	{
		return move_uploaded_file($_FILES['qqfile']['tmp_name'], $path);
	}


	protected function get_file_name()
	{
		return $_FILES['qqfile']['name'];
	}


	protected function get_file_size()
	{
		return $_FILES['qqfile']['size'];
	}

}

