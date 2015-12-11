<?php
// Gets the current number of people on a page

include './util/dbopen.php';

$page = secureParam($_POST['page'], $conn);
$children = secureParam($_POST['children'], $conn);     // True if we want the count for children of this page, false otherwise

$sql = "";
if ($children) {
    $sql = "SELECT COUNT(*) AS viewers " .
                "FROM (SELECT pageid FROM $pageLogTable WHERE logid IN (SELECT MAX(logid) FROM $pageLogTable GROUP BY ip) GROUP BY ip) AS pt " .
                "WHERE pt.pageid IN (SELECT t.child AS pid FROM (SELECT * FROM $relationsTable ORDER BY parent DESC) AS t JOIN " .
                "(SELECT @pv := (SELECT pageid FROM $pagesTable WHERE path = '$page')) AS tmp WHERE FIND_IN_SET(t.parent, @pv) > 0 ORDER BY pid ASC) OR " .
                "pt.pageid IN (SELECT pageid FROM $pagesTable WHERE path = '$page');";
} else {
    $sql = "SELECT COUNT(*) AS viewers " .
                "FROM (SELECT pageid FROM $pageLogTable WHERE logid IN (SELECT MAX(logid) FROM $pageLogTable GROUP BY ip) GROUP BY ip) AS pt " .
                "WHERE pt.pageid IN (SELECT pageid FROM $pagesTable WHERE path = '$page');";
}

if ($result = mysqli_query($conn, $sql)) {
    echo intval(mysqli_fetch_assoc($result)["viewers"]);
} else {
    echo -1;
}

include './util/dbclose.php';