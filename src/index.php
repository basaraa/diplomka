<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
$link = $conn;
$selected = selectAllFieldsOfStudy($link);
if ($selected){
    foreach ($selected as $name)
    echo "$name";
}
?>


<?php
include "partials/footer.php";
?>
