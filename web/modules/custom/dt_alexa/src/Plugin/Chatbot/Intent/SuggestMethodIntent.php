<?php

namespace Drupal\dt_alexa\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\node\Entity\Node;
use Drupal\alexa\AlexaEvent;
use Drupal\taxonomy\Entity\Term;


/**
 * @Intent(
 * id = "SuggestMethodIntent",
 * label = @Translation("SuggestMethodIntent")
 * )
 */
class SuggestMethodIntent extends IntentPluginBase  {
    
    public function process() {
        
        #########3. Intent##########

        $phase = $this->request->getIntentSlot('Phase'); // divergent, konvergent
        $raum = ucfirst($this->request->getIntentSlot('Raum')); // Problemraum, Loesungsraum, Implementierungsraum

        $menge = $this->request->getIntentSlot('Quantity'); // 1, 2,...
        #$menge = 150;
        $dauer = $this->request->getIntentSlot('Dauer'); // Minuten, Stunden etc.
        #dauer = 'stunde';
        $relation = $this->request->getIntentSlot('Relation'); // 'weniger' oder 'mehr'

        $hilfsmittel = ucfirst($this->request->getIntentSlot('Hilfsmittel')); // Schreibmaterial
        $beteiligte = $this->request->getIntentSlot('Beteiligte'); // Design Thinking Team

        $anzahl = $this->request->getIntentSlot('Anzahl'); // 'wie viele'

        $array = array();
        #$methodList = '';
        $output = 'Es ist mind. ein Fehler aufgetreten.';

        # Suche nach Methoden
        # ...wenn Hilfsmittel die Bedingung ist
        if (!empty($hilfsmittel)) {

            $array = $this->methodByHilfsmittel($phase, $raum, $hilfsmittel);
        
        # ...wenn das Team die Bedingung ist
        } elseif (!empty($beteiligte)) {

            // 'design thinking team' -> 'Design-Thinking-Team'
            $temp_beteiligte = explode(" ", $beteiligte);
            $beteiligte = '';

            foreach ($temp_beteiligte as $temp) {
                if (strcmp($temp, end($temp_beteiligte)) == 0) {
                    $beteiligte .= ucfirst($temp);
                } else {
                    $beteiligte .= ucfirst($temp) . ' ';
                } 
            }

            $array = $this->methodByTeam($phase, $raum, $beteiligte);


        # ...wenn die Zeit die Bedinung ist
        } elseif (!empty($menge) and !empty($dauer)) {

            #$array = $this->methodByTime($phase, $raum, $menge, $relation);

            switch ($dauer) {
                case 'minute':
                case 'minuten':
                    # keine Umrechnung erforderlich
                    
                    $array = $this->methodByTime($phase, $raum, $menge, $relation);
                    break;
                
                case 'stunde':
                case 'stunden':
                    # $menge * 60
                    $zeit = '';
                    $zeit = $menge * 60;
                    $array = $this->methodByTime($phase, $raum, $zeit, $relation);
                    break;

                case 'tag':
                case 'tage':
                    # $menge * 60 * 8 (Arbeitstag)
                    $zeit = '';
                    $zeit = $menge * 60 * 8;
                    $array = $this->methodByTime($phase, $raum, $zeit, $relation);
                    break;

                case 'woche':
                case 'wochen':
                    # $menge * 60 * 8 * 5 (Arbeitswoche)
                    $zeit = '';
                    $zeit = $menge * 60 * 8 * 5;
                    $array = $this->methodByTime($phase, $raum, $zeit, $relation);
                    break;
                
                case 'monat':
                case 'monate':
                    # 1 Monat besteht aus 4 Wochen
                    # $menge * 60 * 8 * 5 * 4
                    $zeit = '';
                    $zeit = $menge * 60 * 8 * 5 * 4;
                    $array = $this->methodByTime($phase, $raum, $zeit, $relation);
                    break;
                
                default:
                    break;
            }
        }
        
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

        #$output = '';
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
                $output = 'Für die Phase ' . $phase . ' kenne ich folgende Methoden:' . $methodList;
                break;

            case 'FTT':
                $output = 'Für den ' . $raum . ' kenne ich ' . $num . ' Methoden.';
                break;

            case 'FTF':
                $output = 'Für den ' . $raum . ' kenne ich folgende Methoden:' . $methodList;
                break;

            case 'FFT':
                $output = 'Ich kenne ' . $num . ' Methoden.';
                break;

            case 'FFF':
                $output = 'Ich kenne folgende Methoden:' . $methodList; 
                break;
            
            default:
                break;
        }

        if (empty($methodList)) {
            $output = 'Ich habe keine Methoden gefunden, die zu deiner Anforderung passen.';
        }

