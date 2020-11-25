<?php
namespace Drupal\dt_alexa\Controller;

use Drupal\node\Entity\Node;

class DTAlexaController {
    public function getMethods($raum, $phase) {
        $query = \Drupal::entityQuery('node');

        $query
            ->condition('type', 'Methode');
        $entity_ids = $query->execute();
        #dsm($entity_ids);
        $array = array();
        $nodes = Node::loadMultiple($entity_ids);
        foreach ($nodes as $node) {
            $title = $node->getTitle();
            $raum = $node->field_raum->value;
            dsm($title);
            dsm($raum);
            /* foreach ($node->field_raum as $reference) {
                dsm($reference);
            } */

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