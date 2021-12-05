<?php 

    require_once("connection.php");
    include_once("header.php");

    if(isset($_POST['update']))
    {
        $id = $_GET['ID'];
        $reg = $_POST['reg'];
        $stu_fing = $_POST['stu_fing'];
        $nm = $_POST['nm'];
        $br = $_POST['br'];
        $em = $_POST['em'];
        $mob = $_POST['mob']; 
        $cou = $_POST['cou'];

        $stmt = $conn->prepare("UPDATE `students` set reg_no='".$reg."', stu_fing_serial='".$stu_fing."', name='".$nm."', branch='".$br."', email='".$em."', mobile='".$mob."', fk_course_code = '".$cou."' where stu_id='".$id."'");
        $stmt->execute();

        if($stmt)
        {
            header("location:updel.php");
            
        }
        else
        {
            echo ' Please Check Your Query ';
        }
    }
    else
    {
        header("location:updel.php");
    }


?>