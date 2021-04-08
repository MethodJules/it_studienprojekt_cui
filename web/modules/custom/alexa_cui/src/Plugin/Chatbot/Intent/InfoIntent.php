<?php

namespace Drupal\alexa_cui\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\alexa\AlexaEvent;
use Drupal\node\Entity\Node;


/**
 * 
 * @Intent (
 *  id = "InfoIntent",
 *  label = @Translation("InfoIntent")
 * )
 * 
 */
class InfoIntent extends IntentPluginBase {
  /*
  Intent Nr. 4
  */
    public function process() {
      
      /* Testausgabe
      $this->response->setIntentResponse('PlugIn Anbindung funktioniert');
     */

     //Auslesen der Slots
      $slot_name = $this->request->getIntentSlot('Methodenname');
      $slot_name = $this->_getCleanMethodename($slot_name);
      $slot_info = $this->request->getIntentSlot('Methodeninfo');
      $slot_alle = $this->request->getIntentSlot('KompletteInformationen');
      $slot_dauer = $this->request->getIntentSlot('Dauer');
      $slot_Menge = $this->request->getIntentSlot('Quantity');
      $slot_zsm = $this->request->getIntentSlot('Zsm');
      $slot_hilfsmittel = $this->request->getIntentSlot('Hilfsmittel');
      
      //sammelt alle vorhandenen Methodentitel in einem Array
      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode');
      $entity_ids = $query->execute();
      
      /*Test auslese aller bekannten Methoden

      $alleMethoden = array();
      
      foreach ($entity_ids as $nid) {
        $node = Node::load($nid);
        $title = $node->getTitle();
        
        array_push($alleMethoden, $title);      

      }
        
      $test = 'Ich kenne die folgenden Methoden: ';

      foreach ($alleMethoden as $element) {
        $test .= ' ' . $element;
      }
      */



      //Je nachdem welche Slots ausgelesen werden, wird eine andere Methode aufgerufen  
      if (!empty($slot_alle)) {

        $output = $this->getAllInfos($slot_name, $alleMethoden);

      } else if ((!empty($slot_Menge)) && (!empty($slot_dauer))) {

        $output = $this->getDauer($slot_name, $slot_dauer, $slot_Menge);

      } else if (!empty($slot_hilfsmittel)) {

        $output = $this->getHilfsmittel($slot_name, $slot_hilfsmittel, $alleMethoden);

      } else if (!empty($slot_zsm)) {

        $output = $this->getZusammenfassung($slot_name, $alleMethoden);
        
      } else {

        $output = $this->getMethodInfo($slot_name, $slot_info, $alleMethoden);

      } 

      if ($output == '') {
        $ouptut = 'Ich habe dich leider nicht verstanden. Kannst du deine Frage bitte erneut stellen?';
      }

      $this->response->setIntentResponse($ouptut);
      
    }

    // gibt den Methodennamen ohne eingeklamemrte Objekte wieder
    public function _getCleanMethodename($name) {
      $nids = \Drupal::entityQuery('node')->condition('type','methode')->execute();
      $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

      foreach($nodes as $node) {
          //preg_match('#\((.*?)\)#', $node->title->value, $match);
          //$match[1];
          $method = explode('(', $node->title->value);
          $phase = rtrim($method[1], ")");
          $methods[ strtolower(rtrim($method[0]))] = $node->title->value; 
      }

      //dsm($name);
      //dsm($methods);
      //dsm($methods[$name]);

      return $methods[$name];
  }


  // Fass die Methode zusammen
    public function getZusammenfassung ($name) {


      $output = ''; 
      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode')
      ->condition('title', $name);
      $entity_ids = $query->execute();

      // gibt den Body wieder
      foreach ($entity_ids as $nid) {
        $node = Node::load($nid);
        $title = $node->getTitle();
         
        // Entferne letzten Teil des Titels
        //$split_node_title = explode("(", $title);
        //Output
       // array_push($array,trim($split_node_title[0]));

        $output = 'Die Methode ' . $title . ' wird beschrieben als: ' . strip_tags($node->get('body')->value, '<p></p>');

      }

      $outputGefiltert = str_replace(array('<p>', '&nbsp;', '</p>'), ' ', $output);

      
      if ($output == ' ') {

        $ouptut = 'Ich habe dich leider nicht verstanden. Kannst du deine Frage bitte erneut stellen?';
        $outputGefiltert = 'Ich habe dich leider nicht verstanden. Kannst du deine Frage bitte erneut stellen?';
      
      }

      

      return $output; 

    }


