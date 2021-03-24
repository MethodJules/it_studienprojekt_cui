<?php

namespace Drupal\alexa_cui\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\alexa\AlexaEvent;
use Drupal\node\Entity\Node;


/**
 * 
 * @Intent (
 *  id = "DTModell",
 *  label = @Translation("DTModell")
 * )
 * 
 */
class DTModell extends IntentPluginBase {


    public function process() {

      $slot_etwas = $this->request->getIntentSlot('etwas');
      $slot_phasen = $this->request->getIntentSlot('Phasen');
      $slot_unterschied = $this->request->getIntentSlot('Unterschied');
      $slot_raeume = $this->request->getIntentSlot('Raeume');

     
    

    if (!empty($slot_etwas)) {

        $output = "Die Forderung nach Innovationen zur Stärkung
        der Wettbewerbsfähigkeit von Unternehmen ist
        heute weit verbreitet. Gleichzeitig trifft man nicht
        selten auf die fatale Einstellung, dass Innovationen
        eher zufällig auftreten. Design Thinking stellt ein
        systematisches Vorgehen zur Entwicklung von
        Innovationen vor, das den kreativen Prozess mit
        Methoden zweckmäßig unterstützt und durch die
        Einteilung in Phasen besser plan- und steuerbar
        gestaltet.";
  
  
        
  
      } 
      if (!empty($slot_phasen)) {

        $output = "Der Design Thinking-Ansatz des ISUM kombiniert
        die Unterscheidung der drei Räume und zwei
        Denkweisen zu einem Modell mit sechs Phasen,
        das prozessübergreifend um „Vorbereitung“ und
        „Prozessmanagement“ ergänzt wird.
        Das Vorgehensmodell unterstützt das Method
        Engineering im Rahmen des Design Thinking, das
        darin besteht, für den jeweils nächsten Schritt im
        Projekt die geeignete Phase und den Einsatz einer
        zweckmäßigen Methode zu wählen.
        In der Regel werden die sechs Kernphasen in mehreren
        Iterationen durchlaufen, wobei Rücksprünge
        zu vorhergehenden Phasen vorgesehen sind.
        Rücksprünge werden als Resultat von Erkenntnisfortschritten
        und nicht etwa von Fehlern interpretiert.
        Insbesondere die ersten Iterationen sollten in
        kurzen Zeiträumen durchlaufen werden.
        In Phase 1 befindet man sich im Problemraum und
        pflegt das divergente Denken. Ziel der Phase ist es
        damit zunächst, das verfolgte verzwickte Problem
       durch möglichst viele denkbare konkrete
        Probleme und Herausforderungen zu konkretisieren.
        Phase 2 ist im Problemraum verortet und es wird
        konvergentes Denken betrieben. Ziel der Phase ist
        es, ein konkretes, konsistentes Bild der innovationstreibenden
        Herausforderung zu zeichnen.In Phase 3 befindet man sich im Lösungsraum und
        es wird divergent gedacht. Ziel der Phase ist es,
        zu dem aktuell durch Arbeiten im Problemraum
        vorgegebenen Herausforderungen möglichst viele
        denkbare Lösungen vorzuschlagen.
        ";
  
  
        
  
      } 
      if (!empty($slot_unterschied)) {

        $output = "Für Design Thinking grundlegend ist auch die Unterscheidung
        zweier Denkweisen:
        Divergentes Denken: Ziel ist die Sammlung
        von möglichst vielen Optionen. Typisch für
        divergentes Denken ist die Sammlung von
        möglichst vielen Ideen unter Ausblendung
        von Kritik. Anschaulich wird divergentes
        Denken auch am Beispiel der Frage, welche
        Einsatzmöglichkeiten man sich prinzipiell für
        einen Ziegelstein oder Bleistift etc. vorstellen
        kann.
        Konvergentes Denken: Ziel ist es, aus einer
        Menge von Optionen einzelne auszuwählen
        und zu konsistenten Lösungen zusammenzufügen.
        Die kombinierten Optionen dürfen
        sich dabei nicht widersprechen. Anschaulich
        wird der Unterschied zum divergenten Denken
        anhand der Frage, mit welchem Wort sich z. B.
        die Wörter „Fieber“, „Versicherung“ und „Welt“
        sinnvoll kombinieren lassen. Zur Lösung
        dieses Rätsels denkt man divergent an viele
        Wörter. Konvergent wird der Sinn der Wortkombinationen
        geprüft und die beste Lösung
        ausgewählt.";
  
  
        
  
      } 
      if (!empty($slot_raeume)) {

        $output = "Für Design Thinking werden diverse Vorgehensmodelle
        vorgeschlagen. Agenturen, die Design
        Thinking im Auftrag von Unternehmen ausführen,
        nutzen Vorgehensmodelle zum Kompetenzausweis.
        Eine grundlegende Gemeinsamkeit fortgeschrittener
        Vorgehensweisen stellt die Unterscheidung
        von drei Räumen dar,Problemraum: Zu adressierende Herausforderungen
        und Bedürfnisse sind zu identifizieren
        und für den weiteren Projektverlauf auszuwählen.
        Durch intensive Recherchen mit viel
        Empathie für die Zielgruppen ist sicherzustellen,
        dass echte Bedarfe adressiert werden. Lösungsraum: Erst im Anschluss sind für
        das ausgewählte Problem Lösungsansätze
        aufzuzeigen. Dabei sollten neueste technologische
        Ansätze berücksichtigt und viel
        Phantasie zum Einsatz kommen.Implementierungsraum: Unterschiedlichste
        Prototypen zeigen die Umsetzbarkeit der
        Lösungsidee und testen die Akzeptanz und
        Erfolgsaussichten der Innovation.";
  
  
        
  
      } 

      $this->response->setIntentResponse($output);
   
   


    }
  

   
  
   

         

}

