<?php

//configurazione instagram

//esempio https://api.instagram.com/v1/tags/igersprato/media/recent?access_token=1101949809.5b9e1e6.5a5f82c497794486a4919f543d60fb47&count=50&callback=?

//nome del tag e della tabella cartodb
$tagname="settembreprato";

$instagram = array(
    "api" => "https://api.instagram.com/v1/tags/",
    "endpoint" => $tagname."/media/recent",
    "token" => "1101949809.5b9e1e6.5a5f82c497794486a4919f543d60fb47",
);

//configurazione CartoDB

function getConfig() {
  $config = array();
  $config['key'] = '5CuhpntXzv94s2EvC8PsfkmkH8TZKEsnP67UOznz';
  $config['secret'] = 'oXu4HQgJnAqjpbnVJsBcbUhAopL7Anz8EehgVdB6';
  $config['email'] = 'pratosmart@gmail.com';
  $config['password'] = 'matteo80tempe';
  $config['subdomain'] = 'pratosmart';
  return $config;
}


?>