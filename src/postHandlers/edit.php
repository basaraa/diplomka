<?php
require_once("../config/config.php");
include "../databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["subjectId"]) && isset($_POST["room_id"]) && isset($_POST["subjectTeachers"]) &&
        isset($_POST["lectureDay"]) && isset($_POST["lectureFrom"]) && isset($_POST["lectureTo"]) &&
        isset($_POST["exerciseDay"]) && isset($_POST["exerciseFrom"]) && isset($_POST["exerciseTo"])) {
        $id = $_POST["subjectId"];
        $room_id = $_POST["room_id"];
        $subjectTeachers = $_POST["subjectTeachers"];
        $lectureDay=$_POST["lectureDay"];
        $exerciseDay=$_POST["exerciseDay"];
        $lectureFrom=$_POST["lectureFrom"];
        $lectureTo=$_POST["lectureTo"];
        $exerciseFrom=$_POST["exerciseFrom"];
        $exerciseTo=$_POST["exerciseTo"];
        $result=updateSubject($conn,$id,$room_id,$lectureDay,$lectureFrom,$lectureTo,$exerciseDay,$exerciseFrom,$exerciseTo);
        if ($result){
            deleteSubjectTeachers($conn,$id);
            foreach ($subjectTeachers as $teacherId){
                $resultTeacher=insertSubjectTeachers($conn,$id,$teacherId);
                if (!$resultTeacher) http_response_code(400);
            }
            echo 'Úspešne upravený predmet ';
        }
        else http_response_code(400);
    }
    else echo "0";
}
else echo "-1";
?>
