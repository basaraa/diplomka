<?php
function selectAllFieldsOfStudy ($conn){
    $studies = "SELECT name FROM fieldsOfStudy";
    $result = $conn->query($studies);
    return mysqli_fetch_assoc($result);
}