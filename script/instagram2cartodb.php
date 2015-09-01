<?php

require_once 'cartodb.class.php';
require_once 'config.php';

//date_default_timezone_set($timezone);

date_default_timezone_set("Europe/Rome");

$url = $instagram['api'] . $instagram['endpoint'] . '?access_token=' . $instagram['token'];

// Load images
$jsonString = file_get_contents($url);
$jsonArr = json_decode($jsonString, true);
$images = $jsonArr['data'];

$filelog = (dirname(__FILE__).'/./log.txt');
$cartodb_config = getConfig();

$cartodb = new CartoDBClient($cartodb_config);

if (!$cartodb->authorized) {
  error_log("uauth");
  print 'There is a problem authenticating, check the key and secret.';
  exit();
}

// Find max timestamp
$response = $cartodb->runSql("SELECT MAX(timestamp) FROM ".$tagname);
$max = array_pop($response['return']['rows'])->max;

foreach ($images as $image) {

  if ($image['created_time'] > $max && isset($image['location'])) { 

    $location       = $image['location'];
    $latitude       = $location['latitude'];
    $longitude      = $location['longitude']; 
    $place_name     = isset($location['name']) ? $location['name'] : '';
    $caption        = isset($image['caption']['text']) ? $image['caption']['text'] : '';
  	
	  //formatting chars
	$caption_1 = preg_replace('~(\#)([^\s!,. /()"\'?]+)~', '<a href="tag/$2">#$2</a>', $caption);
	$caption_1 = str_replace("'"," ", $caption_1);
	$caption_1 = str_replace(","," ", $caption_1);
	//$caption_1 = ' ';
	
    $image_thumb    = $image['images']['thumbnail']['url'];
    $image_low      = $image['images']['low_resolution']['url'];
    $image_standard = $image['images']['standard_resolution']['url'];

    if (isset($image['videos'])) {
      $video_low      = $image['videos']['low_resolution']['url'];
      $video_standard = $image['videos']['standard_resolution']['url'];
    }

    $data = array(
      'instagram_id'   => "'" . $image['id'] . "'",
      'the_geom'       => "'SRID=4326;POINT(" . $longitude . " " . $latitude . ")'",
      'type'           => "'" . $image['type'] . "'",
      'link'           => "'" . $image['link'] . "'",     
      'latitude'       => $latitude, 
      'longitude'      => $longitude,
      'place_id'       => isset($location['id']) ? $location['id'] : 0, // null not working 
      'place_name'     => "'" . $place_name . "'",
      'tags'           => "'" . implode(",", $image['tags']) . "'",
      'image_thumb'    => "'" . $image_thumb  . "'",
      'image_low'      => "'" . $image_low  . "'",
      'image_standard' => "'" . $image_standard  . "'",
      'video_low'      => isset($video_low) ? "'" . $video_low . "'" : "''",
      'video_standard' => isset($video_standard) ? "'" . $video_standard . "'" : "''", 
      'created_time'   => "'" . date("Y-m-d H:i:s", $image['created_time']) . "'",
      'timestamp'      => $image['created_time'],  
      'caption'        => "'" . $caption_1 . "'", 
      'username'       => "'" . $image['user']['username'] . "'",
      'user_id'        => $image['user']['id'],
      //'json'           => "'" . json_encode($image) . "'"
    	'json'           => "' '"

    );
	try{
    	$response = $cartodb->insertRow("settembreprato", $data);
	}

catch(Exception $e) {

	file_put_contents($filelog, $e, FILE_APPEND | LOCK_EX);
	file_put_contents($filelog, $caption_1, FILE_APPEND | LOCK_EX);
 }
  }
}

?>