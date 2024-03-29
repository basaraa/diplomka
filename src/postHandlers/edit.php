<?php
require_once("../config/config.php");
include "../databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["subjectId"]) && isset($_POST["lecture_room_id"]) && isset($_POST["exercise_room_id"]) && isset($_POST["subjectTeachers"]) &&
        isset($_POST["lectureDay"]) && isset($_POST["lectureFrom"]) && isset($_POST["lectureTo"]) &&
        isset($_POST["exerciseDay"]) && isset($_POST["exerciseFrom"]) && isset($_POST["exerciseTo"]) &&
        isset($_POST["grade"]) && isset($_POST["semestre"]) && isset($_POST["year"])) {
        $id = $_POST["subjectId"];
        $lecture_room_id = $_POST["lecture_room_id"];
        $exercise_room_id = $_POST["exercise_room_id"];
        $subjectTeachers = $_POST["subjectTeachers"];
        $lectureDay=$_POST["lectureDay"];
        $exerciseDay=$_POST["exerciseDay"];
        $lectureFrom=date('H:i', strtotime($_POST["lectureFrom"].":"."00"));
        $lectureTo=date('H:i', strtotime($_POST["lectureTo"].":"."50"));
        $exerciseFrom=date('H:i', strtotime($_POST["exerciseFrom"].":"."00"));
        $exerciseTo=date('H:i', strtotime($_POST["exerciseTo"].":"."50"));
        if (strtotime($lectureFrom)>strtotime($lectureTo)){
            $lectureFrom=date('H:i', strtotime($_POST["lectureTo"].":"."00"));
            $lectureTo=date('H:i', strtotime($_POST["lectureFrom"].":"."50"));
        }
        if (strtotime($exerciseFrom)>strtotime($exerciseTo)){
            $exerciseFrom=date('H:i', strtotime($_POST["exerciseTo"].":"."00"));
            $exerciseTo=date('H:i', strtotime($_POST["exerciseFrom"].":"."50"));
        }
        if (($lectureDay!=$exerciseDay) || ($lectureFrom<$exerciseFrom && $lectureTo<$exerciseFrom)
            || ($lectureFrom>$lectureTo && $lectureTo>$exerciseTo)){
            $grade =$_POST["grade"];
            $year = $_POST["year"];
            $semestre = $_POST["semestre"];
            $FOSErrorMessage='';
            $FOSErrorSubjects=[];
            $RoomErrorMessage='';
            $RoomErrorSubjects=[];
            $TeacherErrorMessage='';
            $TeacherErrorSubjects=[];
            $TeacherCustomErrorMessage='';
            $TeacherCustomErrorConstraints=[];
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
            //room check constraint
            $roomSubjects=checkSubjectLecturesInRoomConstraint($conn,$id,$semestre,$lecture_room_id,$lectureDay,
                $lectureFrom,$lectureTo,$exercise_room_id,$exerciseDay,$exerciseFrom,$exerciseTo);
            $roomSubjectsE=checkSubjectExercisesInRoomConstraint($conn,$id,$semestre,$lecture_room_id,$lectureDay,
                $lectureFrom,$lectureTo,$exercise_room_id,$exerciseDay,$exerciseFrom,$exerciseTo);
            if (($roomSubjects && ($roomSubjects->num_rows)>0) || ($roomSubjectsE && ($roomSubjectsE->num_rows)>0)){
                while ($subj=mysqli_fetch_assoc($roomSubjects))
                    if(!in_array(($subj["room_name"].':'.$subj["name"]),$RoomErrorSubjects))
                        array_push($RoomErrorSubjects,($subj["room_name"].':'.$subj["name"]));
                while ($subjE=mysqli_fetch_assoc($roomSubjectsE))
                    if(!in_array(($subjE["room_name"].':'.$subjE["name"]),$RoomErrorSubjects))
                        array_push($RoomErrorSubjects,($subjE["room_name"].':'.$subjE["name"]));
                foreach ($RoomErrorSubjects as $subjectName)
                    $RoomErrorMessage.='"'.$subjectName.'",';
            }
            //teacher check collision constraint
            foreach($subjectTeachers as $teacherId){
                $teacherSubjects=checkSubjectLecturesByTeacherConstraint($conn,$id,$semestre,$teacherId,$lectureDay,$exerciseDay,
                    $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
                $teacherSubjectsE=checkSubjectExercisesByTeacherConstraint($conn,$id,$semestre,$teacherId,$lectureDay,$exerciseDay,
                    $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
                $teacherCustomConstraints=checkTeacherCustomConstraint($conn,$teacherId,$lectureDay,$exerciseDay,
                            $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
                if (($teacherCustomConstraints && ($teacherCustomConstraints->num_rows)>0)){
                    while ($constraint=mysqli_fetch_assoc($teacherCustomConstraints)){
                        $day= $constraint["banned_day"] ? ("v ".$constraint["banned_day"]) : '';
                        $from = " od ".date('H:i', strtotime($constraint["time_from"]));
                        $to = " do ".date('H:i', strtotime($constraint["time_to"]));
                        $y=$constraint["teacherName"].": (".$day.$from.$to.")";
                        if(!in_array($y,$TeacherCustomErrorConstraints))
                            array_push($TeacherCustomErrorConstraints,$y);
                    }
                }

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
            foreach ($TeacherCustomErrorConstraints as $constraintName)
                $TeacherCustomErrorMessage.='"'.$constraintName.'",';

            if (empty($FOSErrorMessage) && empty($RoomErrorMessage) && empty($TeacherErrorMessage) && empty ($TeacherCustomErrorMessage)){
                $result=updateSubject($conn,$id,$lecture_room_id,$exercise_room_id,$lectureDay,$lectureFrom,$lectureTo,$exerciseDay,$exerciseFrom,$exerciseTo);
                if ($result){
                    deleteSubjectTeachers($conn,$id,$subjectTeachers);
                    foreach ($subjectTeachers as $teacherId){
                        $selectedByTeacherId = selectTeachersBySubjectAndTeacherID($conn,$id,$teacherId);
                        if(!($selectedByTeacherId && ($selectedByTeacherId->num_rows)>0))
                            $resultTeacher=insertSubjectTeachers($conn,$id,$teacherId);
                    }
                    echo json_encode(["scs" => true,"msg" => "Nenastala žiadna kolízia - predmet bol úspešne upravený"]);
                }
                else echo http_response_code(400);
            }
            else
                echo json_encode(["scs" => false,"FOSerr" => $FOSErrorMessage, "RoomErr" => $RoomErrorMessage,
                    "TeacherErr" => $TeacherErrorMessage, "TeacherCustomErr" => $TeacherCustomErrorMessage]);
        }
        else
            echo "<h2 class='red'>Chyba: nemôže byť prednáška aj cvičenie v rovnaký čas</h2>";

    }
    else echo http_response_code(400);
}
else echo http_response_code(400);
?>
