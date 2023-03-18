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


            echo '<h1>'.$name.'</h1>
                                    
                    <input type="hidden" id="subjectId" name="subjectId" value = "'.$subjectId.'">
                    <input type="hidden" id="grade" name="grade" value = "'.$grade.'">
                    <input type="hidden" id="year" name="year" value = "'.$year.'">
                    <input type="hidden" id="semestre" name="semestre" value = "'.$semestre.'">
                    <div class="form-group">
                    ';

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
            $allRooms=selectAllRooms($conn);
            echo '</div></div><div class="form-group"><label for="lecture_room_id">Miestnosť prednášky:</label>
                    <select class="form-control" name= "lecture_room_id" id="lecture_room_id" required>';
            if ($allRooms){
                $roomId=$subject["lecture_room_id"];
                while ($room=mysqli_fetch_assoc($allRooms)){
                    $id= $room["id"];
                    $name = $room["name"];
                    if ($roomId && $id===$roomId)
                        echo "<option value= '$id' selected>$name</option>";
                    else
                        echo "<option value= '$id'>$name</option>";
                }
            }
            echo '</select><label for="lectureDay">Deň prednášky:</label>
                        <select class="form-control lectureDay" name= "lectureDay" id="lectureDay" required>
                            ';
            for ($x=0;$x<count($days);$x++){
                if ($lectureDay && $days[$x]===$lectureDay)
                    echo '<option value="'.$days[$x].'" selected>'.$days[$x].'</option>';
                else
                    echo '<option value="'.$days[$x].'" >'.$days[$x].'</option>';

            }
            echo '</select><label for="lectureFrom">Čas prednášky:</label><br> <label class="control-label">od ';
            if ($lectureFrom){
                $lectureFrom=trim($lectureFrom,":");
                echo '<input type="number" class="form-inline numberPlace" name= "lectureFrom" id="lectureFrom" value = "'.$lectureFrom[0].$lectureFrom[1].'" min="5" max="23" required>:00';
            }
            else
                echo '<input type="number" class="form-inline numberPlace" name= "lectureFrom" id="lectureFrom" min="5" max="23" required>:00';
            if ($lectureTo){
                $lectureTo=trim($lectureTo,":");
                echo ' do <input type="number" class="form-inline numberPlace" name= "lectureTo" id="lectureTo" value = "'.$lectureTo[0].$lectureTo[1].'" min="5" max="23" required>:50';
            }
            else
                echo ' do <input type="number" class="form-inline numberPlace" name= "lectureTo" id="lectureTo" min="5" max="23" required>:50';
            echo '</label>';

            //cvičenie
            $allRooms=selectAllRooms($conn);
            echo '<br><label for="exercise_room_id">Miestnosť cvičenia:</label>
                    <select class="form-control" name= "exercise_room_id" id="exercise_room_id" required>';
            echo '</div></div><div class="form-group">';
            if ($allRooms){
                $roomId=$subject["exercise_room_id"];
                while ($room=mysqli_fetch_assoc($allRooms)){
                    $id= $room["id"];
                    $name = $room["name"];
                    if ($roomId && $id===$roomId)
                        echo "<option value= '$id' selected>$name</option>";
                    else
                        echo "<option value= '$id'>$name</option>";
                }
            }
            echo '</select><label for="exerciseDay">Deň cvičenia:</label>
                        <select class="form-control lectureDay" name= "exerciseDay" id="exerciseDay" required>
                            ';
            for ($y=0;$y < count($days);$y++){
                if ($exerciseDay && $days[$y]===$exerciseDay)
                    echo '<option value="'.$days[$y].'"  selected>'.$days[$y].'</option>';
                else
                    echo '<option value="'.$days[$y].'" >'.$days[$y].'</option>';
            }

            echo '</select><label for="exerciseFrom">Čas cvičenia:</label><br> <label class="control-label">od ';
            if ($exerciseFrom){
                $exerciseFrom=trim($exerciseFrom,":");
                echo '<input type="number" class="form-inline numberPlace" name= "exerciseFrom" id="exerciseFrom" value = "'.$exerciseTo[0].$exerciseFrom[1].'" min="5" max="23" required>:00';
            }

            else
                echo '<input type="number" class="form-inline numberPlace" name= "exerciseFrom" id="exerciseFrom" min="5" max="23" required>:00';
            if ($exerciseTo) {
                $exerciseTo = trim($exerciseTo, ":");
                echo ' do <input type="number" class="form-inline numberPlace" name= "exerciseTo" id="exerciseTo" value = "' . $exerciseTo[0].$exerciseTo[1] . '" min="5" max="23" required>:50';
            }
            else
                echo ' do <input type="number" class="form-inline numberPlace" name= "exerciseTo" id="exerciseTo" min="5" max="23" required>:50';
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

