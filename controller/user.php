<?php

class User {
	
	public function __construct() {

	}

	public function afficheFormConnexion()
  	{
    return new View(
      [],
      "partialFormConnexion"
      );
  	}


}