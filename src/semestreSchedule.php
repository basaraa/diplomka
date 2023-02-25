<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
if (isset($_GET["study"])&& isset($_GET["year"])&&isset($_GET["semestre"])){
    echo"vybrali ste rozvrh pre elektroenergetiku";
}
else{
    $link = $conn;
    $selected = selectAllFieldsOfStudy($link);
    if ($selected){
        while ($fieldOfStudy=mysqli_fetch_assoc($selected)){
            $name= $fieldOfStudy["name"];
            $id= $fieldOfStudy["id"];
            echo '<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            '.$name.'
        </button>
        <ul class="dropdown-menu">';
            $fields = ["bc","ing","phd"];
            $years = [3,2,2];
            $semestre = ["ZS","LS"];
            for ($i=0;$i<3;$i++){
                echo " <li><a class='dropdown-item' href='#'>$fields[$i]</a>
                       <ul class='dropdown-menu dropdown-submenu'>";

                for ($j=1;$j<$years[$i]+1;$j++){
                    echo "<li> <a class='dropdown-item' href='#'> $j . ročník </a>
                          <ul class='dropdown-menu dropdown-submenu'>";
                    for ($k=0;$k<2;$k++){
                        echo  "<li> <a class='dropdown-item' href='semestreSchedule.php?study=$id&grade=$fields[$i].&year=$j&semestre=$semestre[$k]'>$semestre[$k]</a></li>";
                    }
                    echo "</ul></li>";
                }
                echo "</ul></li>";
            }
            echo "</ul></div>";
        }
    }
}
?>


<?php
include "partials/footer.php";
?>