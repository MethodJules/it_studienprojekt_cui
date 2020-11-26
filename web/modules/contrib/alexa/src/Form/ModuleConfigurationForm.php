<?php

namespace Drupal\alexa\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class ModuleConfigurationForm extends ConfigFormBase {

  /**
   * State Manager.
   *
   * @var \Drupal\Core\State\StateInterface $stateManager
   */
  protected $stateManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, StateInterface $stateManager) {
    parent::__construct($config_factory);
    $this->stateManager = $stateManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'alexa_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'alexa.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('alexa.settings');
    $form['application_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Application IDs'),
      '#description' => $this->t('In order for the Alexa system to work you need to add your Alexa Skill application IDs here. Separate multiple application IDs by a comma. Here is how to get your <a href="https://developer.amazon.com/public/solutions/alexa/alexa-skills-kit/docs/handling-requests-sent-by-alexa#Verifying that the Request is Intended for Your Service">application ID</a>.'),
      '#default_value' => $config->get('application_id'),
    ];
    $form['dev_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Development mode'),
      '#description' => $this->t('Skip certificate validation, so the request will be always accepted. Useful during development, MUST BE DISABLED for production environment.'),
      '#default_value' => $this->stateManager->get('alexa.dev_mode', FALSE),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('alexa.settings')
      ->set('application_id', $values['application_id'])
      ->save();

    $this->stateManager->set('alexa.dev_mode', $values['dev_mode']);
  }

}
