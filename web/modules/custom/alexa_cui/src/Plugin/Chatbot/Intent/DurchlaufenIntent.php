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
      \Drupal::logger("alexa_cui")->notice("testausgabe 21:39");
      // Testausgabe
      $this->response->setIntentResponse('PlugIn Anbindung funktioniert');

      $slot_name = $this->request->getIntentSlot('Methodenname');
      $slot_info = $this->request->getIntentSlot('Methodeninfo');
      $slot_zeit = $this->request->getIntentSlot('Methodenzeit');
     

      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode');
      $entity_ids = $query->execute();
      
      $alleMethoden = array();
  
      foreach ($entity_ids as $nid) {
        $node = Node::load($nid);
        $title = $node->getTitle();
        
        array_push($alleMethoden, $title); 
    }

    //sammelt alle vorhandenen Methodentitel in einem Array
        

    


  

    
  
    
}