<?php
/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	April 05, 2012
 ^
 + Project: 		JS Jobs
 ^ 
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JSJobspayfast
{
	Private $paymentconfig = '';
	Private $app = '';
	function __construct()
	{
		$this->paymentconfig = $this->loadConfig();
		$this->app = JFactory::getApplication();
	}
	function generateRequest($orderid){
		$order = $this->getOrderById($orderid);
                if($this->paymentconfig['testmode_payfast'] == 1) $action = 'https://sandbox.payfast.co.za/eng/process';
                else $action = 'https://www.payfast.co.za/eng/process';
                
		if(!empty($order)){
		?>

		<form action="<?php echo $action; ?>" method="post" name="frmPay" id="frmPay">
		
                        <!-- Receiver Details -->
                        <input type="hidden" name="merchant_id" value="<?php echo $this->paymentconfig['merchantid_payfast']; ?>">
                        <input type="hidden" name="merchant_key" value="<?php echo $this->paymentconfig['merchantkey_payfast']; ?>">
                        <input type="hidden" name="return_url" value="<?php echo JURI::root().$this->paymentconfig['notifyurl_payfast']; ?>">
                        <input type="hidden" name="cancel_url" value="<?php echo $this->paymentconfig['cancelurl_payfast']; ?>">

                        <!-- Payer Details -->
                        <input type="hidden" name="name_first" value="<?php echo $order->username; ?>">
                        <input type="hidden" name="name_last" value="<?php echo $order->username; ?>">
                        <input type="hidden" name="email_address" value="<?php echo $order->useremail; ?>">

                        <!-- Transaction Details -->
                        <input type="hidden" name="m_payment_id" value="<?php echo $orderid; ?>">
                        <input type="hidden" name="amount" value="<?php echo $order->paidamount; ?>">
                        <input type="hidden" name="item_name" value="<?php echo $order->packagetitle; ?>">
                        <input type="hidden" name="item_description" value="<?php echo $order->packagetitle; ?>">

                        <!-- Transaction Options -->
                        <input type="hidden" name="email_confirmation" value="1">

                        <!-- Security -->
                        <input type="hidden" name="signature" value="">

                        <script language=Javascript>
                            //document.adminForm.shopping_url.value = window.location.href;
                            document.frmPay.submit();
                        </script>
		</form>
<?php
		}else{
		}
	}
	function onPaymentNotification(){
		
		$data = $this->getData();
		if($data != false){
			$date = date("Y-m-d H:i:s");
			$db = JFactory::getDBO();
			$query = "UPDATE `#__js_job_sellerpaymenthistory` AS payment SET payment.payer_amount = ".$db->quote($data['payer_paidamount']).", payment.payer_firstname = ".$db->quote($data['payer_firstname']).", payment.payer_lastname = ".$db->quote($data['payer_lastname']).", payment.payer_email = ".$db->quote($data['payer_emailaddress']).", payment.payer_itemname = ".$db->quote($data['payer_itemname']).", payment.payer_status = 1, payment.transactionverified = 1, payment.verifieddate = ".$db->quote($date)." WHERE payment.id = ".$data['orderid'];
			$db->setQuery($query);
			if(!$db->query()) return false;
		}
		$this->app->redirect(JRoute::_($this->paymentconfig['returnurl_payfast']));
	}
	function getOrderById($orderid){
		if(!is_numeric($orderid)) return false;
		$db = JFactory::getDBO();
		$query = "SELECT pro_order.*,currency.code AS currencycode,user.name AS username, user.email AS useremail
					FROM `#__js_job_paymenthistory` AS pro_order 
					JOIN `#__js_job_currencies` AS currency ON currency.id = pro_order.currencyid
					JOIN `#__users` AS user ON user.id = pro_order.uid
					WHERE pro_order.id = ".$orderid;
		$db->setQuery($query);
		$result = $db->loadObject();
		if(!empty($result)){
			if(empty($result->currencycode)) $result->currencycode = "USD";
			return $result;
		}else $this->app->redirect(JRoute::_ ('index.php?option=com_jsjobs&view=employer&layout=norecordfound'));
	}
	function loadConfig(){
		$db = JFactory::getDBO();
		$query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configfor = 'payfast'";
		$db->setQuery($query);
		$config = $db->loadObjectList();
		foreach($config AS $conf){
			$return[$conf->configname] = $conf->configvalue;
		}
		return $return;
	}
	function getData(){
		
                // Variable Initialization
                $pmtToken = isset( $_GET['pt'] ) ? $_GET['pt'] : null;

                if( !empty( $pmtToken ) )
                {
                    // Variable Initialization
                    $error = false;
                    $authToken = $this->paymentconfig['pdtkey_payfast'];
                    $req = 'pt='. $pmtToken .'&at='. $authToken;
                    $data = array();
                    if($this->paymentconfig['testmode_payfast'] == 1 ) $host = 'sandbox.payfast.co.za';
                    else $host = 'www.payfast.co.za';
                    
                    //// Connect to server
                    if( !$error )
                    {
                        // Construct Header
                        $header = "POST /eng/query/fetch HTTP/1.0\r\n";
                        $header .= 'Host: '. $host ."\r\n";
                        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
                        $header .= 'Content-Length: '. strlen( $req ) ."\r\n\r\n";

                        // Connect to server
                        $socket = fsockopen( 'ssl://'. $host, 443, $errno, $errstr, 10 );

                        if( !$socket )
                        {
                            $error = true;
                            print( 'errno = '. $errno .', errstr = '. $errstr );
                        }
                    }

                    //// Get data from server
                    if( !$error )
                    {
                        // Send command to server
                        fputs( $socket, $header . $req );

                        // Read the response from the server
                        $res = '';
                        $headerDone = false;

                        while( !feof( $socket ) )
                        {
                            $line = fgets( $socket, 1024 );

                            // Check if we are finished reading the header yet
                            if( strcmp( $line, "\r\n" ) == 0 )
                            {
                                // read the header
                                $headerDone = true;
                            }
                            // If header has been processed
                            else if( $headerDone )
                            {
                                // Read the main response
                                $res .= $line;
                            }
                        }

                        // Parse the returned data
                        $lines = explode( "\n", $res );
                    }

                    //// Interpret the response from server
                    if( !$error )
                    {
                        $result = trim( $lines[0] );

                        // If the transaction was successful
                        if( strcmp( $result, 'SUCCESS' ) == 0 )
                        {
                            // Process the reponse into an associative array of data
                            for( $i = 1; $i < count( $lines ); $i++ )
                            {
                                list( $key, $val ) = explode( "=", $lines[$i] );
                                $data[urldecode( $key )] = stripslashes( urldecode( $val ) );
                            }
                        }
                        // If the transaction was NOT successful
                        else if( strcmp( $result, 'FAIL' ) == 0 )
                        {
                            // Log for investigation
                            $error = true;
                            // 
                        }
                    }

                    //// Process the payment
                    if( !$error )
                    {
                        // Get the data from the new array as needed
                        $returndata['payer_firstname']   = $data['name_first'];
                        $returndata['payer_lastname']    = $data['name_last'];
                        $returndata['payer_paidamount'] = $data['amount_gross'];
                        $returndata['payer_emailaddress'] = $data['email_address'];
                        $returndata['payer_itemname'] = $data['item_name'];
                        $returndata['orderid'] = $data['m_payment_id'];

                        // Once you have access to this data, you should perform a number of
                        // checks to ensure the transaction is "correct" before processing it.
                        // - Check the payment_status is Completed
                        // - Check the pf_transaction_id has not already been processed
                        // - Check the merchant_id is correct for your account

                        // Process payment
                        // 
                    }
                    // Close socket if successfully opened
                    if( $socket )
                        fclose( $socket );
                    if(isset($returndata)) return $returndata;
                    else{
                        $returndata = false;
                        return $returndata;
                    }
					
                }else{
                    $returndata = false;
                    return $returndata;
                }                
	}
}
