<?php
require_once("../config/config.php");
include "../databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //field of study
    if (isset($_POST["type"])){
        if ($_POST["type"]==0){
            if (isset($_POST["name"]) && isset($_POST["shortcut"])){
                $name = $_POST["name"];
                $shortcut = $_POST["shortcut"];
                $result = insertFieldsOfStudy($conn,$name,$shortcut);
                if($result){
                    echo 1;
                }
                else http_response_code(400);
            }
            else http_response_code(400);
        }
        //teacher
        else if ($_POST["type"]==1){
            if (isset($_POST["name"])){
                $name = $_POST["name"];
                $result = insertTeacher($conn,$name);
                if($result){
                    echo 1;
                }
                else http_response_code(400);
            }
            else http_response_code(400);
        }
        //room
        else if ($_POST["type"]==2){
            if (isset($_POST["name"])){
                $name = $_POST["name"];
                $result = insertRoom($conn,$name);
                if($result){
                    echo 1;
                }
                else http_response_code(400);
            }
            else http_response_code(400);
        }
        //subject
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
        //custom constraint
        else if ($_POST["type"]==4){
            if (isset($_POST["id"]) && isset($_POST["Day"]) && isset($_POST["From"]) && isset($_POST["To"])){
                $teacherId= $_POST["id"];
                $day=$_POST["Day"];
                $from=$_POST["From"];
                $to=$_POST["To"];
                if ($to=='')
                    $to="23:59";
                if ($from=='')
                    $to="00:00";
                $result = insertTeacherConstraints($conn,$teacherId,$day,$from,$to);
                if($result){
                    echo 1;
                }
                else http_response_code(400);
            }
            else http_response_code(400);

        }
    }
    else
        http_response_code(400);
}
?>
