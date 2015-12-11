<?php
// Favorite (upvote) a page

include 'util/dbopen.php';

$page = secureParam($_POST['page'], $conn);

$sql = "INSERT INTO $upvotesTable (pageid) VALUES ((SELECT pageid FROM $pagesTable WHERE path = '$page' LIMIT 1));";

mysqli_query($conn, $sql);

include 'util/dbclose.php';