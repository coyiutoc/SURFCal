<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle .= ' - Accounts';
$pageMode = '';

$accQ = getAllAccounts();
$expQ = getExperiencedAccounts();

$exp = array();
while ($r = mysqli_fetch_array($expQ)) {
    $exp[] = $r['id'];
}

include('styles/header.php');

echo "
                    <main>
                        <header>
                            <h2>Site Admin View</h2>
                        </header>
                        <section class=\"accounts\">
                            <h3>Accounts</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Exp?</th>
                                    </tr>
                                </thead>
                                <tbody>";
while ($r = mysqli_fetch_array($accQ)) {
    echo "\n                                    <tr>"
    . "\n                                        <td>" . $r['id'] . "</td>"
    . "\n                                        <td>" . $r['username'] . "</td>"
    . "\n                                        <td>" . $r['email'] . "</td>"
    . "\n                                        <td>" . $r['name'] . "</td>"
    . "\n                                        <td><i class=\"fas fa-" . (in_array($r['id'], $exp) ? "check" : "times") . "\"></i></td>"
    . "\n                                    </tr>";
}
echo "
                                </tbody>
                            </table>
                        </section>
                    </main>
";

include('styles/footer.php');

?>
