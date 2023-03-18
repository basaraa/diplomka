<?php
include "partials/header.php";
require_once("config/config.php");
include "databaseQueries/databaseQueries.php";
if (isset($_GET["study"]) && isset($_GET["grade"]) && isset($_GET["year"]) && isset($_GET["semestre"])){
    $study = $_GET["study"];
    $grade = $_GET["grade"];
    $year = $_GET["year"];
    $semestre = $_GET["semestre"];
    $fieldOfStudyName=selectFieldOfStudyById($conn,$study);
    if ($fieldOfStudyName)
        echo '<h2 class="purple">Rozvrh pre '.mysqli_fetch_assoc($fieldOfStudyName)["name"].' v '.$grade.' štúdiu v '.$year.'.ročníku v '.$semestre.'</h2>';
    $selected = selectSubjectByStudyGradeYearSemestre($conn,$study,$grade,$year,$semestre);
    if ($selected) {
        echo '<div class="flexdiv">';
        while ($subject = mysqli_fetch_assoc($selected)) {
            $name = $subject["name"];
            $shortcut = $subject["shortcut"];
            $lecture_room_id = $subject["lecture_room_id"];
            $exercise_room_id = $subject["exercise_room_id"];
            $lectureDay=$subject["lecture_day"];
            $exerciseDay=$subject["exercise_day"];
            $lectureFrom=$subject["lecture_time_from"];
            $lectureTo=$subject["lecture_time_to"];
            $exerciseFrom=$subject["exercise_time_from"];
            $exerciseTo=$subject["exercise_time_to"];
            echo '<div class="inflexdiv">
                  <h5>Meno predmetu:</h5>
                  <p>'.$name.'</p>
                  <h5>Skratka:</h5>
                  <p>'.$shortcut.'</p>
                                   
                  ';
            echo '<h5>Vyučujúci:</h5><p>';
            $selectedTeachers = selectTeachersBySubject($conn,$subject["id"]);
            if ($selectedTeachers) {
                $x=0;
                while ($teacher = mysqli_fetch_assoc($selectedTeachers)){
                    if ($x!==0)
                        echo ', ';
                    $x=1;
                    echo ''.$teacher["name"].'';
                }
                if ($x===0)
                    echo '<p>nezadané</p>';
            }
            else
                echo '<p>nezadané</p>';
            echo '<h5>Miestnosť prednášky:</h5>';
            if ($lecture_room_id){
                $result=selectRoomById($conn,$lecture_room_id);
                if ($result){
                    $room=mysqli_fetch_assoc($result);
                    echo'<p>'.$room["name"].'</p>';
                }
            }
            else
                echo '<p>nezadané</p>';
            echo'<h5>Čas prednášky:</h5>';
            if ($lectureDay)
                echo '<p>'.$lectureDay.' </p><p>';
            else
                echo '<p>nezadaný deň </p><p>';
            if ($lectureFrom && $lectureTo)
                echo 'od '.date("H:i",strtotime($lectureFrom)).' do '.date("H:i",strtotime($lectureTo)).'';
            else if ($lectureFrom)
                echo 'od '.date("H:i",strtotime($lectureFrom)).'';
            else if ($lectureTo)
                echo 'do '.date("H:i",strtotime($lectureTo)).'';
            else
                echo 'nezadaný čas';
            echo '</p>';
            echo '<h5>Miestnosť cvičenia:</h5>';
            if ($exercise_room_id){
                $result=selectRoomById($conn,$exercise_room_id);
                if ($result){
                    $room=mysqli_fetch_assoc($result);
                    echo'<p>'.$room["name"].'</p>';
                }
            }
            else
                echo '<p>nezadané</p>';
            echo'<h5>Čas cvičenia:</h5>';
            if ($exerciseDay)
                echo '<p>'.$exerciseDay.' </p><p>';
            else
                echo '<p>nezadaný deň </p><p>';
            if ($exerciseFrom && $exerciseTo)
                echo 'od '.date("H:i",strtotime($exerciseFrom)).' do '.date("H:i",strtotime($exerciseTo)).'';
            else if ($exerciseFrom)
                echo 'od '.date("H:i",strtotime($exerciseFrom)).'';
            else if ($exerciseTo)
                echo 'do '.date("H:i",strtotime($exerciseTo)).'';
            else
                echo 'nezadaný čas';
            echo '</p>';
            $sId=$subject["id"];

            $functionEditForm="'$sId','$grade','$year','$semestre'";
            echo'
                 <button class="btn btn-primary" onclick="generateEditForm('.$functionEditForm.')">Upraviť predmet</button>
                 ';

            echo'
                 <button class="btn btn-primary" onclick="resetSingleSubject('.$sId.')">Resetovať predmet</button>
                 </div>';
        }
        if (isset($sId))
            echo '</div><button class="btn btn-primary reset_fieldOfStudy_subject" onclick="resetFieldOfStudySubjects('.$sId.')">Resetovať všetky predmety</button>';
    }

}
else http_response_code(400);
?>
    <div id="modal_background"></div>
    <div class="modal_div">
        <div id="modal_vrstva">
            <form class="form editForm">
            <div id="modal_text">
            </div>
            </form>
            <button class="btn btn-primary" onclick="go_back();">Vrátiť sa späť</button>
        </div>
    </div>
    <div id="modal_background2"></div>
    <div class="modal_div2">
        <div id="modal_vrstva2">
            <h1 id="result_edit"></h1>
            <button class="btn btn-primary" onclick="window.location.href='subjects.php?study=<?=$study?>&grade=<?=$grade?>&year=<?=$year?>&semestre=<?=$semestre?>'">Vrátiť sa späť</button>
        </div>
    </div>
    <div id="modal_background3"></div>
    <div class="modal_div3">
        <div id="modal_vrstva3">
            <div id="modal_text3">
            </div>
            <button class="btn btn-primary" onclick="go_back2();">Vrátiť sa späť</button>
        </div>
    </div>
<?php
include "partials/footer.php";
?>