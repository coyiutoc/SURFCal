<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Login';
$pageMode = 'micro';
$warnings = '';

if ($loggedin) {
    header("Location: ?$profile=home");
} else if (isset($_POST['username']) && isset($_POST['password'])) {
    $u = sqlSanitize($conn,$_POST['username']);
    $p = sqlSanitize($conn,$_POST['password']);
    $r = getAccountByUser($u);
    if (($r['password'] === hash('sha512', $p.$r['salt'])) || (sha1($p) === $r['password']) || ($p === $r['password'])) {
        $loggedin = true;
        $user = $_SESSION['id'] = $r['id'];
        $user = $_SESSION['user'] = $r['username'];
        $admin = $_SESSION['admin'] = /*$r['admin']*/ true;
        $email = $_SESSION['email'] = $r['email'];
        $calId = $_SESSION['calId'] = $r['calendarId'];
        $pageTitle = "Logged In";
        $warnings .= "\n                                <p>You have successfully logged in.</p>";
    } else {
        $warnings .= "\n                                <p>Invalid username or password.</p>";
    }
} else if (isset($_POST['username']) || isset($_POST['password'])) {
    $warnings .= "\n                                <p>Invalid username or password.</p>";
}

include('styles/header.php');

if ($loggedin) {
    echo "
                    <main>
                        <section class=\"login\">
                            <h4>Logged In</h4>
                            <p>You have successfully logged in.</p>
                        </section>
                    </main>
";
} else {
    echo "
                    <main>
                        <section class=\"login\">
                            <h4>Login</h4>
" . ($warnings !== '' ? "                            <aside class=\"warning\">$warnings\n                            </aside>" : '') .
    "                            <form action=\"?$profile=login\" method=\"post\">
                                <div class=\"field\"><label for=\"username\">Username</label><input type=\"text\" name=\"username\" placeholder=\"Username\" required=\"required\" maxlength=\"32\" /></div>
                                <div class=\"field\"><label for=\"password\">Password</label><input type=\"password\" name=\"password\" placeholder=\"Password\" required=\"required\" maxlength=\"64\" /></div>
                                <div class=\"field\"><input type=\"submit\" value=\"Login\" class=\"button\" /></div>
                            </form>
                            <p>Forgot your password? <a href=\"#\">Reset it.</a></p>
                            <p>No account? <a href=\"?$profile=register\">Create one.</a></p>
                        </section>
                    </main>
";
}

include('styles/footer.php');

?>
