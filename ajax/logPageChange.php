<?php
// Log a new page change

include 'util/dbopen.php';

$page = secureParam($_POST['page'], $conn);
$ip = secureParam($_SERVER['REMOTE_ADDR'], $conn);

$sql = "INSERT INTO $pageLogTable (ip, pageid) VALUES ('$ip', (SELECT pageid FROM $pagesTable WHERE path = '$page' LIMIT 1));";

mysqli_query($conn, $sql);

include 'util/dbclose.php';
