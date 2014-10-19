<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('config.inc.php');

function getJson($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    if($response == false)
    {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('Error: '.var_export($info));
    }

    curl_close($curl);
    $decoded = json_decode($response);
    return $decoded;
}

// Placeholders
$username = 'Irishsmurf';
$artist = 'Muse';

$lastfmJSON = 'http://ws.audioscrobbler.com/2.0/?method=user.getartisttracks&user='.$username.'&artist='.$artist.'&api_key='.$config['api_key'].'&format=json';

$decoded = getJson($lastfmJSON);

$pagenumber = $decoded->{'artisttracks'}->{'@attr'}->{'totalPages'};
$decoded = getJson($lastfmJSON.'&page='.$pagenumber);

$date = $decoded->{'artisttracks'}->{'track'}[0]->{'date'}->{'#text'};

echo $date.' UTC';

?>

<html lang="en">
<head>

    <title> First time listened - Last.fm </title>
    <link rel="stylesheet" href="https://www.paddez.com/style.css" type="text/css" media="screen" />

</head>

<body>
    <div id="content">
    <div id="main-content">
    <section id=intro">
    <header>
        <h2>Last.fm - First time listened to a band </h2>
    </header>
        <p> Lorum </p>
    </section>
    </div>
    </div>
   </body>

</html>
