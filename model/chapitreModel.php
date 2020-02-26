<?php

require_once "model/model.php";

/**
 * 
 */
class ChapitreModel extends Model
{
  
  function __construct($argument)
  {
    parent::__construct();
    global $safeData;

    if (isset($argument["slug"]))   $this->getDataFromSlug($argument["slug"]);
    if (isset($argument["id"]))     $this->getDataFromId($argument["id"]);
    if (isset($argument["start"]))  $this->getList($argument["start"], $argument["quantity"]);

    // die(var_dump($safeData->post['titre']));

   


    if ($safeData->post['Supprimer_chapitre'] !== null) {
      $this->deleteChapitre($safeData->post['idChapitre']);
    }
    
    if ($safeData->post['Editer_chapitre'] !== null) {
      $this->editChapitre($safeData->post['titre'], $safeData->post['resume'], $safeData->post['slug'], $safeData->post['contenu']);
    }

    if($safeData->post['Ajouter_chapitre'] !== null) {
      if(isset($safeData->post['titre']) AND isset($safeData->post['resume']) AND isset($safeData->post['slug']) AND isset($safeData->post['contenu'])){
        $this->createChapitre($safeData->post['titre'], $safeData->post['resume'], $safeData->post['slug'], $safeData->post['contenu']);
      }
    }
  }


  private function getDataFromId($id){
    $sql = "SELECT id, auteur, titre, contenu, resume slug, DATE_FORMAT(dateAjout, '%d %M %Y') AS dateAjout, DATE_FORMAT(dateModif, '%d %M %Y') AS dateModif FROM `chapitre` WHERE id = '$id'";
    $this->query($sql);
  }


  private function getDataFromSlug($slug){
    $sql = "SELECT id, auteur, titre, contenu, resume, slug, DATE_FORMAT(dateAjout, '%d %M %Y') AS dateAjout, DATE_FORMAT(dateModif, '%d %M %Y') AS dateModif FROM `chapitre` WHERE slug = '$slug'";
    $this->query($sql);
  }

  private function getList($start, $quantity){
    $sql = "SELECT `id` as '{{ id }}', `titre` as '{{ titre }}',`resume` as '{{ resume }}', DATE_FORMAT(dateAjout, '%d %M %Y') as '{{ dateAjout }}',`slug`  as '{{ slug }}' FROM `chapitre` ORDER BY `chapitre`.`id`  DESC LIMIT $start, $quantity";
    $this->query($sql, true);
  }

  private function deleteChapitre($id){
    $sql = "DELETE FROM `chapitre` WHERE `id` = :id";
    $this->prepare($sql, ["id"=>$id]);
  }

  private function createChapitre($titre, $resume, $slug, $contenu) {
    $sql = "INSERT INTO `chapitre` (titre, auteur, resume, slug, contenu, dateAjout) VALUES (:titre, 'Jean Forteroche', :resume, :slug, :contenu, NOW())";

    $this->prepare($sql,compact("titre", "resume", "slug", "contenu"));
  }

  private function editChapitre($titre, $resume, $slug, $contenu) {
    $sql = "UPDATE `chapitre` SET titre = :titre,  resume = :resume, contenu = :contenu, dateModif = NOW() WHERE slug = '$slug' ";

    $this->prepare($sql,compact("titre", "resume", "contenu"));

  }
}