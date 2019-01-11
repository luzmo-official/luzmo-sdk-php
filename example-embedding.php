<?php
require 'vendor/autoload.php';
use Cumulio\Cumulio;

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
      <p>Try to resize your page to see the dashboard adapting to different screen modes.</p>
    </div>
    <div id="myDashboard"></div>
    <script type="text/javascript">
      (function(d, a, s, h, b, oa, rd) { 
        if (!d[b]) {oa = a.createElement(s), oa.async = 1; oa.src = h; rd = a.getElementsByTagName(s)[0]; rd.parentNode.insertBefore(oa, rd);}
        d[b] = d[b] || {}; d[b].addDashboard = d[b].addDashboard || function(v) { (d[b].list = d[b].list || []).push(v) };
      })(window, document, 'script', 'https://cdn-a.cumul.io/js/embed.min.js', 'Cumulio');
      Cumulio.addDashboard({
        dashboardId: '<?php echo $dashboardId; ?>'
        , container: '#myDashboard'
        , key: '<?php echo $authorization['id']; ?>'
        , token: '<?php echo $authorization['token']; ?>'
      });
    </script>
  </body>
</html>