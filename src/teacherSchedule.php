<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
if (isset($_POST["semestre"])&&isset($_POST["teacherId"])){
    $semestre =$_POST["semestre"];
    $roomId= $_POST["teacherId"];
    echo'vybrali ste rozvrh pre učiteľa s id '.$roomId.' v semestry '.$semestre.'';
}
else {
    echo '<form class="form" action="teacherSchedule.php" method="post" enctype="multipart/form-data" name = "getSchedule">
            <div class="form-group">
                <label for="semestre">Semester:</label>
                    <select class="form-control" name= "semestre" id="semestre" required>
                            <option value="ZS" >Zimný semester</option>
                            <option value="LS" >Letný semester</option>
                    </select>          
                <label for="name">Meno učiteľa:</label>
                <select class="form-control" name= "teacherId" id="teacherId" required>';
    $selected=selectAllTeachers($conn);
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
            <button type="submit" class="btn btn-primary">Vybrať</button>
            </form>';
}
?>


<?php
include "partials/footer.php";
?>