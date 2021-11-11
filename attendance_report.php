<?php
	include_once("header.php");
$student_data = "";
$option_data = "";
$stmt2 = $conn->prepare("SELECT `subject_code` FROM subjects");
    $stmt2->execute();     
  if($stmt2->rowCount() > 0)
    {
        while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC) )
        {
                                
            $option_data .= "<option value=".$row2['subject_code'].">".$row2['subject_code']."</option>";

                                
        }
    }
    
	if(isset($_GET['from_date']) & isset($_GET['to_date']) & isset($_GET['subj']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];
                                    $subj = $_GET['subj'];
                                    
                                    
            
  
    
  
	$stmt = $conn->prepare("SELECT * FROM attendance_sheet WHERE `date` BETWEEN '$from_date' AND '$to_date' AND fk_subject_code = '$subj'");
	$stmt->execute();
	$total_class_days = 90;
	$student_data = "";
	if($stmt->rowCount() > 0)
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			//$stmt1 =$conn->prepare("SELECT COUNT(*) as attendance_count FROM attendance_sheet WHERE fk_student_id = ".$row['fk_student_id']); // AND date > $start_Date
			//$stmt1->execute();
			//$attendance_data = $stmt1->fetch(PDO::FETCH_ASSOC);
			// print_r($attendance_data);
			//$atd_percentage = $attendance_data['attendance_count'] / $total_class_days;
			$student_data .= '<tr>
									<td>'.$row['fk_student_id'].'</td>
									<td>'.$row['fk_reg'].'</td>
									<td>'.$row['fk_name'].'</td>
									<td>'.$row['fk_subject_code'].'</td>
									<td>'.$row['fk_course_code'].'</td> 
									<td>'.$row['date'].'</td>
									<td>'.$row['time'].'</td> 
								</tr>';
					
		}
    }
    }
?>
    <main role="main" class="container">
    	<meta http-equiv="refresh" content="300">
		<section>
				<div class="col-xs-12 col-sm-12">
					<div class="card custom-cards">
						<div class="card-header"> Attendance Summary</div>
						<div class="card-body">
						 <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Click to Filter</label> <br>
                                      <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                  <div class="form-group">
                                    <label>Faculty</label>
                                    <select type="" name="subj" value="<?php if(isset($_GET['subj'])){ echo $_GET['subj']; } ?>" class="form-control">
                                    <?php echo $option_data; ?>
                                    </select>
                                  </div>
                                </div>
                            </div>
                         </form>
                         <form action="Export_AttendExcel.php" method="POST">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Click to Download</label> <br>
                                      <button type="submit" name="To_AttendExcel" class="btn btn-primary">Download</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                  <div class="form-group">
                                    <label>Faculty</label>
                                    <select type="" name="subj" value="<?php if(isset($_GET['subj'])){ echo $_GET['subj']; } ?>" class="form-control">
                                    <?php echo $option_data; ?>
                                    </select>
                                  </div>
                                </div>
                            </div>
                        </form>
                        

							<table class="table table-bordered table-striped">
								<thead>
									<tr>
                                     <th>Student ID</th>
                                     <th>Registration Number</th>
                                     <th>Name</th>
                                     <th>Subject Code</th>
                                     <th>Course Code</th>
                                     <th>Date</th>
                                     <th>Time</th>
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