<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Logout';
$pageMode = 'macro';
$warnings = '';

if ($loggedin) {
    destroySession();
    $warnings .= "\n                                <p>You have successfully logged out.</p>";
    echo "<meta http-equiv=\"refresh\" content=\"5; url=?$profile\" />";
} else {
    $warnings .= "\n                                <p>You are not logged in.</p>";
    echo "<meta http-equiv=\"refresh\" content=\"5; url=?$profile\" />";
}

include('styles/header.php');

echo "
                    <main>
                        <section class=\"login\">
                            <h4>Logout</h4>
" . ($warnings !== '' ? "                            <aside class=\"warning\">$warnings\n                            </aside>\n" : '') .
"                        </section>
                    </main>
";

include('styles/footer.php');

?>
