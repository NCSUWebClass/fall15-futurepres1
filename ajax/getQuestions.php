<?php
// Get all questions

include 'util/dbopen.php';

$sql = "SELECT questionsid, question, (SELECT name FROM $pagesTable WHERE $pagesTable.pageid = $questionsTable.pageid LIMIT 1) AS p " .
            "FROM $questionsTable WHERE answer = 0 ORDER BY questionsid DESC;";

if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['questionsid'] . '===' . $row['question'] . '===' . $row['p'] . '&&&';
    }
} else {
    echo '';
}

include 'util/dbclose.php';