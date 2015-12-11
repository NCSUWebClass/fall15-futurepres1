<?php
// Get most upvoted pages

include 'util/dbopen.php';

$sql = "SELECT COUNT(*) as c, (SELECT name from $pagesTable WHERE pageid = t.pageid) as n, (SELECT path from $pagesTable WHERE pageid = t.pageid) as p " .
        "FROM (SELECT pageid FROM $pageLogTable WHERE logid IN (SELECT MAX(logid) FROM $pageLogTable GROUP BY ip) GROUP BY ip) as t GROUP BY pageid order by c DESC LIMIT 5;";

if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['n'] . '===' . $row['c'] . '===' . $row['p'] . '&&&';
    }
} else {
    echo '';
}

include 'util/dbclose.php';