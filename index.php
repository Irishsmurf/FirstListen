<?php

include_once('config.inc.php');
$username = 'Irishsmurf';
$artist = 'Muse';
$apiKey = 'd7b0847df10e843f04f691b36736ee28';
$lastfmJSON = 'http://ws.audioscrobbler.com/2.0/?method=user.getartisttracks&user='.$username.'&artist='.$artist.'&api_key='.$apiKey.'&format=json';


$curl = curl_init($lastfmJSON);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
if($response == false)
{
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('Error: '.var_export($info));
}

curl_close($curl);
echo $response;


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
