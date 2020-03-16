<?php

require_once "model/model.php";

/**
 * 
 */
class UserModel extends Model
{
  /**
  * @param array $argument
  *
  */
  public function __construct( $argument )
  {
    parent::__construct();

    extract( $argument );
    if ( isset( $check ) ) $this->checkLogin( $check );
  }

  /**
  * @param array $data
  *
  */
  private function checkLogin( $data ){
    extract( $data );
    $sql = "SELECT pseudo, email FROM `espace_membres` WHERE `pseudo` = '$identifiant' AND `pass` = '$mdp'";
    $this->query( $sql , false );
  }
}