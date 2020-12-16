<?php

namespace Drupal\dropsolid_dependency_injection\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dropsolid_dependency_injection\RESTClientInterface;

/**
 * Provides a 'RestOutputBlock' block.
 *
 * @Block(
 *  id = "rest_output_block",
 *  admin_label = @Translation("Rest output block"),
 * )
 */
class RestOutputBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Album ID.
   *
   * @var int
   */
  const ALBUM_ID = '1';

  /**
   * REST client.
   *
   * @var \Drupal\dropsolid_dependency_injection\RESTClientInterface
   */
  protected $restClient;

  /**
   * Constructs a new RestOutputBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\dropsolid_dependency_injection\RESTClientInterface $rest_client
   *   The REST client.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RESTClientInterface $rest_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->restClient = $rest_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dropsolid.rest_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
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
