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
                $checkFoS= selectFieldOfStudyByName ($conn,$name);
                if ($checkFoS && ($checkFoS->num_rows)===0){
                    $result = insertFieldsOfStudy($conn,$name,$shortcut);
                    if($result){
                        echo json_encode(["scs" => true,"msg" => '<h2 class="blue">Úspešne pridaný štúdijný odbor: '.$name.'</h2>']);
                    }
                    else http_response_code(400);
                }
                else
                    echo json_encode(["scs" => false,"msg" => '<h2 class="red">Štúdijný odbor s názvom: '.$name.' už existuje</h2>']);
            }
            else http_response_code(400);
        }
        //teacher
        else if ($_POST["type"]==1){
            if (isset($_POST["name"])){
                $name = $_POST["name"];
                $checkTeacher= selectTeacherByName ($conn,$name);
                if ($checkTeacher && ($checkTeacher->num_rows)===0){
                    $result = insertTeacher($conn,$name);
                    if($result){
                        echo json_encode(["scs" => true,"msg" => '<h2 class="blue">Úspešne pridaný učiteľ: '.$name.'</h2>']);
                    }
                    else http_response_code(400);
                }
                else
                    echo json_encode(["scs" => false,"msg" => '<h2 class="red">Účiteľ s menom: '.$name.' už existuje</h2>']);
            }
            else http_response_code(400);
        }
        //room
        else if ($_POST["type"]==2){
            if (isset($_POST["name"])){
                $name = $_POST["name"];
                $checkRoom= selectRoomByName ($conn,$name);
                if ($checkRoom && ($checkRoom->num_rows)===0){
                    $result = insertRoom($conn,$name);
                    if($result){
                        echo json_encode(["scs" => true,"msg" => '<h2 class="blue">Úspešne pridaná miestnosť: '.$name.'</h2>']);
                    }
                    else http_response_code(400);
                }
                else
                    echo json_encode(["scs" => false,"msg" => '<h2 class="red">Miestnosť s názvom: '.$name.' už existuje</h2>']);
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
                $checkSubject= selectSubjectByName ($conn,$name);
                if ($checkSubject && ($checkSubject->num_rows)===0){
                    $result = $conn->query($subject) or die("Chyba pri vykonaní insert query: " . $conn->error);
                    if (!$result) http_response_code(400);
                    else {
                        $subjectId=$conn->insert_id;
                        foreach ($fieldOfStudies as $fieldOfStudyId){
                            $result = insertSubjectFieldOfStudies($conn,$subjectId,$fieldOfStudyId);
                            if (!$result) http_response_code(400);
                        }
                        echo json_encode(["scs" => true,"msg" => '<h2 class="blue">Úspešne pridaný predmet s názvom: '.$name.'</h2>']);
                    }
                }
                else
                    echo json_encode(["scs" => false,"msg" => '<h2 class="red">Predmet s názvom: '.$name.' už existuje</h2>']);
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
