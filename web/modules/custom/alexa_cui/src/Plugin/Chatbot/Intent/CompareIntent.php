<?php

namespace Drupal\alexa_cui\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\alexa\AlexaEvent;
use Drupal\node\Entity\Node;


/**
 * 
 * @Intent (
 *  id = "CompareIntent",
 *  label = @Translation("CompareIntent")
 * )
 * 
 */
class CompareIntent extends IntentPluginBase {
    public function process() {
      
      /* Testausgabe
      $this->response->setIntentResponse('PlugIn Anbindung funktioniert');
     */

      $slot_name = $this->request->getIntentSlot('Methodenname');
      $slot_info = $this->request->getIntentSlot('Methodeninfo');
      $slot_alle = $this->request->getIntentSlot('KompletteInformationen');

      
      //sammelt alle vorhandenen Methodentitel in einem Array
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
        /* 
      $test = 'Ich kenne die folgenden Methoden: ';

      foreach ($alleMethoden as $element) {
        $test .= ' ' . $element;
      }*/

      //ForEach Schleife zum durchlaufen aller existenten Methoden
      // Variable um zu prüfen, ob mehr als 1 Methode abgefragt wurde
      $anzahlMethoden = 0;
      foreach($alleMethoden as $Methode){
        
        // Zum debuggen benutzt
        /* 

        \Drupal::logger("alexa_cui")->notice($slot_name);
        \Drupal::logger("alexa_cui")->notice($Methode);*/
        if(strpos(strtolower($slot_name), strtolower($Methode)) !== false) {

        $anzahlMethoden++;

        }


      }

      $infoKategorien = array('ziele', 'beteiligte', 'hilfsmittel', 'vorteile', 'nachteile', 'beispiel', 'vorgehen', 'benötigte zeit', 'phase', 'raum');
      $anzahlInfo = 0;

      foreach($infoKategorien as $kategorie) {

        if(strpos(strtolower($slot_info), strtolower($kategorie)) !== false) {

          $anzahlInfo++;
  
        }

      }
      
      /*Ruft unterschiedliche Methoden auf je nachdem ob der nutzer eine Info, mehrere Infos oder alle INfos haben möchte
      */
      
      if (!empty($slot_alle)) {

        $output = $this->getAllInfos($slot_name);

      } else if (($anzahlMethoden > 1) && ($anzahlInfo == 1)) {

        $output = $this->getMultipleMethods($slot_name, $slot_info, $alleMethoden);

      } else if (($anzahlMethoden > 1) && ($anzahlInfo > 1)) {

        $output = $this->getMultipleMethodsAndInformation($slot_name, $slot_info, $alleMethoden);

      }

      $test = 'Im neuen Intent';
      $this->response->setIntentResponse($output);
      
    }

    public function getMultipleMethodsAndInformation ($name, $info, $alleMethoden) {
      
      $output = 'In richtiger Methode: ';
      
      $infoKategorien = array('ziele', 'beteiligte', 'hilfsmittel', 'vorteile', 'nachteile', 'beispiel', 'vorgehen', 'phase', 'raum');
      $zeit = 'benötigte zeit';
      $ausgegeben = '';
      $ausgegebenZeit = '';

      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode');
      $entity_ids = $query->execute();
      
      for ($i = 0; $i < count($alleMethoden); $i++){
        
        if (strpos(strtolower($name), strtolower($alleMethoden[$i])) !== false) {
          
          
          for ($j = 0; $j < count($infoKategorien); $j++){
           
            // Prüft welche der möglichen Informationskategorien angefragt wurde
            
            if (strpos($info, $zeit) !== false) {
              
              
              foreach ($entity_ids as $nid) {
                
                if (strpos(strtolower($ausgegebenZeit), strtolower($alleMethoden[$i])) === false) {
                  
                  $node = Node::load($nid);
                  $title = $node->getTitle();
                  $output .= 'Die benötigte Zeit für die Methode ' . $alleMethoden[$i] . ': ';
                  $output .= strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');
                  //damit die Information zu einer Methode nicht öfters ausgegebn werden
                  $ausgegebenZeit .= ' ' . $alleMethoden[$i];
                  
                }

                                           
              }

            }
            
            if (strpos($info, $infoKategorien[$j]) !== false) {
              
              foreach ($entity_ids as $nid) {
                
                if (strpos(strtolower($ausgegeben), strtolower($alleMethoden[$i])) === false) {
                  $node = Node::load($nid);
                  $title = $node->getTitle();
                  $output .= ' ' . $infoKategorien[$j] . ' von der Methode ' . $alleMethoden[$i] . ': ';
                  $output .= strip_tags($node->get('field_'. $infoKategorien[$j])->value, '<p></p>');
                  //damit die Information zu einer Methode nicht öfters ausgegebn werden
                  $ausgegeben .= ' ' . $alleMethoden[$i];
  
                }
                
                
              }
              
            } 
           // $ausgegeben .= ' ' . $alleMethoden[$i];
          }
          
          
        }                
                        
      }
      $outputGefiltert = str_replace(array('<p>', '&nbsp;', '</p>'), ' ', $output);
      return $outputGefiltert;
    }

