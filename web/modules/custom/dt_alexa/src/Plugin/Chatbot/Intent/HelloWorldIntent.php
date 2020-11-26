<?php

namespace Drupal\dt_alexa\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\node\Entity\Node;
use Drupal\alexa\AlexaEvent;


/**
 * @Intent(
 * id = "HelloWorld",
 * label = @Translation("HelloWorld")
 * )
 */
class HelloWorldIntent extends IntentPluginBase  {
    
    public function process() {

        #$this->response->setIntentResponse('Hallo! Es hat alles geklappt.');
        
        #########2. Intent##########

        $phase = $this->request->getIntentSlot('Phase');
        $raum = ucfirst($this->request->getIntentSlot('Raum'));
        $anzahl = $this->request->getIntentSlot('Anzahl');

        if (!empty($phase)) {
            $output = 'Für die Phase ' . $phase . ' kenne ich folgende Methoden:';
        } else {
            $output = 'Ich kenne folgende Methoden:';
        }
        
        $array = array();
        $array = $this->getAllMethods($phase, $raum);
        
        if (empty($anzahl)) {
            foreach ($array as $element) {
                $output .= ' ' . $element;
                
            }
        } else {
            #Falls nach der Anzahl an Methoden gefragt ist
            $num = count($array);
            $output = 'Ich kenne '. strval($num) . ' Methoden.';
        } */

        #$output = 'Phase: ' . $phase . ', Raum: ' . $raum . ' und Anzahl: ' . $anzahl;
        
        $this->response->setIntentResponse($output);
        
    }

    public function getAllMethods($phase='', $raum='') {
        $query = \Drupal::entityQuery('node');

        

        /* if (!empty($raum) && empty($phase)) {
            $query
            ->condition('type', 'Methode')
            ->condition('field_raum', $raum);

        } elseif (empty($raum) && !empty($phase)) {
            $query
            ->condition('type', 'Methode')
            ->condition('field_phase', $phase);

        } elseif (!empty($raum) && !empty($phase)) {
            $query
            ->condition('type', 'Methode')
            ->condition('field_phase', $phase)
            ->condition('field_raum', $raum);
            
        } else {
            $query
            ->condition('type', 'Methode');
        } */

        $query
            ->condition('type', 'Methode');
        $entity_ids = $query->execute();
    
        $array = array();

        foreach ($entity_ids as $nid) {
            $node = Node::load($nid);
            $title = $node->getTitle();

            $tid = $node->get('field_raum')->value; #gibt Taxonomie-ID!!!
            $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid);
            $name = $term->label();

            $method_phase = $node->get('field_phase')->value;

           /*  if ((!empty($raum)) and !(empty($phase)) and (strcmp($raum, $method_raum) == 0) and (strcmp($phase, $method_phase) == 0)) {
                array_push($array, $title);
            } elseif (!empty($raum) and (strcmp($raum, $method_raum) == 0)) {
                array_push($array, $title);
            } elseif (!empty($phase) and (strcmp($phase, $method_phase) == 0)) {
                array_push($array, $title);
            } else {
                array_push($array, $title);
            } */

            if (!empty($raum) and (strcmp($raum, $method_raum) == 0)) {
                array_push($array, $title);
            }

            #array_push($array, $title);

            /* if (!empty($phase)) {
                #Prüfe, ob Phase als Substring in Titel enthalten
                if (strpos($title, $phase) !== false) {
                    #Trim $title, sodass Phase nicht mehr im Titel steht
                    $trimmed_title = trim($title, '('.$phase.')');
                    array_push($array, $trimmed_title);
                }   
            } else {
                #TODO: Phase aus Titel entfernen
                array_push($array, $title);
            } */
            
            
        }

        return $array;

      }
}