<?php

namespace Drupal\alexa_cui\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\IntentPluginBase;
use Drupal\alexa\AlexaEvent;
use Drupal\node\Entity\Node;


/**
 * 
 * @Intent (
 *  id = "DefaultIntent",
 *  label = @Translation("DefaultIntent")
 * )
 * 
 */
class DefaultIntent extends IntentPluginBase {
    public function process() {
        $this->response->setIntentResponse('Willkommen im IT-Studienprojekt');
    }
}
