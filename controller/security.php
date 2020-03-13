<?php

class Security
{
  public $get;
  public $post;
  public $uri;
  
  public function __construct( $argument )
  {
    extract( $argument );
    if ( isset( $get ) )  $this->get  = filter_input_array( INPUT_GET ,  $get );
    if ( isset( $post ) ) $this->post = filter_input_array( INPUT_POST , $post );
    if ( isset( $uri ) )  $this->sanitizeUri( $uri );
  }

  private function sanitizeUri( $path ){
    $this->uri = filter_input( INPUT_SERVER , 'REQUEST_URI' , FILTER_SANITIZE_URL );
    if ( $path !== "" ) {
      $this->uri = explode( $path , $this->uri );
      $this->uri = implode( "" , $this->uri );
    }
    $this->uri = explode( "/" , $this->uri );
    $this->uri = array_slice( $this->uri , 1 );
  }

  public function encode( $str ){
    return hash( 'sha256' , $str );
  }
}