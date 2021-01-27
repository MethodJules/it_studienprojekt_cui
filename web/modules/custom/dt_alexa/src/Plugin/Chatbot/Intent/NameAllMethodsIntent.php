<?php

namespace Drupal\dt_alexa\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\node\Entity\Node;
use Drupal\alexa\AlexaEvent;
use Drupal\taxonomy\Entity\Term;


/**
 * @Intent(
 * id = "NameAllMethods",
 * label = @Translation("NameAllMethods")
 * )
 */
class NameAllMethodsIntent extends IntentPluginBase  {
    
    public function process() {
        
        #########2. Intent##########

        $phase = $this->request->getIntentSlot('Phase');
        $raum = ucfirst($this->request->getIntentSlot('Raum'));
        $anzahl = $this->request->getIntentSlot('Anzahl');

        $array = array();
        $array = $this->getAllMethods($phase, $raum);

        $methodList = '';

        foreach ($array as $element) {
            if (strcmp($element, end($array)) == 0) {
                $methodList .= ' ' . $element;
            } else {
                $methodList .= ' ' . $element . ',';
            }  
        }

        $result = '';
        $result .= !empty($phase) ? 'T' : 'F';
        $result .= !empty($raum) ? 'T' : 'F';
        $result .= !empty($anzahl) ? 'T' : 'F';

        $output = '';
        $num = count($array);

        switch ($result) {
            case 'TTT':
                $output = 'Für den ' . $raum . ' und die Phase ' . $phase . ' kenne ich ' . $num . ' Methoden.';
                break;

            case 'TTF':
                $output = 'Für den ' . $raum . ' und die Phase ' . $phase . ' kenne ich folgende Methoden:' . $methodList;
                break;
            
            case 'TFT':
                $output = 'Für die Phase ' . $phase . ' kenne ich ' . $num . ' Methoden.';
                break;

            case 'TFF':
                $output = 'Für die Phase ' . $phase . ' kenne ich folgende Methoden: ' . $methodList;
                break;

            case 'FTT':
                $output = 'Für den ' . $raum . ' kenne ich ' . $num . ' Methoden.';
                break;

            case 'FTF':
                $output = 'Für den ' . $raum . ' kenne ich folgende Methoden:' . $methodList;
                break;

            case 'FFT':
                $output = 'Ich kenne ' . $num . 'Methoden.';
                break;

            case 'FFF':
                $output = 'Ich kenne folgende Methoden:' . $methodList; 
                break;
            
            default:
                break;
        }

        /* if (!empty($phase)) {
            $output = 'Für die Phase ' . $phase . ' kenne ich folgende Methoden:';
        } else {
            $output = 'Ich kenne folgende Methoden:';
        } */
        
        
        
       /*  if (empty($anzahl)) {
            foreach ($array as $element) {
                if (strcmp($element, end($array)) == 0) {
                    $output .= ' ' . $element;
                } else {
                    $output .= ' ' . $element . ',';
                }
                
                
            }
        } else {
            #Falls nach der Anzahl an Methoden gefragt ist
            $num = count($array);
            $output = 'Ich kenne '. strval($num) . ' Methoden.';
        }  */
        
        $this->response->setIntentResponse($output);
        
    }

    public function getAllMethods($phase='', $raum='') {
        $query = \Drupal::entityQuery('node');

        $query
            ->condition('type', 'Methode');
        $entity_ids = $query->execute();
        #dsm($entity_ids);
        $array = array();
        $nodes = Node::loadMultiple($entity_ids); //Damit werden alle nodes gleichzeitig geholt
        //Iteriere über alle node-Objekte
        foreach ($nodes as $node) {            
            $term_raum = Term::load($node->get('field_raum')->target_id);
            $term_phase = Term::load($node->get('field_phase')->target_id);

            $method_raum = '';
            $method_phase = '';

            if(!is_null($term_raum)) {
                $method_raum = $term_raum->getName();
            }

            if (!is_null($term_phase)) {
                $method_phase = $term_phase->getName();
            }

            if (!empty($raum) && empty($phase)) {
                if (strcmp($method_raum, $raum) == 0) {
                    $node_title = $node->getTitle();
                    // Entferne letzten Teil des Titels
                    $split_node_title = explode("(", $node_title);
                    //Output
                    array_push($array,trim($split_node_title[0]));
                }
    
            } elseif (empty($raum) && !empty($phase)) {
                if (strcmp($method_phase, $phase) == 0) {
                    $node_title = $node->getTitle();
                    // Entferne letzten Teil des Titels
                    $split_node_title = explode("(", $node_title);
                    //Output
                    array_push($array,trim($split_node_title[0]));
                }
    
            } elseif (!empty($raum) && !empty($phase)) {
                if (strcmp($method_raum, $raum) == 0 && strcmp($method_phase, $phase) == 0) {
                    $node_title = $node->getTitle();
                    // Entferne letzten Teil des Titels
                    $split_node_title = explode("(", $node_title);
                    //Output
                    array_push($array,trim($split_node_title[0]));
                }
                
            } else {
                $node_title = $node->getTitle();
                // Entferne letzten Teil des Titels
                $split_node_title = explode("(", $node_title);
                //Output
                array_push($array,trim($split_node_title[0]));
            } 
        }

        return $array;

    }
}