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
    // Need to get Date, Artist & SongName
    $pagenumber = $json->{'artisttracks'}->{'@attr'}->{'totalPages'};
    $json = getJson($url.'&page='.$pagenumber);
    $items = $json->{'artisttracks'}->{'@attr'}->{'items'};
    
    // If the number of items mod 50 = 1, this means the last page
    // has 1 item and isn't an array.
    if($items % 50 == 1)
    {
        $track = $json->{'artisttracks'}->{'track'};
    }
    else
        $track = $json->{'artisttracks'}->{'track'}[($items % 50) - 1];

    $song['date'] = $track->{'date'}->{'#text'};
    $song['artist'] = $track->{'artist'}->{'#text'};
    $song['song'] = $track->{'name'};
    $song['album'] = $track->{'album'}->{'#text'};

    return $song;
}

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

// Make sure the names are URL encoded
$username = urlencode($username);;
$artist = urlencode($artist);

$lastfmJSON = 'http://ws.audioscrobbler.com/2.0/?method=user.getartisttracks&user='.$username.'&artist='.$artist.'&api_key='.$config['api_key'].'&format=json';

$lastSong = getLastSong($lastfmJSON);
echo $lastSong['artist'].' - '.$lastSong['song'].' '.$lastSong['date'];

?>

<html lang="en">
<head>

    <title> First time listened - Last.fm </title>
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />

</head>

<body>
<header id="lastfm">
        <img src="images/last-fm_logo.png" id="lastlogo"/><h2 id="lastfm"> First time listened to a band </h2>
    </header>
    
    <section>
 
    <div id="first_content">
    
        <p id="first_input">
            <form action="index.php" method='post'>
            <input type="text" name="username" placeholder='Last.fm Username'></br></br>
            <input type="text" name="artist" placeholder='Artist'>
            </br></br>
            <input type="submit" value="Submit">
            </form>
        </p>
         <div id="first_returned"
    <p> The first time you listened to ARTIST was DAY, DAY MONTH YEAR at TIME</p>
    <p> The song was SONG</p>
    </div><!-- end of first_returned"-->
    </div><!-- end of first content-->
   
    
    </section
   </body>

</html>
