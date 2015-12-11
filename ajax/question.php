<?php
// Submit a question

include 'util/dbopen.php';

$page = secureParam($_POST['page'], $conn);
$question = secureParam($_POST['question'], $conn);

$sql = "INSERT INTO $questionsTable (pageid, question) VALUES ((SELECT pageid FROM $pagesTable WHERE path = '$page' LIMIT 1), '$question');";

mysqli_query($conn, $sql);

include 'util/dbclose.php';