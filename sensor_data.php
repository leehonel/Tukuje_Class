<?php
header('Access-Control-Allow-Origin: *');
include_once('connection.php');
session_start();
// $response = file_POST_contents("php://input");

// in fingerprint enrollment phase
if(isset($_POST['reg_no']) && !empty($_POST['reg_no']) && isset($_POST['fing_serial']) && !empty($_POST['fing_serial'])&& isset($_POST['is_student']))
{
    $reg_no     = $_POST['reg_no'];
    $fing_serial = $_POST['fing_serial'];
    // check if is student
    if($_POST['is_student'] == 1){
        
        $stu_stmt   = $conn->prepare("UPDATE students SET stu_fing_serial=$fing_serial WHERE reg_no=".$reg_no);
        if ($stu_stmt->execute())
        {
            echo 'Student record successfully updated';
        }
        else
        {
            echo 'Operation Failed';
        }
    }
    // check if is faculty
    else if($_POST['is_student'] == 0){
        $fac_stmt   = $conn->prepare("UPDATE faculty_users SET fac_fing_serial=$fing_serial WHERE fac_reg_no=".$reg_no);
        if ($fac_stmt->execute())
        {
            echo 'Faculty record successfully updated';
        }
        else
        {
            echo 'NOT Success';
        }
    }
}


// in fingerprint validation phase
else if(isset($_POST['fing_serial']) && !empty($_POST['fing_serial']))
{
     // serial id to search
    $fing_serial = $_POST['fing_serial'];
    // unset($_SESSION['is_class_started']);
    // unset($_SESSION['subject_id']);
    // search query for faculty 
    $fac_fing_query = $conn->prepare("SELECT * FROM faculty_users WHERE fac_fing_serial=".$fing_serial);
    $fac_fing_query->execute();

    // if faculty fingerprint found
    if ($fac_fing_query->rowCount())
    {
        $faculty_data = $fac_fing_query->fetch(PDO::FETCH_ASSOC);
        if(isset($_SESSION['faculty_id']) && $_SESSION['faculty_id'] != $faculty_data['fac_id'])
        {
            unset($_SESSION['is_class_started']);
            unset($_SESSION['subject_id']);
            unset($_SESSION['faculty_id']);
        }
    }
    // if faculty not exist in session
    if(!isset($_SESSION['is_class_started']) || !isset($_SESSION['subject_id']))
    {
        // search query for faculty 
        $fac_fing_query = $conn->prepare("SELECT * FROM faculty_users WHERE fac_fing_serial=".$fing_serial);
        $fac_fing_query->execute();

        // if faculty fingerprint found
        if ($fac_fing_query->rowCount())
        {
            $faculty_data = $fac_fing_query->fetch(PDO::FETCH_ASSOC);
            $subject_query = $conn->prepare("SELECT * FROM subjects WHERE fk_faculty_id = :fac_id");
            $subject_query->execute(array("fac_id" => $faculty_data['fac_id']));

            if ($subject_query->rowCount())
            {
                $subject_data = $subject_query->fetch(PDO::FETCH_ASSOC);
                $attendance_stmt = $conn->prepare("INSERT INTO fac_attendance_sheet(`fk_subject_code`, `date`, `time`, `fk_faculty_id`)
            VALUES(:fk_subject_code, :entry_date, :entry_time, :fk_faculty_id)");
            
                if($attendance_stmt->execute(array(
                "fk_subject_code" => $subject_data['subject_code'],
                "fk_faculty_id" => $faculty_data['fac_id'],
                'entry_date'=>date('Y-m-d'),
                'entry_time'=>date('H:i:s')
                ))){
                    $_SESSION['is_class_started'] = "YES";
                    $_SESSION['subject_id'] = $subject_data['subject_id'];
                    $_SESSION['faculty_id'] = $faculty_data['fac_id'];
                    echo 'Faculty attendance successfully recorded';
                }
                else
                {
                    echo 'Faculty attendance not recorded';
                }
            }
        }
       

    // if is student fingerprint
    else{
        // find the student id
        
         $stu_stmt4_query   = $conn->prepare("SELECT reg_no FROM students WHERE stu_fing_serial=".$fing_serial);
        $stu_stmt4_query->execute();

        $stu_stmt3_query   = $conn->prepare("SELECT name FROM students WHERE stu_fing_serial=".$fing_serial);
        $stu_stmt3_query->execute();
        
        $stu_stmt2_query   = $conn->prepare("SELECT fk_subject_code FROM fac_attendance_sheet ORDER BY attandance_id DESC LIMIT 1");
        $stu_stmt2_query->execute();

        $stu_stmt1_query   = $conn->prepare("SELECT fk_course_code FROM students WHERE stu_fing_serial=".$fing_serial);
        $stu_stmt1_query->execute();

        $stu_stmt_query   = $conn->prepare("SELECT stu_id FROM students WHERE stu_fing_serial=".$fing_serial);
        $stu_stmt_query->execute();
        if($stu_stmt_query->rowCount())
        {

            $student4_data = $stu_stmt4_query->fetch(PDO::FETCH_ASSOC);
            $student3_data = $stu_stmt3_query->fetch(PDO::FETCH_ASSOC);
            $student2_data = $stu_stmt2_query->fetch(PDO::FETCH_ASSOC);
            $student1_data = $stu_stmt1_query->fetch(PDO::FETCH_ASSOC);
            $student_data = $stu_stmt_query->fetch(PDO::FETCH_ASSOC);
            $attendance_stmt = $conn->prepare("INSERT INTO attendance_sheet(`fk_reg`, `fk_name`, `fk_subject_code`,  `fk_course_code`, `fk_student_id`, `date`, `time` )
            VALUES(:fk_reg, :fk_name, :fk_subject_code, :fk_course_code, :fk_student_id, :entry_date, :entry_time)");
            
            if($attendance_stmt->execute(array(
            "fk_reg" => $student4_data['reg_no'],
            "fk_name" => $student3_data['name'],
            "fk_subject_code" => $student2_data['fk_subject_code'],
            "fk_course_code" => $student1_data['fk_course_code'],
            "fk_student_id" => $student_data['stu_id'],
            'entry_date'=>date('Y-m-d'),
            'entry_time'=>date('H:i:s')
            ))){
                echo 'Student attendance successfully recorded';
            }
            else
            {
                echo 'Student attendance not recorded';
            }
        }
        else
        {
            echo 'Student not present in system';
        }
    }
}
}
else{
    echo "Please send Registration No. and Fingerprint Serial";
    exit(0);
    }
?>