<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
if (isset($_GET["study"])&& isset($_GET["year"])&&isset($_GET["semestre"])){
    echo '<table class="tabulka"><thead>
        <tr>
            <td>Deň/čas</td>
            <td>5:00-5:50</td>
            <td>6:00-6:50</td>
            <td>7:00-7:50</td>
            <td>8:00-8:50</td>
            <td>9:00-9:50</td>
            <td>10:00-10:50</td>
            <td>11:00-11:50</td>
            <td>12:00-12:50</td>
            <td>13:00-13:50</td>
            <td>14:00-14:50</td>
            <td>15:00-15:50</td>
            <td>16:00-16:50</td>
            <td>17:00-17:50</td>
            <td>18:00-18:50</td>
            <td>19:00-19:50</td>
            <td>20:00-20:50</td>
            <td>21:00-21:50</td>
            <td>22:00-22:50</td>
            <td>23:00-23:50</td>
        </tr>
        </thead>
        <tbody>';
    echo '<tr>
            <td class="dni_tabulky">Po</td>   
            <td colspan="19"></td>         
        </tr>';
    echo '<tr>
            <td class="dni_tabulky">Ut</td> 
            <td colspan="19"></td>           
        </tr>';
    echo '<tr>
            <td class="dni_tabulky">St</td>  
            <td colspan="19"></td>            
        </tr>';
    echo '<tr>
            <td class="dni_tabulky">Št</td> 
            <td colspan="19"></td>             
        </tr>';
    echo '<tr>
            <td class="dni_tabulky">Pi</td> 
            <td colspan="19"></td>             
        </tr>';
    echo '</tbody></table>';
    //echo"vybrali ste rozvrh pre elektroenergetiku";
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