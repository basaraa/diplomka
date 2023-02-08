<?php
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
function insertFieldsOfStudy ($conn,$name){
    $studies = "INSERT INTO fieldsOfStudy (name) VALUES ('$name')";
    $result = $conn->query($studies) or die("Chyba pri vykonaní insert query: " . $conn->error);
    return $result;
}

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