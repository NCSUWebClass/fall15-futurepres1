<?php

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
<link rel="stylesheet" type="text/css" href="../thirdparty/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../thirdparty/font-awesome/css/font-awesome.min.css">

<style type="text/css">
span#x:hover {
    text-decoration: underline;
    cursor: pointer;
    display: inline-block;
}
</style>

<script type="text/javascript" src="../thirdparty/jquery/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="../thirdparty/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    var c = 0;

    $('div#sql').on('click', 'span#x', function() {
        $(this).remove();
        c--;
    });

    $('input#submit').on('click', function() {
        var parent = $('select#parent option:selected').attr('val').trim();
        var child = $('select#child option:selected').attr('val').trim();
        if (parent.length == 0 || child.length == 0) {
            $('select#parent').val('');
            $('select#child').val('');
            return;
        }
        var span = '<span id="x">';
        if (c > 0) {
            span += ', ';
        }
        span += '(' + parent + ', ' + child + ')';
        span += '</span>';
        $('div#sql').append(span);
        $('select#parent').val('');
        $('select#child').val('');
        c++;
    });
});
</script>
</head>

<body>

<div class="container container-fluid">
    <div class="row">
        <div class="col-xs-2">
            <label for="parent">Parent</label>
            <select id="parent">
                <option val=""></option>
                <?php
                if ($result = mysqli_query($conn, "SELECT pageid, name FROM $pagesTable ORDER BY name ASC")) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option val="' . $row['pageid'] . '">' . $row['name'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-xs-2">
            <label for="child">Child</label>
            <select id="child">
                <option val=""></option>
                <?php
                if ($result = mysqli_query($conn, "SELECT pageid, name FROM $pagesTable ORDER BY name ASC")) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option val="' . $row['pageid'] . '">' . $row['name'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-xs-2">
            <label for="submit">&nbsp;</label>
            <input type="submit" value="Generate SQL" id="submit" class="btn btn-block btn-success btn-sm">
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">&nbsp;</div>
    </div>

    <div class="row">
        <div class="col-xs-12">INSERT INTO parents (parent, child) VALUES</div>
    </div>
    <div class="row">
        <div class="col-xs-12" id="sql"></div>
    </div>

    <div class="row">
        <div class="col-xs-12">&nbsp;</div>
    </div>

    <?php
    if ($result = mysqli_query($conn, "SELECT pageid, name FROM $pagesTable ORDER BY name ASC")) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="row"><div class="col-xs-2">' . $row['name'] . '</div><div class="col-xs-2">' . $row['pageid'] . '</div></div>';
        }
    }
    ?>
</div>

</body>

</html>

<?php
mysqli_close($conn);
?>