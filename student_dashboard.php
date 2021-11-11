<?php
	include_once("header.php");
    if(isset($_POST['submit']))
    { 
        if(isset($_POST['regd_no']) && !empty($_POST['regd_no'])&& isset($_POST['stu_fing_serial']) && !empty($_POST['stu_fing_serial'])  && isset($_POST['nm']) && !empty($_POST['nm']) && isset($_POST['brn']) && !empty($_POST['brn'])&& isset($_POST['mail']) && !empty($_POST['mail'])&& isset($_POST['mob']) && !empty($_POST['mob'])&& isset($_POST['coursecode']) && !empty($_POST['coursecode'])  )
        {
            // print_r($_POST);
			$regd_no = $_POST['regd_no'];
			$stu_fing_serial = $_POST['stu_fing_serial'];
            $nm = $_POST['nm'];
            $brn = $_POST['brn'];
            $mail = $_POST['mail'];
			$mob = $_POST['mob'];
			$coursecode = $_POST['coursecode'];
			
            $stmt1 = $conn->prepare("INSERT INTO students(`reg_no`, `stu_fing_serial`, `name`, `branch`, `email`,`mobile`, `created_by`, `created_on`, `fk_course_code`)
			VALUES(:regno, :stud_fing_serial, :nam, :branch, :email, :mobile, :creator, :creationtime, :coursecd)");
			
			if($stmt1->execute(array(
			"regno" => $regd_no,
			"stud_fing_serial" => $stu_fing_serial,
            "nam" => $nm,
            "branch" => $brn,
            "email" => $mail,
            "mobile" => $mob,
            "creator" => $_SESSION['uid'],
            "creationtime" => date('Y-m-d H:i:s'),
            "coursecd" => $coursecode
            )))
            {
                echo 'Success';
            }
            else
            {
                echo 'NOT Success';
            }
        }
        else
        {
            echo "All fields are mandatory";
        }
	}
	
	$stmt = $conn->prepare("SELECT * FROM students");
	$stmt->execute();
    $stmt2 = $conn->prepare("SELECT * FROM courses");
	$stmt2->execute();	

   // $subject_data = "";
	$student_data = "";
	$option_data = "";

	if($stmt2->rowCount() > 0)
	{
		while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC) )
		{
                                
            $option_data .= "<option value=".$row2['course_code'].">".$row2['course_name']."</option>";

                                
		}
	}
	if($stmt->rowCount() > 0)
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$student_data .= '<tr>
									<td>'.$row['reg_no'].'</td>
									<td>'.$row['stu_fing_serial'].'</td>
									<td>'.$row['name'].'</td>
									<td>'.$row['branch'].'</td>
									<td>'.$row['email'].'</td>
                                    <td>'.$row['mobile'].'</td> 
                                    <td>'.$row['fk_course_code'].'</td>                                   
								</tr>';
		}
	}
?>
    <main role="main" class="container">
    	<meta http-equiv="refresh" content="300">

		<section>
			<div class="row">
				<div class="col-xs-12 col-sm-3">
					<div class="card custom-cards">
						<div class="card-header">Add Student</div>
						<div class="card-body">
							<form method="POST" action="">
								<div class="form-group">
									<label>Registration No.</label>
									<input type="text" name="regd_no" class="form-control">
								</div>
								<div class="form-group">
									<label>Finger Serial</label>
									<input type="text" name="stu_fing_serial" class="form-control">
								</div>

								<div class="form-group">
									<label>Name</label>
									<input type="text" name="nm" class="form-control">
								</div>
								<div class="form-group">
									<label>School</label>
									<input type="text" name="brn" class="form-control">
								</div>
                                <div class="form-group">
									<label>Email</label>
									<input type="text" name="mail" class="form-control">
								</div>
                                <div class="form-group">
									<label>Mobile</label>
									<input type="text" name="mob" class="form-control">
								</div>
								<div class="form-group">
									<label>Course</label>
                                    <select name='coursecode'>
                                    <?php echo $option_data; ?>
                                    </select>
								</div>								
                                <div class="form-group">
									<input type="submit" name="submit" class="btn btn-success" value="Submit">
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-9">
					<div class="card custom-cards">
                    <div class="card-header">  Students List</div>
						<div class="card-body">
					        <form method="POST" action="updel.php">
  			                <button type="submit" name="updel" class="btn btn-primary">Update Details</button>
  		                    </form>
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Registration No.</th>
										<th>Finger Serial</th>
										<th>Name</th>
										<th>Branch</th>
										<th>Email</th>
                                        <th>Mobile</th>
                                        <th>Course Code</th>
									</tr>									
								</thead>
								<tbody>
									<?php echo $student_data; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>

    </main><!-- /.container -->
<?php include_once("footer.php"); ?>