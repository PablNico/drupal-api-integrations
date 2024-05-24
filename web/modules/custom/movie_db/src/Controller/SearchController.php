<?php

namespace Drupal\movie_db\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 *
 */
class SearchController extends ControllerBase {

  public function search($category = NULL, $query = NULL) {
    $results = \Drupal::service('movie_db.api_integration')->searchMovie($query)['results'];
    return [
      '#theme' => 'movie_db_search_results_template',
      '#results' => $results,
      '#cache' => [
        'max-age' => 0,
      ]
    ];
  }

  public function fetchMovieDetails($movie_id) {


  }
}
