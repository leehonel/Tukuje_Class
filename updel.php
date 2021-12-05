<?php 
    include_once("header.php");
    require_once("connection.php");
    $stmt = $conn->prepare("SELECT * FROM students");
    $stmt->execute();
   

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" a href="CSS/bootstrap.css"/>
    <title>View Records</title>
    <button class="btn btn-primary" onclick="history.go(-1);">GO BACK </button>
</head>
<body class="#FFFFFF">

        <div class="container">
            <div class="row">
                <div class="col m-auto">
                    <div class="card mt-5">
                        <table class="table table-bordered">
                            <tr>
                                        <td> User ID </td>
                                        <th>Registration No.</th>
                                        <th>Finger Serial</th>
                                        <th>Name</th>
                                        <th>Branch</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Course</th>
                            </tr>

                            <?php 
                                    
                                    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                                    {
                                    $id = $row['stu_id'];
                                    $reg = $row['reg_no'];
                                    $stu_fing = $row['stu_fing_serial'];
                                    $nm = $row['name'];
                                    $br = $row['branch'];
                                    $em = $row['email'];
                                    $mob = $row['mobile']; 
                                    $cou = $row['fk_course_code'];
                            ?>
                                    <tr>
                                        <td><?php echo $id ?></td>
                                        <td><?php echo $reg ?></td>
                                        <td><?php echo $stu_fing ?></td>
                                        <td><?php echo $nm ?></td>
                                        <td><?php echo $br ?></td>
                                        <td><?php echo $em ?></td>
                                        <td><?php echo $mob ?></td>
                                        <td><?php echo $cou ?></td>
                                        <td><a href="edit.php?GetID=<?php echo $id ?>">Edit</a></td>
                                        <td><a href="delete.php?Del=<?php echo $id ?>">Delete</a></td>
                                    </tr>        
                            <?php 
                                    }  
                            ?>                                                                    
                                   

                        </table>
                    </div>
                </div>
            </div>
        </div>
    
</body>
</html