<?php

/**
 * @file
 */

namespace Drupal\movie_db\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\movie_db\Services\MovieDBIntegration;
use Psr\Log\LoggerInterface;

/**
 * Provides a block with Movie trends of the day
 */
 #[Block(
   id: "trend_movies_block",
   admin_label: new TranslatableMarkup("Trend Movies"),
   category: new TranslatableMarkup("Trend Movies")
 )]
class TrendMoviesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\movie_db\Services\MovieDBIntegration
   */
  protected MovieDBIntegration $apiClient;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected LoggerInterface $logger;


  /**
   * @param MovieDBIntegration $apiClient
   * @param LoggerInterface $logger
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition,
                              MovieDBIntegration $apiClient, LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->apiClient = $apiClient;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   * @param ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('movie_db.api_integration'),
      $container->get('logger.channel.movie_db'),
    );
  }
  /**
   *
   */

  public function build()  {
    $results = $this->apiClient->trending()['results'];
    $i = random_int(0, count($results) - 1);
    return [
      '#type' => 'markup',
      '#markup' => $this->t('<h3>%content</h3><img src=":img-url">',
        [
          '%content' =>  $results[$i]['original_name'] ?? $results[$i]['original_title'],
          ':img-url' => 'https://image.tmdb.org/t/p/original' . $results[$i]['poster_path']
        ]),
      '#cache' => [
        'max-age' => 0,
      ]
    ];
  }


}
