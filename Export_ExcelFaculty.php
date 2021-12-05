<?php
//Connect to database
include_once('connection.php');

$total_class_days = 90;
$output = '';
$Log_date = date("Y-m-d");

if(isset($_POST["To_ExcelFaculty"])){
  
  $f_stmt = $conn->prepare("SELECT * FROM faculty_users");
  $f_stmt->execute();

       if($f_stmt->rowCount() > 0){
            $output .= '
                        <table class="table" bordered="1">  
                          <TR>
                              <th>Registration No.</th>
                              <th>Name</th>
                              <th>Branch</th>
                              <th>Email</th>
                              <th>Mobile</th>
                              <th>Atd Count</th>
                              <th>Atd Percentage</th>
                              <th>Date</th>
                              <th>Time</th>

                          </TR>';

              while($f_row = $f_stmt->fetch(PDO::FETCH_ASSOC)){

      $f_stmt3 =$conn->prepare("SELECT max(time) as F_MaxTime FROM fac_attendance_sheet WHERE fk_faculty_id = ".$f_row['fac_id']); // AND date > $start_Date
      $f_stmt3->execute();
      $f_time_data = $f_stmt3->fetch(PDO::FETCH_ASSOC);

      $f_stmt2 =$conn->prepare("SELECT max(date) as F_MaxDate FROM fac_attendance_sheet WHERE fk_faculty_id = ".$f_row['fac_id']); // AND date > $start_Date
      $f_stmt2->execute();
      $f_date_data = $f_stmt2->fetch(PDO::FETCH_ASSOC);

      $f_stmt1 =$conn->prepare("SELECT COUNT(*) as attendance_count FROM fac_attendance_sheet WHERE fk_faculty_id = ".$f_row['fac_id']); // AND date > $start_Date
      $f_stmt1->execute();
      $f_attendance_data = $f_stmt1->fetch(PDO::FETCH_ASSOC);
      // print_r($attendance_data);
      $f_atd_percentage = $f_attendance_data['attendance_count'] / $total_class_days * 100;
                  $output .= '
                              <TR> 
                                  <td>'.$f_row['national_no'].'</td>
                                  <td>'.$f_row['name'].'</td>
                                  <td>'.$f_row['branch'].'</td>
                                  <td>'.$f_row['email'].'</td>
                                  <td>'.$f_row['mobile'].'</td> 
                                  <td>'.$f_attendance_data['attendance_count'].'</td>
                                  <td>'.number_format($f_atd_percentage, 2).'</td>
                                  <td>'.$f_date_data['F_MaxDate'].'</td>
                                  <td>'.$f_time_data['F_MaxTime'].'</td>
                              </TR>';
              }
              $output .= '</table>';
              header('Content-Type: application/xls');
              header('Content-Disposition: attachment; filename=User_Log'.$Log_date.'.xls');
              
              echo $output;
              exit();
        }
        else{
            header( "location: dashboard.php" );
            exit();
        }
}
?>