<?php

namespace Drupal\chatbot_api_apiai;

use ApiAi\Model\Context;
use Drupal\api_ai_webhook\ApiAi\Model\Webhook\Response;
use Drupal\chatbot_api\IntentResponseInterface;

/**
 * Proxy wrapping Api.ai Response in a ChatbotRequestInterface.
 *
 * @package Drupal\chatbot_api_apiai
 */
class IntentResponseApiAiProxy implements IntentResponseInterface {

  use ApiAiContextTrait;

  /**
   * Original object.
   *
   * @var \ApiAi\Model\Webhook\Response
   */
  protected $original;

  /**
   * IntentResponseAlexaProxy constructor.
   *
   * @param \ApiAi\Model\Webhook\Response $original
   *   Original response instance.
   */
  public function __construct(Response $original) {
    $this->original = $original;
  }

  /**
   * Proxy-er calling original response methods.
   *
   * @param string $method
   *   The name of the method being called.
   * @param array $args
   *   Array of arguments passed to the method.
   *
   * @return mixed
   *   Value returned from the method.
   */
  public function __call($method, array $args) {
    return call_user_func_array([$this->original, $method], $args);
  }

  /**
   * {@inheritdoc}
   */
  public function addIntentAttribute($name, $value) {

    // Lookup for existing context.
    $contexts = $this->original->get('contextOut', []);
    /** @var \ApiAi\Model\Context $context */
    foreach ($contexts as $context) {
      if ($this->contextNameIs($context, $this->getContextName($name))) {
        // Original library doesn't allow parameters settings. Let's work
        // this around.
        $params = $context->getParameters();
        $params[$this->getParameterName($name)] = $value;
        $context->add('parameters', $params);
        return;
      }
    }

    // No context with this name has been found. Create a new one.
    $data = [
      'name' => $this->getContextName($name),
      'parameters' => [
        $this->getParameterName($name) => $value,
      ],
    ];
    $context = new Context($data);
    $this->original->addContext($context);
  }

  /**
   * {@inheritdoc}
   */
  public function setIntentResponse($text) {
    return $this->original->setSpeech($text);
  }

  /**
   * {@inheritdoc}
   */
  public function setIntentDisplayCard($content, $title = NULL) {
    return $this->original->setDisplayText($content);
  }

}
