<?php

namespace Cumulio;

class Cumulio {
  private $app = 'https://app.cumul.io';
  private $host = 'https://api.cumul.io';
  private $port = '443';
  private $apiVersion = '0.1.0';
  private $apiKey;
  private $apiToken;
  private $format;

  public function __construct() {
  }

  public static function initialize($apiKey, $apiToken, $format = 'array') {
    $instance = new self();
    $instance->apiKey = $apiKey;
    $instance->apiToken = $apiToken;
    $instance->format = ($format === 'object' || $format === 'array') ? $format : 'array';
    return $instance;
  }
  
  public function create($resource, $properties, $associations = array()) {
    return $this->_emit($resource, 'POST', array(
      'action'       => 'create',
      'properties'   => $properties,
      'associations' => $associations
    ));
  }

  public function get($resource, $filter) {
    return $this->_emit($resource, 'SEARCH', array(
      'action'       => 'get',
      'find'         => $filter
    ));
  }

  public function delete($resource, $id, $properties = array()) {
    return $this->_emit($resource, 'DELETE', array(
      'action'       => 'delete',
      'id'           => $id,
      'properties'   => $properties
    ));
  }

  public function update($resource, $id, $properties) {
    return $this->_emit($resource, 'PATCH', array(
      'action'       => 'update',
      'id'           => $id,
      'properties'   => $properties
    ));
  }

  public function associate($resource, $id, $associationRole, $associationId, $properties = array()) {
    return $this->_emit($resource, 'LINK', array(
      'action'       => 'associate',
      'id'           => $id,
      'resource'     => array('role' => $associationRole, 'id' => $associationId),
      'properties'   => $properties
    ));
  }

  public function dissociate($resource, $id, $associationRole, $associationId) {
    return $this->_emit($resource, 'UNLINK', array(
      'action'       => 'dissociate',
      'id'           => $id,
      'resource'     => array('role' => $associationRole, 'id' => $associationId)
    ));
  }

  public function query($filter) {
    return $this->get('data', $filter);    
  }

  public function iframe($dashboardId, $authorization) {
    return $this->app . '/s/' . $dashboardId . '?key=' . $authorization['id'] . '&token=' . $authorization['token'];
  }

  public function _emit($resource, $action, $query) {
    $url = $this->host . ':' . $this->port . '/' . $this->apiVersion . '/' . $resource;

    $query['key'] = $this->apiKey;
    $query['token'] = $this->apiToken;
    $query['version'] = $this->apiVersion;
    $payload = json_encode($query);

    $curl = curl_init();
    $curl_options = array(
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_URL            => $url,
      CURLOPT_CUSTOMREQUEST  => $action,
      CURLOPT_HTTPHEADER     => array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
      ),
      CURLOPT_POSTFIELDS     => $payload
    );
    curl_setopt_array($curl, $curl_options);
    return json_decode(curl_exec($curl), $this->format === 'array' ? true : false);
  }

}
