<?php
require_once("../config/config.php");
include "../databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset ($_POST["id"]) && isset ($_POST["type"]) ){
        $id=$_POST["id"];
        $type=$_POST["type"];
        $result = resetSubjects($conn,$id,$type);
        if($result)
        {
            echo $id;
        }
        else {
            http_response_code(400);
        }
    }
}
?>