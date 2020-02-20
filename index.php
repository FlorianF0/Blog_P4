<?php
require_once "controller/security.php";

//config florian
$config = [
  "baseDeDonne" => "blogoc",
  "userBDD"     => "root",
  "passwordBDD" => "",
  "debug"       => true,
  "path"        => "/Blog_P4",
];

// config Lionel
// $config = [
//   "baseDeDonne" => "florian-p4",
//   "userBDD"     => "root",
//   "passwordBDD" => "root",
//   "debug"       => true,
//   "path"        => "",
// ];

// show errors in debug mode
if ($config["debug"]){
  error_reporting(E_ALL | E_STRICT);
  ini_set('display_errors',1);
}

//initialize secure process
$safeData = new Security([
  "uri" => $config["path"],
  "post" =>[
    "contenue"            => FILTER_SANITIZE_STRING,
    "resume"              => FILTER_SANITIZE_STRING,
    "titre"               => FILTER_SANITIZE_STRING,

    "Envoyez_commentaire" => FILTER_SANITIZE_STRING,
    "Supprimer"           => FILTER_SANITIZE_STRING,
    "Valider"             => FILTER_SANITIZE_STRING,
    "Signaler"            => FILTER_SANITIZE_STRING,
    "auteurCommentaire"   => FILTER_SANITIZE_STRING,
    "messageCommentaire"  => FILTER_SANITIZE_STRING,
    "idCommentaire"       => FILTER_SANITIZE_NUMBER_INT,
    "etatCommentaire"     => FILTER_SANITIZE_NUMBER_INT,
    "idChapitre"          => FILTER_SANITIZE_NUMBER_INT,

    "Editer_chapitre"     => FILTER_SANITIZE_STRING,
    "Supprimer_chapitre"  => FILTER_SANITIZE_STRING,
    "slug"                => FILTER_SANITIZE_STRING,

  ]
]);

//define requested uri
$uri = $safeData->uri;

//define main route
switch ($uri[0]){
  case "admin" : 
    require_once "controller/back.php";
    $page = new Back($uri);
    break;
  default:
    require_once "controller/front.php";
    $page = new Front($uri);
    break;
}

echo $page->html;