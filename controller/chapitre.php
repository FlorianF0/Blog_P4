<?php

require_once "model/chapitreModel.php";
require_once "view/view.php";

/**
 * 
 */
class Chapitre
{

  public  $auteur;
  public  $contenu;
  public  $data;
  public  $html;
  public  $id = null;
  public  $titre;
  public  $slug;
  public  $resume;
  private $dateAjout;
  private $dateModif;



  /**
   * [__construct description]
   * @param Array $uri [description]
   */
  public function __construct($source)
  {
    global $safeData;  

    // on récupère les donnees de la bdd, et ont les hydrates
    $this->data = new ChapitreModel($source);

    if ($safeData->post["Supprimer_chapitre"]) return $this->messageDeleteChapitre();
    if ($safeData->post["Ajouter_chapitre"])   return $this->messageAjoutChapitre();
    if ($safeData->post["Editer_chapitre"])    return $this->messageEditChapitre();

    $this->data = $this->data->donneesRead;
    
    if (isset($source["start"])) return $this->html = $this->afficheListeChapitre($source);
    $this->hydrate($this->data);
  }

  public function hydrate($donnees){

    if (!$donnees) return;
    if (!isset($donnees[0])) 
    {
      foreach ($donnees as $key => $value) {
        $this->$key = $value;
      }
      $donnees = null;
    }
  }


  public function afficheContenueChapitre(){
    
    // on regarde si la date de Modif est nul, si elle est nul on met dateAjout, sinon on met DateModif
    if ($this->dateModif !== null) {
      $parution = "modifié";
      $date     = $this->dateModif;
    }
    else {
      $parution = "publié";
      $date     = $this->dateAjout;
    }

    // on apelle la vue, pour qu'elle remplace les mots dans partials par les donnees de la bdd que l'on a hydrater ds le constructeur
    return new View(
      [
        "{{ id }}"       => $this->id,
        "{{ titre }}"    => $this->titre,
        "{{ parution }}" => $parution,
        "{{ dateAjout }}"=> $date,
        "{{ auteur }}"   => $this->auteur,
        "{{ contenu }}"  => htmlspecialchars_decode($this->contenu)
      ],
      "front/partialContenueChapitre"
    );
  }

  public function afficheContenueChapitreBack(){
    
    // on regarde si la date de Modif est nul, si elle est nul on met dateAjout, sinon on met DateModif
    if ($this->dateModif !== null) {
      $parution = "modifié";
      $date     = $this->dateModif;
    }
    else {
      $parution = "publié";
      $date     = $this->dateAjout;
    }


    // on apelle la vue, pour qu'elle remplace les mots dans partials par les donnees de la bdd que l'on a hydrater ds le constructeur
    return new View(
      [
        "{{ id }}"       => $this->id,
        "{{ titre }}"    => $this->titre,
        "{{ slug }}"     => $this->slug,
        "{{ parution }}" => $parution,
        "{{ dateAjout }}"=> $date,
        "{{ auteur }}"   => $this->auteur,
        "{{ contenu }}"  => htmlspecialchars_decode($this->contenu)
      ],
      "back/partialContenueChapitreBack"
    );
  }

  public function afficheListeChapitre($infos)
  {
    global $uri;
    if ($uri[0] === "admin") $template = "back/partialListeChapitreBack";
    else $template = "front/partialListeChapitre";
    $this->titre = "liste des {$infos['quantity']} derniers chapitres à partir de {$infos['start']}";

    return new View(
      $this->data,
      $template
    );
  } 

  public function afficheEditChapitre() {

    return new view ([
        "{{ id }}"       => $this->id,
        "{{ titre }}"    => $this->titre,
        "{{ resume }}"   => $this->resume,
        "{{ slug }}"     => $this->slug,
        "{{ contenu }}"  => $this->contenu
      ],
      "back/partialEditChapitre"
    );
  }

  private function messageDeleteChapitre(){
    $this->html = file_get_contents("./templates/back/messageDeleteChapitre.html");
    $this->titre = 'Chapitre-delete';
  }

  private function messageAjoutChapitre(){
    $this->html = file_get_contents("./templates/back/messageAjoutChapitre.html");
  }

  private function messageEditChapitre(){
    $this->html = file_get_contents("./templates/back/messageEditChapitre.html");
  }
}