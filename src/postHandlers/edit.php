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
        $errorMessage='';
        $errorSubjects=[];
        $x =0;
        //fieldOfStudy semestre contraint check
        $subjectFieldOfStudies = selectFieldOfStudyBySubjectId($conn,$id);
        if ($subjectFieldOfStudies){
            $errorStarter='Nastala kolízia v semestri v predmetoch: ';
            while ($fieldOfStudy=mysqli_fetch_assoc($subjectFieldOfStudies)){
                $selectedSubjects=checkSubjectLecturesInFieldOfStudyConstraint($conn,$id,$fieldOfStudy["fieldOfStudy_id"],$grade,$year,$semestre,$lectureDay,$exerciseDay,
                    $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
                $selectedSubjectsE=checkSubjectExercisesInFieldOfStudyConstraint($conn,$id,$fieldOfStudy["fieldOfStudy_id"],$grade,$year,$semestre,$lectureDay,$exerciseDay,
                    $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
                if (($selectedSubjects && ($selectedSubjects->num_rows)>0) or ($selectedSubjectsE && ($selectedSubjectsE->num_rows)>0) ){
                    if ($x===0){
                        $x=1;
                        $errorMessage=$errorStarter;
                    }
                    while ($subj=mysqli_fetch_assoc($selectedSubjects))
                        if(!in_array($subj["name"],$errorSubjects))
                            array_push($errorSubjects,$subj["name"]);
                    while ($subjE=mysqli_fetch_assoc($selectedSubjectsE))
                        if(!in_array($subjE["name"],$errorSubjects))
                            array_push($errorSubjects,$subjE["name"]);
                }
            }
            foreach ($errorSubjects as $subjectName)
                $errorMessage=$errorMessage.'"'.$subjectName.'",';
        }
        $errorSubjects=[];
        $x=0;
        //room check constraint
        $roomSubjects=checkSubjectLecturesInRoomConstraint($conn,$id,$semestre,$room_id,$lectureDay,$exerciseDay,
            $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
        $roomSubjectsE=checkSubjectExercisesInRoomConstraint($conn,$id,$semestre,$room_id,$lectureDay,$exerciseDay,
            $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
        if (($roomSubjects && ($roomSubjects->num_rows)>0) || ($roomSubjectsE && ($roomSubjectsE->num_rows)>0)){
            $errorStarter='Nastala kolízia v miestnosti pri predmetoch: ';
            if ($x===0){
                $x=1;
                $errorMessage.=$errorStarter;
            }
            while ($subj=mysqli_fetch_assoc($roomSubjects))
                if(!in_array($subj["name"],$errorSubjects))
                    array_push($errorSubjects,$subj["name"]);
            while ($subjE=mysqli_fetch_assoc($roomSubjectsE))
                if(!in_array($subjE["name"],$errorSubjects))
                    array_push($errorSubjects,$subjE["name"]);
            foreach ($errorSubjects as $subjectName)
                $errorMessage.='"'.$subjectName.'",';
        }


        if (empty($errorMessage)){
            $result=updateSubject($conn,$id,$room_id,$lectureDay,$lectureFrom,$lectureTo,$exerciseDay,$exerciseFrom,$exerciseTo);
            if ($result){
                deleteSubjectTeachers($conn,$id);
                foreach ($subjectTeachers as $teacherId){
                    $resultTeacher=insertSubjectTeachers($conn,$id,$teacherId);
                    if (!$resultTeacher) http_response_code(400);
                }
                echo json_encode(["scs" => true,"msg" => "Nenastala žiadna kolízia - predmet bol úspešne upravený"]);;
            }
            else echo http_response_code(400);
        }
        else
            echo json_encode(["scs" => false,"msg" => $errorMessage]);

    }
    else echo http_response_code(400);
}
else echo http_response_code(400);
?>
