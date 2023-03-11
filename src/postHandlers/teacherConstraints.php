<?php
require_once("../config/config.php");
include "../databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["id"]) && !(isset($_POST["list"]))){
        $teacherId=$_POST["id"];
        $selected=selectTeacherConstraints($conn,$teacherId);
        if($selected)
        {
            echo'<select class="form-control" name= "id" id="id" required>';
            while ($constraint = mysqli_fetch_assoc($selected)) {
                $id = $constraint["id"];
                $day= $constraint["banned_day"] ? $constraint["banned_day"] : '';
                $from = " od ".date('h:i', strtotime($constraint["time_from"]));
                $to = " do ".date('h:i', strtotime($constraint["time_to"]));
                $name = $day.$from.$to;
                echo "<option value= '$id'>$name</option>";
            }
            echo'</select>';
        }
        else http_response_code(400);
    }
    else if (isset($_POST["id"]) && isset($_POST["list"])){
        $teacherId=$_POST["id"];
        $selected=selectTeacherConstraints($conn,$teacherId);
        if($selected)
        {
            $z=0;
            while ($constraint = mysqli_fetch_assoc($selected)) {
                if ($z==0){
                    $z=1;
                    echo '<h1 id="succes_found_coinstraint">Zoznam obmedzení:</h1>';
                }
                $id = $constraint["id"];
                $day= $constraint["banned_day"] ? $constraint["banned_day"] : '';
                $from = " od ".date('H:i', strtotime($constraint["time_from"]));
                $to = " do ".date('H:i', strtotime($constraint["time_to"]));
                $name = $day.$from.$to;
                echo "<p>$name</p>";
            }
            if ($z==0)
                echo '<h1 id="no_coinstraint">Učiteľ nemá priradené žiadne obmedzenia</h1>';
        }
        else http_response_code(400);
    }
    else http_response_code(400);
}

else http_response_code(400);
