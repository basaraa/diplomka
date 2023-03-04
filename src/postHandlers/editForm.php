<?php
require_once("../config/config.php");
include "../databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["subjectId"]) && isset ($_POST["grade"])
        && isset ($_POST["year"]) && isset ($_POST["semestre"])){
        $subjectId=$_POST["subjectId"];
        $grade =$_POST["grade"];
        $year = $_POST["year"];
        $semestre = $_POST["semestre"];
        $selected=selectSubjectById($conn,$subjectId);
        if ($selected){
            $days=["pondelok","utorok","streda","štvrtok","piatok"];
            $subject=mysqli_fetch_assoc($selected);
            $name = $subject["name"];
            $lectureDay=$subject["lecture_day"];
            $exerciseDay=$subject["exercise_day"];
            $lectureFrom=$subject["lecture_time_from"];
            $lectureTo=$subject["lecture_time_to"];
            $exerciseFrom=$subject["exercise_time_from"];
            $exerciseTo=$subject["exercise_time_to"];
            $selectedSubjectTeachers=selectTeachersBySubject($conn,$subjectId);
            $subjectTeachers=[];
            if ($selectedSubjectTeachers)
                while ($teacher=mysqli_fetch_assoc($selectedSubjectTeachers))
                    array_push($subjectTeachers,$teacher["id"]);
            $roomId=$subject["room_id"];
            $allRooms=selectAllRooms($conn);
            echo '<h1>'.$name.'</h1>
                                    
                    <input type="hidden" id="subjectId" name="subjectId" value = "'.$subjectId.'">
                    <input type="hidden" id="grade" name="grade" value = "'.$grade.'">
                    <input type="hidden" id="year" name="year" value = "'.$year.'">
                    <input type="hidden" id="semestre" name="semestre" value = "'.$semestre.'">
                    <div class="form-group">
                    <label for="room_id">Miestnosť:</label>
                    <select class="form-control" name= "room_id" id="room_id" required>';
            if ($allRooms){
                while ($room=mysqli_fetch_assoc($allRooms)){
                    $id= $room["id"];
                    $name = $room["name"];
                    if ($roomId && $id===$roomId)
                        echo "<option value= '$id' selected>$name</option>";
                    else
                        echo "<option value= '$id'>$name</option>";
                }
            }
            echo '</select><label >Vyučujúci:</label>';
            echo '</div><div class="form-group checkboxPlace"><div class="checkboxPlaceContainer">';
            $selectedTeachers=selectAllTeachers($conn);
            if ($selectedTeachers){
                while ($item=mysqli_fetch_assoc($selectedTeachers)){
                    $id= $item["id"];
                    $name = $item["name"];
                    if (in_array($id,$subjectTeachers))
                        echo '<label for="'.$id.'"><input type="checkbox" name="subjectTeachers[]" value="'.$id.'" id="'.$id.'" Checked>'.$name.'</input></label>';
                    else
                        echo '<label for="'.$id.'"><input type="checkbox" name="subjectTeachers[]" value="'.$id.'" id="'.$id.'">'.$name.'</input></label>';
                }
            }
            //prednáška
            echo '</div></div><div class="form-group">';
            echo '<label for="lectureDay">Deň prednášky:</label>
                        <select class="form-control lectureDay" name= "lectureDay" id="lectureDay" required>
                            ';
            for ($x=0;$x<count($days);$x++){
                if ($lectureDay && $days[$x]===$lectureDay)
                    echo '<option value="'.$days[$x].'" selected>'.$days[$x].'</option>';
                else
                    echo '<option value="'.$days[$x].'" >'.$days[$x].'</option>';

            }
            echo '</select><label for="lectureFrom">Čas prednášky:</label><br> <label class="control-label">od ';
            if ($lectureFrom)
                echo '<input type="time" class="form-inline" name= "lectureFrom" id="lectureFrom" value = "'.$lectureFrom.'" required>';
            else
                echo '<input type="time" class="form-inline" name= "lectureFrom" id="lectureFrom" required>';
            if ($lectureTo)
                echo ' do <input type="time" class="form-inline" name= "lectureTo" id="lectureTo" value = "'.$lectureTo.'" required>';
            else
                echo ' do <input type="time" class="form-inline" name= "lectureTo" id="lectureTo" required>';
            echo '</label>';

            //cvičenie
            echo '<br><label for="exerciseDay">Deň cvičenia:</label>
                        <select class="form-control lectureDay" name= "exerciseDay" id="exerciseDay" required>
                            ';
            for ($y=0;$y < count($days);$y++){
                if ($exerciseDay && $days[$y]===$exerciseDay)
                    echo '<option value="'.$days[$y].'"  selected>'.$days[$y].'</option>';
                else
                    echo '<option value="'.$days[$y].'" >'.$days[$y].'</option>';
            }

            echo '</select><label for="exerciseFrom">Čas prednášky:</label><br> <label class="control-label">od ';
            if ($exerciseFrom)
                echo '<input type="time" class="form-inline" name= "exerciseFrom" id="exerciseFrom" value = "'.$exerciseFrom.'" required>';
            else
                echo '<input type="time" class="form-inline" name= "exerciseFrom" id="exerciseFrom" required>';
            if ($exerciseTo)
                echo ' do <input type="time" class="form-inline" name= "exerciseTo" id="exerciseTo" value = "'.$exerciseTo.'" required>';
            else
                echo ' do <input type="time" class="form-inline" name= "exerciseTo" id="exerciseTo" required>';
            echo '</label>';
            echo '</div>
            <button type="submit" class="btn btn-primary">Upraviť predmet</button>
                    ';

        }
        else http_response_code(400);
    }
    else http_response_code(400);

}
else
    http_response_code(400);

?>

