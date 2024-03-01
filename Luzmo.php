<?php

namespace Luzmo;

class Luzmo {
  private $host;
  private $port = '443';
  private $apiVersion = '0.1.0';
  private $apiKey;
  private $apiToken;
  private $format;

  public function __construct() {
  }

  public static function initialize($apiKey, $apiToken, $host = 'https://api.luzmo.com', $format = 'array') {
    $instance = new self();
    $instance->apiKey = $apiKey;
    $instance->apiToken = $apiToken;
    $instance->host = $host ? $host : 'https://api.luzmo.com';
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
    $response = curl_exec($curl);
    $content_type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
    
    // Check content type and handle accordingly
    if (strpos($content_type, 'application/json') === false) {
      // Non-JSON response (e.g. image or PDF export) -> return as such
      return $response;
    }
    else {
      // JSON response from Luzmo API -> decode
      return json_decode($response, $this->format === 'array');
    }
  }

}