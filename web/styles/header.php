<?php

if (basename($_SERVER['PHP_SELF']) === 'header.php') {
    require_once('../403.php');
}

echo <<<_END
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>SURFCal | $pageTitle</title>
        <meta name="robots" content="noindex, nofollow" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i400,400i,700,700i" rel="stylesheet" type="text/css" />
        <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous" />
        <link href="styles/styles.css" rel="stylesheet" type="text/css" />
        <script src="http://twemoji.maxcdn.com/2/twemoji.min.js?2.7"></script>
        <script src="scripts/surfcal.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="wrapper">
            <header class="global">
                <div class="container">
                    <h1>SURFCal</h1>
                    <nav class="userProfile">
                        <div class="userAvatar">
_END;

echo "                            <span>" . ($loggedin ? $user[0] : "<i class=\"fas fa-user\"></i>") . "</span>
                        </div><!-- .userAvatar ends -->
                        <div class=\"userMenu\">
                            <div class=\"userAvatar\">
                                <span>" . ($loggedin ? $user[0] : "<i class=\"fas fa-user\"></i>") . "</span>
                            </div><!-- .userAvatar ends -->
                            <h3>" . ($loggedin ? $user : "&nbsp;") . "</h3>
                            <p>" . ($loggedin ? $email : "&nbsp;") . "</p>
                            <ul>
                                <li><a href=\"?$profile=home\">Home</a></li>";

if ($loggedin) {
    echo "
                                <li><a href=\"?$profile=calendar\">Calendars</a></li>
                                <li><a href=\"?$profile=contact\">Contacts</a></li>
                                <li><a href=\"?$profile=settings\">Settings</a></li>";
    if ($admin) {
        echo "
                                <li class=\"acp\"><a href=\"?$profile=acp\">Admin Panel</a></li>";
    }
    echo "
                                <li class=\"logout\"><a href=\"?$profile=misc&amp;misc=logout\">Logout</a></li>";
} else {
    echo "
                                <li class=\"new\"><a href=\"?$profile=register\">Create Account</a></li>
                                <li class=\"login\"><a href=\"?$profile=login\">Login</a></li>";
}

echo <<<_END
                            </ul>
                            <h6>$siteTitle <span class="version">v$SiteMeta->version</span></h6>
                            <p>Instance: $SiteMeta->commit</p>
                            <p>Last Updated: $SiteMeta->lastUpdated</p>
                        </div><!-- .userMenu ends -->
                    </nav>
                </div><!-- .container ends -->
            </header>

_END;

echo "            <div class=\"main " . ($pageMode === '' ? '' : $pageMode.' ') ."global\">
                <div class=\"container\">
";

?>
