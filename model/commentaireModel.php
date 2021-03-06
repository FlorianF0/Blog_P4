<?php

require_once "model/model.php";

/**
 * 
 */
class CommentaireModel extends Model
{
  
  function __construct($argument)
  {
    parent::__construct();
    global $safeData;

    // si le bouton Valider/Supprimer/Signaler est activé (non nul), on  appel la fonction associé
    if ($safeData->post['idCommentaire'] !== null) {
      if ($safeData->post['Valider'] !== null) {
        $this->updateState($safeData->post['idCommentaire'], $safeData->post['etatCommentaire']);
            die(var_dump($safeData->post['etatCommentaire']));
      }

      if ($safeData->post['Supprimer'] !== null) {
        $this->deleteComment($safeData->post['idCommentaire']);
      }


      if ($safeData->post['Signaler'] !== null) {
        $this->updateState($safeData->post['idCommentaire'], $safeData->post['etatCommentaire']);
      }

    }

    if ($safeData->post['Envoyez_commentaire'] !== null) {

      if(isset($safeData->post['auteurCommentaire']) AND isset($safeData->post['messageCommentaire'])) {
        $this->createComment($safeData->post['auteurCommentaire'], $safeData->post['messageCommentaire'], $safeData->post['idChapitre']);
      } 
    }

    // récupére la liste des commentaires
    if (isset($argument["id"])) $this->getDataFromChapitreId($argument["id"]);


  }


  private function getDataFromChapitreId($id){
    $sql = "SELECT id AS '{{ id }}', etat, id_chapitre AS '{{ idChapitre }}', auteurCommentaire AS '{{ auteurCommentaire }}', contenuCommentaire AS '{{ contenuCommentaire }}', DATE_FORMAT(datePublication, '%d %M %Y') AS '{{ datePublication }}', etat AS '{{ etat }}' FROM `commentaires` WHERE id_chapitre = '$id'";
    $this->query($sql, true);

  }


  private function deleteComment($id){

   $sql = "DELETE FROM `commentaires` WHERE  id = '$id'";
   $this->prepare($sql);
  }

  private function updateState($id, $etat){
    //on récupère l'état du commentaire 
    // on incrémente l'état

    // on fait une requete pour mettre à jour l'état
    $sql = "UPDATE `commentaires` SET etat = $etat + '1' WHERE  id = '$id'";
    $this->prepare($sql);


  }

  private function createComment($auteurCommentaire, $contenuCommentaire, $idChapitre) {
    $sql = "INSERT INTO `commentaires` (auteurCommentaire, contenuCommentaire, datePublication, id_chapitre) VALUES (:auteurCommentaire, :contenuCommentaire, NOW(), :idChapitre)";

    $this->prepare($sql, compact("auteurCommentaire", "contenuCommentaire", "idChapitre")); 


  }





}