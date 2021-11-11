<?php 
    include_once("header.php");
    require_once("connection.php");
    $stmt = $conn->prepare("SELECT * FROM faculty_users");
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
                                        <th>Faculty Id</th>
                                        <th>National ID No.</th>
                                        <th>Faculty Serial</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Branch</th>
                            </tr>

                            <?php 
                                    
                                    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                                    {
                                    $id = $row['fac_id'];
                                    $natdno = $row['national_no'];
                                    $fac_fingserial = $row['fac_fing_serial'];
                                    $nme = $row['name'];
                                    $mail = $row['email'];
                                    $mobi = $row['mobile'];
                                    $brnch = $row['branch'];
                            ?>
                                    <tr>
                                        <td><?php echo $id ?></td>
                                        <td><?php echo $natdno ?></td>
                                        <td><?php echo $fac_fingserial ?></td>
                                        <td><?php echo $nme ?></td>
                                        <td><?php echo $mail ?></td>
                                        <td><?php echo $mobi ?></td>
                                        <td><?php echo $brnch ?></td>
                                        <td><a href="editF.php?GetID=<?php echo $id ?>">Edit</a></td>
                                        <td><a href="deleteF.php?Del=<?php echo $id ?>">Delete</a></td>
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