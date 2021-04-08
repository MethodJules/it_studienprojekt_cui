<?php

namespace Drupal\alexa_cui\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\alexa\AlexaEvent;
use Drupal\node\Entity\Node;


/**
 * 
 * @Intent (
 *  id = "DesignThinkingIntent",
 *  label = @Translation("DesignThinkingIntent")
 * )
 * 
 */

class DesignThinkingIntent extends IntentPluginBase {
  /*
  Intent Nr. 1
  */
    public function process() {
      
      $output = '';

      //liest die Slots aus 
      $slot_dt = $this->request->getIntentSlot('DT');
      $slot_zsm = $this->request->getIntentSlot('Zsm');
      $slot_alle = $this->request->getIntentSlot('KompletteInformationen');


      //Je nachdem welche Slots ausgelesen werden, wird eine andere Methode aufgerufen  
      if (!empty($slot_dt)) {

        $output = $this->getInfo($slot_dt);

      } else if (!empty($slot_zsm)) {

        $output = $this->getZusammenfassung();

      }else if (!empty($slot_alle)) {

        $output = $this->getAllInfos();

      }

      //Falls keine Informationen zu der Anfrage gefunden wurde wird dieser Output angegeben
      if (strpos(strtolower($output), strtolower('')) !== false) {

        $ouptut = "Ich habe dich leider nicht verstanden kannst du bitte deine Frage wiederholen?";
      
      }
      
      $this->response->setIntentResponse($output);
      
    }

    // gibt eine Zusammenfassung von Design Thinking wieder
    public function getZusammenfassung () {

      $output = 'Design Thinking stellt ein systematisches und iteratives Vorgehen zur Lösung von
      Problemen und der Entwicklung neuer Ideen oder Innovationen dar. Es ist ein kreativer
      Prozess der durch unterschiedliche Methoden zweckmäßig unterstützt wird. Iterativ
      bedeutet dabei wiederkehrend oder wiederholend. Der Begriff Iteration wird allgemein
      für einen Prozess des mehrfachen Wiederholens gleicher oder ähnlicher Handlungen
      zur Annäherung an eine Lösung verwendet. Durch die Einteilung in Phasen ist der
      Design Thinking Ansatz zudem besser plan- und steuerbar. Jeder Phase dienen
      unterschiedliche Kreativitätsmethoden, um die jeweiligen Ziele zu erreichen. Je nach
      Anwendungsbereich kommt beim Design Thinking eine Vielzahl von Methoden zum
      Einsatz, die sich meist durch Benutzerorientierung, Visualisierung, Simulation sowie
      durch iteratives oder durch forschendes Vorgehen auszeichnen. Zu wichtigen
      Methoden des Design Thinking, die vor allem im Marketing eingesetzt werden, zählen
      unter anderen das Customer Journey Mapping oder die Erstellung von Persona.
      Design Thinking basiert auf der Annahme, dass Probleme besser gelöst werden können,
      wenn Menschen unterschiedlicher Disziplinen in einem die Kreativität fördernden
      Umfeld zusammenarbeiten, gemeinsam eine Fragestellung entwickeln, die Bedürfnisse
      und Motivationen von Menschen berücksichtigen und dann Konzepte entwickeln, die
      mehrfach geprüft werden. Design Thinking basiert auf der Annahme, dass Probleme besser gelöst werden können,
      wenn Menschen unterschiedlicher Disziplinen in einem die Kreativität fördernden
      Umfeld zusammenarbeiten, gemeinsam eine Fragestellung entwickeln, die Bedürfnisse
      und Motivationen von Menschen berücksichtigen und dann Konzepte entwickeln, die
      mehrfach geprüft werden.'; 
      
      
      return $output; 

    }

