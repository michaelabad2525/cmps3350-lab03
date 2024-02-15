<?php
require_once("auth.php");
require_once("users.php");

if (GH_AUTH_TOKEN == '' || GH_USERNAME == '') {
    echo "Error: both GH_AUTH_TOKEN and GH_USERNAME must be defined\n";
    die();
}

// Create curl resource
$ch = curl_init();
$auth = "Authorization: Bearer " . GH_AUTH_TOKEN;
$useragent = "User-Agent: " . GH_USERNAME;
$contenttype = "Content-Type: application/json";
$headers = array($contenttype, $useragent, $auth);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING, '');


// Gets user data using GitHub REST API.
function getUser($user) {
    global $ch;
    
    curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/users/' . $user);
    
    $result = curl_exec($ch);

    return json_decode($result, true);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>CMPS 3350 Gallery</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>CMPS 3350 Gallery</h1>
        <p>Here's a collection of GitHub profiles from students enrolled in CMPS 3350 this semester.</p>
        <button onclick="shuffleUsers()">Shuffle</button>
        <div id="gallery">
<?php
// Iterate over users and create a GitHub card for each one.
foreach($users as $user) {
    $userData = getUser($user);
    // Skip over users that don't exist
    if (array_key_exists("message", $userData)) {
        if ($userData["message"] == "Not Found") {
            continue;
        }
        if ($userData["message"] == "Bad credentials") {
            echo "ERROR: " . $userData["message"] . "\n";
            die;
        }
    }
?>
    <div class="card">
        <div><strong><a href="<?=$userData['html_url']?>"><?=$userData['login']?></a></strong></div>
        Repos: <?= $userData['public_repos'] ?>
        <img src="<?= $userData['avatar_url']?>" alt="GitHub avatar for <?=$userData['login']?>">
    </div>
<?php
}
?>
        </div>
    </body>
</html>

<script>

const gallery = document.getElementById("gallery");

function shuffleUsers() {

    let cards = gallery.getElementsByClassName("card");

    let orders = Array.from(Array(cards.length).keys());
    orders.sort(() => Math.random() - 0.5);
    let order = 0;


    for(let card of cards) {
        card.style.order = orders[order++];
    }
}
</script>

<?php
curl_close($ch);
?>
