<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */


if ( ! function_exists('is_active'))
{

	function is_active($selected_page_name = "") {
        $CI	=&	get_instance();
        $CI->load->library('session');

        if ($CI->session->userdata('last_page') == $selected_page_name) {
            return "active";
        }else {
            return "";
        }
	}
}

if ( ! function_exists('is_multi_level_active'))
{
    function is_multi_level_active($selected_pages = "", $item = "") {
        $CI	=&	get_instance();
        $CI->load->library('session');

		for ($i = 0; $i < sizeof($selected_pages); $i++) {
			if ($CI->session->userdata('last_page') == $selected_pages[$i]) {
	            if ($item == 1) {
	                return "opened active";
	            }else {
	                return "opened";
	            }
	        }
		}
		return "";
    }
}

if (! function_exists('get_settings')) {
  function get_settings($key = '') {
    $CI	=&	get_instance();
    $CI->load->database();

    $CI->db->where('key', $key);

    $value = 0;
    try {
        $row = $CI->db->get('settings')->row();
        if (is_object($row)) {
            $value = $row->value;
        }
    } catch (Exception $e) {}

    return $value;
  }
}

if (! function_exists('currency')) {
  function currency($price = "") {
    $CI	=&	get_instance();
    $CI->load->database();
		if ($price != "") {
			$CI->db->where('key', 'system_currency');
			$currency_code = $CI->db->get('settings')->row()->value;

			$CI->db->where('code', $currency_code);
			$symbol = $CI->db->get('currency')->row()->symbol;

			$CI->db->where('key', 'currency_position');
			$position = $CI->db->get('settings')->row()->value;

            $CI->db->where('key', 'thousand_separator');
            $thousand_separator = $CI->db->get('settings')->row()->value;

            $CI->db->where('key', 'decimal_separator');
            $decimal_separator = $CI->db->get('settings')->row()->value;

            $CI->db->where('key', 'number_of_decimal');
            $number_of_decimal = $CI->db->get('settings')->row()->value;

            if (!empty($thousand_separator) && !empty($decimal_separator)) {
                $price = number_format($price, $number_of_decimal, $decimal_separator, $thousand_separator);
            }

			if ($position == 'right') {
				return $price.$symbol;
			}elseif ($position == 'right-space') {
				return $price.' '.$symbol;
			}elseif ($position == 'left') {
				return $symbol.$price;
			}elseif ($position == 'left-space') {
				return $symbol.' '.$price;
			}
		}
  }
}
if (! function_exists('currency_code_and_symbol')) {
  function currency_code_and_symbol($type = "") {
    $CI	=&	get_instance();
    $CI->load->database();
		$CI->db->where('key', 'system_currency');
		$currency_code = $CI->db->get('settings')->row()->value;

		$CI->db->where('code', $currency_code);
		$symbol = $CI->db->get('currency')->row()->symbol;
		if ($type == "") {
			return $symbol;
		}else {
			return $currency_code;
		}

  }
}

if (! function_exists('get_frontend_settings')) {
  function get_frontend_settings($key = '') {
    $CI	=&	get_instance();
    $CI->load->database();

    $CI->db->where('key', $key);
    $result = $CI->db->get('frontend_settings')->row()->value;
    return $result;
  }
}

if ( ! function_exists('slugify'))
{
  function slugify($text) {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
            return 'n-a';
        return $text;
    }
}

if ( ! function_exists('get_video_extension'))
{
    // Checks if a video is youtube, vimeo or any other
    function get_video_extension($url) {
        if (strpos($url, '.mp4') > 0) {
            return 'mp4';
        } elseif (strpos($url, '.webm') > 0) {
            return 'webm';
        } else {
            return 'unknown';
        }
    }
}

if ( ! function_exists('is_expired'))
{
    function is_expired() {
        $CI	=&	get_instance();
        $CI->load->database();

        $CI->db->where('key', 'purchase_code');

        $value = null;
        try {
            $row = $CI->db->get('settings')->row();
            if (is_object($row)) {
                $value = $row->value;
            }
        } catch (Exception $e) {}

        if (!empty($value)) {
            $splits = explode("-", $value);
            $length = count($splits);
            $end = end($splits);
            $str_end = str_split($end, 2);
            $time = $splits[$length-3].$splits[$length-2].$str_end[0];

            return (time() > $time);
        }
        return false;
    }
}

// ------------------------------------------------------------------------
/* End of file user_helper.php */
/* Location: ./system/helpers/common.php */
