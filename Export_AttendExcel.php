<?php
//Connect to database
include_once('connection.php');

$output = '';

if(isset($_POST["To_AttendExcel"])){
  
                  
                                    $from_date = $_POST['from_date'];
                                    $to_date = $_POST['to_date'];
                                    $subj = $_POST['subj'];
       

$stmt = $conn->prepare("SELECT * FROM attendance_sheet WHERE `date` BETWEEN '$from_date' AND '$to_date' AND fk_subject_code = '$subj'");
$stmt->execute();
        if($stmt->rowCount() > 0){ 
                    $output .= '
                        <table class="table" bordered="1">  
                          <TR>
                                     <th>Student ID</th>
                                     <th>Registration Number</th>
                                     <th>Name</th>
                                     <th>Subject Code</th>
                                     <th>Course Code</th>
                                     <th>Date</th>
                                     <th>Time</th>
                             
                          </TR>';
              while($row = $stmt->fetch(PDO::FETCH_ASSOC)){


                  $output .= '
                              <TR> 
                  <td>'.$row['fk_student_id'].'</td>
                  <td>'.$row['fk_reg'].'</td>
                  <td>'.$row['fk_name'].'</td>
                  <td>'.$row['fk_subject_code'].'</td>
                  <td>'.$row['fk_course_code'].'</td> 
                  <td>'.$row['date'].'</td>
                  <td>'.$row['time'].'</td> 
                                  
                              </TR>';
              }
              $output .= '</table>';
              header('Content-Type: application/xls');
              header('Content-Disposition: attachment; filename=User_Log'.date('Y-m-d').'.xls');
              
              echo $output;
              exit();
        }
        else{
            header( "location: attendance_report.php" );
            exit();
        }
}
?>