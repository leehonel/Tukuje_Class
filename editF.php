<?php 

    require_once("connection.php");
    include_once("header.php");

    $id = $_GET['GetID'];
    $stmt = $conn->prepare("SELECT * from faculty_users where fac_id = '".$id."'"); 
    $stmt->execute();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $id = $row['fac_id'];
        $natdno = $row['national_no'];
        $fac_fingserial = $row['fac_fing_serial'];
        $nme = $row['name'];
        $mail = $row['email'];
        $mobi = $row['mobile'];
        $brnch = $row['branch'];
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

                            <form action="updateF.php?ID=<?php echo $id ?>" method="post">
                                <input type="text" class="form-control mb-2" placeholder=" National ID No " name="natdno" value="<?php echo $natdno ?>">
                                <input type="text" class="form-control mb-2" placeholder=" User Fingerpint Serial " name="fac_fingserial" value="<?php echo $fac_fingserial ?>">
                                <input type="text" class="form-control mb-2" placeholder=" User Name " name="nme" value="<?php echo $nme ?>">
                                <input type="text" class="form-control mb-2" placeholder=" User Email " name="mail" value="<?php echo $mail ?>">
                                <input type="text" class="form-control mb-2" placeholder=" Mobile No " name="mobi" value="<?php echo $mobi ?>">
                                <input type="text" class="form-control mb-2" placeholder=" Branch " name="brnch" value="<?php echo $brnch ?>">
                                <button class="btn btn-primary" name="updateF">Update</button>
                                <button class="btn btn-primary" style="float: right; onclick="history.go(-1);">GO BACK </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</body>
</html>