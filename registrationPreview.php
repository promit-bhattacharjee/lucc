<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>registration_status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

  <table class="table table-striped">
    <thead>
      <tr class="bg-dark text-white">
        <th scope="col">User ID</th>
        <th scope="col">Name</th>
        <th scope="col">Student ID</th>
        <th scope="col">Batch</th>
        <th scope="col">Section</th>
        <th scope="col">Email</th>
        <th scope="col">Payment Method</th>
        <th scope="col">Payment ID</th>
        <th scope="col">Registration Status</th>
        <th scope="col">Submited Time</th>
        <th scope="col">Payment Approve </th>
        <th scope="col">Delete Entry</th>
      </tr>
    </thead>
    <tbody>
      <?php

      //require_once('../../../wp-load.php');
      global $wpdb;
      date_default_timezone_set('Asia/Dhaka');
      $tableName = $wpdb->prefix . 'picnic_registration_form';
      $sql = "SELECT * FROM $tableName";
      $results = $wpdb->get_results($sql, ARRAY_A);

      if ($results) {
        foreach ($results as $row) {
          $user_id = $row['id'];
          $name = $row['name'];
          $student_id = $row['student_id'];
          $batch = $row['batch'];
          $section = $row['s_section'];
          $email = $row['email'];
          $payment_method = $row['payment_method'];
          $payment_id = $row['payment_id'];
          $registration_status = $row['registration_status'];
          $submitted_time = $row['created_at'];

          echo "<tr>
              <th scope='row'>$user_id</th>
              <td>$name</td>
              <td>$student_id</td>
              <td>$batch</td>
              <td>$section</td>
              <td>$email</td>
              <td>$payment_method</td>
              <td>$payment_id</td>
              <td>$registration_status</td>
              <td>$submitted_time</td>
              <td>
                  <button class='px-3 btn btn-success rounded-3' data-bs-toggle='modal' data-bs-target='#verifyPaymentModal$user_id'>Make Verify</button>
              </td>
              <td>
                  <button class='px-3 btn btn-danger rounded-3' data-bs-toggle='modal' data-bs-target='#deleteEntryModal$user_id'>Remove</button>
              </td>
          </tr>";

          // Confirm payment modal
          echo "<div class='modal fade' id='verifyPaymentModal$user_id' tabindex='-1' aria-labelledby='verifyPaymentModalLabel$user_id' aria-hidden='true'>
          <div class='modal-dialog'>
              <div class='modal-content'>
                  <div class='modal-header'>
                      <h5 class='modal-title' id='verifyPaymentModalLabel$user_id'>Make This User Payment Verified</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                  </div>
                  <div class='modal-body'>
                      <form action='' method='post'>
                          <input type='hidden' name='currentConfirmPaymentingId' value='$user_id'>
                          <button type='submit' class='btn btn-success' id='confirmPaymentBtn$user_id'>Accept</button>                       
                      </form>
                      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                  </div>
              </div>
          </div>
      </div>";

          // Delete Entry Modal
          echo "<div class='modal fade' id='deleteEntryModal$user_id' tabindex='-1' aria-labelledby='deleteEntryModalLabel$user_id' aria-hidden='true'>
              <div class='modal-dialog'>
                  <div class='modal-content'>
                      <div class='modal-header'>
                          <h5 class='modal-title' id='deleteEntryModalLabel$user_id'>Delete This User Entry</h5>
                          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                          <form action='' method='post'>
                              <input type='hidden' name='deletingEntryID' value='$user_id'>
                              <button type='submit' class='btn btn-danger' value='deleteEntryConfirm'>Delete</button>
                          </form>
                          <button type='button' class='btn btn-success' data-bs-dismiss='modal'>Close</button>
                      </div>
                  </div>
              </div>
          </div>";
        }
      }
      function verifyPaymentcreatingSql()
      {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['currentConfirmPaymentingId'])) {
          global $tableName, $wpdb, $paidTableName;
          $paidTableName = $wpdb->prefix . 'paid_' . $tableName . '_registration_form';
          $charset_collate = $wpdb->get_charset_collate();
          $confirmPaymentSql = "CREATE TABLE IF NOT EXISTS $paidTableName (
          id INT NOT NULL AUTO_INCREMENT,
          name VARCHAR(250) NOT NULL,
          student_id VARCHAR(20) NOT NULL UNIQUE,
          mobile_number VARCHAR(20) NOT NULL UNIQUE,
          batch VARCHAR(10) NOT NULL,
          s_section VARCHAR(20) NOT NULL,
          email VARCHAR(20) NOT NULL UNIQUE,
          payment_method VARCHAR(20) NOT NULL,  
          payment_id VARCHAR(20) NOT NULL,
          registration_status VARCHAR(20) NOT NULL,
          submition_time DATETIME NOT NULL,
          approved_time DATETIME NOT NULL,
          approved_by VARCHAR(20) NOT NULL,
          token_number VARCHAR(255) NOT NULL UNIQUE,
          PRIMARY KEY (id)
      ) $charset_collate;";

          //require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          dbDelta($confirmPaymentSql);


        }
      }
      
      function update($id)
      {
          global $tableName, $wpdb;
          $currentConfirmPaymentingId = $id;
          $confirmPaymentSql = $wpdb->prepare("SELECT * FROM $tableName WHERE id = %d", $currentConfirmPaymentingId);
          $confirmPaymentResult = $wpdb->get_row($confirmPaymentSql, ARRAY_A);
      
          
                  $data = array(
                      'registration_status' => 'verified',
                      'approved_time' => date('Y-m-d H:i:s'),
                      'approved_by' => wp_get_current_user()->display_name
                  );
      
                  $where = array('id' => $currentConfirmPaymentingId);
      
                  $wpdb->update($tableName, $data, $where);
                        $reload_url = esc_url($_SERVER['REQUEST_URI']);
                  echo '<script type="text/javascript">window.location.href="' . $reload_url . '";</script>';
                  exit;
            
      }
      


      function verifyPaymentPush($id)
      {
        global $tableName, $wpdb;
        $currentConfirmPaymentingId = $id;
        $paidTableName = $wpdb->prefix . 'paid_' . $tableName . '_registration_form';
        $confirmPaymentSql = $wpdb->prepare("SELECT * FROM $tableName WHERE id = %d", $currentConfirmPaymentingId);
        $confirmPaymentResult = $wpdb->get_row($confirmPaymentSql, ARRAY_A);
        $studentName = $confirmPaymentResult['name'];
        $studentId = $confirmPaymentResult['student_id'];
        $studentMobileNumber = $confirmPaymentResult['mobile_number'];
        $studentBatch = $confirmPaymentResult['batch'];
        $studentSection = $confirmPaymentResult['s_section'];
        $studentEmail = $confirmPaymentResult['email'];
        $paymentMethod = $confirmPaymentResult['payment_method'];
        $paymentId = $confirmPaymentResult['payment_id'];
        $submitionTime = $confirmPaymentResult['created_at'];
        $approvedBy = wp_get_current_user()->display_name;
        // if (empty($studentName) || empty($studentId) || empty($studentMobileNumber) || empty($studentBatch) || empty($studentSection) || empty($studentEmail) || empty($paymentMethod) || empty($paymentId)) {
        //     echo $studentName.' fields are required.';
        //     exit;
        // }
        // if (
        //   (function ($studentId) {
        //     global $paidTableName, $wpdb;
        //     $duplicate_finder = $wpdb->prepare("SELECT COUNT(*) FROM $paidTableName WHERE student_id = %s", $studentId);
        //     return $wpdb->get_var($duplicate_finder);
        //   })($studentId) > 0 ||
        //   (function ($mobileNumber) {
        //     global $paidTableName, $wpdb;
        //     $duplicate_finder = $wpdb->prepare("SELECT COUNT(*) FROM $paidTableName WHERE mobile_number = %s", $mobileNumber);
        //     return $wpdb->get_var($duplicate_finder);
        //   })($studentMobileNumber) > 0 ||
        //   (function ($email) {
        //     global $paidTableName, $wpdb;
        //     $duplicate_finder = $wpdb->prepare("SELECT COUNT(*) FROM $paidTableName WHERE email = %s", $email);
        //     return $wpdb->get_var($duplicate_finder);
        //   })($studentEmail) > 0
        // ) {
        //   echo "Duplicate Data";
        //   unset($studentName, $studentId, $studentMobileNumber, $studentBatch, $studentSection, $studentEmail, $paymentMethod, $paymentId, $submitionTime, $approvedBy, $currentConfirmPaymentingId);
        // }
      


      $tokenNumber=substr($studentBatch, 0, 2).substr($studentSection, 0, 2).substr($studentId, -2).substr($studentMobileNumber, -2).$currentConfirmPaymentingId;
        $data = array(
          'name' => $studentName,
          'student_id' => $studentId,
          'mobile_number' => $studentMobileNumber,
          'batch' => $studentBatch,
          's_section' => $studentSection,
          'email' => $studentEmail,
          'payment_method' => $paymentMethod,
          'payment_id' => $paymentId,
          'registration_status' => 'verified',
          'submition_time' => $submitionTime,
          'approved_time' => date('Y-m-d H:i:s'),
          'approved_by' => $approvedBy,
          'token_number' => $tokenNumber

        );

        $formats = array(
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s'
        );
        $result = $wpdb->insert($paidTableName, $data, $formats);
        if ($result === false) {
          $wpdb_error = $wpdb->last_error;
          $wpdb->show_errors(); 
          echo 'Error inserting data: ' . $wpdb_error;
          $wpdb->hide_errors();
        } else {
          unset($studentName, $studentId, $studentMobileNumber, $studentBatch, $studentSection, $studentEmail, $paymentMethod, $paymentId, $submitionTime, $approvedBy, $currentConfirmPaymentingId);
          $reload_url = esc_url($_SERVER['REQUEST_URI']);
          echo '<script type="text/javascript">window.location.href="' . $reload_url . '";</script>';
          exit;
        }
      }
      function deleteData($id)
      {
        global $tableName, $wpdb, $paidTableName;
        $where = array('id' => $id);
        $wpdb->delete($tableName, $where);
        unset($studentName, $studentId, $studentMobileNumber, $studentBatch, $studentSection, $studentEmail, $paymentMethod, $paymentId, $submitionTime, $approvedBy, $currentConfirmPaymentingId);
      }
      function updateData()
      {
        echo "got";
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['currentConfirmPaymentingId'])) {
          verifyPaymentcreatingSql();
          update($_POST['currentConfirmPaymentingId']);
          //verifyPaymentPush($_POST['currentConfirmPaymentingId']);
          echo $_POST['currentConfirmPaymentingId'];
          deleteData($_POST['currentConfirmPaymentingId']);
        }
      }
      function removeData()
      {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletingEntryID'])) {
          deleteData($_POST['deletingEntryID']);
        }
      }

      ?>
    </tbody>
  </table>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
</body>

</html>