    //Gibt alle vorhandenen Inforamtionen zu Design Thinking wieder
    public function getAllInfos() {
      
      $output = 'Design Thinking stellt ein systematisches und iteratives Vorgehen zur Lösung von
      Problemen und der Entwicklung neuer Ideen oder Innovationen dar. Es ist ein kreativer
      Prozess der durch unterschiedliche Methoden zweckmäßig unterstützt wird. Iterativ
      bedeutet dabei wiederkehrend oder wiederholend. Der Begriff Iteration wird allgemein
      für einen Prozess des mehrfachen Wiederholens gleicher oder ähnlicher Handlungen
      zur Annäherung an eine Lösung verwendet. Durch die Einteilung in Phasen ist der
      Design Thinking Ansatz zudem besser plan- und steuerbar. Jeder Phase dienen
      unterschiedliche Kreativitätsmethoden, um die jeweiligen Ziele zu erreichen. Je nach
      Anwendungsbereich kommt beim Design Thinking eine Vielzahl von Methoden zum
      Einsatz, die sich meist durch Benutzerorientierung, Visualisierung, Simulation sowie
      durch iteratives oder durch forschendes Vorgehen auszeichnen. Zu wichtigen
      Methoden des Design Thinking, die vor allem im Marketing eingesetzt werden, zählen
      unter anderen das Customer Journey Mapping oder die Erstellung von Persona.
      Design Thinking basiert auf der Annahme, dass Probleme besser gelöst werden können,
      wenn Menschen unterschiedlicher Disziplinen in einem die Kreativität fördernden
      Umfeld zusammenarbeiten, gemeinsam eine Fragestellung entwickeln, die Bedürfnisse
      und Motivationen von Menschen berücksichtigen und dann Konzepte entwickeln, die
      mehrfach geprüft werden.
      Ziel des Design Thinking Prozesses ist es Lösungen zu finden, die aus Nutzersicht
      relevant und überzeugend sind. Der Ansatz des Design Thinking hat allgemeine
      Verbreitung gefunden und kann in unterschiedlichen Projekten angewendet werden,
      um Lösungsansätze zu finden. Dies können zum Beispiel innovative Geschäftsmodelle
      für Sach- und Dienstleistungen, optimierte Geschäftsprozesse oder neuartige
      Softwaresysteme sein.
      Zahlreiche internationale Unternehmen und Organisationen jeglicher Größe nutzen
      Design Thinking als Projekt-, Innovations-, Portfolio-, Analyse- und/oder
      Entwicklungsmethode. Insbesondere die SAP SE nutzt Design Thinking dabei als Ansatz,
      wie die Entwicklungseinheiten mit den Kunden und deren Endnutzern
      zusammenarbeiten. Weitere Unternehmen, die Design Thinking anwenden, sind u. a.
      Swisscom, Deutsche Bank, Volkswagen, Deutsche Bahn, Siemens, Airbnb und Pinterest.
      Es existieren diverse Vorgehensmodelle für das Design Thinking. Ein allgemeines
      Vorgehensmodell, welches an der Stanford University gelehrt wird teilt den Prozess in
      fünf Schritten auf, welche als Empathie, Definition, Ideenfindung, Prototyp und Testen
      definiert werden können. In der Empathie-Phase werden wird ein Problem, ein Produkt oder bestimmte Aspekte
      untersucht und beobachtet, um ein Verständnis von der Situation zu erhalten. In der
      Definitionsphase wird anschließend das Problem definiert. In der Ideenfindung werden
      neue Ansätze zur Lösung oder Optimierung generiert. Die Ideen werden in der
      Prototyp-Phase umgesetzt und können anhand dieses ausprobiert werden. In der
      Testphase wird die zukünftige Zielgruppe einbezogen. Diese Testen die neue Idee und
      geben Feedback für Verbesserungen und Alternativen. Hierdurch kann die Idee
      beziehungsweise das neue Produkt verbessert werden.
      Ein weiteres grundlegendes Vorgehensmodell stellt das Hildesheimer-Modell dar,
      welches auf den Grundprinzipien Team, Raum und Prozess aufbaut. In diesem werden sechs Design Thinking Phasen auf drei Räume aufgeteilt, welche als
      Problemraum, Lösungsraum und Implementierungsraum definiert werden. Jeder Raum
      enthält zwei Phasen, welche sich durch ihre individuellen Tätigkeiten und anwendbaren
      Methoden unterscheiden. Im Problemraum werden die zu adressierenden
      Herausforderungen und Bedürfnisse identifiziert. Im Lösungsraum werden neue
      Lösungsansätze entwickelt. Im Implementierungsraum findet die Umsetzung der Idee
      anhand eines Prototyp und dem anschließenden Test statt. Die beiden Phasen des
      Problemraums werden als Recherche und Definition bezeichnet. Die Phasen des
      Lösungsraums sind die Ideenfindung und Ideenauswahl. Der Implementierungsraum
      enthält die Phasen Prototyp und Test. In jedem Raum finden zudem zwei
      unterschiedliche Denkweisen je nach Phase statt. Jeder Raum beginnt mit einem
      divergenten Denkansatz und endet mit einem konvergenten Denken. Beim Divergenten
      denken ist das Ziel möglichst viele Informationen, Ansätze und Optionen zu sammeln.
      Kritik und Risiken werden zunächst ausgeblendet. Beim Konvergenten Denken werden
      die besten Lösungen ausgewählt, abgewogen und konkretisiert. Die drei Problemräume
      samt ihrer sechs Kernphasen werden im Hildesheimer Modell noch prozessübergreifend
      um die Vorbereitung und das Prozessmanagement ergänzt und unterstützt. ';
      
      return $output;
    }


