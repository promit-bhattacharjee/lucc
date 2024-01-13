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
<form class="d-flex" action="" method="post">
    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_query" id="search_query">
    <button class="btn btn-outline-success" type="submit">Search</button>
</form>

<?php
require_once('../../../wp-load.php');
global $wpdb;
$tableName = $wpdb->prefix . 'picnic_registration_form';

if (isset($_POST['search_query']) && !empty($_POST['search_query'])) {
    $searchInputs=trim($_POST['search_query'], $characters = " \t\n\r\0\x0B");
    $searchQuery = sanitize_text_field($searchInputs);
    $sql = $wpdb->prepare("SELECT DISTINCT * FROM $tableName WHERE student_id = %s OR mobile_number = %s OR student_name = %s", $searchQuery, $searchQuery, $searchQuery);
    $results = $wpdb->get_results($sql, ARRAY_A);

    if (count($results) > 0) {
        echo '<h2>Search Results Found:</h2>';
        echo '<ul class="list-group">';
        foreach ($results as $result) {
            echo '<li class="list-group-item">';
            echo '<a href="#'. $result['id'] .'" class="btn btn-primary">' . 'Student ID: ' . $result['student_id'] . ' | Mobile Number: ' . $result['mobile_number'] . '</a>';
            echo '</li>';
            break;
        }
        echo '</ul>';
    } else {
        echo '<p>No results found.</p>';
    }
}
?>





  <table class="table table-striped">
    <thead>
      <tr class="bg-dark text-white">
        <th scope="col">User ID</th>
        <th scope="col">Name</th>
        <th scope="col">Student ID</th>
        <th scope="col">Mobile Number</th>
        <th scope="col">Batch</th>
        <th scope="col">Section</th>
        <th scope="col">Email</th>
        <th scope="col">Payment Method</th>
        <th scope="col">Payment ID</th>
        <th scope="col">Registration Status</th>
        <th scope="col">Token Number</th>
        <th scope="col">Submited Time</th>
        <th scope="col">Updated Time</th>
        <th scope="col">Updated By</th>
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
          $userId = $row['id'];
          $studentName = $row['student_name'];
          $studentId = $row['student_id'];
          $mobileNumber = $row['mobile_number'];
          $studentBatch = $row['student_batch'];
          $studentSection = $row['student_section'];
          $studentEmail = $row['student_email'];
          $paymentMethod = $row['payment_method'];
          $paymentId = $row['payment_id'];
          $registrationStatus = $row['registration_status'];
          $tokenNumber = $row['token_number'];
          $submittedTime = $row['created_at'];
          $updatedTime = $row['updated_at'];
          $updatedBy = $row['updated_by'];

          echo "<tr id='$userId'>
              <th scope='row'>$userId</th>
              <td>$studentName</td>
              <td>$studentId</td>
              <td>$mobileNumber</td>
              <td>$studentBatch</td>
              <td>$studentSection</td>
              <td>$studentEmail</td>
              <td>$paymentMethod</td>
              <td>$paymentId</td>
              <td>$registrationStatus</td>
              <td>$tokenNumber</td>
              <td>$submittedTime</td>
              <td>$updatedTime</td>
              <td>$updatedBy</td>
              <td>
                  <button class='px-3 btn btn-success rounded-3' data-bs-toggle='modal' data-bs-target='#verifyPaymentModal$userId'>Make Verify</button>
              </td>
              <td>
                  <button class='px-3 btn btn-danger rounded-3' data-bs-toggle='modal' data-bs-target='#deleteEntryModal$userId'>Remove</button>
              </td>
          </tr?";
          echo "<hr class='text-danger p-4'>";

          // Confirm payment modal
          echo "<div class='modal fade' id='verifyPaymentModal$userId' tabindex='-1' aria-labelledby='verifyPaymentModalLabel$userId' aria-hidden='true'>
          <div class='modal-dialog'>
              <div class='modal-content'>
                  <div class='modal-header'>
                      <h5 class='modal-title' id='verifyPaymentModalLabel$userId'>Make This User Payment Verified</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                  </div>
                  <div class='modal-body'>
                      <form action='' method='post'>
                          <input type='hidden' name='currentConfirmPaymentingId' value='$userId'>
                          <button type='submit' class='btn btn-success' id='confirmPaymentBtn$userId'>Accept</button>                       
                      </form>
                      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                  </div>
              </div>
          </div>
      </div>";

          // Delete Entry Modal
          echo "<div class='modal fade' id='deleteEntryModal$userId' tabindex='-1' aria-labelledby='deleteEntryModalLabel$userId' aria-hidden='true'>
              <div class='modal-dialog'>
                  <div class='modal-content'>
                      <div class='modal-header'>
                          <h5 class='modal-title' id='deleteEntryModalLabel$userId'>Delete This User Entry</h5>
                          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                          <form action='' method='post'>
                              <input type='hidden' name='deletingEntryID' value='$userId'>
                              <button type='submit' class='btn btn-danger' value='deleteEntryConfirm'>Delete</button>
                          </form>
                          <button type='button' class='btn btn-success' data-bs-dismiss='modal'>Close</button>
                      </div>
                  </div>
              </div>
          </div>";
          echo "";
        }


          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['currentConfirmPaymentingId'])) {
           //require_once('../../../wp-load.php');
            global $wpdb, $tableName;
            $confirmPaymentingId = $_POST['currentConfirmPaymentingId'];
            $confirmPaymentSql = $wpdb->prepare("SELECT * FROM $tableName WHERE id = %s", $confirmPaymentingId);
            $confirmPaymentResult = $wpdb->get_row($confirmPaymentSql, ARRAY_A);

            $studentBatch = $row['student_batch'];
            $studentSection = $row['student_section'];
            $studentId = $row['student_id'];
            $studentEmail = $row['student_email'];

            $UpdatingApprovedBy = wp_get_current_user()->display_name;
            $tokenNumber = substr($studentBatch, 0, 2) . substr($studentSection, 0, 2) . substr($studentId, -2) . substr($studentEmail, -2) . $confirmPaymentingId;
            $data = array(
                'registration_status' => 'verified',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $UpdatingApprovedBy,
                'token_number' => $tokenNumber,
            );
            $wpdb->update($tableName, $data, array('id' => $confirmPaymentingId));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletingEntryID'])) {
            global $tableName, $wpdb;
            $deleteEntryId = $_POST['deletingEntryID'];
            $wpdb->query($wpdb->prepare("DELETE FROM $tableName WHERE id = %d", $deleteEntryId));
            unset($studentName, $studentId, $studentMobileNumber, $studentBatch, $studentSection, $studentEmail, $paymentMethod, $paymentId, $submitionTime, $approvedBy, $confirmPaymentingId);
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