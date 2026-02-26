<?php

use Application\Manager\PrestataireManager;

$prestataireManager = new PrestataireManager();
$prestataireManager->em = $this->em;
$tab = $prestataireManager->getListePrestataire(null, "1", "-1", 1, null, false, null, array("CENTRE_HOSPITALIER", "CENTRE_HOSPITALIER_SIMPLE", "SERVICE_SANTE","CENTRE_HOSPITALIER_OPTIQUE","CENTRE_HOSPITALIER_DENTISTE"));

$valueOptions = array();
foreach ($tab as $element)
{
    $element->afficheChaine();
    $valueOptions[$element->getId()] = $element->getNom();
}


 return array(
     array(
         "name" => "natureAffection",
         "attributes" => array(
             "type" => "text",
             "id" => "natureAffection",
             "class" => "required",
             "placeholder" => $this->translate ("Veuillez saisir la nature de l'affection", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Nature de l'affection", "application"),
         ),
    ),
    array(
         "name" => "prestataire",
         "type" => "Zend\Form\Element\Select",
         "attributes" => array(
             "type" => "select",
             "id" => "prestataire",
             "class" => "form-control required",
         ),
         "options" => array(
             "label" => $this->translate ("Centre hospitalier", "application"),
             'label_attributes' => array(
                 'class'  => 'control-label',
             ),
             "empty_option" => $this->translate ("Selectionnez", "application"),
             "value_options" => $valueOptions,
         ),
    ),
    array(
         "name" => "visite",
         "attributes" => array(
             "type" => "text",
             "id" => "visite",
             "class" => "required",
             "placeholder" => $this->translate ("Saisissez le code de la visite", "application"),
         ),
         "options" => array(
             "label" => $this->translate ("Code de la visite", "application"),
             "concatUnite" => array("unite" => '<i class="fa fa-search" style="cursor: pointer;" title="'.$this->translate ("Retrouver la visite", "application").'"></i>')
         ),
    ),
    array(
         "name" => "visteTouvee",
         "attributes" => array(
             "type" => "hidden",
             "id" => "visteTouvee",
         ),
    ),
    array(
         'type' => 'Zend\Form\Element\Csrf',
         'name' => 'csrf',
         'options' => array(
             'csrf_options' => array(
                 'timeout' => 5000
             )
         )
    ),
    array(
         "name" => "submitClose",
         "attributes" => array(
             "type" => "submit",
             "value" => $this->translate ("Soumettre", "application"),
             "id" => "submitClose",
             "class" => "btn btn-primary",
         ),
    ),
    array(
         "name" => "submit",
         "attributes" => array(
             "type" => "submit",
             "value" => $this->translate ("Enregistrer", "application"),
             "id" => "submit",
             "class" => "btn btn-primary",
             "style" => "display: none;"
         ),
    ),
    array(
         "name" => "cancel",
         "attributes" => array(
             "type" => "button",
             "value" => $this->translate ("Retour", "application"),
             "id" => "cancel",
             "class" => "btn btn-default",
         ),
    ),
 );