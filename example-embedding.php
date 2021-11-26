<?php
// This code should be executed server-side. Your API key and token should be kept confidential.
require 'vendor/autoload.php';
use Cumulio\Cumulio;

// Connect to Cumul.io API
$client = Cumulio::initialize('< Your API key >', '< Your API token >'); // Fill in your API key & token
// Set third, optional property to https://api.cumul.io/ (default, EU multitenant env), https://api.us.cumul.io (US multitenant env) or your specific VPC address

// On page requests of pages containing embedded dashboards, request an "authorization"
$integrationId = 'b9a0c66e-2986-4b0f-913f-af54d9132453'; // Fill in your integration ID
$authorization = $client->create('authorization', array(
  'type' => 'sso',
  'integration_id' => $integrationId,
  'expiry' => '24 hours',
  'inactivity_interval' => '10 minutes',
  // user information
  'username' => '12345678', // unique, immutable username
  'name' => 'John Doe',
  'email' => 'johndoe@burritosnyc.com',
  'suborganization' => 'Burritos NYC',
  'role' => 'viewer',
  // data restrictions 
  'metadata' => array(
    'client_id' => 1234 // specify your parameter names and values
  )
));
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Cumul.io embedding example</title>
  </head>
  <body style="font-family: sans-serif;">
    <div style="margin-left: 28px">
      <h1 style="font-weight: 200;">Cumul.io embedding example</h1>
      <p>This page contains an example of an embedded dashboard of Cumul.io. The dashboard data is securely filtered server-side, so clients can only access data to which your application explicitly grants access (in this case, the data of client_id = 1234).</p>
      <p>Try to resize your page to see the dashboard adapting to different screen modes.</p>
    </div>
    <cumulio-dashboard
        appServer="https://app.cumul.io/"> 
        <!-- Set appServer to https://app.cumul.io/ (default, EU multitenant env), https://app.us.cumul.io (US multitenant env) or your specific VPC address -->
    </cumulio-dashboard>
    <!-- Check out the latest version on our npm page, as well as our components for frameworks such as react, vue and angular -->
    <script src="https://cdn-a.cumul.io/js/cumulio-dashboard/1.0.7/cumulio-dashboard.min.js" charset="utf-8"></script>
    <script type="text/javascript">
      const dashboardElement = document.querySelector('cumulio-dashboard');
      // We can now set the key and token to the dashboard component.
      dashboardElement.authKey = '<?php echo $authorization['id']; ?>'
      dashboardElement.authToken ='<?php echo $authorization['token']; ?>'
      // retrieve the accessible dashboards from the Integration
      dashboardElement.getAccessibleDashboards()
        .then(dashboards => {
          if (dashboards.length > 0) {
          dashboardElement.dashboardId = dashboards[0].id;
          };
        });
    </script>
  </body>
</html>
