<?php
require_once("../config/config.php");
include "../databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["subjectId"]) && isset($_POST["room_id"]) && isset($_POST["subjectTeachers"]) &&
        isset($_POST["lectureDay"]) && isset($_POST["lectureFrom"]) && isset($_POST["lectureTo"]) &&
        isset($_POST["exerciseDay"]) && isset($_POST["exerciseFrom"]) && isset($_POST["exerciseTo"]) &&
        isset($_POST["grade"]) && isset($_POST["semestre"]) && isset($_POST["year"])) {
        $id = $_POST["subjectId"];
        $room_id = $_POST["room_id"];
        $subjectTeachers = $_POST["subjectTeachers"];
        $lectureDay=$_POST["lectureDay"];
        $exerciseDay=$_POST["exerciseDay"];
        $lectureFrom=$_POST["lectureFrom"];
        $lectureTo=$_POST["lectureTo"];
        $exerciseFrom=$_POST["exerciseFrom"];
        $exerciseTo=$_POST["exerciseTo"];
        $grade =$_POST["grade"];
        $year = $_POST["year"];
        $semestre = $_POST["semestre"];
        $FOSErrorMessage='';
        $FOSErrorSubjects=[];
        $RoomErrorMessage='';
        $RoomErrorSubjects=[];
        $TeacherErrorMessage='';
        $TeacherErrorSubjects=[];
        $x =0;
        //fieldOfStudy semestre contraint check
        $subjectFieldOfStudies = selectFieldOfStudyBySubjectId($conn,$id);
        if ($subjectFieldOfStudies){
            while ($fieldOfStudy=mysqli_fetch_assoc($subjectFieldOfStudies)){
                $selectedSubjects=checkSubjectLecturesInFieldOfStudyConstraint($conn,$id,$fieldOfStudy["fieldOfStudy_id"],$grade,$year,$semestre,$lectureDay,$exerciseDay,
                    $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
                $selectedSubjectsE=checkSubjectExercisesInFieldOfStudyConstraint($conn,$id,$fieldOfStudy["fieldOfStudy_id"],$grade,$year,$semestre,$lectureDay,$exerciseDay,
                    $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
                if (($selectedSubjects && ($selectedSubjects->num_rows)>0) or ($selectedSubjectsE && ($selectedSubjectsE->num_rows)>0) ){

                    while ($subj=mysqli_fetch_assoc($selectedSubjects))
                        if(!in_array($subj["name"],$FOSErrorSubjects))
                            array_push($FOSErrorSubjects,$subj["name"]);
                    while ($subjE=mysqli_fetch_assoc($selectedSubjectsE))
                        if(!in_array($subjE["name"],$FOSErrorSubjects))
                            array_push($FOSErrorSubjects,$subjE["name"]);
                }
            }
            foreach ($FOSErrorSubjects as $subjectName)
                $FOSErrorMessage.='"'.$subjectName.'",';
        }
        $x=0;
        //room check constraint
        $roomSubjects=checkSubjectLecturesInRoomConstraint($conn,$id,$semestre,$room_id,$lectureDay,$exerciseDay,
            $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
        $roomSubjectsE=checkSubjectExercisesInRoomConstraint($conn,$id,$semestre,$room_id,$lectureDay,$exerciseDay,
            $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
        if (($roomSubjects && ($roomSubjects->num_rows)>0) || ($roomSubjectsE && ($roomSubjectsE->num_rows)>0)){
            while ($subj=mysqli_fetch_assoc($roomSubjects))
                if(!in_array($subj["name"],$RoomErrorSubjects))
                    array_push($RoomErrorSubjects,$subj["name"]);
            while ($subjE=mysqli_fetch_assoc($roomSubjectsE))
                if(!in_array($subjE["name"],$RoomErrorSubjects))
                    array_push($RoomErrorSubjects,$subjE["name"]);
            foreach ($RoomErrorSubjects as $subjectName)
                $RoomErrorMessage.='"'.$subjectName.'",';
        }
        $x=0;
        //teacher check collision constraint
        foreach($subjectTeachers as $teacherId){
            $teacherSubjects=checkSubjectLecturesByTeacherConstraint($conn,$id,$semestre,$teacherId,$lectureDay,$exerciseDay,
                $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
            $teacherSubjectsE=checkSubjectExercisesByTeacherConstraint($conn,$id,$semestre,$teacherId,$lectureDay,$exerciseDay,
                $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
            if (($teacherSubjects && ($teacherSubjects->num_rows)>0) or ($teacherSubjectsE && ($teacherSubjectsE->num_rows)>0) ){
                while ($subj=mysqli_fetch_assoc($teacherSubjects)){
                    $y=$subj["teacherName"].":".$subj["subjectName"];
                    if(!in_array($y,$TeacherErrorSubjects))
                        array_push($TeacherErrorSubjects,$y);
                }
                while ($subjE=mysqli_fetch_assoc($teacherSubjectsE)){
                    $y=$subjE["teacherName"].":".$subjE["subjectName"];
                    if(!in_array($y,$TeacherErrorSubjects))
                        array_push($TeacherErrorSubjects,$y);
                }
            }
        }
        foreach ($TeacherErrorSubjects as $subjectName)
            $TeacherErrorMessage.='"'.$subjectName.'",';

        if (empty($FOSErrorMessage) && empty($RoomErrorMessage) && empty($TeacherErrorMessage)){
            $result=updateSubject($conn,$id,$room_id,$lectureDay,$lectureFrom,$lectureTo,$exerciseDay,$exerciseFrom,$exerciseTo);
            if ($result){
                deleteSubjectTeachers($conn,$id,$subjectTeachers);
                foreach ($subjectTeachers as $teacherId){
                    $selectedByTeacherId = selectTeachersBySubjectAndTeacherID($conn,$id,$teacherId);
                    if(!($selectedByTeacherId && ($selectedByTeacherId->num_rows)>0))
                        $resultTeacher=insertSubjectTeachers($conn,$id,$teacherId);
                }
                echo json_encode(["scs" => true,"msg" => "Nenastala žiadna kolízia - predmet bol úspešne upravený"]);;
            }
            else echo http_response_code(400);
        }
        else
            echo json_encode(["scs" => false,"FOSerr" => $FOSErrorMessage, "RoomErr" => $RoomErrorMessage, "TeacherErr" => $TeacherErrorMessage]);
    }
    else echo http_response_code(400);
}
else echo http_response_code(400);
?>
