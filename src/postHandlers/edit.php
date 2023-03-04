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
        $subjectFieldOfStudies = selectFieldOfStudyBySubjectId($conn,$id);
        $errorMessage='';
        $errorSubjects=[];
        $x =0;
        if ($subjectFieldOfStudies)
            while ($fieldOfStudy=mysqli_fetch_assoc($subjectFieldOfStudies)){

                $selectedSubjects=checkSubjectLecturesInFieldOfStudyConstrain($conn,$id,$fieldOfStudy["fieldOfStudy_id"],$lectureDay,$exerciseDay,
                    $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
                if ($selectedSubjects && ($selectedSubjects->num_rows)>0){
                    if ($x===0){
                        $x=1;
                        $errorMessage='Nastala kolízia v časoch prednášok alebo cvičení v predmetoch: ';
                    }
                    while ($subj=mysqli_fetch_assoc($selectedSubjects))
                        if(!in_array($subj["name"],$errorSubjects))
                            array_push($errorSubjects,$subj["name"]);
                }
                //$errorMessage=$selectedSubjects;
                $selectedSubjects=checkSubjectExercisesInFieldOfStudyConstrain($conn,$id,$fieldOfStudy["fieldOfStudy_id"],$lectureDay,$exerciseDay,
                    $lectureFrom,$lectureTo,$exerciseFrom,$exerciseTo);
                if ($selectedSubjects && ($selectedSubjects->num_rows)>0){
                    if ($x===0){
                        $x=1;
                        $errorMessage='Nastala kolízia v časoch prednášok alebo cvičení v predmetoch: ';
                    }
                    while ($subj=mysqli_fetch_assoc($selectedSubjects))
                        if(!in_array($subj["name"],$errorSubjects))
                            array_push($errorSubjects,$subj["name"]);

                }
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
        else {
            foreach ($errorSubjects as $subjectName)
                $errorMessage=$errorMessage.'"'.$subjectName.'",';
            echo json_encode(["scs" => false,"msg" => $errorMessage]);
        }
    }
    else echo http_response_code(400);
}
else echo http_response_code(400);
?>
