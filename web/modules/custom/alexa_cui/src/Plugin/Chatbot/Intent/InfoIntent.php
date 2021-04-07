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

      $slot_name = $this->request->getIntentSlot('Methodenname');
      $slot_info = $this->request->getIntentSlot('Methodeninfo');
      $slot_alle = $this->request->getIntentSlot('KompletteInformationen');
      $slot_dauer = $this->request->getIntentSlot('Dauer');
      $slot_zsm = $this->request->getIntentSlot('Zsm');
      $slot_hilfsmittel = $this->request->getIntentSlot('Hilfsmittel');
      
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
        
      $test = 'Ich kenne die folgenden Methoden: ';

      foreach ($alleMethoden as $element) {
        $test .= ' ' . $element;
      }



      //ForEach Schleife zum durchlaufen aller existenten Methoden
      // Variable um zu prüfen, ob mehr als 1 Methode abgefragt wurde

      
      if (!empty($slot_alle)) {

        $output = $this->getAllInfos($slot_name, $alleMethoden);

      } else if (!empty($slot_dauer)) {

        $output = $this->getDauer($slot_name, $slot_dauer, $alleMethoden);

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

      $this->response->setIntentResponse($output);
      
    }

    public function getZusammenfassung ($name, $alleMethoden) {

      foreach ($alleMethoden as $element) {
        
        $elementFil = str_replace(array( '(', ')', ',', '/'), '', $element);
        $elementFilter = str_replace(array('-', '   ' ), ' ', $elementFil);
       

        if (strpos(strtolower($elementFilter), strtolower($name)) !== false) {

          $name =  $element;
        
        }

        if (strpos(strtolower($name), strtolower('sechs w. methode empathize')) !== false) {

          $name = '6 W-Methode (Empathize)';

        }

        

        if (strpos(strtolower($name), strtolower('fünf warum-technik define')) !== false) {

          $name = '5-Warum-Technik (Define)';

        }

        $test .= ' ' . $element;


      }



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
        $output = 'Die Methode ' . $title . ' wird beschreiben als: ' . strip_tags($node->get('body')->value, '<p></p>');

      }

      $outputGefiltert = str_replace(array('<p>', '&nbsp;', '</p>'), ' ', $output);

      /*
      if ($output == '') {

        $ouptut = 'Ich habe dich leider nicht verstanden. Kannst du deine Frage bitte erneut stellen?';
        $outputGefiltert = 'Ich habe dich leider nicht verstanden. Kannst du deine Frage bitte erneut stellen?';
      
      }

      */

      return $outputGefiltert; 

    }


    public function getDauer ($name, $dauer, $alleMethoden) {
      $output = ' ';

      foreach ($alleMethoden as $element) {

        $elementFil = str_replace(array( '(', ')', ',', '/'), '', $element);
        $elementFilter = str_replace(array('-', '   ' ), ' ', $elementFil);
       

        if (strpos(strtolower($elementFilter), strtolower($name)) !== false) {

          $name =  $element;
        
        }

        if (strpos(strtolower($name), strtolower('sechs w. methode empathize')) !== false) {

          $name = '6 W-Methode (Empathize)';

        }

        

        if (strpos(strtolower($name), strtolower('fünf warum-technik define')) !== false) {

          $name = '5-Warum-Technik (Define)';

        }

        $test .= ' ' . $element;


      }


      $test = 'draußen';
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

      }

      preg_match_all('!\d+!', $text, $zahlen);

      /*
      Je anchdem ob der NUtzer die ANgabe in Stunden, Minuten oder sekunden macht
      in u. a. Sekunde sind zusätzliche parameter in der IF Bediungung, weil Zahlen die Buchstaben enthalten können
      und dadurch ohne die Parameter Probleme aufgetreten sind
      */
      if ((strpos(strtolower($dauer), "s") !== false) && (strpos(strtolower($dauer), "min") === false) && (strpos(strtolower($dauer), "tage") === false) 
      && (strpos(strtolower($dauer), "tag") === false) && (strpos(strtolower($dauer), "woche") === false) && (strpos(strtolower($dauer), "wochen") === false)
      && (strpos(strtolower($dauer), "stunde") === false) && (strpos(strtolower($dauer), "stunden") === false)) {
         /*
         Sekunden werden in Minuten umgerechnet und die Zeiteinheit wird herausgefiltert
         */
        $dauerZahl = str_replace(array('s'), '', $dauer);
        $sInMin = 1;
        
        if ($dauerZahl > 0) {
          $sInMin = floor ($dauerZahl / 60);
        }

        $sInMinInt = $sInMin + 0;
        /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        if ($sInMinInt >= $zahlen[0][0] && $sInMinInt <= $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit genügt, um die Methode ' . $name . ' durchzuführen.';
        } else if ($sInMinInt < $zahlen[0][0]) {
          $output = ' Die vorgeschlagene Zeit genügt nicht um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($sInMinInt > $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }

        
      } else if (strpos(strtolower($dauer), "min") !== false || is_numeric($dauer)) {
        
        /*
        Zeiteinheit wird herausgefiltert
        */ 
        $test .= "In Minuten Bereich";

        $dauerZahl = str_replace(array('min'), '', $dauer);

        $dauerInt = $dauerZahl + 0;
         /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        if ($dauerInt >= $zahlen[0][0] && $dauerInt <= $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit genügt, um die Methode ' . $name . ' durchzuführen.';
        } else if ($dauerInt < $zahlen[0][0]) {

          $output = ' Die vorgeschlagene Zeit genügt nicht, um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($dauerInt > $zahlen[0][1]) {
          $output = ' Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }
        
        
      } else if (((strpos(strtolower($dauer), "h") !== false) && (strpos(strtolower($dauer), "min") === false) && (strpos(strtolower($dauer), "woche") === false) 
      && (strpos(strtolower($dauer), "wochen") === false)) or ((strpos(strtolower($dauer), "stunde") !== false) && (strpos(strtolower($dauer), "min") === false) 
      && (strpos(strtolower($dauer), "woche") === false) && (strpos(strtolower($dauer), "wochen") === false))) {
        
        /*
        Stunden in Minuten umgerechnet und die Zeiteinheit wird herausgefiltert
        */ 
        $dauerZahl = str_replace(array('h'), '', $dauer);
        $hInM = 60;

        if ($dauerZahl > 0) {
          $hInM = 60 * $dauerZahl;
        }

        $hInMInt = $hInM + 0;
         /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        if ($hInMInt >= $zahlen[0][0] && $hInMInt <= $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit genügt, um die Methode ' . $name . ' durchzuführen.';
        } else if ($hInMInt < $zahlen[0][0]) {
          $output = 'Die vorgeschlagene Zeit genügt nicht, um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($hInMInt > $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }

        
      } else if ((strpos(strtolower($dauer), "tage") !== false) or (strpos(strtolower($dauer), "tag") !== false)) {
        
        /*
        Tage in Minuten umgerechnet mit 1 Tag = 8 Arbeitsstunden und die Zeiteinheit wird herausgefiltert
        */ 
        $dauerZahl = str_replace(array('tag'), '', $dauer);
        $TinMin = 8 * 60;

        if ($dauerZahl > 0) {
          $TinMin = 8 * 60 * $dauerZahl;
        }
        $TinMinInt = $TinMin + 0;
         /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        if ($TinMinInt >= $zahlen[0][0] && $TinMinInt <= $zahlen[0][1]) {
          $output = ' Die vorgeschlagene Zeit genügt, um die Methode ' . $name . ' durchzuführen.';
        } else if ($TinMinInt < $zahlen[0][0]) {
          $output = ' Die vorgeschlagene Zeit genügt nicht, um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($TinMinInt > $zahlen[0][1]) {
          $output = ' Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }
        
      } else if ((strpos(strtolower($dauer), "woche") !== false) or (strpos(strtolower($dauer), "wochen") !== false)) {
        $dauerZahl = str_replace(array('woche'), '', $dauer);
        $wInMin = 8 * 60 * 5;
         /*
        Wochen in Minuten umgerechnet mit 1 Woche = 8 Stunden pro Tag und 5 Tage und die Zeiteinheit wird herausgefiltert
        */ 
        if ($dauerZahl > 0) {
          $wInMin = 8 * 60 * 5 * $dauerZahl;
        }
        $wInMinInt = $wInMin + 0;

         /*
        Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
        */ 
        if ($wInMinInt >= $zahlen[0][0] && $wInMinInt <= $zahlen[0][1]) {
          $output = 'Die vorgeschlagene Zeit genügt, um um die Methode ' . $name . ' durchzuführen.';
        } else if ($wInMinInt < $zahlen[0][0]) {
          $output = 'Die vorgeschlagene Zeit genügt nicht, um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
        } else if ($wInMinInt > $zahlen[0][1]) {
          $output = ' Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
          } else {
          $output = 'Fragen Sie bitte erneut nach';

        }
      }

      //$output = "neu";
      $dauerInt = $dauer + 0;
      /*
     Abfrage wird mit den Rahmenzeiten verglichen und es wird eine Antwort gewählt
     */ 
     if ($dauerInt >= $zahlen[0][0] && $dauerInt <= $zahlen[0][1]) {

       $output = 'Die vorgeschlagene Zeit genügt, um die Methode ' . $name . ' durchzuführen.';
    
      } else if ($dauerInt < $zahlen[0][0]) {

       $output = ' Die vorgeschlagene Zeit genügt nicht, um die Methode ' . $name . ' durchzuführen. Es werden minimal ' . $zahlen[0][0] .  ' Minuten und maximal ' . $zahlen[0][1] . ' Minuten benötigt.';
     
      } else if ($dauerInt > $zahlen[0][1]) {
       
        $output = ' Die vorgeschlagene Zeit sollte nicht für die Methode ' . $name . ' verwendet werden. Es sollten von ' . $zahlen[0][0] .  ' Minuten bis ' . $zahlen[0][1] . ' Minuten verwendet werden.';
      
      } else {
      
        $output = 'Fragen Sie bitte erneut nach';

     }




      $test .= " min: " . $zahlen[0][0] . " max: " . $zahlen[0][1] . " dauer: " . $dauer . " output: " . $output;

      return $output;
    }

    public function getHilfsmittel ($name, $hilfsmittel, $alleMethoden) {
      $output = '';

      foreach ($alleMethoden as $element) {

        $elementFil = str_replace(array( '(', ')', ',', '/'), '', $element);
        $elementFilter = str_replace(array('-', '   ' ), ' ', $elementFil);
       

        if (strpos(strtolower($elementFilter), strtolower($name)) !== false) {

          $name =  $element;
        
        }

        if (strpos(strtolower($name), strtolower('sechs w. methode empathize')) !== false) {

          $name = '6 W-Methode (Empathize)';

        }

        

        if (strpos(strtolower($name), strtolower('fünf warum-technik define')) !== false) {

          $name = '5-Warum-Technik (Define)';

        }

        $test .= ' ' . $element;


      }


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

        $output = $hilfsmittel . ' genügt um die Methode ' . $name . ' zu bearbeiten';

      } else if ($anzahl > $angefragt) {
        /*
        Falls nicht alle Hilfsmittel angefragt wurden. Außerdem werden die benötigten hilfsmittel angegeben
        */
        $output = $hilfsmittel . ' genügt nicht, um die Methode ' . $name . ' zu bearbeiten. Es werden die Hilfsmittel ' . $benötigt . ' benötigt.';

      }


      return $output;
    }


    //gibt alle Informationen zu einer Methode aus in der Reihenfolge wie sie auf der Webseite ist
    public function getAllInfos ($name, $alleMethoden) {
      
      foreach ($alleMethoden as $element) {

        $elementFil = str_replace(array( '(', ')', ',', '/'), '', $element);
        $elementFilter = str_replace(array('-', '   ' ), ' ', $elementFil);
       

        if (strpos(strtolower($elementFilter), strtolower($name)) !== false) {

          $name =  $element;
        
        }

        if (strpos(strtolower($name), strtolower('sechs w. methode empathize')) !== false) {

          $name = '6 W-Methode (Empathize)';

        }

        

        if (strpos(strtolower($name), strtolower('fünf warum-technik define')) !== false) {

          $name = '5-Warum-Technik (Define)';

        }

        $test .= ' ' . $element;


      }

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
        $output = 'Die Methode ' . $title . ' wird beschreiben als: ' . strip_tags($node->get('body')->value, '<p></p>');

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
        $output .= 'Die benötigte Zeit für ' . $title . ' beträgt: ';
        $output .= strip_tags($node->get('field_benoetigte_zeit')->value, '<p></p>');

      }

      for ($i = 0; $i < count($infoKategorien2); $i++) {
        
        foreach ($entity_ids as $nid) {
          $node = Node::load($nid);
          $title = $node->getTitle();
          $output .= ' ' . $infoKategorien2[$i] . ' von ' . $title . ': ';
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
    public function getMethodInfo ($name, $info, $alleMethoden) {


      foreach ($alleMethoden as $element) {

        $elementFil = str_replace(array( '(', ')', ',', '/'), '', $element);
        $elementFilter = str_replace(array('-', '   ' ), ' ', $elementFil);
        $test .= ' ' . $elementFilter;

        if (strpos(strtolower($elementFilter), strtolower($name)) !== false) {

          $name =  $element;
          
        }

        if (strpos(strtolower($name), strtolower('sechs w. methode empathize')) !== false) {

          $name = '6 W-Methode (Empathize)';

        }

        

        if (strpos(strtolower($name), strtolower('5 warum-technik define')) !== false) {

          $name = '5-Warum-Technik (Define)';

        }

        


      }

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
              $title = $node->getTitle();
              $output .= 'Die ' . $infoKategorien[$i] . ' von ' . $title . ': ';
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
                $output .= 'Die benötigte Zeit für ' . $title . ' beträgt: ';
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
