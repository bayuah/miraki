<?php
/*
 * Author: Bayu Aditya H. <b@yuah.web.id>
 * Copyright: 2014 - 2015
 * Licence: see LICENCE.txt
 */
 
if (!defined('BASE_DIR')){
	header("HTTP/1.0 501 Not Implemented");
	die("<h1>Direct access not permitted.</h1>");
};


// HTTP.
$http=new http_query();

// Select page type.
$directory=$http->directory_array[0];
if(empty($directory)){
	// If homepage.
	include("./string-homepage.php");
}elseif(
	strtolower($directory)=="minecraftskins"
	|| strtolower($directory)=="minecraftcloaks"
){
	
	// Get filename.
	if(isset($http->path_array[2]) && !empty($http->path_array[2])){
		$file=$http->path_array[2];
	}else{
		// Something doesn't exist.
		http_header::notfound("<h1>What are you looking at?</h1>");
		exit();
	};
	
	// File location.
	switch(strtolower($directory)){
		case "minecraftskins":
			$file_location="{$SKINS_DIR}/{$file}";
			$url="{$MAINSERVER_IP}/".stripslashes("{$MAINSERVER_SKINS_PATH}/{$file}");
		break;
		case "minecraftcloaks":
			$file_location="{$CLOAKS_DIR}/{$file}";
			$url="{$MAINSERVER_IP}/".stripslashes("{$SKINS_DIR}/{$file}");
		break;
	};
	
	// Check if file is exists in the local dir.
	if(!file_exists("$file_location")){
		// Request to main server.
		$ch = curl_init($url);
		$fp = fopen($file_location, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_USERAGENT,
			"Mozilla/5.0 (compatible; Miraki/${VERSION}; +https://github.com/bayuah/miraki)");
		curl_setopt($ch,CURLOPT_HTTPHEADER,array(
			"Host: $MAINSERVER_HOSTNAME"
			));
		curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		fclose($fp);
		
		// If failed to retrieve the file.
		if ($http_code!=200){
			unlink($file_location);
		}
	};
	
	// If readable.
	if(!is_readable("$file_location")){
		
		// Something bad happen.
		if($http_code==404)
			http_header::notfound();
		elseif($http_code!=200)
			http_header::fail_header("Failed retrieving file. Response code: $http_code.");
		else
			http_header::fail_header("Failed to read the local file.");
		exit();
	}else{
		// get the filename extension.
		$ext = substr($file_location, -3);
		
		// set the MIME type.
		switch ($ext) {
			case 'jpg':
				$mime = 'image/jpeg';
				break;
			case 'gif':
				$mime = 'image/gif';
				break;
			case 'png':
				$mime = 'image/png';
				break;
			default:
				$mime = false;
		}
		
		if ($mime) {
			http_header::identifier();
			header('Content-type: '.$mime);
			header('Content-length: '.filesize($file_location));
			$file = @ fopen($file_location, 'rb');
			if ($file) {
				fpassthru($file);
				exit;
			}
		}
	};
	
	exit();
}else{
	// Something doesn't exist.
	http_header::notfound("<h1>What are you looking at?</h1>");
	exit();
};


?>