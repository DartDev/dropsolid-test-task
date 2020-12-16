<?php

namespace Drupal\dropsolid_dependency_injection\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dropsolid_dependency_injection\RESTClientInterface;

/**
 * Class RestOutputController.
 *
 * @package Drupal\dropsolid_dependency_injection\Controller
 */
class RestOutputController extends ControllerBase {

  /**
   * Album ID.
   *
   * @var int
   */
  const ALBUM_ID = '2';

  /**
   * REST client.
   *
   * @var \Drupal\dropsolid_dependency_injection\RESTClientInterface
   */
  protected $restClient;

  /**
   * RestOutputController constructor.
   *
   * @param \Drupal\dropsolid_dependency_injection\RESTClientInterface $rest_client
   *   The REST client.
   */
  public function __construct(RESTClientInterface $rest_client) {
    $this->restClient = $rest_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dropsolid.rest_client')
    );
  }

  /**
   * Returns render array with photos.
   *
   * @return array
   *   Render array with photos.
   */
  public function showPhotos() {
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => ['url'],
      ],
    ];

    $photos = $this->restClient->getPhotos(static::ALBUM_ID);

    if ($photos) {
      foreach ($photos as $photo) {
        $build['rest_output_block']['photos'][] = [
          '#theme' => 'image',
          '#uri' => $photo['thumbnailUrl'],
          '#alt' => $photo['title'],
          '#title' => $photo['title'],
        ];
      }
    }

    return $build;
  }

}
