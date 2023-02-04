<?php
const database = "myDatabase";
const username = "root";
const password = "admin";
const servername = "db";
$conn = new mysqli(servername, username, password, database, 3306);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>