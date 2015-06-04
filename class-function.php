<?php
/*
 * Author: Bayu Aditya H. <b@yuah.web.id>
 * Copyright: 2015
 * Licence: see LICENCE.txt
 */

if (!defined('BASE_DIR')){
	header("HTTP/1.0 501 Not Implemented");
	die("<h1>Direct access not permitted.</h1>");
};

/*
 * Classes.
 */
class i18n{
	protected $language;
	protected $dirname;
	protected $domain;
	
	public function __construct($language, $dirname, $domain="messages") {
		$this->language = $language;
		$this->dirname = $dirname;
		$this->domain = $domain;
	}
	
	public function init(){
		// I18N support information.
		putenv("LANG={$this->language}"); 
		setlocale(LC_ALL, $this->language);
		
		// Set the text domain.
		bindtextdomain($this->domain, $this->dirname); 
		textdomain($this->domain);
	}
	
	public function gettext($message){
		$result=gettext($message);
		
		// If empty, then return original message.
		if(empty($result))
			return $message;
		else return $result;
	}
}
/* 
 * HTTP header handler.
 */
class http_header{
	
	public static function identifier(){
		header("X-Cache: Miraki");
	}
	
	// Something bad happen.
	public static function fail($message=NULL){
		http_header::identifier();
		header("HTTP/1.0 500 Internal Server Error");
		if(!empty($message)){
			echo $message;
		}
	}

	// Something bad happen.
	public static function fail_header($message=NULL){
		http_header::identifier();
		header("HTTP/1.0 500 Internal Server Error");
		if(!empty($message)){
			header("X-Message: $message");
		}
	}
	
	// Something doesn't exist.
	public static function notfound($message=NULL){
		http_header::identifier();
		header("HTTP/1.0 404 Not Found");
		if(!empty($message)){
			echo $message;
		}else{
			echo "<h1>Not found!</h1>";
		}
	}
	
}
/*
 * Template controller.
 */
class template{
	protected $file;
	protected $values = array();
	protected $output=NULL;
	
	public function __construct($file) {
		
		if (!file_exists($file)) {
			
			// Call handler.
			http_header::fail("Fail to load template file ($this->file).");
			
			// Stop execution.
			exit();
		}else{
			
			// Assign to file variable.
			$this->file = $file;
		}
	}
	
	public function set($key, $value) {
		$this->values[$key] = $value;
	}
	
	public function reset(){
		$this->output=NULL;
	}
	
	public function subtemplate($key, $file){
		if (!file_exists($file)) {
			
			// Call handler.
			http_header::fail("Fail to load sub-template file ($this->file).");
			
			// Stop execution.
			exit();
		}
		
		// Set sub template as key
		// and prevent result assign as global output.
		$this->values[$key]=$this->built($file, false);
		
	}
	
	// Built key.
	public function built($file=NULL, $to_global=true){
		$html=NULL;
		
		// If file not defined.
		if(is_null($file)){
			$html = file_get_contents($this->file);
		}else{
			$html = file_get_contents($file);
		}
		
		// Replace all key to their value.
		foreach ($this->values as $key => $value) {
			$tagToReplace = "[%$key]";
			$html = str_ireplace($tagToReplace, $value, $html);
		}
		
		// Assign value.
		if($to_global){
			$this->output=$html;
		};
		
		return $html;
	}
	
	/*
	 * HTML output.
	 * @param: $echo (bool) If true, then print result to STDOUT.
	 *                      Default: false;
	 */
	public function output($echo=false) {
		
		// Result.
		if($echo)
			echo $this->output;
		return $this->output;
	}
}
class http_query{
	public $protocol=NULL;
	public $scheme=NULL;
	public $hostname=NULL;
	public $request_uri=NULL;
	public $request_uri_array=array();
	public $query_string=NULL;
	public $request_array=array();
	public $query_string_array=array();
	public $directory_array=array();
	public $path=NULL;
	public $path_array=array();
	
	public function __construct(){
		
		// Basic HTTP.
		$this->protocol     = $_SERVER['SERVER_PROTOCOL'];
		$this->scheme = (
				!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
			) ? "https" : "http";
		$this->hostname      = $_SERVER['HTTP_HOST'];
		$this->request_uri   = stripslashes($_SERVER['REQUEST_URI']);
		$this->query_string  = $_SERVER['QUERY_STRING'];
		$this->request_array = $_REQUEST;
		
		// Prepare.
		parse_str($this->query_string, $this->query_string_array);
		$this->request_uri_array=explode("/",$this->request_uri);
		
		$directory=array_filter($this->request_uri_array);
		$directory=current($directory);
		$this->directory_array=explode("/",$directory);
		
		$url=parse_url($this->request_uri);
		$this->path=$url['path'];
		$this->path_array=explode("/",$url['path']);
	}
}
/*
 * Function list.
 */
function __($message){
	global $i18n;
	return $i18n->gettext($message);
};
function _e($message){
	global $i18n;
	echo $i18n->gettext($message);
	return $i18n->gettext($message);
};
?>