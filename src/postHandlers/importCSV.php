<?php
require_once("../config/config.php");
include "../databaseQueries/databaseQueries.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["type"])){
        $types=array (0,1,2,4);
        $type = (int)$_POST["type"];
        if (in_array ($_POST["type"],$types)){
            $type=$_POST["type"];
            if (isset ($_FILES["fileCSV"]["tmp_name"])){
                $msg='';
                if($_FILES["fileCSV"]["size"] > 0) {
                    $file_name=$_FILES["fileCSV"]["tmp_name"];
                    $opened_file = fopen ($file_name,"r");
                    $days=["pondelok","utorok","streda","štvrtok","piatok"];
                    while ($line=fgetcsv($opened_file,80)){
                        $result=null;
                        //odbor
                        if ($type==0){
                            if (isset ($line[0]) && isset ($line[1])){
                                $name=$line[0];
                                $shortcut=$line[1];
                                $checkFoS= selectFieldOfStudyByName ($conn,$name);
                                if ($checkFoS && ($checkFoS->num_rows)===0) {
                                    if ($name != '' && $shortcut != '' && (!(isset($line[2]))))
                                        $result = insertFieldsOfStudy($conn, $name, $shortcut);
                                }
                            }
                        }
                        //učiteľ
                        else if ($type==1){
                            if (isset ($line[0])) {
                                $name = $line[0];
                                $checkTeacher = selectTeacherByName($conn, $name);
                                if ($checkTeacher && ($checkTeacher->num_rows) === 0) {
                                    if ($name != '' && (!(isset($line[1]))))
                                        $result = insertTeacher($conn, $name);
                                }
                            }
                        }
                        //miestnosť
                        else if ($type==2){
                            if (isset ($line[0])) {
                                $name = $line[0];
                                $checkRoom = selectRoomByName($conn, $name);
                                if ($checkRoom && ($checkRoom->num_rows) === 0) {
                                    if ($name != '' && (!(isset($line[1]))))
                                        $result = insertRoom($conn, $name);
                                }
                            }
                        }
                        //obmedzenie
                        else {
                            if (isset ($line[0]) && isset ($line[1]) && isset ($line[2]) && isset ($line[3])) {
                                $teacher = $line[0];
                                $day = $line[1];
                                $ok = 1;
                                $name = '';
                                $from = $line[2];
                                if ($from != '')
                                    if (DateTime::createFromFormat('H:i', $from) === false)
                                        $ok = 0;
                                $to = $line[3];
                                if ($to != '')
                                    if (DateTime::createFromFormat('H:i', $to) === false)
                                        $ok = 0;
                                if ($to == '')
                                    $to = "23:59";
                                if ($from == '')
                                    $from = "00:00";
                                if ((($day != '' && in_array($day, $days)) || $day == '') &&
                                    $teacher != '' && $ok == 1 && (!(isset($line[4])))) {
                                    $selectedTeacher = selectTeacherByName($conn, $teacher);
                                    if (isset($selectedTeacher) && !empty($selectedTeacher)) {
                                        $name = $day . ' od ' . $from . ' do ' . $to . '<br>';
                                        if ($day == '')
                                            $day = 0;
                                        $teacherId = mysqli_fetch_assoc($selectedTeacher)["id"];
                                        $result = insertTeacherConstraints($conn, $teacherId, $day, $from, $to);
                                    }
                                }
                            }
                        }
                        if((isset($result))) {
                            if ($type==4){
                                if ($name!=='')
                                    $msg.=$name;
                            }
                            else {
                                if ($name !== '')
                                    $msg .= $name . ', ';
                            }
                        }
                    }
                }
                if (!empty($msg)){
                    $msg = "<h2 class='blue'>Úspešne pridané: ".$msg."</h2>";
                    echo json_encode(["scs" => true,"msg" => $msg]);
                }
                else
                    echo json_encode(["scs" => false,"msg" => "<h2 class='red'>Súbor nie je csv, má nesprávnu štruktúru, je prázdny alebo obsahuje len duplikáty</h2>"]);
            }
            else echo http_response_code(400);
        }
        else echo http_response_code(400);
    }
    else echo http_response_code(400);
}
?>