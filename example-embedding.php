<?php
include('cumulio.php');

// Connect to Cumul.io API
$client = Cumulio::initialize('< Your API key >', '< Your API token >');

// On page requests of pages containing embedded dashboards, request an "authorization"
$dashboardId = '1d5db81a-3f88-4c17-bb4c-d796b2093dac';
$time = new DateTime();
$authorization = $client->create('authorization', array(
  'type' => 'temporary',
  // User restrictions
  'expiry' => $time->modify('+5 minutes')->format('c'),
  // Data & dashboard restrictions
  'securables' => array('4db23218-1bd5-44f9-bd2a-7e6051d69166', 'f335be80-b571-40a1-9eff-f642a135b826', $dashboardId),
  'filters' => array(
    array(
      'clause'       => 'where',
      'origin'       => 'global',
      'securable_id' => '4db23218-1bd5-44f9-bd2a-7e6051d69166',
      'column_id'    => '3e2b2a5d-9221-4a70-bf26-dfb85be868b8',
      'expression'   => '? = ?',
      'value'        => 'Damflex'
    )
  ),
  // Presentation options
  'locale_id' => 'en',
  'screenmode' => 'desktop'
));

// Generate the embedding url
$url = $client->iframe($dashboardId, $authorization);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Cumul.io embedding example</title>
  </head>
  <body>
    <div style="margin-left: 28px; width: 650px;">
      <h1 style="font-weight: 200;">Cumul.io embedding example</h1>
      <p>This page contains an example of an embedded dashboard of Cumul.io. The dashboard data is securely filtered server-side, so clients can only access data to which your application explicitly grants access (in this case, the "Damflex" product).</p>
    </div>
    <iframe src="<?php echo $url; ?>" style="border: 0; width: 1024px; height: 650px;"></iframe>
  </body>
</html>