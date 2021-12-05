<?php 

    require_once("connection.php");
    include_once("header.php");

    if(isset($_POST['updateF']))
    {
        $id = $_GET['ID'];
        $natdno = $_POST['natdno'];
        $fac_fingserial = $_POST['fac_fingserial'];
        $nme = $_POST['nme'];
        $mail = $_POST['mail'];
        $mobi = $_POST['mobi'];
        $brnch = $_POST['brnch'];

        $stmt = $conn->prepare("UPDATE `faculty_users` set national_no='".$natdno."', fac_fing_serial='".$fac_fingserial."', name='".$nme."', email='".$mail."', mobile='".$mobi."', branch = '".$brnch."' where fac_id='".$id."'");
        $stmt->execute();

        if($stmt)
        {
            header("location:updelF.php");
            
        }
        else
        {
            echo ' Please Check Your Query ';
        }
    }
    else
    {
        header("location:updelF.php");
    }


?>