<?php

namespace Drupal\alexa_demo\EventSubscriber;

use Alexa\Request\IntentRequest;
use Drupal\alexa\AlexaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber for Alexa request events.
 */
class RequestSubscriber implements EventSubscriberInterface {

  /**
   * Gets the event.
   */
  public static function getSubscribedEvents() {
    $events['alexaevent.request'][] = ['onRequest', 0];
    return $events;
  }

  /**
   * Called upon a request event.
   *
   * @param \Drupal\alexa\AlexaEvent $event
   *   The event object.
   */
  public function onRequest(AlexaEvent $event) {
    $request = $event->getRequest();
    $response = $event->getResponse();
    $intentName = $request instanceof IntentRequest ? $request->intentName : NULL;

    switch ($intentName) {
      case 'AMAZON.HelpIntent':
        $response->respond('You can ask anything and I will respond with "Hello Drupal"');
        break;

      default:
        $response->respond('Hello Drupal');
        break;
    }
  }

}
