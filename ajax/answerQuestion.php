<?php
// Answer a question

include 'util/dbopen.php';

$id = secureParam($_POST['id'], $conn);

$sql = "UPDATE $questionsTable SET answer = 1 WHERE questionsid = $id;";

mysqli_query($conn, $sql);

include 'util/dbclose.php';