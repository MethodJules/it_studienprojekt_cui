<?php

namespace Drupal\alexa_cui\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\alexa\AlexaEvent;
use Drupal\node\Entity\Node;


/**
 * 
 * @Intent (
 *  id = "InitialIntent",
 *  label = @Translation("InitialIntent")
 * )
 * 
 */

class InitialIntent extends IntentPluginBase {
  /*
  Intent Nr. 7
  */
    public function process() {
      
      //Falls keine Informationen zu der Anfrage gefunden wurde wird dieser Output angegeben
      $output = 'Ich habe dich leider nicht verstanden kannst du bitte deine Frage wiederholen?';

      //liest die Slots aus 
      $slot_benutzen = $this->request->getIntentSlot('Benutzen');
      $slot_zsm = $this->request->getIntentSlot('Zsm');
        

      //Je nachdem welche Slots ausgelesen werden, wird eine andere Methode aufgerufen  
      if (!empty($slot_benutzen)) {

        $output = $this->getBenutzung();

      } else if (!empty($slot_zsm)) {

        $output = $this->getZusammenfassung();

      }
   
      $this->response->setIntentResponse($output);
      
    }

    //Gibt wieder was die Methode weiß und wie sie genutzt werden kann
    public function getZusammenfassung () {
      // Die Anzahl der bekannten Methoden
      $anzahl = 70;
      $output = 'Hallo, Freut mich, dass du dich für Design Thinking interessierst. 
      Ich kann dir einen guten Einblick in verschiedene Themenfelder des Design Thinking geben. 
      Ich besitze allgemeines Wissen über Design Thinking und kann dir den Design Thinking Prozess anhand verschiedener 
      Modelle erläutern. Im speziellen besitze ich auch Kentnisse über das Hildesheimer Design Thinking Modell, welches ich dir
      erklären kann. Zu meinen Fähigkeiten zählt auch das Wissen über unterschiedliche Methoden, die während des Design 
      Thinking Prozesses zum Einsatz kommen. Ich kann ' . $anzahl . ' Methoden voneinander unterscheiden und vorstellen.
      Möchtest du, dass ich dir allgemein etwas über Design Thinking erzähle, so stelle mir einfach beispielsweise folgende Frage: 
      Ich möchte wissen was Design Thinking ist.
      Möchtest du, dass ich dir unterschiedliche Methoden aufzähle, so frag mich einfachielsweise wie folgt: 
      Welche Methoden des Design Thinking kennst du?
      Möchtest du, dass ich dir bestimmte Methoden vorschlage, so stelle einfach beispielsweise folgende Frage:
      Zähle mir alle Design Thinking Methoden aus der Phase X auf, die länger als X dauern?
      Möchtest du etwas über eine konkrete Methode wissen, frag mich einfach Beispielsweise wie folgt ab und nenne den Namen der Methode:
      Nenne mir dir Vorteile von Methode X.
      Möchtest du, dass ich mit dir eine beispielhafte Methode durchspiele, so stelle einfach folgende Frage und gebe den Namen einer konkreten Methode an:
      Kannst du die Methode X modieren?
      Möchtest du mehr über Design Thinking wissen und das Hildeshimer Modell kennenlernen, dann stelle beispielhaft folgende Frage:
      Wie funktioniert das Design Thinking-Modell nach Hildesheimer Art?
      '; 
      
      
      return $output; 

    }

    //Gibt wieder wie die Methode genuttz werden kann
    public function getBenutzung () {
      
      $output = ' Um mit mir zu kommunizieren, musst du mir Fragen zu einzelnen Bestandteilen stellen.
      Möchtest du, dass ich dir allgemein etwas über Design Thinking erzähle, so stelle mir einfach beispielsweise folgende Frage:
      Ich möchte wissen was Design Thinking ist.
      Möchtest du, dass ich dir unterschiedliche Methoden aufzähle, so frag mich einfach Beispielsweise wie folgt:
      Welche Methoden des Design Thinking kennst du?
      Möchtest du, dass ich dir bestimmte Methoden vorschlage, so stelle einfach beispielsweise folgende Frage: 
      Zähle mir alle Design Thinking Methoden aus der Phase X auf, die länger als X dauern?
      Möchtest du etwas über eine konkrete Methode wissen, frag mich einfach Beispielsweise wie folgt ab und nenne den Namen der Methode:
      Nenne mir dir Vorteile von Methode X.
      Möchtest du, dass ich mit dir eine beispielhafte Methode durchspiele, so stelle einfach folgende Frage und gebe den Namen einer konkreten Methode an:
      Kannst du die Methode X modieren?
      Möchtest du mehr über Design Thinking wissen und das Hildeshimer Modell kennenlernen, dann stelle beispielhaft folgende Frage:
      Wie funktioniert das Design Thinking-Modell nach Hildesheimer Art?
      ';
      
      return $output;
    }

}
