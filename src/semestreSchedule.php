<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["study"])&& isset($_GET["grade"])&& isset($_GET["year"])&&isset($_GET["semestre"])){
    $study = $_GET["study"];
    $grade = $_GET["grade"];
    $year = $_GET["year"];
    $semestre = $_GET["semestre"];
    $days=["pondelok","utorok","streda","štvrtok","piatok"];
    $fieldOfStudyName=selectFieldOfStudyById($conn,$study);
    if ($fieldOfStudyName)
        echo '<h2 class="purple">Rozvrh pre '.mysqli_fetch_assoc($fieldOfStudyName)["name"].' v '.$grade.' štúdiu v '.$year.'.ročníku v '.$semestre.'</h2>';

    echo '<table class="tabulka"><thead>
        <tr>
            <td>Deň/čas</td>
            <td>5:00-5:50</td>
            <td class="empty">   </td>
            <td>6:00-6:50</td>
            <td class="empty">   </td>
            <td>7:00-7:50</td>
            <td class="empty">   </td>
            <td>8:00-8:50</td>
            <td class="empty">   </td>
            <td>9:00-9:50</td>
            <td class="empty">   </td>
            <td>10:00-10:50</td>
            <td class="empty">   </td>
            <td>11:00-11:50</td>
            <td class="empty">   </td>
            <td>12:00-12:50</td>
            <td class="empty">   </td>
            <td>13:00-13:50</td>
            <td class="empty">   </td>
            <td>14:00-14:50</td>
            <td class="empty">   </td>
            <td>15:00-15:50</td>
            <td class="empty">   </td>
            <td>16:00-16:50</td>
            <td class="empty">   </td>
            <td>17:00-17:50</td>
            <td class="empty">   </td>
            <td>18:00-18:50</td>
            <td class="empty">   </td>
            <td>19:00-19:50</td>
            <td class="empty">   </td>
            <td>20:00-20:50</td>
            <td class="empty">   </td>
            <td>21:00-21:50</td>
            <td class="empty">   </td>
            <td>22:00-22:50</td>
            <td class="empty">   </td>
            <td>23:00-23:50</td>
        </tr>
        </thead>
        <tbody>';
    foreach ($days as $day){
        $selected = selectSubjectsByStudyGradeYearSemestreDay($conn,$study,$grade,$year,$semestre,$day);
        if ($selected) {
            $hour=5;
            echo '<tr>
                    <td class="day">'.mb_substr($day, 0,2,"utf-8").'</td>';
            if (($selected->num_rows)==0){
                $hours=37;
                echo '<td colspan="37"></td>';
            }
            else {
                $y=0;
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
                    echo '<td colspan="'.($diffFrom*2+$y).'"></td>';
                    //prednáška/cvičenie
                    $diffFromTo = $timeTo - $timeFrom;
                    echo '<td class="'.$type.'" colspan="'.($diffFromTo*2+1).'">'.$roomName.' <br> '.$name.' <br> '.$teachers.'</td>';
                    $hour=$hour+$diffFrom+$diffFromTo+1;
                    $y=1;
                }
                if ($hour <37)
                    echo '<td colspan="'.(37-$hour+1).'"></td>';
            }
        }
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '<div class="legend"> <h4 class="purple">Legenda k rozvrhu: </h4>';
    echo '<span class="legend_item lecture">Prednáška</span><span class="legend_item exercise">Cvičenie</span></div>';

}
else{
    $selected = selectAllFieldsOfStudy($conn);
    if ($selected){
        while ($fieldOfStudy=mysqli_fetch_assoc($selected)){
            $name= $fieldOfStudy["name"];
            $shortcut= $fieldOfStudy["shortcut"];
            $id= $fieldOfStudy["id"];
            echo '<div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            '.$name.' ('.$shortcut.')
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