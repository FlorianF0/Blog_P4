<?php

/**
 * Class Security
 *
 * Permet de sécruriser les donnees du site.
 */
class Security
{

  public $get;
  public $post;
  public $uri;
  
    /**
   * Sécurise les données en POST, GET, URI. 
   * @param string $argument
   *
   * @return string $get|$post|$uri
   */
  public function __construct( $argument )
  {
    extract( $argument );
    if ( isset( $get ) )  $this->get  = filter_input_array( INPUT_GET ,  $get );
    if ( isset( $post ) ) $this->post = filter_input_array( INPUT_POST , $post );
    if ( isset( $uri ) )  $this->sanitizeUri( $uri );
  }

    /**
   * Récupère l'uri, le nettoie et l'intègre ds un tableau. 
   * @param string $path 
   *
   * @return array $uri
   */
  private function sanitizeUri( $path ){
    $this->uri = filter_input( INPUT_SERVER , 'REQUEST_URI' , FILTER_SANITIZE_URL );
    if ( $path !== "" ) {
      $this->uri = explode( $path , $this->uri );
      $this->uri = implode( "" , $this->uri );
    }
    $this->uri = explode( "/" , $this->uri );
    $this->uri = array_slice( $this->uri , 1 );
  }

  /**
   * Encode les mdp. 
   * @param string $str 
   *
   * @return string $str
   */
  public function encode( $str ){
    return hash( 'sha256' , $str );
  }
}