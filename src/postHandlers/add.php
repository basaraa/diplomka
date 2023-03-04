<?php
require_once("../config/config.php");
include "../databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["type"])){
        if ($_POST["type"]==0){
            if (isset($_POST["name"])){
                $name = $_POST["name"];
                $result = insertFieldsOfStudy($conn,$name);
                if($result)
                {
                    echo 1;
                }
                else http_response_code(400);
            }
            else http_response_code(400);
        }
        else if ($_POST["type"]==1){
            if (isset($_POST["name"])){
                $name = $_POST["name"];
                $result = insertTeacher($conn,$name);
                if($result)
                {
                    echo 1;
                }
                else http_response_code(400);
            }
            else http_response_code(400);
        }
        else if ($_POST["type"]==2){
            if (isset($_POST["name"]) && isset($_POST["roomType"])){
                $roomType = $_POST["roomType"];
                $name = $_POST["name"];
                $result = insertRoom($conn,$name,$roomType);
                if($result)
                {
                    echo 1;
                }
                else http_response_code(400);
            }
            else http_response_code(400);
        }
        else if ($_POST["type"]==3){
            if (isset($_POST["name"])&&isset($_POST["shortcut"])&&isset($_POST["grade"])&&isset($_POST["year"])&&isset($_POST["semestre"])&&isset($_POST["fieldOfStudies"])){
                $name = $_POST["name"];
                $shortcut = $_POST["shortcut"];
                $grade = $_POST["grade"];
                $year = $_POST["year"];
                $semestre = $_POST["semestre"];
                $fieldOfStudies = $_POST["fieldOfStudies"];
                $subject = insertSubject($name,$shortcut,$grade,$year,$semestre);
                $result = $conn->query($subject) or die("Chyba pri vykonaní insert query: " . $conn->error);
                if (!$result) http_response_code(400);
                else {
                    $subjectId=$conn->insert_id;
                    foreach ($fieldOfStudies as $fieldOfStudyId){
                        $result = insertSubjectFieldOfStudies($conn,$subjectId,$fieldOfStudyId);
                        if (!$result) http_response_code(400);
                    }
                    echo '1';
                }
            }
            else http_response_code(400);
        }
    }
    else
        http_response_code(400);
}