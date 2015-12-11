<?php
// Get most visited pages

include 'util/dbopen.php';

$sql = "SELECT $pagesTable.name AS n, $pagesTable.path AS p, (SELECT COUNT(*) FROM $pageLogTable WHERE $pagesTable.pageid = $pageLogTable.pageid) AS c " .
        "FROM $pagesTable, $pageLogTable WHERE $pagesTable.pageid != 1 GROUP BY n ORDER BY c DESC, $pagesTable.pageid ASC LIMIT 5;";

if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['n'] . '===' . $row['c'] . '===' . $row['p'] . '&&&';
    }
} else {
    echo '';
}

include 'util/dbclose.php';