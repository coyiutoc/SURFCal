<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle .= ' - Stats';
$pageMode = '';

$totalItemsByType = getTotalItemsByType();

$stats = array(
    array('Items created', getTotalItems(), ''),
    array('&nbsp;&nbsp;&nbsp;&nbsp; - Events', mysqli_fetch_array($totalItemsByType), ''),
    array('&nbsp;&nbsp;&nbsp;&nbsp; - Reminders', mysqli_fetch_array($totalItemsByType), ''),
    array('&nbsp;&nbsp;&nbsp;&nbsp; - Tasks', mysqli_fetch_array($totalItemsByType), ''),
    array('&nbsp;&nbsp;&nbsp;&nbsp; - Notes', mysqli_fetch_array($totalItemsByType), ''),
    array('Average Items Per Account', getAverageItemsPerAccount(), ''),
    array('Average Contacts Per Account', getAverageContactsPerAccount(), ''),
    array('', getMinMaxContactsPerAccount(), ''),
    array('', getMinMaxContactsPerAccount(), ''),
    array('', getMinMaxItemsPerAccount('min'), ''),
    array('', getMinMaxItemsPerAccount('max'), '')
);

include('styles/header.php');

echo "
                    <main>
                        <header>
                            <h2>Site Admin View</h2>
                        </header>
                        <section class=\"accounts\">
                            <h3>Site Stats</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Metric</th>
                                        <th>Stat</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>";

for ($i = 0; $i < count($stats); $i++) {
    echo "\n                                    <tr>"
    . "\n                                        <td>" . $stats[$i][0] . "</td>"
    . "\n                                        <td>" . $stats[$i][1] . "</td>"
    . "\n                                        <td>" . $stats[$i][2] . "</td>"
    . "\n                                    </tr>";
}

echo "
                                </tbody>
                            </table>
                        </section>
                    </main>
";

echo var_dump(getTotalItems());
echo var_dump(getTotalItemsByType());

include('styles/footer.php');

?>
