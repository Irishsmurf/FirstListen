<?php

include_once('config.inc.php');

if($debug)
{
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}



if(isset($_POST['artist']) && isset($_POST['username']))
{
    $username = $_POST['username'];
    $artist = $_POST['artist'];
}
else
{
    $username = 'Irishsmurf';
    $artist = 'Brand New';
}

if($debug)
    print_r($_POST);


function getLastSong($url)
{
    $json = getJson($url);

    //Error checking

    if(isset($json->{'error'}))
    {
        $error['error'] = true;
        $error['message'] = $json->{'message'};
        return $error;    
    }
    //Getting the number of pages.
    $pagenumber = $json->{'artisttracks'}->{'@attr'}->{'totalPages'};
    $json = getJson($url.'&page='.$pagenumber);
    $items = $json->{'artisttracks'}->{'@attr'}->{'items'};
    
    // If the number of items mod 50 = 1, this means the last page
    // has 1 item and isn't an array.
    if($items % 50 == 1)
        $track = $json->{'artisttracks'}->{'track'};
    else
        $track = $json->{'artisttracks'}->{'track'}[($items % 50) - 1];

    $song['date'] = $track->{'date'}->{'uts'};
    $song['artist'] = $track->{'artist'}->{'#text'};
    $song['song'] = $track->{'name'};
    $song['album'] = $track->{'album'}->{'#text'};

    return $song;
}

function getJson($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'www.paddez.com/projects/lastfm/first (cURL)');
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

// Make sure the names are URL encoded
$username = urlencode($username);;
$artist = urlencode($artist);

$lastfmJSON = 'http://ws.audioscrobbler.com/2.0/?method=user.getartisttracks&user='.$username.'&artist='.$artist.'&api_key='.$config['api_key'].'&format=json';

$lastSong = getLastSong($lastfmJSON);
if(isset($lastSong['error']))
    $error = $lastSong;
if($debug)
    echo $lastSong['artist'].' - '.$lastSong['song'].' '.$lastSong['date'];

?>

<html lang="en">
<head>

    <title> First time listened - Last.fm </title>
    <link rel="stylesheet" href="https://www.paddez.com/style.css" type="text/css" media="screen" />

</head>

<body>
    <div id="content">
    <div id="main-content">
    <section id="intro">
    <header>
        <h2>Last.fm - First time listened to a band </h2>
        <?php
            if($error)
            {
                echo "<h3>Error: ".$error['message']." </h3>";
            }
            else
            {
                echo "<h3>".$username." first listened to ".$lastSong['artist']." - ".$lastSong['song']."</h3>";
                echo "<h3>".date('l jS \of F Y h:i:s A', $lastSong['date'])."</h3>";
            }
        ?>
    </header>
        <p>
            <form action="index.php" method='post'>
            <input type="text" name="username" placeholder='Irishsmurf'>
            <input type="text" name="artist" placeholder='Brand New'>
            <input type="submit" value="Submit">
            </form>
        </p>
    </section>
    </div>
    </div>
   </body>

</html>
