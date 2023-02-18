<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";

    if ((isset($_GET["type"])) && $_GET["type"] >=0 && $_GET["type"] <=3){
        $type= $_GET["type"];
        echo '<form class="form deleteForm">
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
?>

<div id="modal_background"></div>
<div class="modal_div">
    <div id="modal_vrstva">
        <h1 id="success_insert">Úspešne vymazanie z databázy</h1>
        <button class="btn btn-primary" onclick="window.location.href='index.php'">Späť na hlavnú stránku</button>
    </div>
</div>
<?php
include "partials/footer.php";
?>
