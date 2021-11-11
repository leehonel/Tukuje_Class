<?php
//Connect to database
include_once('connection.php');

$total_class_days = 90;
$output = '';

if(isset($_POST["To_Excel"])){

  $stmt = $conn->prepare("SELECT * FROM students");
  $stmt->execute(); 

        if($stmt->rowCount() > 0){ 
                    $output .= '
                        <table class="table" bordered="1">  
                          <TR>
                    <th>Registration No.</th>
                    <th>Name</th>
                    <th>Branch</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Subject</th>
                    <th>Atd Count</th>
                    <th>Atd Percentage</th>
                    <th>Date</th>
                    <th>Time</th>
                             
                          </TR>';
              while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

      $stmt4 =$conn->prepare("SELECT fk_subject_code as subject_code FROM attendance_sheet WHERE fk_student_id = ".$row['stu_id']); // AND date > $start_Date
      $stmt4->execute();
      $subject_data = $stmt4->fetch(PDO::FETCH_ASSOC);

      $stmt3 =$conn->prepare("SELECT max(time) as MaxTime FROM attendance_sheet WHERE fk_student_id = ".$row['stu_id']); // AND date > $start_Date
      $stmt3->execute();
      $time_data = $stmt3->fetch(PDO::FETCH_ASSOC);
      

      $stmt2 =$conn->prepare("SELECT max(date) as MaxDate FROM attendance_sheet WHERE fk_student_id = ".$row['stu_id']); // AND date > $start_Date
      $stmt2->execute();
      $date_data = $stmt2->fetch(PDO::FETCH_ASSOC);

      $stmt1 =$conn->prepare("SELECT COUNT(*) as attendance_count FROM attendance_sheet WHERE fk_student_id = ".$row['stu_id']); // AND date > $start_Date
      $stmt1->execute();
      $attendance_data = $stmt1->fetch(PDO::FETCH_ASSOC);
      // print_r($attendance_data);
      $atd_percentage = $attendance_data['attendance_count'] / $total_class_days * 100;
                  $output .= '
                              <TR> 
                  <td>'.$row['reg_no'].'</td>
                  <td>'.$row['name'].'</td>
                  <td>'.$row['branch'].'</td>
                  <td>'.$row['email'].'</td>
                  <td>'.$row['mobile'].'</td>
                   <td>'.$subject_data['subject_code'].'</td>
                  <td>'.$attendance_data['attendance_count'].'</td>
                  <td>'.number_format($atd_percentage, 2).'</td>
                  <td>'.$date_data['MaxDate'].'</td>
                  <td>'.$time_data['MaxTime'].'</td>
                                  
                              </TR>';
              }
              $output .= '</table>';
              header('Content-Type: application/xls');
              header('Content-Disposition: attachment; filename=User_Log'.date('Y-m-d').'.xls');
              
              echo $output;
              exit();
        }
        else{
            header( "location: dashboard.php" );
            exit();
        }
}
?>