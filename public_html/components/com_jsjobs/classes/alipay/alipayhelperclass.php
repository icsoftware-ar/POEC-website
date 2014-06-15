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

class alipayhelperclass {
	var $_order_params;
	var $_security_code;
	var $_sign_type;
	var $_partner_id;
	var $_transport;
	var $_gateway;
	var $_notify_gateway;

	function set_order_params($order_params) {
		if(is_array($order_params)) {
			$tmp_array = array();
			foreach($order_params as $key=>$value) {
				if($value != '' && $key != 'sign' && $key != 'sign_type') {
					$tmp_array[$key] = $value;
				}
			}
			ksort($tmp_array);
			reset($tmp_array);
			$this->_order_params = $tmp_array;
		} else {
			return false;
		}
	}

	function set_security_code($security_code) {
		$this->_security_code = $security_code;
	}

	function set_sign_type($sign_type) {
		$this->_sign_type = strtoupper($sign_type);
	}

	function set_partner_id($partner_id) {
		$this->_partner_id = $partner_id;
	}

	function set_transport($transport) {
		$this->_transport = strtolower($transport);
		if($this->_transport == 'https') {
			$this->_gateway = 'http://www.alipay.com/cooperate/gateway.do?';
			$this->_notify_gateway = $this->_gateway;
		} elseif($this->_transport == 'http') {
			$this->_gateway = 'http://www.alipay.com/cooperate/gateway.do?';
			$this->_notify_gateway = 'http://notify.alipay.com/trade/notify_query.do?';
		}
	}

	function _sign($params) {
		$params_str = '';
		foreach($params as $key => $value) {
			if($params_str == '') {
				$params_str = "$key=$value";
			} else {
				$params_str .= "&$key=$value";
			}
		}
		if($this->_sign_type == 'MD5') {
			return md5($params_str . $this->_security_code);
		}
	}

	function create_payment_link() {
		$params_str = '';
		foreach($this->_order_params as $key => $value) {
			$params_str .= "$key=" . urlencode($value) . "&";
		}
		return $this->_gateway . $params_str . 'sign=' . $this->_sign($this->_order_params) . '&sign_type=' . $this->_sign_type;
	}
}
?>
