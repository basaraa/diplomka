<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
    if ((isset($_GET["type"]))) {
        $days=["pondelok","utorok","streda","štvrtok","piatok"];
        if ($_GET["type"]==0)
            echo '<form class="form addForm">                   
                    <input type="hidden" id="type" name="type" value = "0">
                    <div class="form-group">                 
                        <label for="name">Názov odboru:</label>
                        <input type="text" class="form-control" name= "name" id="name" placeholder="Zadajte názov odboru" required>
                    </div>
                        <button type="submit" class="btn btn-primary">Vložiť odbor</button>
                </form>';
        else if ($_GET["type"]==1)
            echo '<form class="form addForm">                   
                    <input type="hidden" id="type" name="type" value = "1">
                    <div class="form-group">
                        <label for="name">Meno učiteľa:</label>
                        <input type="text" class="form-control" name= "name" id="name" placeholder="Zadajte meno učiteľa" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Vložiť učiteľa</button>
                </form>';
        else if ($_GET["type"]==2)
            echo '<form class="form addForm">                   
                    <input type="hidden" id="type" name="type" value = "2">
                    <div class="form-group">
                        <label for="name">Názov miestnosti:</label>
                        <input type="text" class="form-control" name= "name" id="name" placeholder="Zadajte názov miestnosti" required>
                        <label for="name">Typ miestnosti:</label>
                        <input type="text" class="form-control" name= "roomType" id="roomType" placeholder="Zadajte typ miestnosti" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Vložiť miestnosť</button>
                </form>';
        else if ($_GET["type"]==3){
            echo '<form class="form addForm">                   
                    <input type="hidden" id="type" name="type" value = "3">
                    <div class="form-group">
                        <label for="name">Meno predmetu:</label>
                        <input type="text" class="form-control" name= "name" id="name" placeholder="Zadajte meno predmetu" required>
                        <label for="name">Skratka predmetu:</label>
                        <input type="text" class="form-control" name= "shortcut" id="shortcut" placeholder="Zadajte skratku predmetu" required>
                        <label for="grade">Stupeň štúdia:</label>
                        <select class="form-control subjectFieldOfStudy" name= "grade" id="grade" required>
                            <option value="bc." >Bakalársky</option>
                            <option value="ing." >Inžiniersky</option>
                            <option value="phd." >Doktoranský</option>
                        </select>
                        <label for="year">Ročník:</label>
                        <select class="form-control" name= "year" id="year" required>
                            <option value="1" >1. ročník</option>
                            <option value="2" >2. ročník</option>
                            <option value="3" >3. ročník</option>
                        </select>
                        <label for="semestre">Semester:</label>
                        <select class="form-control" name= "semestre" id="semestre" required>
                            <option value="ZS" >Zimný semester</option>
                            <option value="LS" >Letný semester</option>
                        </select>
                        <label>Štúdijné odbory:</label>
                        </div><div class="form-group checkboxPlace"><div class="checkboxPlaceContainer">      
                    ';
            $selected=selectAllFieldsOfStudy($conn);
            if ($selected){
                while ($item=mysqli_fetch_assoc($selected)){
                    $id= $item["id"];
                    $name = $item["name"];
                    echo '<label for="'.$id.'"><input type="checkbox" name="fieldOfStudies[]" value="'.$id.'" id="'.$id.'">'.$name.'</input></label>';
                }
            }
            echo '</div></div>
                        <button type="submit" class="btn btn-primary">Vložiť predmet</button>
                    </form>';
        }
        else if ($_GET["type"]==4){
            echo '<form class="form addForm">                   
                    <input type="hidden" id="type" name="type" value = "4">
                    <div class="form-group"> <label for="id">Meno učiteľa:</label>
                    <select class="form-control" name= "id" id="id" required>
                        ';
            $selected=selectAllTeachers($conn);
            if ($selected){
                while ($item=mysqli_fetch_assoc($selected)){
                    $id= $item["id"];
                    $name = $item["name"];
                    echo "<option value= '$id'>$name</option>";
                }
            }
             echo '</select><label for="Day">Deň:</label><select class="form-control" name= "Day" id="Day">';
            echo '<option value ="0">Vyber si deň obmedzenia</option>';
            for ($y=0;$y < count($days);$y++){
                echo '<option value="'.$days[$y].'" >'.$days[$y].'</option>';
            }
            echo '</select><label for="exerciseFrom">Čas:</label><br> <label class="control-label">od 
                        <input type="time" class="form-inline" name= "From" id="From" min="00:00" max="23:59" value = null>
                        do <input type="time" class="form-inline" name= "To" id="To" min="00:00" max="23:59" value = null>
                    </div>
                    <button type="submit" class="btn btn-primary">Vložiť obmedzenie</button>
                </form>';
        }
        else
            echo "<h1>Nesprávne navštívenie stránky</h1>";
    }
    else
        echo "<h1>Nesprávne navštívenie stránky</h1>";


?>
    <div id="modal_background"></div>
    <div class="modal_div">
        <div id="modal_vrstva">
            <h1 id="success_insert">Úspešne pridanie do databázy</h1>
            <button class="btn btn-primary" onclick="window.location.href='index.php'">Späť na hlavnú stránku</button>
        </div>
    </div>

<?php
include "partials/footer.php";
?>