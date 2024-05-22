<?php

/**
 * @file
 * Provide a BaseForm for Searches on TMDB.
 */

namespace Drupal\movie_db\Form;

use \Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;

class MovieDBSearchForm extends FormBase {

  public function getFormId() {
    return 'movie_db_search_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['categories'] = [
      '#type' => 'radios',
      '#options' => [
        'all' =>  t('All'),
        'tv' => t('TV'),
        'movies' => t('Movies'),
      ],
      '#default_value' => 'all',
      '#required' => TRUE,
    ];

    $form['search'] = [
      '#type' => 'textfield',
      '#placeholder' => 'Search something',
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    return [
      '#type' => 'markup',
      '#markup' => '<h1>TESTE</h1>',
      '#cache' => [
        'max-age' => 0,
      ]
    ];
  }
}
