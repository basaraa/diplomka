<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset ($_POST["id"]) && isset ($_POST["type"]) ){
        $nametypes=["odbor","učiteľ","miestnosť","predmet"];
        $id=$_POST["id"];
        $type=$_POST["type"];
        $result = delete($conn,$id,$type);
        if ($result){
            echo "<h1 id ='success_insert'> Úspešne vymazaný odbor '.$nametypes[$type].'</h1>";
        }
    }
}
else if ($_SERVER["REQUEST_METHOD"] == "GET"){
    if ((isset($_GET["type"]))){
        $type= $_GET["type"];
        echo '<form class="form" action="delete.php" method="post" enctype="multipart/form-data" name = "addStudy">
            <div class="form-group">          
                <input type="hidden" id="type" name="type" value = "'.$type.'" >
                <label for="name">Názov:</label>
                <select class="form-control" name= "id" id="id" required>';
        if ($type==0)
            $selected=selectAllFieldsOfStudy($conn);
        else if ($type==1)
            $selected=selectAllTeachers($conn);
        else if ($type==2)
            $selected=selectAllRooms($conn);
        else
            $selected=selectAllSubjects($conn);
        if ($selected){
            while ($item=mysqli_fetch_assoc($selected)){
                $id= $item["id"];
                $name = $item["name"];
                echo "<option value= '$id'>$name</option>";
            }
        }
            echo'
            </select>
            </div>
            <button type="submit" class="btn btn-primary">Vymazať</button>
            </form>';
    }
    else {
        echo "<h1>Zlá url</h1>";
        echo '<button class="btn btn-primary" onclick="window.location.href=\'index.php\'">Späť na hlavnú stránku</button>';
    }
}
else {
    echo "<h1>Zlá url</h1>";
    echo '<button class="btn btn-primary" onclick="window.location.href=\'index.php\'">Späť na hlavnú stránku</button>';
}
?>


<?php
include "partials/footer.php";
?>