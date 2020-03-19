<?php 

/**
* Class Model
*
* Connexion à la bdd (PDO)
*/
class Model 
{

	protected $bdd;
  public $donneesRead;

  /**
  * @return void
  */
	public function __construct()
	{
  
    try {
      global $config;
        $this->bdd = new PDO( "mysql:host=localhost;dbname={$config['baseDeDonne']};charset=utf8" , $config["userBDD"] , $config["passwordBDD"] );
        if ($config["debug"]) $this->bdd->setAttribute( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
        $this->bdd->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_ASSOC );
        $reponse = $this->bdd->query( 'SET lc_time_names = \'fr_FR\'' );
    }

    catch ( Exception $e ) {
        die( 'Erreur : ' . $e->getMessage() );
    } 
	}
  /**
  * @param string $sql
  * @param boolean $all
  */
  protected function query( $sql , $all=false ){
    $reponse = $this->bdd->query( $sql );
    
    $reponse->execute();

    if ( $all ) $this->donneesRead = $reponse->fetchAll();
    else $this->donneesRead = $reponse->fetch();
  }

  /**
  * @param string $sql
  * @param array $data
  */
  protected function prepare( $sql , $data = null ) {
    $req = $this->bdd->prepare( $sql );
    $req->execute( $data );
  }
}