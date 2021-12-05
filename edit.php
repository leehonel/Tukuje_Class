<?php 

    require_once("connection.php");
    include_once("header.php");

    $id = $_GET['GetID'];
    $stmt = $conn->prepare("SELECT * from students where stu_id = '".$id."'"); 
    $stmt->execute();

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
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" a href="CSS/bootstrap.css"/>
    <title>Document</title>
</head>
<body class="#FFFFFF">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <div class="card mt-5">
                        <div class="card-title">
                            <h3 class="bg-success text-white text-center py-3"> Update Student Details</h3>
                        </div>
                        <div class="card-body">

                            <form action="update.php?ID=<?php echo $id ?>" method="post">
                                <input type="text" class="form-control mb-2" placeholder=" Registration No " name="reg" value="<?php echo $reg ?>">
                                <input type="text" class="form-control mb-2" placeholder=" User Fingerpint Serial " name="stu_fing" value="<?php echo $stu_fing ?>">
                                <input type="text" class="form-control mb-2" placeholder=" User Name " name="nm" value="<?php echo $nm ?>">
                                <input type="text" class="form-control mb-2" placeholder=" User Branch " name="br" value="<?php echo $br ?>">
                                <input type="text" class="form-control mb-2" placeholder=" User Age " name="em" value="<?php echo $em ?>">
                                <input type="text" class="form-control mb-2" placeholder=" Mobile No " name="mob" value="<?php echo $mob ?>">
                                <input type="text" class="form-control mb-2" placeholder=" Course " name="cou" value="<?php echo $cou ?>">
                                <button class="btn btn-primary" name="update">Update</button>
                                <button class="btn btn-primary" style="float: right; onclick="history.go(-1);">GO BACK </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</body>
</html>