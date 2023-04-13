<?php
//insert queries
function insertFieldsOfStudy ($conn,$name,$shortcut){
    $studies = "INSERT INTO fieldsOfStudy (name,shortcut) VALUES ('$name','$shortcut')";
    $result = $conn->query($studies) or die("Chyba pri vykonaní insert query: " . $conn->error);
    return $result;
}
function insertTeacher ($conn,$name){
    $teacher = "INSERT INTO Teachers (name) VALUES ('$name')";
    $result = $conn->query($teacher) or die("Chyba pri vykonaní insert query: " . $conn->error);
    return $result;
}
function insertRoom ($conn,$name){
    $room = "INSERT INTO Rooms (name) VALUES ('$name')";
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
function insertTeacherConstraints ($conn,$teacherId,$day,$timeFrom,$timeTo){
    $room = "INSERT INTO TeacherConstraints (teacher_id,banned_day,time_from,time_to) VALUES ('$teacherId',NULLIF('$day','0'),'$timeFrom','$timeTo')";
    $result = $conn->query($room) or die("Chyba pri vykonaní insert query: " . $conn->error);
    return $result;
}

//delete queries
function delete($conn,$id,$type){
    if ($type==0)
        $sql = "DELETE FROM fieldsOfStudy where id='".$id."'";
    else if ($type==1)
        $sql = "DELETE FROM Teachers where id='".$id."'";
    else if ($type==2)
        $sql = "DELETE FROM Rooms where id='".$id."'";
    else if ($type==3)
        $sql = "DELETE FROM Subjects where id='".$id."'";
    else
        $sql = "DELETE FROM TeacherConstraints where id = '".$id."'";

    $result = $conn->query($sql) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function deleteSubjectTeachers($conn,$id,$subjectTeachers){
    $sql= "DELETE FROM SubjectTeachers where subject_id='".$id."' and teacher_id not in (".implode(',', $subjectTeachers).")" ;
    $result = $conn->query($sql) or die("Chyba pri vykonaní query: " . $conn->error);
}
function deleteSubjectTeachersBySubject($conn,$id){
    $sql= "DELETE FROM SubjectTeachers where subject_id='".$id."'" ;
    $result = $conn->query($sql) or die("Chyba pri vykonaní query: " . $conn->error);
}
function deleteSubjectTeachersByFieldOfStudies($conn,$id){
    $sql= "DELETE SubjectTeachers FROM SubjectTeachers JOIN Subjects ON Subjects.id=SubjectTeachers.subject_id
           JOIN SubjectFieldOfStudies ON Subjects.id= SubjectFieldOfStudies.subject_id
            where SubjectFieldOfStudies.fieldOfStudy_id='".$id."'";
    $result = $conn->query($sql) or die("Chyba pri vykonaní query: " . $conn->error);
}
//select queries
function selectAllFieldsOfStudy ($conn){
    $studies = "SELECT * FROM fieldsOfStudy order by name ASC";
    $result = $conn->query($studies) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectAllTeachers ($conn){
    $teachers = "SELECT id,name FROM Teachers ORDER BY name ASC";
    $result = $conn->query($teachers) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectAllRooms ($conn){
    $rooms = "SELECT id,name FROM Rooms ORDER BY name ASC";
    $result = $conn->query($rooms) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectAllSubjects ($conn){
    $subjects = "SELECT id,name FROM Subjects ORDER BY name ASC";
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectSubjectByStudyGradeYearSemestre ($conn,$study,$grade,$year,$semestre){
    $subjects = "SELECT Subjects.id,Subjects.name,Subjects.shortcut,Subjects.lecture_room_id,Subjects.exercise_room_id,
                Subjects.lecture_day,Subjects.lecture_time_from,Subjects.lecture_time_to,
                Subjects.exercise_day,Subjects.exercise_time_from,Subjects.exercise_time_to
                FROM Subjects JOIN SubjectFieldOfStudies ON Subjects.id=SubjectFieldOfStudies.subject_id 
                JOIN fieldsOfStudy ON SubjectFieldOfStudies.fieldOfStudy_id=fieldsOfStudy.id
                where fieldsOfStudy.id='".$study."' and Subjects.grade='".$grade ."' and Subjects.year='".$year."' and Subjects.semestre='".$semestre."'
                ORDER BY Subjects.name ASC";
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectSubjectsByStudyGradeYearSemestreDay ($conn,$study,$grade,$year,$semestre,$day){
    $subjects = "SELECT Subjects.id,Subjects.name,Rooms.name as 'room_name', 'lecture' as 'type',
                Subjects.lecture_day as 'day',HOUR(Subjects.lecture_time_from) as 'time_from',HOUR(Subjects.lecture_time_to) as 'time_to'           
                FROM Subjects JOIN SubjectFieldOfStudies ON Subjects.id=SubjectFieldOfStudies.subject_id 
                JOIN fieldsOfStudy ON SubjectFieldOfStudies.fieldOfStudy_id=fieldsOfStudy.id
                JOIN Rooms ON Rooms.id=Subjects.lecture_room_id
                where fieldsOfStudy.id='".$study."' and Subjects.grade='".$grade ."' and Subjects.year='".$year."' 
                and Subjects.semestre='".$semestre."' and lecture_day ='".$day."'
                UNION
                SELECT Subjects.id,Subjects.name,Rooms.name as 'room_name','exercise' as 'type',
                Subjects.exercise_day as 'day',HOUR(Subjects.exercise_time_from) as 'time_from',HOUR(Subjects.exercise_time_to) as 'time_to'           
                FROM Subjects JOIN SubjectFieldOfStudies ON Subjects.id=SubjectFieldOfStudies.subject_id 
                JOIN fieldsOfStudy ON SubjectFieldOfStudies.fieldOfStudy_id=fieldsOfStudy.id
                JOIN Rooms ON Rooms.id=Subjects.exercise_room_id
                where fieldsOfStudy.id='".$study."' and Subjects.grade='".$grade ."' and Subjects.year='".$year."' 
                and Subjects.semestre='".$semestre."' and exercise_day ='".$day."'
                ORDER BY time_from ASC";
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectSubjectsByTeacherDay ($conn,$teacherId,$semestre,$day){
    $subjects = "SELECT Subjects.id,Subjects.name,Rooms.name as 'room_name', 'lecture' as 'type',
                Subjects.lecture_day as 'day',HOUR(Subjects.lecture_time_from) as 'time_from',HOUR(Subjects.lecture_time_to) as 'time_to'           
                FROM Subjects JOIN SubjectTeachers ON Subjects.id=SubjectTeachers.subject_id 
                JOIN Teachers ON Teachers.id=SubjectTeachers.teacher_id
                JOIN Rooms ON Rooms.id=Subjects.lecture_room_id
                where Teachers.id='".$teacherId."' and Subjects.semestre='".$semestre."' and lecture_day ='".$day."'
                UNION
                SELECT Subjects.id,Subjects.name,Rooms.name as 'room_name','exercise' as 'type',
                Subjects.exercise_day as 'day',HOUR(Subjects.exercise_time_from) as 'time_from',HOUR(Subjects.exercise_time_to) as 'time_to'           
                FROM Subjects JOIN SubjectTeachers ON Subjects.id=SubjectTeachers.subject_id 
                JOIN Teachers ON Teachers.id=SubjectTeachers.teacher_id
                JOIN Rooms ON Rooms.id=Subjects.exercise_room_id
                where Teachers.id='".$teacherId."' and Subjects.semestre='".$semestre."' and exercise_day ='".$day."'
                ORDER BY time_from ASC";
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectSubjectsByRoomDay ($conn,$roomId,$semestre,$day){
    $subjects = "SELECT Subjects.id,Subjects.name,Rooms.name as 'room_name', 'lecture' as 'type',
                Subjects.lecture_day as 'day',HOUR(Subjects.lecture_time_from) as 'time_from',HOUR(Subjects.lecture_time_to) as 'time_to'           
                FROM Subjects JOIN Rooms ON Rooms.id=Subjects.lecture_room_id
                where Rooms.id='".$roomId."' and Subjects.semestre='".$semestre."' and lecture_day ='".$day."'
                UNION
                SELECT Subjects.id,Subjects.name,Rooms.name as 'room_name','exercise' as 'type',
                Subjects.exercise_day as 'day',HOUR(Subjects.exercise_time_from) as 'time_from',HOUR(Subjects.exercise_time_to) as 'time_to'           
                FROM Subjects JOIN Rooms ON Rooms.id=Subjects.exercise_room_id
                where Rooms.id='".$roomId."' and Subjects.semestre='".$semestre."' and exercise_day ='".$day."'
                ORDER BY time_from ASC";
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}

function selectSubjectById ($conn,$id){
    $subject = "SELECT * FROM Subjects where id='".$id."'";
    $result = $conn->query($subject) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectSubjectByName ($conn,$name){
    $subject = "SELECT * FROM Subjects where name='".$name."'";
    $result = $conn->query($subject) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectRoomById ($conn,$id){
    $room = "SELECT * FROM Rooms where id='".$id."'";
    $result = $conn->query($room) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectRoomByName ($conn,$name){
    $room = "SELECT * FROM Rooms where name='".$name."' limit 1";
    $result = $conn->query($room) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectTeachersBySubject ($conn,$subjectId){
    $teachers = "SELECT Teachers.id,Teachers.name FROM Teachers 
                JOIN SubjectTeachers ON Teachers.id=SubjectTeachers.teacher_id 
                WHERE SubjectTeachers.subject_id='".$subjectId."'";
    $result = $conn->query($teachers) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectTeacherById ($conn,$id){
    $teachers = "SELECT name FROM Teachers WHERE id='".$id."'";
    $result = $conn->query($teachers) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectTeacherByName($conn,$name){
    $teachers = "SELECT id FROM Teachers WHERE name='".$name."' limit 1";
    $result = $conn->query($teachers) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectTeachersBySubjectAndTeacherID ($conn,$subjectId,$teacherId){
    $teachers = "SELECT Teachers.id,Teachers.name FROM Teachers 
                JOIN SubjectTeachers ON Teachers.id=SubjectTeachers.teacher_id 
                WHERE SubjectTeachers.subject_id='".$subjectId."' and SubjectTeachers.teacher_id='".$teacherId."'";
    $result = $conn->query($teachers) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectFieldOfStudyBySubjectId($conn, $subjectId){
    $fieldOfStudies = "SELECT * FROM SubjectFieldOfStudies WHERE subject_id='".$subjectId."'";
    $result = $conn->query($fieldOfStudies) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectFieldOfStudyById($conn, $id){
    $fieldOfStudies = "SELECT name FROM fieldsOfStudy WHERE id='".$id."'";
    $result = $conn->query($fieldOfStudies) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectFieldOfStudyByName($conn, $name){
    $fieldOfStudies = "SELECT name FROM fieldsOfStudy WHERE name='".$name."'";
    $result = $conn->query($fieldOfStudies) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectTeacherConstraints ($conn,$teacherId){
    $subject = "SELECT * FROM TeacherConstraints where teacher_id='".$teacherId."'";
    $result = $conn->query($subject) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}

//kontrola obmedzeni
//podľa fieldOfStudy semestra prednášok
function checkSubjectLecturesInFieldOfStudyConstraint($conn,$subjectId,$fieldOfStudyId,$grade,$year,$semestre,$lectureDay,$exerciseDay,
                                                     $fromLecture,$toLecture,$fromExercise,$toExercise)
{
    $subjects = "SELECT distinct Subjects.id, Subjects.name FROM Subjects JOIN SubjectFieldOfStudies ON Subjects.id= SubjectFieldOfStudies.subject_id           
                 where Subjects.id !='".$subjectId."' and SubjectFieldOfStudies.fieldOfStudy_id='".$fieldOfStudyId."'
                 and Subjects.grade = '".$grade."' and Subjects.year = '".$year."' and Subjects.semestre = '".$semestre."'
                 and (
                     (Subjects.lecture_day = '".$lectureDay."' 
                         and ('".$fromLecture."' between lecture_time_from and lecture_time_to
                             or '".$toLecture."' between lecture_time_from and lecture_time_to
                             or '".$fromLecture."' <= lecture_time_from AND '".$toLecture."' >= lecture_time_to
                        )                        
                    ) 
                    or (Subjects.lecture_day = '" . $exerciseDay . "'
                        and ('".$fromExercise."' between lecture_time_from and lecture_time_to
                             or '" .$toExercise."' between lecture_time_from and lecture_time_to
                             or '" .$fromExercise."' <= lecture_time_from AND '".$toExercise."' >= lecture_time_to
                        )
                    )
                )           
                 " ;
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;

}

//podľa fieldOfStudy semestra cvičení
function checkSubjectExercisesInFieldOfStudyConstraint($conn,$subjectId,$fieldOfStudyId,$grade,$year,$semestre,$lectureDay,$exerciseDay,
                                                     $fromLecture,$toLecture,$fromExercise,$toExercise)
{
    $subjects = "SELECT distinct Subjects.id, Subjects.name FROM Subjects JOIN SubjectFieldOfStudies ON Subjects.id= SubjectFieldOfStudies.subject_id           
                 where Subjects.id !='".$subjectId."' and SubjectFieldOfStudies.fieldOfStudy_id='".$fieldOfStudyId."'
                 and Subjects.grade = '".$grade."' and Subjects.year = '".$year."' and Subjects.semestre = '".$semestre."'
                 and (
                     (Subjects.exercise_day = '".$lectureDay."' 
                         and ('".$fromLecture."' between exercise_time_from and exercise_time_to
                             or '".$toLecture."' between exercise_time_from and exercise_time_to
                             or '".$fromLecture."' <= exercise_time_from AND '".$toLecture."' >= exercise_time_to
                        )                        
                    ) 
                    or (Subjects.exercise_day = '" . $exerciseDay . "'
                        and ('".$fromExercise."' between exercise_time_from and exercise_time_to
                             or '" .$toExercise."' between exercise_time_from and exercise_time_to
                             or '" .$fromExercise."' <= exercise_time_from AND '".$toExercise."' >= exercise_time_to
                        )
                    )
                )           
                 " ;
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
//podľa prednášok v miestnosti
function checkSubjectLecturesInRoomConstraint($conn,$subjectId,$semestre,$roomId,$lectureDay,
                                                      $fromLecture,$toLecture)
{
    $subjects = "SELECT distinct Subjects.id, Subjects.name, Rooms.name as 'room_name' FROM Subjects    
                 JOIN Rooms ON Rooms.id = lecture_room_id 
                 where Subjects.id !='".$subjectId."' and semestre = '".$semestre."' and lecture_room_id='".$roomId."'
                 and lecture_day = '".$lectureDay."' 
                         and ('".$fromLecture."' between lecture_time_from and lecture_time_to
                             or '".$toLecture."' between lecture_time_from and lecture_time_to
                             or '".$fromLecture."' <= lecture_time_from and '".$toLecture."' >= lecture_time_to
                        )                                                
                 " ;
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
//podľa cvičení v miestnosti
function checkSubjectExercisesInRoomConstraint($conn,$subjectId,$semestre,$roomId,$exerciseDay
                                                       ,$fromExercise,$toExercise)
{
    $subjects = "SELECT distinct Subjects.id, Subjects.name,Rooms.name as 'room_name' FROM Subjects
                 JOIN Rooms ON Rooms.id = exercise_room_id
                 where Subjects.id !='".$subjectId."' and semestre = '".$semestre."' and exercise_room_id='".$roomId."'
                 and exercise_day = '" . $exerciseDay . "'
                        and ('".$fromExercise."' between exercise_time_from and exercise_time_to
                             or '" .$toExercise."' between exercise_time_from and exercise_time_to
                             or '" .$fromExercise."' <= exercise_time_from AND '".$toExercise."' >= exercise_time_to
                        )                                   
                 " ;
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}

//podľa prednášok učiteľa
function checkSubjectLecturesByTeacherConstraint($conn,$subjectId,$semestre,$teacherId,$lectureDay,$exerciseDay,
                                              $fromLecture,$toLecture,$fromExercise,$toExercise)
{
    $subjects = "SELECT distinct Subjects.name as subjectName,Teachers.name as teacherName FROM Subjects 
                 JOIN SubjectTeachers ON Subjects.id= SubjectTeachers.subject_id JOIN Teachers ON Teachers.id= SubjectTeachers.teacher_id         
                 where Subjects.id !='".$subjectId."' and Subjects.semestre = '".$semestre."' and SubjectTeachers.teacher_id='".$teacherId."'
                 and (
                     (lecture_day = '".$lectureDay."' 
                         and ('".$fromLecture."' between lecture_time_from and lecture_time_to
                             or '".$toLecture."' between lecture_time_from and lecture_time_to
                             or '".$fromLecture."' <= lecture_time_from AND '".$toLecture."' >= lecture_time_to
                        )                        
                    ) 
                    or (lecture_day = '" . $exerciseDay . "'
                        and ('".$fromExercise."' between lecture_time_from and lecture_time_to
                             or '" .$toExercise."' between lecture_time_from and lecture_time_to
                             or '" .$fromExercise."' <= lecture_time_from AND '".$toExercise."' >= lecture_time_to
                        )
                    )
                )          
                 " ;
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
//podľa cvičení učiteľa
function checkSubjectExercisesByTeacherConstraint($conn,$subjectId,$semestre,$teacherId,$lectureDay,$exerciseDay,
                                               $fromLecture,$toLecture,$fromExercise,$toExercise)
{
    $subjects = "SELECT distinct Subjects.name as subjectName,Teachers.name as teacherName FROM Subjects 
                 JOIN SubjectTeachers ON Subjects.id= SubjectTeachers.subject_id JOIN Teachers ON Teachers.id= SubjectTeachers.teacher_id      
                 where Subjects.id !='".$subjectId."' and Subjects.semestre = '".$semestre."' and SubjectTeachers.teacher_id='".$teacherId."'
                 and (
                     (exercise_day = '".$lectureDay."' 
                         and ('".$fromLecture."' between exercise_time_from and exercise_time_to
                             or '".$toLecture."' between exercise_time_from and exercise_time_to
                             or '".$fromLecture."' <= exercise_time_from AND '".$toLecture."' >= exercise_time_to
                        )                        
                    ) 
                    or (exercise_day = '" . $exerciseDay . "'
                        and ('".$fromExercise."' between exercise_time_from and exercise_time_to
                             or '" .$toExercise."' between exercise_time_from and exercise_time_to
                             or '" .$fromExercise."' <= exercise_time_from AND '".$toExercise."' >= exercise_time_to
                        )
                    )
                )     
                 " ;
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}

//podľa vlastných obmedzení učiteľa
function checkTeacherCustomConstraint($conn,$teacherId,$lectureDay,$exerciseDay,
                                                  $fromLecture,$toLecture,$fromExercise,$toExercise)
{
    $subjects = "SELECT distinct banned_day,time_from,time_to, Teachers.name as teacherName FROM TeacherConstraints 
                JOIN Teachers ON TeacherConstraints.teacher_id=Teachers.id                  
                 where teacher_id='".$teacherId."'
                 and (
                     ((banned_day = '".$lectureDay."' or banned_day = null) 
                         and ('".$fromLecture."' between time_from and time_to
                             or '".$toLecture."' between time_from and time_to
                             or '".$fromLecture."' <= time_from AND '".$toLecture."' >= time_to
                        )                        
                    ) 
                    or ((banned_day = '" . $exerciseDay . "' or banned_day = null)
                        and ('".$fromExercise."' between time_from and time_to
                             or '" .$toExercise."' between time_from and time_to
                             or '" .$fromExercise."' <= time_from AND '".$toExercise."' >= time_to
                        )
                    )                  
                )     
                 " ;
    $result = $conn->query($subjects) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}

//update
function updateSubject ($conn,$id,$lecture_room_id,$exercise_room_id,$lectureDay,$lectureFrom,$lectureTo,$exerciseDay,$exerciseFrom,$exerciseTo){
    $subject = "UPDATE Subjects
                SET lecture_room_id='".$lecture_room_id."',exercise_room_id='".$exercise_room_id."', lecture_day='".$lectureDay."',
                lecture_time_from = '".$lectureFrom."', lecture_time_to = '".$lectureTo."',
                exercise_day='".$exerciseDay."',exercise_time_from = '".$exerciseFrom."', exercise_time_to = '".$exerciseTo."'           
                WHERE id='".$id."'";
    $result = $conn->query($subject) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}

function resetSubjects($conn,$id,$type){
    if ($type==0){
        deleteSubjectTeachersBySubject($conn,$id);
        $sql= "UPDATE Subjects
                SET lecture_room_id=null,exercise_room_id=null, lecture_day=null,
                lecture_time_from = null, lecture_time_to = null,
                exercise_day=null,exercise_time_from = null, exercise_time_to = null           
                WHERE id='".$id."'";
    }
    else if ($type==1){
        deleteSubjectTeachersByFieldOfStudies($conn,$id);
        $sql= "UPDATE Subjects JOIN SubjectFieldOfStudies ON Subjects.id = SubjectFieldOfStudies.subject_id
                SET Subjects.lecture_room_id=null,Subjects.exercise_room_id=null, Subjects.lecture_day=null,
                Subjects.lecture_time_from = null, Subjects.lecture_time_to = null,
                Subjects.exercise_day=null,Subjects.exercise_time_from = null, Subjects.exercise_time_to = null           
                WHERE SubjectFieldOfStudies.fieldOfStudy_id='".$id."'";
    }
    $result = $conn->query($sql) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}