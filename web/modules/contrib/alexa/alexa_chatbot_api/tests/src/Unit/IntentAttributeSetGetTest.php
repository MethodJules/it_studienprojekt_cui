<?php

namespace Drupal\Tests\alexa_chatbot_api\Unit;

use Alexa\Request\Certificate;
use Alexa\Request\Request;
use Alexa\Response\Response;
use Drupal\alexa_chatbot_api\IntentRequestAlexaProxy;
use Drupal\alexa_chatbot_api\IntentResponseAlexaProxy;
use Drupal\Tests\UnitTestCase;
use Prophecy\Argument;

/**
 * Tests set/get intent attributes for Alexa proxy classes.
 *
 * @group alexa
 */
class IntentAttributeSetGetTest extends UnitTestCase {

  /**
   * Mockup of \Alexa\Request\Certificate , to be reveal()-ed.
   *
   * @var \Alexa\Request\Certificate|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $certificate;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $certificate = $this->prophesize(Certificate::class);
    $certificate->validateRequest(Argument::type('string'))->willReturn();
    $certificate->getCertificate()->willReturn('');
    $certificate->validateCertificate()->willReturn(TRUE);

    $this->certificate = $certificate;
  }

  /**
   * Tests getIntentSlot method.
   *
   * @dataProvider requestJson
   */
  public function testGetIntentSlot($json, $intent_name, $slots, $attributes) {
    $original_request = new Request($json);
    $original_request->setCertificateDependency($this->certificate->reveal());
    $request = new IntentRequestAlexaProxy($original_request->fromData());

    $this->assertEquals($intent_name, $request->getIntentName());

    foreach ($slots as $name => $value) {
      $this->assertEquals(mb_strtolower($value), mb_strtolower($request->getIntentSlot($name)));
    }
  }

  /**
   * Tests getIntentAttribute() method.
   *
   * @dataProvider requestJson
   */
  public function testGetIntentAttribute($json, $intent_name, $slots, $attributes) {
    $original_request = new Request($json);
    $original_request->setCertificateDependency($this->certificate->reveal());
    $request = new IntentRequestAlexaProxy($original_request->fromData());

    foreach ($attributes as $name => $value) {
      $this->assertEquals(mb_strtolower($value), mb_strtolower($request->getIntentAttribute($name)));
    }
  }

  /**
   * Tests setIntentAttribute() method.
   */
  public function testSetIntentAttribute() {

    $original_response = new Response();
    $response = new IntentResponseAlexaProxy($original_response);

    // Set some contexts.
    $response->addIntentAttribute('foo', 'bar');
    $response->addIntentAttribute('foo2', 'bar2');

    // Assert setter works.
    $data = $response->render();
    $this->assertEquals($data['sessionAttributes']['foo'], 'bar');
    $this->assertEquals($data['sessionAttributes']['foo2'], 'bar2');

    // Change some parameters.
    $response->addIntentAttribute('foo', 'foobar');
    $response->addIntentAttribute('foo3', 'foobar3');

    // Assert setter works.
    $data = $response->render();
    $this->assertEquals($data['sessionAttributes']['foo'], 'foobar');
    $this->assertEquals($data['sessionAttributes']['foo2'], 'bar2');
    $this->assertEquals($data['sessionAttributes']['foo3'], 'foobar3');

  }

  /**
   * Request JSON data provider.
   *
   * @return array
   *   Array of JSON request data + intent name + array of expected slots and
   *   session attributes both in the format name => value.
   */
  public function requestJson() {
    $body = <<<EOL
{
  "version": "1.0",
	"session": {
		"new": false,
		"sessionId": "amzn1.echo-api.session.0000000-0000-0000-0000-00000000000",
		"application": {
			"applicationId": ""
		},
		"attributes": {
			"foo": "bar",
			"foo2": "bar2"
		},
		"user": {
			"userId": "amzn1.account.AM3B00000000000000000000000"
		}
	},
	"context": {},
	"request": {
		"type": "IntentRequest",
		"requestId": "amzn1.echo-api.request.0000000-0000-0000-0000-00000000000",
		"timestamp": "2020-05-18T17:36:28Z",
		"locale": "en-GB",
		"intent": {
			"name": "ListItemsInRegionByDate",
			"confirmationStatus": "NONE",
			"slots": {
				"Region": {
					"name": "Region",
					"value": "shire",
					"resolutions": {
						"resolutionsPerAuthority": [
							{
								"authority": "amzn1.er-authority.echo-sdk.amzn1.ask.skill.0000000-0000-0000-0000-00000000000.REGION",
								"status": {
									"code": "ER_SUCCESS_MATCH"
								},
								"values": [
									{
										"value": {
											"name": "Shire",
											"id": "xxxx"
										}
									}
								]
							}
						]
					},
					"confirmationStatus": "NONE",
					"source": "USER"
				},
				"Date": {
					"name": "Date",
					"value": "2020-05-19",
					"confirmationStatus": "NONE",
					"source": "USER"
				}
			}
		}
	}
}
EOL;
    return [
      [
        $body,
        'ListItemsInRegionByDate',
        ['Region' => 'Shire', 'Date' => '2020-05-19'],
        ['foo' => 'bar', 'foo2' => 'bar2'],
      ],
    ];
  }

}
