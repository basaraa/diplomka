<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
if (isset($_POST["semestre"])&&isset($_POST["roomId"])){
    $semestre =$_POST["semestre"];
    $roomId= $_POST["roomId"];
    //echo'vybrali ste rozvrh pre miestnosť s id '.$roomId.' v semestry '.$semestre.'';
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