<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
if (isset($_POST["semestre"])&&isset($_POST["roomId"])){
    $semestre =$_POST["semestre"];
    $roomId= $_POST["roomId"];
    echo'vybrali ste rozvrh pre miestnosť s id '.$roomId.' v semestry '.$semestre.'';
}
else {
    echo '<form class="form" action="roomSchedule.php" method="post" enctype="multipart/form-data" name = "getSchedule">
            <div class="form-group">
                <label for="semestre">Semester:</label>
                    <select class="form-control" name= "semestre" id="semestre" required>
                            <option value="ZS" >Zimný semester</option>
                            <option value="LS" >Letný semester</option>
                    </select>         
                <label for="name">Názov miestnosti:</label>
                <select class="form-control" name= "roomId" id="roomId" required>';
    $selected=selectAllRooms($conn);
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