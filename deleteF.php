<?php
	include_once("header.php");
	include('connection.php');

    if(isset($_GET['Del']))
         {
             $id = $_GET['Del'];
            $stmt = $conn->prepare("DELETE from faculty_users where fac_id = '".$id."'"); 
            $stmt->execute();
             if($stmt)
             {
                 
                 
                 header("location:updelF.php");
             }
        }
         else
         {
             echo ' Please Check Your Query ';
         }
	
?>