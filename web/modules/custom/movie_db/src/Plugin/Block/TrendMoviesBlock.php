<?php

namespace Drupal\movie_db\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block with Movie trends of the day
 *
 * @Block(
 *   id = "trend_movies_block",
 *   admin_label = @Translation("Trend Movies"),
 *   category = @Translation("Trend Movies")
 * )
 */

class TrendMoviesBlock extends BlockBase {

  /**
   *
   */

  public function build()  {
    return [
      '#type' => 'markup',
      '#markup' => \Drupal::service('movie_db.api_integration')->trendMovies()->results[0]->original_title,
      '#cache' => [
        'max-age' => 0,
      ]
    ];
  }
}
