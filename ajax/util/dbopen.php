<?php

// Open DB

// If you get a file not found error, execute these commands (Mac):
    // cd /var
    // sudo mkdir mysql
    // sudo chmod 755 mysql
    // cd mysql
    // sudo ln -s /tmp/mysql.sock mysql.sock
    // source: http://stackoverflow.com/questions/4219970/warning-mysql-connect-2002-no-such-file-or-directory-trying-to-connect-vi

$server = 'localhost';
$username = 'root';
$password = '';
$db = 'futurepres';

$conn = mysqli_connect($server, $username, $password, $db);

if (!$conn) {
    die('Connection error');
}

// Table names
$pageLogTable = 'eventlog';
$pagesTable = 'pages';
$upvotesTable = 'upvotes';
$relationsTable = 'parents';
$questionsTable = 'questions';

function secureParam($param, $conn) {
    return mysqli_real_escape_string($conn, $param);
}