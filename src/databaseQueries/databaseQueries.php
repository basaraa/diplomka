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

//delete queries
function delete($conn,$id,$type){
    if ($type==0){
        $sql= "DELETE FROM fieldsOfStudy where id=$id";
    }
    else if ($type==1){
        $sql= "DELETE FROM Teachers where id=$id";
    }
    else if ($type==2){
        $sql= "DELETE FROM Rooms where id=$id";
    }
    else {
        $sql= "DELETE FROM Subjects where id=$id";
    }
    $result = $conn->query($sql) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}

//select queries
function selectAllFieldsOfStudy ($conn){
    $studies = "SELECT id,name FROM fieldsOfStudy";
    $result = $conn->query($studies) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectAllTeachers ($conn){
    $studies = "SELECT id,name FROM Teachers";
    $result = $conn->query($studies) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectAllRooms ($conn){
    $studies = "SELECT id,name FROM Rooms";
    $result = $conn->query($studies) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}
function selectAllSubjects ($conn){
    $studies = "SELECT id,name FROM Subjects";
    $result = $conn->query($studies) or die("Chyba pri vykonaní query: " . $conn->error);
    return $result;
}