    // prüft, ob die Dauer in der angefragten Zeit durchgeführt werden kann
    public function getDauer ($name, $dauer, $menge) {
      $output = ' ';

      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode')
      ->condition('title', $name);
      $entity_ids = $query->execute();

      //gibt die benötigte Zeit wieder
      foreach ($entity_ids as $nid) {

        $node = Node::load($nid);
        $title = $node->getTitle();
        $text = strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');
        // Entferne letzten Teil des Titels
        $split_node_title = explode("(", $title);
        //Output
        array_push($array,trim($split_node_title[0]));

      }

      preg_match_all('!\d+!', $text, $zahlen);
      
      /*
      Je anchdem ob der NUtzer die ANgabe in Stunden, Minuten oder sekunden macht
      in u. a. Sekunde sind zusätzliche parameter in der IF Bediungung, weil Zahlen die Buchstaben enthalten können
      und dadurch ohne die Parameter Probleme aufgetreten sind
      */
      if ((strpos(strtolower($dauer), "minute") !== false) or (strpos(strtolower($dauer), "minuten") !== false)) {


        $test = "Minute eingeben";

        /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        if ($menge >= $zahlen[0][0] && $menge <= $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit genügt, um die Methode ' . $name . ' durchzuführen.';
        } else if ($menge < $zahlen[0][0]) {
          $output = ' Die vorgeschlagene Zeit genügt nicht um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($menge > $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }

        
      } else if ((strpos(strtolower($dauer), "stunde") !== false) or (strpos(strtolower($dauer), "stunden") !== false)) {

        $test .= "stunde eingegeben";

        $HinMin = $menge * 60;
         /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        
        if ($HinMin >= $zahlen[0][0] && $HinMin <= $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit genügt, um die Methode ' . $name . ' durchzuführen.';
        } else if ($HinMin < $zahlen[0][0]) {

          $output = ' Die vorgeschlagene Zeit genügt nicht, um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($HinMin > $zahlen[0][1]) {
          $output = ' Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }
        
        
      } else if ((strpos(strtolower($dauer), "tag") !== false) or (strpos(strtolower($dauer), "tage") !== false)) {
        
        $test .= "tag eingegeben";

        /*
       Ein Tag ist ein Arbeitstag und wird in Min umgerechnet
        */ 
        $tInM = $menge * 60 * 8;;

         /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        if ($tInM >= $zahlen[0][0] && $tInM <= $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit genügt, um die Methode ' . $name . ' durchzuführen.';
        } else if ($tInM < $zahlen[0][0]) {
          $output = 'Die vorgeschlagene Zeit genügt nicht, um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($tInM > $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }

        
      } else if ((strpos(strtolower($dauer), "woche") !== false) or (strpos(strtolower($dauer), "wochen") !== false)) {
        
        /*
        Woche in Minuten umgerechnet mit 1 Woche = 5 Arbeitstage
        */ 

        $test .= "woche eingegeben";
        $wInMin = $menge * 60 * 8 * 5;

         /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        if ($wInMin >= $zahlen[0][0] && $wInMin <= $zahlen[0][1]) {
          $output = ' Die vorgeschlagene Zeit genügt, um die Methode ' . $name . ' durchzuführen.';
        } else if ($wInMin < $zahlen[0][0]) {
          $output = ' Die vorgeschlagene Zeit genügt nicht, um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($wInMin > $zahlen[0][1]) {
          $output = ' Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }
        
      } else if ((strpos(strtolower($dauer), "monat") !== false) or (strpos(strtolower($dauer), "monate") !== false)) {

        $test .= "monat eingegeben";
        /*
        Monate in Minuten umgerechnet mit 1 Monat = 4 Wochen mit 5 Tage die Woche und 8 Stunden pro Tag 
        */ 
        
        $mInMin = $menge * 60 * 8 * 5 * 4;

         /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        if ($mInMin >= $zahlen[0][0] && $mInMin <= $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit genügt, um um die Methode ' . $name . ' durchzuführen.';
        } else if ($mInMin < $zahlen[0][0]) {
          $output = 'Die vorgeschlagene Zeit genügt nicht, um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($mInMin > $zahlen[0][1]) {
          $output = ' Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }
      }


      $test .= " min: " . $zahlen[0][0] . " max: " . $zahlen[0][1] . " dauer: " . $dauer . " output: " . $output;

      return $output;
    }

    // prüft ob mehr als die angefragten Hilfmittel benötigt werden
    public function getHilfsmittel ($name, $hilfsmittel) {
      $output = '';


      /*
      Variablen um zu erkennen wie viele Hilfsmittel benötigt werden, also mehr als das angefragte
      */
      $bekannteHilfsmittel = array('schreibmaterial', 'stakeholder analyse', 'zettel', 'stift');
      $anzahl = 0;
      $benötigt = '';
      $angefragt = 0;

      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode')
      ->condition('title', $name);
      $entity_ids = $query->execute();

      /*
      Hilfsmittel Text wird gespeichert
      */
      foreach ($entity_ids as $nid) {

        $node = Node::load($nid);
        $title = $node->getTitle();
        $text = strip_tags($node->get('field_hilfsmittel')->value, '<p></p>');

        
        $ausgabe_title = $node->getTitle();
        // Entferne letzten Teil des Titels
        $split_node_title = explode("(", $node_title);
        //Output
        array_push($array,trim($split_node_title[0]));

      }
      $textGefiltert = str_replace(array('<p>', '&nbsp;', '</p>', ',', '.'), ' ', $text);


      

      /*
      Liste mit HIlfsmitteln wird mit dem Text verglichen
      */
      foreach ($bekannteHilfsmittel as $mittel) {
        
        if(strpos(strtolower($text), strtolower($mittel)) !== false) {
          
          if (strpos(strtolower($mittel), strtolower($hilfsmittel)) !== false) {
            /*
            Wenn ein angefragtes Hilfsmittel im Text gefunden wird, erhöht sich der COunter
            */
            $angefragt++;
          }
          /*
            Wenn ein Hilfsmittel im Text gefunden wird, erhöht sich der COunter
            */
          $anzahl++;
          $benötigt .= ' ' . $mittel;
  
        }
        
      }

      /*
      Sollten alle benötigten Hilfmittel angefragt werden
      */
      if ($anzahl == $angefragt) {

        $output = $hilfsmittel . ' genügt um die Methode ' . $split_node_title[0] . ' zu bearbeiten';

      } else if ($anzahl > $angefragt) {
        /*
        Falls nicht alle Hilfsmittel angefragt wurden. Außerdem werden die benötigten hilfsmittel angegeben
        */
        $output = $hilfsmittel . ' genügt nicht, um die Methode ' . $split_node_title[0] . ' zu bearbeiten. Es werden die Hilfsmittel ' . $benötigt . ' benötigt.';

      }


      return $output;
    }


    //gibt alle Informationen zu einer Methode aus 
    public function getAllInfos ($name) {

      $output = '';
      

      $infoKategorien1 = array('ziele', 'beteiligte', 'hilfsmittel');
      //Beispiel fehlt wegen Bilder
      $infoKategorien2 = array('vorteile', 'nachteile', 'beispiel', 'vorgehen', 'phase', 'raum');

      $query = \Drupal::entityQuery('node');
      $query
      ->condition('type', 'Methode')
      ->condition('title', $name);
      $entity_ids = $query->execute();

      // gibt den Body wieder
      foreach ($entity_ids as $nid) {
        $node = Node::load($nid);
        $title = $node->getTitle();

        
        $node_title = $node->getTitle();
        // Entferne letzten Teil des Titels
        $split_node_title = explode("(", $node_title);
        //Output
        array_push($array,trim($split_node_title[0]));

        $output = 'Die Methode ' . $split_node_title[0] . ' wird beschrieben als: ' . strip_tags($node->get('body')->value, '<p></p>');

      }

      //gibt alle kategorien asu dem Array infoKategorien1 aus
      
      for ($i = 0; $i < count($infoKategorien1); $i++) {
        
        foreach ($entity_ids as $nid) {
          $node = Node::load($nid);
          $title = $node->getTitle();

          $node_title = $node->getTitle();
          // Entferne letzten Teil des Titels
          $split_node_title = explode("(", $node_title);
          //Output
          array_push($array,trim($split_node_title[0]));

          $output .= ' ' . $infoKategorien1[$i] . ' von ' . $split_node_title[0] . ': ';
          $output .= strip_tags($node->get('field_'. $infoKategorien1[$i])->value, '<p></p>');
          
        }

      }

      //gibt die benötigte Zeit wieder
      foreach ($entity_ids as $nid) {

        $node = Node::load($nid);
        $title = $node->getTitle();
        $output .= 'Die benötigte Zeit für ' . $name . ' beträgt: ';
        $output .= strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');

      }

      for ($i = 0; $i < count($infoKategorien2); $i++) {
        
        foreach ($entity_ids as $nid) {
          $node = Node::load($nid);

          $node_title = $node->getTitle();
          // Entferne letzten Teil des Titels
          $split_node_title = explode("(", $node_title);
          //Output
          array_push($array,trim($split_node_title[0]));

          $title = $node->getTitle();
          $output .= ' ' . $infoKategorien2[$i] . ' von ' . $split_node_title[0] . ': ';
          $output .= strip_tags($node->get('field_'. $infoKategorien2[$i])->value, '<p></p>');
          
        }

      }


      //filtert die HTML Elemente 
      $outputGefiltert = str_replace(array('<p>', '&nbsp;', '</p>'), ' ', $output);

      if ( $output = '') {
        $outputGefiltert = 'Ich habe leider keine Antwort für deine Frage gefunden. Kannst du bitte erneut nachfragen oder eine andere Frage stellen?';
      }


      return $outputGefiltert;
    }

    
    //gibt die vom Nutzer angefragen Informatinoskategorien zu einer Methode wieder
    public function getMethodInfo ($name, $info) {

      $output = '';
      $infoKategorien = array('ziele', 'beteiligte', 'hilfsmittel', 'vorteile', 'nachteile', 'beispiel', 'vorgehen', 'phase', 'raum');
      $zeit = 'benötigte zeit';

      $iteration = 0;

      if (!empty($name) && !empty($info)) {
        
        $query = \Drupal::entityQuery('node');
        $query
        ->condition('type', 'Methode')
        ->condition('title', $name);
        $entity_ids = $query->execute();
        
        // Iteriert über alle möglichen Kategorien. +1 weil die benötigte Zeit nicht im Array ist

        for ($i = 0; $i < count($infoKategorien)+1; $i++){
          
          // Prüft welche der möglichen Informationskategorien angefragt wurde
          if (strpos($info, $infoKategorien[$i]) !== false) {
            
            
            foreach ($entity_ids as $nid) {
              $node = Node::load($nid);
              //$title = $node->getTitle();

              $node_title = $node->getTitle();
              // Entferne letzten Teil des Titels
              $split_node_title = explode("(", $node_title);
              //Output
              array_push($array,trim($split_node_title[0]));

              $output .= 'Die ' . $infoKategorien[$i] . ' von ' . $split_node_title[0] . ': ';
              $output .= strip_tags($node->get('field_'. $infoKategorien[$i])->value, '<p></p>');
              
            }
            

          }
          //falls nach der benögigten Zeit gefragt wird
          if ((strpos($info, $zeit) !== false)) {
            
            foreach ($entity_ids as $nid) {
              $node = Node::load($nid);
              $title = $node->getTitle();
              
              //sorgt dafür, dass die benötigte Zeit nicht merhmals ausgegeben wird
              if($iteration == 0) {

                $node_title = $node->getTitle();
                // Entferne letzten Teil des Titels
                $split_node_title = explode("(", $node_title);
                //Output
                array_push($array,trim($split_node_title[0]));

                $output .= 'Die benötigte Zeit für ' . $split_node_title[0] . ' beträgt: ';
                $output .= strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');
                $iteration++;
              }
              
            }
            
          }
          
        }

        if (empty($output)) {
          $output = 'Es ist keine Information hierzu vorhanden.';
        }
        
        $output .= ' Kann ich dir weitere Informationen zu einer Methode geben?';
      } else {
        $output = 'Die angeforderte Information kann ich nicht finden. Kann ich dir Informationen zu einer anderen Methode geben?';
        }
      
      $outputGefiltert = str_replace(array('<p>', '&nbsp;', '</p>'), ' ', $output);

      if ( $output = '') {
        $outputGefiltert = 'Ich habe leider keine Antwort für deine Frage gefunden. Kannst du bitte erneut nachfragen oder eine andere Frage stellen?';
      }
      
      return $outputGefiltert;
    }
    
}
