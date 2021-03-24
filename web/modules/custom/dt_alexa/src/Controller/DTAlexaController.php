<?php
namespace Drupal\dt_alexa\Controller;

use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

class DTAlexaController {
    public function getMethods($raum, $phase) {
        $query = \Drupal::entityQuery('node');

        $query
            ->condition('type', 'Methode');
        $entity_ids = $query->execute();
        #dsm($entity_ids);
        $array = array();
        $nodes = Node::loadMultiple($entity_ids); //Damit werden alle nodes gleichzeitig geholt
        //Iteriere über alle node-Objekte
        foreach ($nodes as $node) {            
            $term = Term::load($node->get('field_raum')->target_id); //Hole das Term-Objekt des Feldes field_raum
            //Da manche Term-Objekte noch null sind, da unser aktueller DB-Stand das abbildet muss das geprueft werden,
            //sonst wird ein Fehler geworfen
            if(!is_null($term)) {
                $raum = $term->getName();
                ksm($raum); // Hier dann tauschen mit dsm(). Ich habe hier Kint als Debug Modul installiert (https://www.drupal.org/docs/contributed-modules/devel/installation-whats-in-the-box)
            }
        }

        /* foreach ($entity_ids as $key => $value) {
            #dsm($value);
            $node = Node::load($value);
            $title = $node->getTitle();
            dsm($title);
            /* foreach ($node->field_raum as $reference) {
                $references[] = $reference;
            } */
            #dsm($references);
            #$tid = $node->get('field_raum')->value; #gibt Taxonomie-ID!!!
            #dsm($tid);
            /* $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid);
            $name_raum = $term->label();

            $method_phase = $node->get('field_phase')->value;
            $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($method_phase);
            $name_phase = $term->label();

            if ((!empty($raum)) and !(empty($phase)) and (strcmp($raum, $name_raum) == 0) and (strcmp($phase, $name_phase) == 0)) {
                array_push($array, $title);
            } elseif (!empty($raum) and (strcmp($raum, $name_raum) == 0)) {
                array_push($array, $title);
            } elseif (!empty($phase) and (strcmp($phase, $name_phase) == 0)) {
                array_push($array, $title);
            } else {
                array_push($array, $title);
            }  */
        #}

        dsm($array);
        return ['#markup' => 'Hier werden Methoden angezeigt.'];
    }
}