<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/


// This function helps us to get the translated phrase from the file. If it does not exist this function will save the phrase and by default it will have the same form as given
if ( ! function_exists('get_phrase'))
{
	function get_phrase($phrase = '') {
	    
	    return ucfirst(str_replace('_', ' ', $phrase));
		/*$CI = get_instance();
		$CI	=&	get_instance();
		$CI->load->database();
		$language_code = get_settings('language');
		$key = str_replace(" ", "_", strtolower(preg_replace('/\s+/', '_', $phrase)));
		$langArray = openJSONFile($language_code);

		if ($langArray && !array_key_exists($key, $langArray) ) {
            $langArray[$key] = ucfirst(str_replace('_', ' ', $key));
            $jsonData = json_encode($langArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            file_put_contents(APPPATH.'language/'.$language_code.'.json', stripslashes($jsonData));
        }

		return $langArray[$key];*/
	}
}

// This function helps us to decode the language json and return that array to us
if ( ! function_exists('openJSONFile'))
{
	function openJSONFile($code)
	{
		$jsonString = [];
		if (file_exists(APPPATH.'language/'.$code.'.json')) {
			$jsonString = file_get_contents(APPPATH.'language/'.$code.'.json');
			$jsonString = json_decode($jsonString, true);
		}
		return $jsonString;
	}
}

// This function helps us to create a new json file for new language
if ( ! function_exists('saveDefaultJSONFile'))
{
	function saveDefaultJSONFile($language_code){
		$language_code = strtolower($language_code);
		if(file_exists(APPPATH.'language/'.$language_code.'.json')){
			$newLangFile 	= APPPATH.'language/'.$language_code.'.json';
			$enLangFile   = APPPATH.'language/english.json';
			copy($enLangFile, $newLangFile);
		}else {
			$fp = fopen(APPPATH.'language/'.$language_code.'.json', 'w');
			$newLangFile = APPPATH.'language/'.$language_code.'.json';
			$enLangFile   = APPPATH.'language/english.json';
			copy($enLangFile, $newLangFile);
			fclose($fp);
		}
	}
}

// This function helps us to update a phrase inside the language file.
if ( ! function_exists('saveJSONFile'))
{
	function saveJSONFile($language_code, $updating_key, $updating_value){
		$jsonString = [];
		if(file_exists(APPPATH.'language/'.$language_code.'.json')){
			$jsonString = file_get_contents(APPPATH.'language/'.$language_code.'.json');
			$jsonString = json_decode($jsonString, true);
			$jsonString[$updating_key] = $updating_value;
		}else {
			$jsonString[$updating_key] = $updating_value;
		}
		$jsonData = json_encode($jsonString, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		file_put_contents(APPPATH.'language/'.$language_code.'.json', stripslashes($jsonData));
	}
}


// ------------------------------------------------------------------------
/* End of file language_helper.php */