        // Output
        $this->response->setIntentResponse($output);
        
    }    

    public function methodByTime($phase='', $raum='', $zeit='', $relation='') {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'Methode');
        $entity_ids = $query->execute();
        $array = array();
        $nodes = Node::loadMultiple($entity_ids);

        foreach ($nodes as $node) {
            $node_time = '';
            $condition_sat = FALSE;
            #$method_intervall = array();

            // Hole benoetigte Zeit der Methode und filtere die Zahlen heraus
            $node_time = $node->get('field_benoetigte_zeit')->value;
            preg_match_all('!\d+!', $node_time, $method_intervall);            
                       
            
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

            $condition_sat = $this->testRelation($relation, $zeit, $method_intervall);

            // Pruefe, ob Methode zu Array hinzugefuegt werden kann
            if (!empty($raum) && empty($phase)) {
                if (strcmp($method_raum, $raum) == 0 && $condition_sat) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
    
            } elseif (empty($raum) && !empty($phase)) {
                if (strcmp($method_phase, $phase) == 0 && $condition_sat) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
    
            } elseif (!empty($raum) && !empty($phase)) {
                if (strcmp($method_raum, $raum) == 0 && strcmp($method_phase, $phase) == 0 && $condition_sat) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
                
            } else {
                if ($condition_sat) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
                
            } 
        } 

        return $array;
    }

    public function testRelation($relation, $zeit, $method_intervall) {
        $condition_sat = FALSE;
        if ((strcmp($relation, 'weniger') == 0) && !empty($method_intervall[0][1]) && $zeit >= $method_intervall[0][1]) { //...fuege hinzu, wenn Methode weniger als x dauert
            $condition_sat = TRUE;
        } elseif ((strcmp($relation, 'länger') == 0) && !empty($method_intervall[0][0]) && $zeit <= $method_intervall[0][0]) { //...fuege hinzu, wenn Methode laenger als x dauert
            $condition_sat = TRUE;
        }

        return $condition_sat;
    }

    public function methodByHilfsmittel($phase='', $raum='', $hilfsmittel='') {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'Methode');
        $entity_ids = $query->execute();
        $array = array();
        $nodes = Node::loadMultiple($entity_ids);

        foreach ($nodes as $node) {
            $node_hilfsmittel = $node->get('field_hilfsmittel')->value;

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

            // Fuege Methode hinzu, wenn gefragtes Hilfsmittel in Aufzaehlung auftaucht
            # TODO: Methode hinzufuegen, wenn NUR gefragtes Hilfsmittel erforderlich 
            if (!empty($raum) && empty($phase)) {
                if (strcmp($method_raum, $raum) == 0 and strpos($node_hilfsmittel, 'lediglich ' . $hilfsmittel) !== False) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
    
            } elseif (empty($raum) && !empty($phase)) {
                if (strcmp($method_phase, $phase) == 0 and strpos($node_hilfsmittel, 'lediglich ' . $hilfsmittel) !== False) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
    
            } elseif (!empty($raum) && !empty($phase) ) {
                if (strcmp($method_raum, $raum) == 0 && strcmp($method_phase, $phase) == 0 && strpos($node_hilfsmittel, 'lediglich ' .  $hilfsmittel) !== False) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
                
            } elseif(strpos($node_hilfsmittel, 'lediglich ' . $hilfsmittel) !== False) {
                $node_title = $node->getTitle();
                $split_node_title = explode("(", $node_title);
                array_push($array,trim($split_node_title[0]));
            }

        }

        return $array;
    }

    public function methodByTeam($phase='', $raum='', $beteiligte='') {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'Methode');
        $entity_ids = $query->execute();
        $array = array();
        $nodes = Node::loadMultiple($entity_ids);

        foreach ($nodes as $node) {
            $node_beteiligte = $node->get('field_beteiligte')->value;

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

            // Fuege Methode hinzu, wenn gefragtes Team in Aufzaehlung auftaucht
            # TODO: Methode hinzufuegen, wenn NUR gefragtes Team erforderlich ist
            if (!empty($raum) && empty($phase)) {
                if (strcmp($method_raum, $raum) == 0 and strpos($node_beteiligte, 'lediglich das ' . $beteiligte) !== False) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
    
            } elseif (empty($raum) && !empty($phase)) {
                if (strcmp($method_phase, $phase) == 0 and strpos($node_beteiligte, 'lediglich das ' . $beteiligte) !== False) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
    
            } elseif (!empty($raum) && !empty($phase)) {
                if (strcmp($method_raum, $raum) == 0 && strcmp($method_phase, $phase) == 0 && strpos($node_beteiligte, 'lediglich das ' . $beteiligte) !== False) {
                    $node_title = $node->getTitle();
                    $split_node_title = explode("(", $node_title);
                    array_push($array,trim($split_node_title[0]));
                }
                
            } elseif(strpos($node_beteiligte, 'lediglich das ' . $beteiligte) !== False) {
                $node_title = $node->getTitle();
                $split_node_title = explode("(", $node_title);
                array_push($array,trim($split_node_title[0]));
            } 
            
        }

        return $array;
    }
}