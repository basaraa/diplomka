<?php
//insert queries
function insertFieldsOfStudy ($conn,$name){
    $studies = "INSERT INTO fieldsOfStudy (name) VALUES ('$name')";
    $result = $conn->query($studies) or die("Chyba pri vykonaní insert query: " . $conn->error);
    return $result;
}
function insertTeacher ($conn,$name){
    $teacher = "INSERT INTO Teachers (name) VALUES ('$name')";
    $result = $conn->query($teacher) or die("Chyba pri vykonaní insert query: " . $conn->error);
    return $result;
}
function insertRoom ($conn,$name,$roomType){
    $room = "INSERT INTO Rooms (name,room_type) VALUES ('$name','$roomType')";
    $result = $conn->query($room) or die("Chyba pri vykonaní insert query: " . $conn->error);
    return $result;
}
function insertSubject ($name,$shortcut,$grade,$year,$semestre){
    return "INSERT INTO Subjects (name,shortcut,grade,year,semestre) VALUES ('$name','$shortcut','$grade','$year','$semestre')";

}
function insertSubjectFieldOfStudies ($conn,$subject_id,$fieldOfStudy_id){
    $room = "INSERT INTO SubjectFieldOfStudies (subject_id,fieldOfStudy_id) VALUES ('$subject_id','$fieldOfStudy_id')";
    $result = $conn->query($room) or die("Chyba pri vykonaní insert query: " . $conn->error);
    return $result;
}
function insertSubjectTeachers ($conn,$subjectId,$teacherId){
    $room = "INSERT INTO SubjectTeachers (subject_id,teacher_id) VALUES ('$subjectId','$teacherId')";
    $result = $conn->query($room) or die("Chyba pri vykonaní insert query: " . $conn->error);
    return $result;
}

//delete queries
function delete($conn,$id,$type){
    if ($type==0){
        $sql= "DELETE FROM fieldsOfStudy where id='".$id."'";
    }
    else if ($type==1){
        $sql= "DELETE FROM Teachers where id='".$id."'";
    }
    else if ($type==2){
        $sql= "DELETE FROM Rooms where id='".$id."'";
    }
    else {
        $sql= "DELETE FROM Subjects where id='".$id."'";
    }
    $result = $conn->query($sql) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function deleteSubjectTeachers($conn,$id){
    $sql= "DELETE FROM SubjectTeachers where subject_id='".$id."'";
    $result = $conn->query($sql) or die("Chyba pri vykonaní query: " . $conn->error);
}

//select queries
function selectAllFieldsOfStudy ($conn){
    $studies = "SELECT id,name FROM fieldsOfStudy order by name ASC";
    $result = $conn->query($studies) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectAllTeachers ($conn){
    $teachers = "SELECT id,name FROM Teachers";
    $result = $conn->query($teachers) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectAllRooms ($conn){
    $rooms = "SELECT id,name FROM Rooms";
    $result = $conn->query($rooms) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectAllSubjects ($conn){
    $subjects = "SELECT id,name FROM Subjects";
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectSubjectByStudyGradeYearSemestre ($conn,$study,$grade,$year,$semestre){
    $subjects = "SELECT Subjects.id,Subjects.name,Subjects.shortcut,Subjects.room_id,
                Subjects.lecture_day,Subjects.lecture_time_from,Subjects.lecture_time_to,
                Subjects.exercise_day,Subjects.exercise_time_from,Subjects.exercise_time_to
                FROM Subjects JOIN SubjectFieldOfStudies ON Subjects.id=SubjectFieldOfStudies.subject_id 
                JOIN fieldsOfStudy ON SubjectFieldOfStudies.fieldOfStudy_id=fieldsOfStudy.id
                where fieldsOfStudy.id='".$study."' and Subjects.grade='".$grade ."' and Subjects.year='".$year."' and Subjects.semestre='".$semestre."'";
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectSubjectById ($conn,$id){
    $subject = "SELECT * FROM Subjects where id='".$id."'";
    $result = $conn->query($subject) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectRoomById ($conn,$id){
    $room = "SELECT * FROM Rooms where id='".$id."'";
    $result = $conn->query($room) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectTeachersBySubject ($conn,$subjectId){
    $teachers = "SELECT * FROM Teachers 
                JOIN SubjectTeachers ON Teachers.id=SubjectTeachers.teacher_id 
                WHERE SubjectTeachers.subject_id='".$subjectId."'";
    $result = $conn->query($teachers) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}

//update
function updateSubject ($conn,$id,$room_id,$lectureDay,$lectureFrom,$lectureTo,$exerciseDay,$exerciseFrom,$exerciseTo){
    $subject = "UPDATE Subjects
                SET room_id='".$room_id."', lecture_day='".$lectureDay."',
                lecture_time_from = '".$lectureFrom."', lecture_time_to = '".$lectureTo."',
                exercise_day='".$exerciseDay."',exercise_time_from = '".$exerciseFrom."', exercise_time_to = '".$exerciseTo."'           
                WHERE id='".$id."'";
    $result = $conn->query($subject) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}