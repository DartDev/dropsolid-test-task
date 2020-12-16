<?php

namespace Drupal\dropsolid_dependency_injection;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\dropsolid_dependency_injection\RESTClientInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class RESTClient.
 *
 * @package Drupal\dropsolid_dependency_injection
 */
class RESTClient implements RESTClientInterface {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * RESTClient constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   A Guzzle client object.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger channel factory.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $logger_factory, MessengerInterface $messenger, ConfigFactoryInterface $config_factory) {
    $this->httpClient = $http_client;
    $this->loggerFactory = $logger_factory;
    $this->messenger = $messenger;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getPhotos($album_id) {
    $endpoint = static::ENDPOINT_BASE . '/albums/' . $album_id . '/photos';
    $options = [];

    try {
      $request = $this->httpClient->request(
        'GET',
        $endpoint,
        $options
      );

      $photos = Json::decode($request->getBody()->getContents());
//      file_put_contents('vardump.txt', print_r($photos, TRUE));
    }
    catch (GuzzleException $e) {
      $this->handleError($e->getMessage());
    }

    return $photos ?? [];
  }

  /**
   * Passes the error message to the Messenger and Logger objects for handling.
   *
   * @param string $error_message
   *   A string containing the error message to log.
   */
  protected function handleError($error_message) {
    $this->loggerFactory->get('dropsolid_dependency_injection')->error($error_message);
  }
}