<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 20.12.18
 * Time: 19:50
 */

namespace Drupal\dt_procedure\Controller;


use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class DTProcedure extends ControllerBase
{
    public function front_page()
    {


        global $base_url;

        $render_html = [
            '#type' => 'markup',
            '#markup' => '<a class="use-ajax" data-dialog-type="modal" href="' . $base_url . '/node/4">Hilfe</a><div id="visualization"></div>',
        ];

        $render_html['#attached']['library'][] = 'dt_procedure/dt-procedure';
        $render_html['#attached']['drupalSettings']['baseUrl'] = $base_url;
        /*
        return [
            '#theme' => 'dt_procedure',

        ];
        */

        return $render_html;


    }

    public function display_methods($room, $phase) {

      global $base_url;

      $roomTermId = $this->getTermIdByTermName($room);
      $phaseTermId = $this->getTermIdByTermName($phase);

      if($room == "projektmanagement") {
        $roomTermId = $this->getTermIdByTermName($room);
        $nodes = $this->getNodesByTaxonomyTermId($roomTermId);
        foreach ($nodes as $node) {
          $method_name = $node->get('title')->value;
          $node_id = $node->id();
          $path = $base_url . '/node/' . $node_id;
          $list[] = '<li>' . '<a href="' . $path .'">' . $method_name. '</a></li>';
        }
        //Build an unordered list
        $list_start_item = "<ul>";
        foreach($list as $list_item) {
          $list_start_item .= $list_item;
        }
        $list_start_item .= "</ul>";

        return new JsonResponse($list_start_item);
      } elseif ($room == "vorbereitung") {
        $roomTermId = $this->getTermIdByTermName($room);
        $nodes = $this->getNodesByTaxonomyTermId($roomTermId);
        foreach ($nodes as $node) {
          $method_name = $node->get('title')->value;
          $node_id = $node->id();
          $path = $base_url . '/node/' . $node_id;
          $list[] = '<li>' . '<a href="' . $path .'">' . $method_name. '</a></li>';
        }
        //Build an unordered list
        $list_start_item = "<ul>";
        foreach($list as $list_item) {
          $list_start_item .= $list_item;
        }
        $list_start_item .= "</ul>";

        return new JsonResponse($list_start_item);
      } else {

        $nodes = $this->getNodesByTaxonomyTermIds($roomTermId, $phaseTermId);
        foreach ($nodes as $node) {
          $method_name = $node->get('title')->value;
          $node_id = $node->id();
          $path = $base_url . '/node/' . $node_id;
          $list[] = '<li>' . '<a href="' . $path . '">' . $method_name . '</a></li>';
        }
        //Build an unordered list
        $list_start_item = "<ul>";
        foreach ($list as $list_item) {
          $list_start_item .= $list_item;
        }
        $list_start_item .= "</ul>";

        return new JsonResponse($list_start_item);
      }

    }

    public function getTermIdByTermName($termName){

      $connection = \Drupal::database();
      $result = $connection->query('SELECT tid FROM {taxonomy_term_field_data} 
                                          WHERE name = :name', [':name' => $termName])
                            ->fetchAll();

      $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
        ->loadByProperties(['name' => $termName]);



      $tid = $result[0]->tid;

      return $tid;

    }
    public function getNodesByTaxonomyTermId($termIdRoom) {
      $connection = \Drupal::database();
      $result = $connection->query("SELECT raum.entity_id FROM {node__field_raum} 
                                          AS raum WHERE field_raum_target_id = :termIdRoom ", [
        ':termIdRoom' => $termIdRoom,
      ] )
        ->fetchAll();
      foreach ($result as $row) {
        $nids[] = $row->entity_id;
      }

      $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

      if(!is_null($nids)) {
        return $nodes;
      }
    }
    public function getNodesByTaxonomyTermIds($termIdRoom, $termIdPhase) {

      $connection = \Drupal::database();
      $result = $connection->query("SELECT raum.entity_id FROM {node__field_raum} 
                                          AS raum INNER JOIN {node__field_phase} AS phasen ON 
                                          raum.entity_id = phasen.entity_id 
                                          WHERE field_raum_target_id = :termIdRoom 
                                          AND field_phase_target_id = :termIdPhase", [
                                            ':termIdRoom' => $termIdRoom,
                                            ':termIdPhase' => $termIdPhase,
      ] )
      ->fetchAll();

      foreach ($result as $row) {
        $nids[] = $row->entity_id;
      }

      $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

      if(!is_null($nids)) {
        return $nodes;
      }

      return [
        $links,
      ];

    }
}