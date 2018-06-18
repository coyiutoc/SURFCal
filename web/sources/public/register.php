<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Register';
$pageMode = 'macro';

$createdAcc = false;
$errors = '';
$formInput = array();

if ($loggedin) {
    header("Location: ?$profile=home");
} else if (isset($_POST['username'])) {
    $formInput = array(
        'username' => isset($_POST['username']) ? $_POST['username'] : '',
        'password' => isset($_POST['password']) ? $_POST['password'] : '',
        'passwordC' => isset($_POST['passwordC']) ? $_POST['passwordC'] : '',
        'email' => isset($_POST['email']) ? $_POST['email'] : '',
        'emailC' => isset($_POST['emailC']) ? $_POST['emailC'] : '',
        'name' => isset($_POST['name']) ? $_POST['name'] : '',
        'bdateM' => isset($_POST['bdateM']) ? $_POST['bdateM'] : '',
        'bdateD' => isset($_POST['bdateD']) ? $_POST['bdateD'] : '',
        'bdateY' => isset($_POST['bdateY']) ? $_POST['bdateY'] : ''
    );
    
    if ($formInput['username'] === '') {
        $errors .= "\n                                    <li>Enter a username.</li>";
    }
    if ($formInput['password'] === '') {
        $errors .= "\n                                    <li>Enter a password</li>";
    }
    if ($formInput['passwordC'] === '' || $formInput['password'] !== $formInput['passwordC']) {
        $errors .= "\n                                    <li>Passwords do not match.</li>";
    }
    if ($formInput['email'] === '') {
        $errors .= "\n                                    <li>Enter an email.</li>";
    }
    if ($formInput['emailC'] === '' || $formInput['email'] !== $formInput['emailC']) {
        $errors .= "\n                                    <li>Emails do no match.</li>";
    }
    if ($formInput['name'] === '') {
        $errors .= "\n                                    <li>Enter your full name.</li>";
    }
    if ($formInput['bdateM'] === '' || $formInput['bdateD'] === '' || $formInput['bdateY'] === '' || $formInput['bdateM'] < 1 || $formInput['bdateM'] > 12 || $formInput['bdateD'] < 1 || $formInput['bdateD'] > 31 || $formInput['bdateY'] < 1900 || $formInput['bdateY'] > 2018) {
        $errors .= "\n                                    <li>Enter a valid birthday.</li>";
    }
    
    if ($errors === '') {
        if (checkExistingUsernameEmail($formInput['username'], $formInput['email'])) {
            $errors .= "\n                                    <li>Username or email is taken. Enter a different one.</li>";
        } else {
            $createdAcc = createAccountHelper($formInput['username'], $formInput['email'], sha1($formInput['password']), $formInput['name'], $formInput['bdateY'] . '-' . $formInput['bdateM'] . '-' . $formInput['bdateD']);
            if (!$createdAcc) {
                $errors .= "\n                                    <li>An unknown error occured.</li>";
            }
        }
    }
}

include('styles/header.php');

if ($createdAcc) {
     echo '
                    <main>
                        <section class="register">
                            <h4>Account Created</h4>
                            <p>You have successfully registered, ' . $formInput['name'] . '.</p>
                        </section>
                    </main>

';
} else {
    echo <<<_END
                    <main>
                        <section class="register">
                            <h4>Create an Account</h4>
_END;
    
    if ($errors !== '') {
        echo "
                            <aside class=\"errors\">
                                <ul>$errors
                                </ul>
                            </aside>";
    }
    
    echo "
                            <form action=\"?$profile=register\" method=\"post\">
                                <div class=\"field\"><label for=\"username\">Username</label><input type=\"text\" name=\"username\" placeholder=\"Username\" required=\"required\" maxlength=\"32\" value=\"" . sanitizeString($formInput['username']) . "\" /></div>
                                <div class=\"field\"><label for=\"password\">Password</label><input type=\"password\" name=\"password\" placeholder=\"Password\" required=\"required\" maxlength=\"64\" /></div>
                                <div class=\"field\"><label for=\"passwordC\">Confirm Password</label><input type=\"password\" name=\"passwordC\" placeholder=\"Password\" required=\"required\" maxlength=\"64\" /></div>
                                <div class=\"field\"><label for=\"email\">Email</label><input type=\"text\" name=\"email\" placeholder=\"Email\" required=\"required\" maxlength=\"64\" value=\"" . sanitizeString($formInput['email']) . "\" /></div>
                                <div class=\"field\"><label for=\"emailC\">Confirm Email</label><input type=\"text\" name=\"emailC\" placeholder=\"Email\" required=\"required\" maxlength=\"64\" value=\"" . sanitizeString($formInput['emailC']) . "\" /></div>
                                <div class=\"field\"><label for=\"name\">Name</label><input type=\"text\" name=\"name\" placeholder=\"Name\" required=\"required\" maxlength=\"32\" value=\"" . sanitizeString($formInput['name']) . "\" /></div>
                                <div class=\"field birthdate\">
                                    <label for=\"bdate\">Birthday</label>
                                    <input type=\"number\" name=\"bdateM\" placeholder=\"MM\" required=\"required\" min=\"1\" max=\"12\" value=\"" . sanitizeString($formInput['bdateM']) . "\" />
                                    <input type=\"number\" name=\"bdateD\" placeholder=\"DD\" required=\"required\" min=\"1\" max=\"31\" value=\"" . sanitizeString($formInput['bdateD']) . "\" />
                                    <input type=\"number\" name=\"bdateY\" placeholder=\"YYYY\" required=\"required\" min=\"0\" value=\"" . sanitizeString($formInput['bdateY']) . "\" />
                                </div>
                                <div class=\"field\"><input type=\"submit\" value=\"Create\" class=\"button\" /></div>
                            </form>";
    
    echo <<<_END
                            <p>Have an account? <a href="?$profile=login">Sign in.</a></p>
                        </section>
                    </main>

_END;
}

include('styles/footer.php');

?>
