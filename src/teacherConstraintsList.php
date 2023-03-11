<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";

echo '<form class="constraintListGet"><div class="form-group">
<input type="hidden" id="list" name="list" value="1">
<select class="form-control" name= "id" id="id" required>';
$selected=selectAllTeachers($conn);
if ($selected){
    while ($item=mysqli_fetch_assoc($selected)){
        $id= $item["id"];
        $name = $item["name"];
        echo "<option value= '$id'>$name</option>";
    }
}
echo '</select></div><button type="submit" class="btn btn-primary">Zobraziť zoznam obmedzení</button></form>';
?>

<div id="modal_background"></div>
<div class="modal_div">
    <div id="modal_vrstva">
            <div id="modal_text">
            </div>
        <button class="btn btn-primary" onclick="go_back();">Vrátiť sa späť</button>
    </div>
</div>
<?php
include "partials/footer.php";
?>
