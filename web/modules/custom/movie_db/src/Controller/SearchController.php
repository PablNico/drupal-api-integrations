<?php

namespace Drupal\movie_db\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\movie_db\Services\MovieDBIntegration;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class SearchController extends ControllerBase {

  /**
   * @var \Drupal\movie_db\Services\MovieDBIntegration
   */
  protected MovieDBIntegration $apiClient;

  public function __construct(MovieDBIntegration $apiClient) {
    $this->apiClient = $apiClient;
  }

  /**
   * @{@inheritdoc}
   */
  public static function create(ContainerInterface $container){
    return new static(
      $container->get('movie_db.api_integration')
    );
  }
  public function search($category = NULL, $query = NULL) {
    $results = $this->apiClient->searchMovie($query)['results'];
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
