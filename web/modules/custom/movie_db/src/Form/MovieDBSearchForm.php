<?php

/**
 * @file
 * Provide a BaseForm for Searches on TMDB.
 */

namespace Drupal\movie_db\Form;

use \Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MovieDBSearchForm extends FormBase {

  public function getFormId() {
    return 'movie_db_search_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['category'] = [
      '#type' => 'radios',
      '#options' => [
        'all' =>  t('All'),
        'tv' => t('TV'),
        'movies' => t('Movies'),
      ],
      '#default_value' => 'all',
      '#required' => TRUE,
    ];

    $form['query'] = [
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
    $category = $form_state->getValue('category');
    $query = $form_state->getValue('query');

    $path = Url::fromRoute('movie_db.search_page', ['category' => $category,'query' => $query])->toString();
    $response = new RedirectResponse($path);
    $response->send();
  }
}
