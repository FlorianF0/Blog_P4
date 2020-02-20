<?php 



class Model 
{

	protected $bdd;
  public $donneesRead;
  public $donneesPrepare;


	public function __construct()
	{
  
    try {
      global $config;
        $this->bdd = new PDO("mysql:host=localhost;dbname={$config['baseDeDonne']};charset=utf8", $config["userBDD"], $config["passwordBDD"]);
        if ($config["debug"]) $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    } 
	}

  protected function query($sql, $all=false){
    $reponse = $this->bdd->query($sql);
    
    $reponse->execute();

    if ($all) $this->donneesRead = $reponse->fetchAll();
    else $this->donneesRead = $reponse->fetch();
  }

  protected function prepare($sql){
    $req = $this->bdd->prepare($sql);
    
    $req->execute();
  }
}