<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["name"])){
        $link = $conn;
        $name = $_POST["name"];
        $result = insertFieldsOfStudy($link,$name);
        if ($result){
            echo "<h1 id ='success_insert'> Úspešne pridaný odbor $name</h1>";
        }
    }
}
else echo '<form class="form" action="addFieldOfStudy.php" method="post" enctype="multipart/form-data" name = "addStudy">
    <div class="form-group">
        <label for="name">Názov odboru:</label>
        <input type="text" class="form-control" name= "name" id="name" placeholder="Zadajte názov odboru:" required>
    </div>
    <button type="submit" class="btn btn-primary">Vložiť odbor</button>
</form>'
?>


<?php
include "partials/footer.php";
?>