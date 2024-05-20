<?php

namespace Drupal\movie_db\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * @file
 * Implements the Integration with TheMovieDatabase. More info at: https://api.themoviedb.org/
 */
class MovieDBIntegration {

  const SERVICE_ID = 'movie_db.api_integration';

  /**
   * The logger interface.
   *
   * @var \Psr\Log\LoggerInterface
   */
  private LoggerInterface $logger;

  /**
   * The Movie DB API Client.
   *
   * @var \GuzzleHttp\Client
   */
  private Client $apiClient;

  /**
   * ConfigFactory service container.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface;
   */
  private ConfigFactoryInterface $configFactory;

  /**
   * Settings values for TheMovieDB config.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  private ImmutableConfig $movieSettings;

  /**
   * Static value of this service ID for easy external access.
   *
   * @return string
   */
  public static function serviceId(): string {
    return self::SERVICE_ID;
  }

  /**
   * Service container constructor.
   *
   * @param ConfigFactoryInterface $configFactory
   * @param LoggerInterface $logger
   */
  public function __construct(ConfigFactoryInterface $configFactory, LoggerInterface $logger) {
    $this->logger = $logger;
    $this->configFactory = $configFactory;
    $this->movieSettings = $this->configFactory->get('movie_db.settings');
    $this->apiClient = new Client([
      'base_uri' => $this->movieSettings->get('base_url'),
      'headers' => [
        'Authorization' => 'Bearer ' . $this->movieSettings->get('access_token'),
        'accept' => 'application/json'
      ]
    ]);
  }

  /**
   * Validate if at least one of the keys is valid to make requests.
   *
   * @returns bool
   * @throws GuzzleException
   */
  public function validateKey(): bool {
    $response = $this->getRequest('authentication');
    return json_decode($response->getBody()->getContents())->success;
  }

  /**
   * Trigger GET requests.
   *
   * @param string $endpoint
   * @param array $parameters
   * @param bool $force_api_key
   * @return ResponseInterface
   * @throws GuzzleException
   */
  private function getRequest(string $endpoint, array $parameters = []): ResponseInterface {
    $query_param = '?';
    foreach ($parameters as $key => $value) {
      $query_param .= "{$key}={$value}&";
    }
    if ($this->movieSettings->get('prefer_api_key')) {
      $api_key = $this->movieSettings->get('api_key');
      $query_param .= "api_key={$api_key}";
    }
    return $this->apiClient->get($endpoint . $query_param);
  }


  /**
   * Make a request to /search/movie endpoint. See: https://developer.themoviedb.org/reference/search-movie.
   * @throws GuzzleException
   */
  public function searchMovie($name, $parameters = []) {
    $parameters['query'] = $name;
    $parameters = array_reverse($parameters);
    $response = $this->getRequest('search/movie', $parameters);
    return json_decode($response->getBody()->getContents(), true);
  }

  public function trending($time_window = 'day', $category = "all",
                           $parameters = ['language' => 'en-US', 'page' => 1]
  ) {
    $parameters = array_reverse($parameters);
    $response = $this->getRequest("trending/{$category}/{$time_window}", $parameters);
    return json_decode($response->getBody()->getContents(), true);
  }

}
