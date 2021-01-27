<?php

namespace Drupal\alexa_cui\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\alexa\AlexaEvent;
use Drupal\node\Entity\Node;


/**
 * 
 * @Intent (
 *  id = "AuflistenIntent",
 *  label = @Translation("AuflistenIntent")
 * )
 * 
 */
class AuflistenIntent extends IntentPluginBase {
    public function process() {
    

      //Code gibt alle Methodennamen aus 
      
      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode');
      $entity_ids = $query->execute();
      
      $array = array();
      
      foreach ($entity_ids as $nid) {
        $node = Node::load($nid);
        $title = $node->getTitle();
        
        array_push($array, $title);      

      }

      $output = 'Ich kenne die folgenden Methoden: ';

      foreach ($array as $element) {
        $output .= ' ' . $element;
      }

      $this->response->setIntentResponse($output);
      
    }
}
