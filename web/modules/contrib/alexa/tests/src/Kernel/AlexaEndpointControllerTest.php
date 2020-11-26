<?php

namespace Drupal\Tests\alexa\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\user\RoleInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test description.
 *
 * @group alexa
 */
class AlexaEndpointControllerTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'alexa',
    'alexa_demo',
    'user',
    'system',
  ];

  /**
   * Symfony 'http_kernel' service.
   *
   * @var \Symfony\Component\HttpKernel\HttpKernelInterface
   */
  protected $httpKernel;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig(['user']);
    $this->installSchema('system', ['sequences']);
    $this->installEntitySchema('user');
    // Create anonymous user.
    $anonymous = $this->container->get('entity_type.manager')
      ->getStorage('user')
      ->create([
        'uid' => 0,
        'status' => 0,
        'name' => '',
      ]);
    $anonymous->save();
    /** @var \Drupal\user\RoleInterface $anonymous_role */
    $anonymous_role = $this->container->get('entity_type.manager')
      ->getStorage('user_role')
      ->load(RoleInterface::ANONYMOUS_ID);
    $anonymous_role->grantPermission('access content');
    $anonymous_role->save();

    $this->container->get('state')->set('alexa.dev_mode', TRUE);

    $this->httpKernel = $this->container->get('http_kernel');
  }

  /**
   * Test acquia_demo launch request response.
   */
  public function testLaunchRequest() {
    $body = '{
	"version": "1.0",
	"session": {
		"new": true,
		"sessionId": "amzn1.echo-api.session.0000000-0000-0000-0000-00000000000",
		"application": {
			"applicationId": ""
		},
		"user": {
			"userId": "amzn1.account.AM3B00000000000000000000000"
		}
	},
	"context": {},
	"request": {
		"type": "LaunchRequest",
		"requestId": "amzn1.echo-api.request.0000000-0000-0000-0000-00000000000",
		"timestamp": "2020-05-18T14:52:54Z",
		"locale": "en-GB",
		"shouldLinkResultBeReturned": false
	}
}';
    $request = Request::create('/alexa/callback', 'POST', [], [], [], ['HTTP_CONTENT_TYPE' => 'application/json'], $body);
    $response = json_decode($this->httpKernel->handle($request)
      ->getContent(), TRUE);

    $expected = json_decode('{
  "version": "1.0",
  "response": {
    "outputSpeech": {
      "type": "PlainText",
      "text": "Hello Drupal"
    },
    "shouldEndSession": false,
    "card": null,
    "reprompt": null
  },
  "sessionAttributes": {}
}', TRUE);
    $this->assertEquals($expected, $response);

  }

  /**
   * Test alexa_demo launch request response.
   */
  public function testHelpIntentRequest() {
    $body = '{
	"version": "1.0",
	"session": {
		"new": true,
		"sessionId": "amzn1.echo-api.session.0000000-0000-0000-0000-00000000000",
		"application": {
			"applicationId": ""
		},
		"user": {
			"userId": "amzn1.account.AM3B00000000000000000000000"
		}
	},
	"context": {},
	"request": {
		"type": "IntentRequest",
		"requestId": "amzn1.echo-api.request.2e9c5662-1423-4e66-b8c5-2537a9e164d0",
		"timestamp": "2020-05-18T15:05:54Z",
		"locale": "en-GB",
		"intent": {
			"name": "AMAZON.HelpIntent",
			"confirmationStatus": "NONE"
		}
	}
}';
    $request = Request::create('/alexa/callback', 'POST', [], [], [], ['HTTP_CONTENT_TYPE' => 'application/json'], $body);
    $response = json_decode($this->httpKernel->handle($request)
      ->getContent(), TRUE);

    $expected = json_decode('{
  "version": "1.0",
  "response": {
    "outputSpeech": {
      "type": "PlainText",
      "text": "You can ask anything and I will respond with \"Hello Drupal\""
    },
    "shouldEndSession": false,
    "card": null,
    "reprompt": null
  },
  "sessionAttributes": {}
}', TRUE);
    $this->assertEquals($expected, $response);

  }

}
