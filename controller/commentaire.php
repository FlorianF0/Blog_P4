<?php

require_once "model/commentaireModel.php";
require_once "view/view.php";

/**
 * 
 */
class Commentaire
{

  public  $data;
  public  $id = null;
  public  $etat;
  public  $auteurCommentaire;
  public  $contenuCommentaire;
  private $datePublication;
  private $idChapitre;


  /**
   * [__construct description]
   * @param Array $uri [description]
   */
  public function __construct($source)
  {
    // on récupère les donnees de la bdd, et ont les hydrates
    $this->data = new CommentaireModel($source);
    $this->data = $this->data->donneesRead;
    $this->hydrate($this->data);
    
    if(isset($source["id"])) $this->idChapitre = $source["id"];
        
  }

  public function hydrate($donnees)
  {
    if (!$donnees) return;
    if (!isset($donnees[0])) 
    {
      foreach ($donnees as $key => $value) {
        $this->$key = $value;
      }
      $donnees = null;
    }
  }


  public function afficheCommentaires(){


    // on crée une boucle pour connaitre l'etat du commentaire ds la bdd, puis on lui associe les boutons de son état
    foreach ($this->data as $key => $value) {
      $this->data[$key]["{{ idChapitre }}"]  = $this->idChapitre;

      if($value["{{ etat }}"] === "0")  {
        $this->data[$key]["{{ buttons }}"]  = "";
      }  
      if($value["{{ etat }}"] === "1") {
        $this->data[$key]["{{ buttons }}"]  = $this->bouttonFormulaire("Signaler");
      }
      if($value["{{ etat }}"] === "2") {
        $this->data[$key]["{{ buttons }}"]  = file_get_contents("./templates/reportedAck.html");
      }
      if($value["{{ etat }}"] === "3") {
        $this->data[$key]["{{ buttons }}"]  = "";
      }
    }
    return $this->finaliseAfficheCommentaire($this->data);
  }


  public function afficheCommentairesBack(){
    
    // on crée une boucle pour connaitre l'etat du commentaire ds la bdd, puis on lui associe les boutons de son état

    foreach ($this->data as $key => $value) {
      $this->data[$key]["{{ idChapitre }}"]  = $this->idChapitre;

      if($value["{{ etat }}"] === "0" || $value["{{ etat }}"] === "2") {
        $this->data[$key]["{{ buttons }}"]  = $this->bouttonFormulaire("Valider");
        $this->data[$key]["{{ buttons }}"] .= $this->bouttonFormulaire("Supprimer");
      }
      if($value["{{ etat }}"] === "1" || $value["{{ etat }}"] === "3") {
        $this->data[$key]["{{ buttons }}"]  = "";
      }
    }
    return $this->finaliseAfficheCommentaire($this->data);
  }


  private function bouttonFormulaire($texte){
    return new View(
      [
        "{{ texte }}" => $texte,
      ],
      "generateurBouttonFormulaire"
    );
  }
  
  private function finaliseAfficheCommentaire($data){

    if(empty($this->data))  $partialListeCommentaire = "Aucun commentaire";

    else $partialListeCommentaire = new View(
      $data,
      "partialListeCommentaire"
    );

    return new View(
      [
        "{{ partialListeCommentaire }}"  => $partialListeCommentaire,
        "{{ idChapitre }}"               => $this->idChapitre
      ],
      "partialCommentaire"
    );
  }



}