    // gibt spezifische Informationen wieder
    public function getInfo($info) {
      
      /*
      Die einzelnen Abschnitte sind im Code hinterlegt, weil sie noch nicht in der Datenbank existiert haben
      */ 

      //Je nachdem welche Informationen von dem Nutzer abgefragt wurde und gibt diese wieder
      if (strpos(strtolower($info), 'ziel') !== false) {

        $output = 'Ziel des Design Thinking Prozesses ist es Lösungen zu finden, die aus Nutzersicht
        relevant und überzeugend sind. Der Ansatz des Design Thinking hat allgemeine
        Verbreitung gefunden und kann in unterschiedlichen Projekten angewendet werden,
        um Lösungsansätze zu finden. Dies können zum Beispiel innovative Geschäftsmodelle
        für Sach- und Dienstleistungen, optimierte Geschäftsprozesse oder neuartige
        Softwaresysteme sein. ';

      } else if (strpos(strtolower($info), 'phase') !== false) {

        $output = 'Es existieren diverse Vorgehensmodelle für das Design Thinking. Ein allgemeines
        Vorgehensmodell, welches an der Stanford University gelehrt wird teilt den Prozess in
        fünf Schritten auf, welche als Empathie, Definition, Ideenfindung, Prototyp und Testen
        definiert werden können. In der Empathie-Phase werden wird ein Problem, ein Produkt oder bestimmte Aspekte
        untersucht und beobachtet, um ein Verständnis von der Situation zu erhalten. In der
        Definitionsphase wird anschließend das Problem definiert. In der Ideenfindung werden
        neue Ansätze zur Lösung oder Optimierung generiert. Die Ideen werden in der
        Prototyp-Phase umgesetzt und können anhand dieses ausprobiert werden. In der
        Testphase wird die zukünftige Zielgruppe einbezogen. Diese Testen die neue Idee und
        geben Feedback für Verbesserungen und Alternativen. Hierdurch kann die Idee
        beziehungsweise das neue Produkt verbessert werden.
        Ein weiteres grundlegendes Vorgehensmodell stellt das Hildesheimer-Modell dar,
        welches auf den Grundprinzipien Team, Raum und Prozess aufbaut. In diesem werden sechs Design Thinking Phasen auf drei Räume aufgeteilt, welche als
        Problemraum, Lösungsraum und Implementierungsraum definiert werden. Jeder Raum
        enthält zwei Phasen, welche sich durch ihre individuellen Tätigkeiten und anwendbaren
        Methoden unterscheiden. Im Problemraum werden die zu adressierenden
        Herausforderungen und Bedürfnisse identifiziert. Im Lösungsraum werden neue
        Lösungsansätze entwickelt. Im Implementierungsraum findet die Umsetzung der Idee
        anhand eines Prototyp und dem anschließenden Test statt. Die beiden Phasen des
        Problemraums werden als Recherche und Definition bezeichnet. Die Phasen des
        Lösungsraums sind die Ideenfindung und Ideenauswahl. Der Implementierungsraum
        enthält die Phasen Prototyp und Test. In jedem Raum finden zudem zwei
        unterschiedliche Denkweisen je nach Phase statt. Jeder Raum beginnt mit einem
        divergenten Denkansatz und endet mit einem konvergenten Denken. Beim Divergenten
        denken ist das Ziel möglichst viele Informationen, Ansätze und Optionen zu sammeln.
        Kritik und Risiken werden zunächst ausgeblendet. Beim Konvergenten Denken werden
        die besten Lösungen ausgewählt, abgewogen und konkretisiert. Die drei Problemräume
        samt ihrer sechs Kernphasen werden im Hildesheimer Modell noch prozessübergreifend
        um die Vorbereitung und das Prozessmanagement ergänzt und unterstützt.';

      } else if ((strpos(strtolower($info), 'raum') !== false) or (strpos(strtolower($info), 'räume') !== false)) {

        $output = 'Ein grundlegendes Design Thinking Vorgehensmodell ist das Hildesheimer Modell. 
        In diesem werden sechs Design Thinking Phasen auf drei Räume aufgeteilt, welche als
        Problemraum, Lösungsraum und Implementierungsraum definiert werden. Jeder Raum
        enthält zwei Phasen, welche sich durch ihre individuellen Tätigkeiten und anwendbaren
        Methoden unterscheiden. Im Problemraum werden die zu adressierenden
        Herausforderungen und Bedürfnisse identifiziert. Im Lösungsraum werden neue
        Lösungsansätze entwickelt. Im Implementierungsraum findet die Umsetzung der Idee
        anhand eines Prototyp und dem anschließenden Test statt.';

      } else if (strpos(strtolower($info), 'stanford') !== false) {

        $output = 'Ein allgemeines
        Vorgehensmodell, welches an der Stanford University gelehrt wird teilt den Prozess in
        fünf Schritten auf, welche als Empathie, Definition, Ideenfindung, Prototyp und Testen
        definiert werden können. In der Empathie-Phase werden ein Problem, ein Produkt oder bestimmte Aspekte
        untersucht und beobachtet, um ein Verständnis von der Situation zu erhalten. In der
        Definitionsphase wird anschließend das Problem definiert. In der Ideenfindung werden
        neue Ansätze zur Lösung oder Optimierung generiert. Die Ideen werden in der
        Prototyp-Phase umgesetzt und können anhand dieses ausprobiert werden. In der
        Testphase wird die zukünftige Zielgruppe einbezogen. Diese Testen die neue Idee und
        geben Feedback für Verbesserungen und Alternativen. Hierdurch kann die Idee
        beziehungsweise das neue Produkt verbessert werden. ';

      } else if (strpos(strtolower($info), 'hildesheim') !== false) {

        $output = 'Ein grundlegendes Vorgehensmodell stellt das Hildesheimer-Modell dar,
        welches auf den Grundprinzipien Team, Raum und Prozess aufbaut. In diesem werden sechs Design Thinking Phasen auf drei Räume aufgeteilt, welche als
        Problemraum, Lösungsraum und Implementierungsraum definiert werden. Jeder Raum
        enthält zwei Phasen, welche sich durch ihre individuellen Tätigkeiten und anwendbaren
        Methoden unterscheiden. Im Problemraum werden die zu adressierenden
        Herausforderungen und Bedürfnisse identifiziert. Im Lösungsraum werden neue
        Lösungsansätze entwickelt. Im Implementierungsraum findet die Umsetzung der Idee
        anhand eines Prototyp und dem anschließenden Test statt. Die beiden Phasen des
        Problemraums werden als Recherche und Definition bezeichnet. Die Phasen des
        Lösungsraums sind die Ideenfindung und Ideenauswahl. Der Implementierungsraum
        enthält die Phasen Prototyp und Test. In jedem Raum finden zudem zwei
        unterschiedliche Denkweisen je nach Phase statt. Jeder Raum beginnt mit einem
        divergenten Denkansatz und endet mit einem konvergenten Denken. Beim Divergenten
        denken ist das Ziel möglichst viele Informationen, Ansätze und Optionen zu sammeln.
        Kritik und Risiken werden zunächst ausgeblendet. Beim Konvergenten Denken werden
        die besten Lösungen ausgewählt, abgewogen und konkretisiert. Die drei Problemräume
        samt ihrer sechs Kernphasen werden im Hildesheimer Modell noch prozessübergreifend
        um die Vorbereitung und das Prozessmanagement ergänzt und unterstützt. ';

      } else if (strpos(strtolower($info), 'def') !== false) {
        
        $output = 'Design Thinking stellt ein systematisches und iteratives Vorgehen zur Lösung von
        Problemen und der Entwicklung neuer Ideen oder Innovationen dar. Es ist ein kreativer
        Prozess der durch unterschiedliche Methoden zweckmäßig unterstützt wird. In diesem werden sechs Design Thinking Phasen auf drei Räume aufgeteilt, welche als
        Problemraum, Lösungsraum und Implementierungsraum definiert werden. Jeder Raum
        enthält zwei Phasen, welche sich durch ihre individuellen Tätigkeiten und anwendbaren
        Methoden unterscheiden. Im Problemraum werden die zu adressierenden
        Herausforderungen und Bedürfnisse identifiziert. Im Lösungsraum werden neue
        Lösungsansätze entwickelt. Im Implementierungsraum findet die Umsetzung der Idee
        anhand eines Prototyp und dem anschließenden Test statt. Die beiden Phasen des
        Problemraums werden als Recherche und Definition bezeichnet. Die Phasen des
        Lösungsraums sind die Ideenfindung und Ideenauswahl. Der Implementierungsraum
        enthält die Phasen Prototyp und Test. In jedem Raum finden zudem zwei
        unterschiedliche Denkweisen je nach Phase statt. Jeder Raum beginnt mit einem
        divergenten Denkansatz und endet mit einem konvergenten Denken. Beim Divergenten
        denken ist das Ziel möglichst viele Informationen, Ansätze und Optionen zu sammeln.
        Kritik und Risiken werden zunächst ausgeblendet. Beim Konvergenten Denken werden
        die besten Lösungen ausgewählt, abgewogen und konkretisiert. Die drei Problemräume
        samt ihrer sechs Kernphasen werden im Hildesheimer Modell noch prozessübergreifend
        um die Vorbereitung und das Prozessmanagement ergänzt und unterstützt. ';

      } else if (strpos(strtolower($info), 'viele') !== false) {

        $output = 'Es existieren diverse Vorgehensmodelle für das Design Thinking. Ein allgemeines
        Vorgehensmodell, welches an der Stanford University gelehrt wird teilt den Prozess in
        fünf Schritten auf, welche als Empathie, Definition, Ideenfindung, Prototyp und Testen
        definiert werden können. Im Hildesheimer Prozess werden sechs Design Thinking Phasen auf drei Räume aufgeteilt, welche als
        Problemraum, Lösungsraum und Implementierungsraum definiert werden.';

      } else if (strpos(strtolower($info), 'iterativ') !== false) {

        $output = 'Iterativ bedeutet dabei wiederkehrend oder wiederholend. Der Begriff Iteration wird allgemein
        für einen Prozess des mehrfachen Wiederholens gleicher oder ähnlicher Handlungen
        zur Annäherung an eine Lösung verwendet.';

      } else if (strpos(strtolower($info), 'betreibt') !== false) {

        $output = 'Zahlreiche internationale Unternehmen und Organisationen jeglicher Größe nutzen
        Design Thinking als Projekt-, Innovations-, Portfolio-, Analyse- und/oder
        Entwicklungsmethode. Insbesondere die SAP SE nutzt Design Thinking dabei als Ansatz,
        wie die Entwicklungseinheiten mit den Kunden und deren Endnutzern
        zusammenarbeiten. Weitere Unternehmen, die Design Thinking anwenden, sind u. a.
        Swisscom, Deutsche Bank, Volkswagen, Deutsche Bahn, Siemens, Airbnb und Pinterest.';
        
      } else if (strpos(strtolower($info), 'modell') !== false) {

        $output = 'Ich kenne die Design Thinking Modelle der Stanford University und das Design Thinking Modell hildehseimer Art.
        Im Modell der Stanford University wird der Prozess in die fünf Schritten Empathie, Definition, Ideenfindung, Prototyp 
        und Testen aufgeteilt werden. Das hildesheimer Modell baut auf den Grunprinzipien Team, Raum und Prozess auf. ';
        
      } else if ((strpos(strtolower($info), 'prozess') !== false) || (strpos(strtolower($info), 'funktioniert') !== false) ) {

        $output = 'Design Thinking stellt ein systematisches und iteratives Vorgehen zur Lösung von Problemen und der 
        Entwicklung neuer Ideen oder Innovationen dar. Es ist ein kreativer Prozess der durch unterschiedliche Methoden zweckmäßig 
        unterstützt wird. Iterativ bedeutet dabei wieder kehrend oder wiederholend. Der Begriff Iteration wird allgemein für einen 
        Prozess des mehrfachen Wiederholens gleicher oder ähnlicher Handlungen zur Annäherung an eine Lösung verwendet.
        Ziel des Design Thinking Prozesses ist es Lösungen zu finden, die aus Nutzersicht relevant und überzeugend sind. 
        Der Ansatz des Design Thinking hat allgemeine Verbreitung gefunden und kann in unterschiedlichen Proje kten angewendet werden, 
        um Lösungsansätze zu finden. Dies können zum Beispiel innovative Geschäftsmodelle für Sach - und Dienstleistungen, optimierte 
        Geschäftsprozesse oder neuartige Softwaresysteme sein';

      } else if (strpos(strtolower($info), 'benötigt') !== false) {

        $output = 'Durch die Einteilung in Phasen ist der Design Thinking Ansatz zudem be sser plan - und steuerbar.
        Jeder Phase dienen unterschiedliche Kreativitätsmethoden, um die jeweiligen Ziele zu erreichen. Je nach Anwendungsbereich kommt beim Design Thinking
         eine Vielzahl von Methoden zum Einsatz, die sich meist durch Benutzerorientierung , Visualisierung, Simulation sowie durch iteratives oder durch forschendes
          Vorgehen auszeichnen. Zu wichtigen Methoden des Design Thinking, die vor allem im Marketing eingesetzt werden, zählen unter anderen das Customer Journey Mapping
           oder die Erstellung von Persona';

      }else {
        $output = 'Ich habe dich leider nicht verstanden. Kannst du bitte noch einmal nachfragen. ';
      }


      return $output;

    }



}
