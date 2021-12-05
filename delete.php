<?php
	include_once("header.php");
	include('connection.php');

    if(isset($_GET['Del']))
         {
             $id = $_GET['Del'];
            $stmt = $conn->prepare("DELETE from students where stu_id = '".$id."'"); 
            $stmt->execute();
             if($stmt)
             {
                 
                 
                 header("location:updel.php");
             }
        }
         else
         {
             echo ' Please Check Your Query ';
         }
	
?>