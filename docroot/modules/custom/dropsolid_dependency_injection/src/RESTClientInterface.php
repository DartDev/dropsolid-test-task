<?php

namespace Drupal\dropsolid_dependency_injection;

/**
 * Interface RESTClientInterface.
 *
 * @package Drupal\dropsolid_dependency_injection
 */
interface RESTClientInterface {

  /**
   * REST API endpoint for requests.
   *
   * @var string
   */
  const ENDPOINT_BASE = 'https://jsonplaceholder.typicode.com';

  /**
   * Retrieves the photos from specified album.
   *
   * @param int $album_id
   *   ID of album to retrieve the photos from.
   *
   * @return array
   *   Returns an array with photos if they were successfully retrieved.
   *   Returns an empty array by default.
   */
  public function getPhotos($album_id);

}
