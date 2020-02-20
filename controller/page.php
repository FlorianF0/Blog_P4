<?php

class Page
{

  public $html;
  public $titre;

  protected function footer(){
    return file_get_contents("./templates/partialFooter.html");
  }

  protected function renderPage(){
    $this->html  = new View(
      [
        "{{ titre }}"                => $this->titre,
        "{{ partialMain }}"          => $this->html,
        "{{ partialFooter }}"        => $this->footer(),
        // "{{ partialFormConnexion }}" => $monChapitre->afficheFormConnexion()
      ],
      "template"
    );
  }
}