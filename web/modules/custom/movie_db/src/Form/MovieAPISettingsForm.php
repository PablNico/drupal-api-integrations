<?php

namespace Drupal\movie_db\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Render\Markup;

class MovieAPISettingsForm extends ConfigFormBase{

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'movie_db.settings'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'movie_db.settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('movie_db.settings');

    $form['movie_db'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('The Movie DB API Settings'),
    ];

    $form['movie_db']['base_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Base URL for API'),
      '#placeholder' => $config->get('base_url'),
      '#default_value' => 'https://api.themoviedb.org/3/',
      '#required' => TRUE,
    ];

    $form['credentials'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Your credentials settings'),
    ];

    $form['credentials']['api_key'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Your API Key'),
      '#placeholder' =>  $this->t('Click here to change'),
      '#default_value' => $config->get('api_key'),
    ];

    $form['credentials']['access_token'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Your Access Token'),
      '#placeholder' => $this->t('Click here to change'),
      '#default_value' => $config->get('access_token'),
    ];

    $form['options'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('API Options'),
    ];

    $form['options']['prefer_api_key']= [
      '#type' => 'checkbox',
      '#title' => $this->t('Force API Key usage'),
      '#description' => $this->t('Request made using API Key are made using GET parameters'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $configFactory = $this->configFactory()->getEditable('movie_db.settings');
    $config_keys = ['base_url', 'api_key', 'access_token', 'prefer_api_key'];
    foreach ($config_keys as $key) {
      empty($form_state->getValue($key)) ? NULL : $configFactory->set($key, $form_state->getValue($key))->save();
    }
    parent::submitForm($form, $form_state);
  }

}
