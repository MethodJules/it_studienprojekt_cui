<?php

namespace Drupal\alexa_cui\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\alexa\AlexaEvent;
use Drupal\node\Entity\Node;


/**
 * 
 * @Intent (
 *  id = "DurchlaufenIntent",
 *  label = @Translation("DurchlaufenIntent")
 * )
 * 
 */
class DurchlaufenIntent extends IntentPluginBase {


    public function process() {
    //Drupal::logger("alexa_cui")->notice("testausgabe 21:39");
      // Testausgabe
    //$this->response->setIntentResponse('daniel Anbindung funktioniert');
      
      $slot_name = $this->request->getIntentSlot('Methodenname');


      $slot_vorgehen = $this->request->getIntentSlot('Moderieren');
      $slot_timer = $this->request->getIntentSlot('Timer');
     
    //sammelt alle vorhandenen Methodentitel in einem Array



    $query = \Drupal::entityQuery('node');
    $query
    ->condition('type', 'Methode');
    $entity_ids = $query->execute();
    
    $array = array();
    $array_zeit = array();
    $array_body = array();
    
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid);
      $text = strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');
      preg_match_all('!\d+!', $text, $zahlen);
      $title = $node->getTitle();
     
      
      $body = strip_tags($node->get('body')->value, '<p></p>');
      if ( $zahlen[0][0] <= 10 ){ // anpassen für 60 minuten
       // $title = 'Die Methode ' . $title . ' wird beschreiben als: ' . strip_tags($node->get('body')->value, '<p></p>');
       
       
        array_push($array, $title);
        array_push($array_zeit, $text); // alle zeit texte 
        array_push($array_body, $body);//
        
      }

      
            

    }

    $output = '  Ich kenne die folgenden Methoden: ';

    foreach ($array as $element) {
   // foreach ($array_body as $element) {
      $output .= ' ' . $element;

    }



    if (!empty($slot_name)) {

      $output = $this->auswahl($slot_name);


      

    } 
    if  (!empty($slot_vorgehen)) {

      $output = $this->vorgehen($slot_name);


      

    } 

    if  (!empty($slot_timer)) {

      $output = $this->timer($slot_name);

     
      

    } 

    

    
  
    

    $this->response->setIntentResponse($output);

    
    
  

   
    }


    
    public function auswahl($Methodenname){

      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode')
      ->condition('title', $Methodenname);
    
      $entity_ids = $query->execute();
      
      
      foreach ($entity_ids as $nid) {
        $node = Node::load($nid);
       
        $title = $node->getTitle();
       
        
        $body = strip_tags($node->get('body')->value, '<p></p>');
        $output = "Die Methode: ".$title." wird folgendermaßen beschrieben ".$body;
       
      }

      return $output;

    }
   
    public function vorgehen($Methodenname){

      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode')
      ->condition('title', $Methodenname);
    
      $entity_ids = $query->execute();
      
      
      foreach ($entity_ids as $nid) {
        $node = Node::load($nid);
       
        $title = $node->getTitle();
       
        
        $vorgehen  = strip_tags($node->get('field_vorgehen')->value, '<p></p>');
        $output = "Die Methode: ".$title." wird folgendermaßen vorgegangen ".$vorgehen. " wenn ich einrn Timer setzen soll sage: Timmer setzte von der Methode ".$Methodenname;
       
      }

      return $output;


    }

    public function timer($Methodenname) {

      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode')
      ->condition('title', $Methodenname);
      $entity_ids = $query->execute();
      
     
   
      
      foreach ($entity_ids as $nid) {
        $node = Node::load($nid);
        $text = strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');
        preg_match_all('!\d+!', $text, $zahlen);
        $title = $node->getTitle();     

       


    }

   
    $output = "zahl1 :".$zahlen[0][0]." zahl2:".$zahlen[0][1];

    return $output;



  }       

}

