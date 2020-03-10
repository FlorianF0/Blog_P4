<?php

require_once "model/model.php";

/**
 * 
 */
class UserModel extends Model
{
  
  function __construct($argument)
  {
    parent::__construct();
    extract($argument);
    if (isset($check)) $this->checkLogin($check);
  }

  private function checkLogin($data){
    extract($data);
    $sql = "SELECT pseudo, email FROM `espace_membres` WHERE `pseudo` = '$identifiant' AND `pass` = '$mdp'";
    $this->query($sql, false);
  }
}