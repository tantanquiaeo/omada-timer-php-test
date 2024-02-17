<?php
// Function to get the visitor's public IP address
function getPublicIPAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


// Read the JSON file
$json_data = file_get_contents('clients.json');
$data = json_decode($json_data, true);

// Get visitor's public IP address
$visitor_ip = getPublicIPAddress();

// Initialize end datetime variable
$end_datetime = date('Y-m-d H:i:s'); // Default value as current time

// Initialize end timestamp variable
$end_timestamp_seconds = null;

// Iterate through the data to find matching IP address
foreach ($data as $client) {
    if ($client['ip'] == $visitor_ip) {
        // Convert the end timestamp to seconds and then to readable date-time format in GMT
        $end_timestamp_seconds = $client['end'] / 1000; // Convert milliseconds to seconds
        date_default_timezone_set('GMT'); // Set the default timezone to GMT
        $end_datetime_gmt = gmdate('Y-m-d H:i:s', $end_timestamp_seconds); // Format the datetime string in GMT with 24-hour format

        // Add 8 hours (28800 seconds) to the GMT time to convert it to GMT+8
        $end_timestamp_seconds_gmt_plus_8 = $end_timestamp_seconds + 28800;
        $end_datetime = gmdate('Y-m-d H:i:s', $end_timestamp_seconds_gmt_plus_8); // Format the datetime string in GMT+8 with 24-hour format

        break; // No need to continue the loop once a match is found
    }
}

// If no matching IP address found, set end datetime to current time
if ($end_timestamp_seconds === null) {
    $end_datetime = date('Y-m-d H:i:s'); // Set end datetime to current time
}
?>

<!-- Display public IP address -->
<h2>Visitor's Public IP Address: <?php echo $visitor_ip; ?></h2>

<!-- Display end timestamp and converted end datetime for debugging -->
<p>End Timestamp: <?php echo $end_timestamp_seconds; ?></p>
<p>End Datetime: <?php echo $end_datetime; ?></p>

<a data-type="countdown"
 data-name="Time Remaining:"
 data-bg_color="#97B8FF"
 data-name_color="#008922"
 data-border_color="#888888"
 data-dt="<?php echo $end_datetime; ?>"
 data-timezone="Asia/Manila"
 style="display: block; width: 100%; position: relative; padding-bottom: 25%"
 class="tickcounter"
 href="//www.tickcounter.com"
 >Countdown</a>
<script>
// If no matching IP address found, set countdown timer to all zeros
if ('<?php echo $end_datetime; ?>' === '2025-01-01 00:00:00') {
    document.querySelector('.tickcounter').setAttribute('data-dt', '2025-01-01 00:00:00');
}
</script>

<script>(function(d, s, id) { var js, pjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//www.tickcounter.com/static/js/loader.js"; pjs.parentNode.insertBefore(js, pjs); }(document, "script", "tickcounter-sdk"));</script>