    public function getMultipleMethods ($name, $info, $alleMethoden) {
      
      $output = '';
      $zeit = 'benötigte zeit';
      $ausgegebenZeit = '';
      $ausgegeben = '';
      $infoKategorien = array('ziele', 'beteiligte', 'hilfsmittel', 'vorteile', 'nachteile', 'beispiel', 'vorgehen', 'phase', 'raum');
      //sammelt alle vorhandenen Methodentitel in einem Array
      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode');
      $entity_ids = $query->execute();

      /*
      $test = 'Ich kenne die folgenden Methoden: ';

      foreach ($alleMethoden as $element) {
        $test .= ' ' . $element;
      }*/

      // For each Variante durchläuft alle Methoden 
      /*
      foreach($alleMethoden as $Methode) {
        
        // Zum debuggen benutzt
         

        \Drupal::logger("alexa_cui")->notice($name);
        \Drupal::logger("alexa_cui")->notice($Methode);
        if(strpos(strtolower($name), strtolower($Methode)) !== false) {

         

          if ((strpos($info, $zeit) !== false)) {

            //sorgt dafür, dass die benötigte Zeit ausgegeben wird
            
            foreach ($entity_ids as $nid) {
              
              if (strpos(strtolower($ausgegebenZeit), strtolower($alleMethoden[$i])) === false) {
                $node = Node::load($nid);
                $title = $node->getTitle();
                $output .= 'Die benötigte Zeit für ' .  $alleMethoden[$i] . ' beträgt: ';
                $output .= strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');
                $ausgegebenZeit .= ' ' . $alleMethoden[$i];

              }
              
            }

           } else {
            
            foreach ($entity_ids as $nid) {

              if (strpos(strtolower($ausgegeben), strtolower($alleMethoden[$i])) === false) {
                $node = Node::load($nid);
                $title = $node->getTitle();
                $output .= ' ' . $info . ' von ' . $alleMethoden[$i] . ': ';
                $output .= strip_tags($node->get('field_'. $info)->value, '<p></p>');
                $ausgegeben .= ' ' . $alleMethoden[$i];

              }
              
            }
            
          }

        }
      }*/

      

    
      for ($i = 0; $i < count($alleMethoden); $i++){
          
        
        if (strpos(strtolower($name), strtolower($alleMethoden[$i])) !== false) {
          //falls nach der benögigten Zeit gefragt wird

          if ((strpos($info, $zeit) !== false)) {
            
            foreach ($entity_ids as $nid) {
              
              if (strpos(strtolower($ausgegebenZeit), strtolower($alleMethoden[$i])) === false) {
                $node = Node::load($nid);
                $title = $node->getTitle();
                $output .= 'Die benötigte Zeit für ' .  $alleMethoden[$i] . ' beträgt: ';
                $output .= strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');
                $ausgegebenZeit .= ' ' . $alleMethoden[$i];

              }
                            
            }
            
          } else {

            foreach ($entity_ids as $nid) {
              
              if (strpos(strtolower($ausgegeben), strtolower($alleMethoden[$i])) === false) {
                $node = Node::load($nid);
                $title = $node->getTitle();
                $output .= ' ' . $info . ' von der Methode ' . $alleMethoden[$i] . ': ';
                $output .= strip_tags($node->get('field_'. $info)->value, '<p></p>');
                $ausgegeben .= ' ' . $alleMethoden[$i];

              }
                          
            }
            
            

          }
                   
        }
                
      }

      $outputGefiltert = str_replace(array('<p>', '&nbsp;', '</p>'), ' ', $output);
      return $outputGefiltert;
    }


    //gibt alle Informationen zu einer Methode aus in der Reihenfolge wie sie auf der Webseite ist
    public function getAllInfos ($name) {
      
      $output1 = '';
      $ausgabe = array();    

      $infoKategorien1 = array('ziele', 'beteiligte', 'hilfsmittel');
      
      $infoKategorien2 = array('vorteile', 'nachteile', 'beispiel', 'vorgehen');

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

      
      foreach ($alleMethoden as $Methode) {
        
        if(strpos(strtolower($name), strtolower($Methode)) !== false) {
          
          $query = \Drupal::entityQuery('node');
          $query
          ->condition('type', 'Methode')
          ->condition('title', $Methode);
          $entity_ids = $query->execute();
        

          // gibt den Body wieder
          foreach ($entity_ids as $nid) {
            $node = Node::load($nid);
            $title = $node->getTitle();
            $output = 'Die Methode ' . $title . ' wird beschreiben als: ' . strip_tags($node->get('body')->value, '<p></p>');
            \Drupal::logger("alexa_cui")->notice($title);
            \Drupal::logger("alexa_cui")->notice($output);
            

          }

          //gibt alle kategorien asu dem Array infoKategorien1 aus
      
          for ($i = 0; $i < count($infoKategorien1); $i++) {
        
           foreach ($entity_ids as $nid) {
              $node = Node::load($nid);
              $title = $node->getTitle();
              $output .= ' ' . $infoKategorien1[$i] . ' von ' . $title . ': ';
              $output .= strip_tags($node->get('field_'. $infoKategorien1[$i])->value, '<p></p>');
          
            }

          }
          
          //gibt die benötigte Zeit wieder
          foreach ($entity_ids as $nid) {

          $node = Node::load($nid);
          $title = $node->getTitle();
          $output1 .= 'Die benötigte Zeit für ' . $title . ' beträgt: ';
          $output1 .= strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');

          }
         
          for ($j = 0; $j < count($infoKategorien2); $j++) {
        
            foreach ($entity_ids as $nid) {
              $node = Node::load($nid);
              $title = $node->getTitle();
              $output .= ' ' . $infoKategorien2[$j] . ' von ' . $title . ': ';
              $output .= strip_tags($node->get('field_'. $infoKategorien2[$j])->value, '<p></p>');
            
            }
  
          }

        }

      }
      /*
      foreach ($ausgabe as $out) {
        $endOutput .= ' ' . $out;
      }*/

      //filtert die HTML Elemente 
      $outputGefiltert = str_replace(array('<p>', '&nbsp;', '</p>'), ' ', $output);
      $test = 'In Methode';
      return $test;
    }

    
    
}
