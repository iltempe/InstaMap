<?php

//configurazione instagram

//esempio https://api.instagram.com/v1/tags/igersprato/media/recent?access_token=1101949809.5b9e1e6.5a5f82c497794486a4919f543d60fb47&count=50&callback=?

//nome del tag e della tabella cartodb
$tagname="";

$instagram = array(
    "api" => "https://api.instagram.com/v1/tags/",
    "endpoint" => $tagname."/media/recent",
    "token" => "",
);

//configurazione CartoDB

function getConfig() {
  $config = array();
  $config['key'] = '';
  $config['secret'] = '';
  $config['email'] = '';
  $config['password'] = '';
  $config['subdomain'] = '';
  return $config;
}


?>