<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["semestre"])&&isset($_POST["roomId"])){
    $semestre =$_POST["semestre"];
    $roomId= $_POST["roomId"];
    $days=["pondelok","utorok","streda","štvrtok","piatok"];
    $selectedRoom=selectRoomById($conn,$roomId);
    if ($selectedRoom)
        echo '<h2 class="purple">Rozvrh pre '.mysqli_fetch_assoc($selectedRoom)["name"].' v '.$semestre.'</h2>';
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
    foreach ($days as $day){
        $selected = selectSubjectsByRoomDay($conn,$roomId,$semestre,$day);
        if ($selected) {
            $hour=5;
            echo '<tr>
                    <td class="day">'.mb_substr($day, 0,2,"utf-8").'</td>';
            if (($selected->num_rows)==0)
                echo '<td colspan="19"></td>';
            else {
                while ($subject = mysqli_fetch_assoc($selected)) {
                    $name=$subject["name"];
                    $roomName=$subject["room_name"];
                    $type=$subject["type"];
                    $timeFrom=intval($subject["time_from"]);
                    $timeTo=intval($subject["time_to"]);
                    $selectedTeachers = selectTeachersBySubject($conn,$subject["id"]);
                    $teachers='';
                    if ($selectedTeachers) {
                        $x=0;
                        while ($teacher = mysqli_fetch_assoc($selectedTeachers)){
                            if ($x!==0)
                                $teachers.=", ";
                            $x=1;
                            $teachers.=$teacher["name"];
                        }
                    }

                    //miesto medzi prednáškami/cvičeniami
                    $diffFrom=$timeFrom-$hour;
                    if ($diffFrom>0)
                        echo '<td colspan="'.$diffFrom.'"></td>';
                    //prednáška/cvičenie
                    $diffFromTo = $timeTo - $timeFrom;
                    echo '<td class="'.$type.'" colspan="'.$diffFromTo.'">'.$roomName.' <br> '.$name.' <br> '.$teachers.'</td>';
                    $hour=$hour+$diffFrom+$diffFromTo;
                }

            }
        }
    }
    echo '</tbody></table>';
    echo '<div class="legend"> <h4 class="purple">Legenda k rozvrhu: </h4>';
    echo '<span class="legend_item lecture">Prednáška</span><span class="legend_item exercise">Cvičenie</span></div>';
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