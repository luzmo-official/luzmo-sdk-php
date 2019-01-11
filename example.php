<?php
require 'vendor/autoload.php';
use Cumulio\Cumulio;

// Setup connection
$client = Cumulio::initialize(
  '< Your API key >',
  '< Your API token >'
);


// Example 1: create a new dataset
$securable = $client->create('securable', array(
  'type' => 'dataset',
  'name' => array('nl' => 'Burrito-statistieken', 'en' => 'Burrito statistics')
));

// Example 2: update a dataset
$client->update('securable', $securable['id'], array('description' => array('nl' => 'Het aantal geconsumeerde burrito\'s per type')));


// Example 3: create 2 columns
$client->create(
  'column',
  array(
    'type'     => 'hierarchy',
    'format'   => '',
    'informat' => 'hierarchy',
    'order'    => 0,
    'name'     => array('nl' => 'Type burrito')
  ),
  array(
    array('role' => 'Securable', 'id' => $securable['id'])
  )
);
$client->create(
  'column',
  array(
    'type'     => 'numeric',
    'format'   => ',.0f',
    'informat' => 'numeric',
    'order'    => 1,
    'name'     => array('nl' => 'Burrito-gewicht')
  ),
  array(
    array('role' => 'Securable', 'id' => $securable['id'])
  )
);


// Example 4: push 2 data points to a (pre-existing) dataset
$client->create(
  'data',
  array(
    'securable_id' => $securable['id'],
    'data' => array(
      array('sweet','126'),
      array('sour', '352')
    )
  )
);