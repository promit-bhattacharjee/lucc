<?php
// require_once('../../../wp-load.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Dhaka');

global $wpdb;
$tableName = $wpdb->prefix . 'picnic_registration_form';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize user inputs
    $studentName = sanitize_user($_POST['studentName']);
    $studentId = sanitize_text_field($_POST['studentId']);
    $studentMobileNumber = sanitize_text_field($_POST['studentMobileNumber']);
    $studentBatch = sanitize_text_field($_POST['studentBatch']);
    $studentSection = sanitize_text_field($_POST['studentSection']);
    $studentEmail = sanitize_email($_POST['studentEmail']);
    $paymentMethod = sanitize_text_field($_POST['paymentMethod']);
    $paymentId = sanitize_text_field($_POST['paymentId']);

    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $tableName (
        id INT NOT NULL AUTO_INCREMENT,
        student_name VARCHAR(255) NOT NULL,
        student_id VARCHAR(100) NOT NULL,
        mobile_number VARCHAR(100) NOT NULL,
        student_batch VARCHAR(100) NOT NULL,
        student_section VARCHAR(100) NOT NULL,
        student_email VARCHAR(250) NOT NULL,
        payment_method VARCHAR(100) NOT NULL,  
        payment_id VARCHAR(100) NOT NULL,
        registration_status VARCHAR(100) NOT NULL,
        token_number VARCHAR(255) UNIQUE,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL,
        updated_by VARCHAR(100) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Run the table creation query
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Prepare data for insertion
    $data = array(
        'student_name' => $studentName,
        'student_id' => $studentId,
        'mobile_number' => $studentMobileNumber,
        'student_batch' => $studentBatch,
        'student_section' => $studentSection,
        'student_email' => $studentEmail,
        'payment_method' => $paymentMethod,
        'payment_id' => $paymentId,
        'registration_status' => 'Paid',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => 'not'
    );

    // Define data formats
    $formats = array(
        '%s', // 'student_name'
        '%s', // 'student_id'
        '%s', // 'student_mobile_number'
        '%s', // 'student_batch'
        '%s', // 'student_section'
        '%s', // 'student_email'
        '%s', // 'payment_method'
        '%s', // 'payment_id'
        '%s', // 'registration_status'
        '%s', // 'created_at'
        '%s', // 'updated_at'
        '%s'  // 'updated_by'
    );

    // Insert data into the table
    $result = $wpdb->insert($tableName, $data, $formats);

    // Check for errors during the insertion
    if ($result === false) {
        // Display an error message or log the error
        echo 'Error inserting data: ' . $wpdb->last_error;
    } else {
        // Data insertion successful
        echo 'Data inserted successfully!';
    }
}
?>



    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Form</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>

    <body>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Form</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
            <form class="m-auto" method="post" id="registrationForm" action="">
                <h1 class="text-primary text-center"><php? $eventName;?> Registration Form</h1>
                <div class="container">
                    <div class="row">
                        <div class="mb-3 col-md-10 col-lg-8 col-xl-6 col-xxl-6">
                            <label for="studentName" class="form-label">Your Full Name</label>
                            <input type="text" class="form-control" id="studentName" name="studentName"
                                aria-describedby="emailHelp">
                            <small class="form-text text-danger" id="nameError"></small>
                        </div>
                        <div class="mb-3 col-md-10 col-lg-8 col-xl-6 col-xxl-6">
                            <label for="studentId" class="form-label">Your Student ID</label>
                            <input type="number" class="form-control" id="studentId" name="studentId"
                                aria-describedby="emailHelp">
                            <small class="form-text text-danger" id="idError"></small>
                        </div>
                        <div class="mb-3 col-md-10 col-lg-8 col-xl-6 col-xxl-6">
                            <label for="studentMobileNumber" class="form-label">Your Mobile Number</label>
                            <input type="tel" class="form-control" id="studentMobileNumber" name="studentMobileNumber"
                                aria-describedby="emailHelp">
                            <small class="form-text text-danger" id="mobileError"></small>
                        </div>
                        <div class="mb-3 col-md-10 col-lg-8 col-xl-6 col-xxl-6">
                            <label for="studentBatch" class="form-label">Your Batch</label>
                            <input type="number" class="form-control" id="studentBatch" name="studentBatch"
                                aria-describedby="emailHelp">
                            <small class="form-text text-danger" id="batchError"></small>
                        </div>
                        <div class="mb-3 col-md-10 col-lg-8 col-xl-6 col-xxl-6">
                            <label for="studentSection" class="form-label">Your Section</label>
                            <input type="text" class="form-control" id="studentSection" name="studentSection"
                                aria-describedby="emailHelp">
                            <small class="form-text text-danger" id="sectionError"></small>
                        </div>
                        <div class="mb-3 col-md-10 col-lg-8 col-xl-6 col-xxl-6">
                            <label for="studentEmail" class="form-label">Email address</label>
                            <input type="text" class="form-control" id="studentEmail" name="studentEmail"
                                aria-describedby="emailHelp">
                            <small class="form-text text-danger" id="emailError"></small>
                        </div>
                        <div class="mb-3 border col-md-10 col-lg-8 col-xl-6 col-xxl-6">
                            <label class="m-auto">Select Your payment method </label>
                            <div class="container">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethod" value="online" checked>
                                    <label class="form-check-label" for="paymentMethod">
                                    Online Payment
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentMethod" value="offline">
                                    <label class="form-check-label" for="paymentMethod">
                                    Ofline Payment
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-10 col-lg-8 col-xl-6 col-xxl-6">
                            <label for="paymentId" class="form-label">Payment ID</label>
                            <input type="text" class="form-control" id="paymentId" name="paymentId"
                                aria-describedby="emailHelp">
                            <small class="form-text text-danger" id="nameError"></small>
                        </div>
                        <button type="submit" class="btn btn-primary col-11 col-sm-4 col-md-6" name="submit" value="submit">Submit Your
                            Details</button>
                    </div>
                </div>
            </form>

        <!-- <script>
            function validateForm() {
                var studentName = document.getElementById('studentName').value;
                var studentId = document.getElementById('studentId').value;
                var studentMobileNumber = document.getElementById('studentMobileNumber').value;
                var studentBatch = document.getElementById('studentBatch').value;
                var studentSection = document.getElementById('studentSection').value;
                var studentEmail = document.getElementById('studentEmail').value;

                // Validate Name
                if (studentName === '') {
                    document.getElementById('nameError').innerHTML = 'Name is required';
                    return false;
                } else {
                    document.getElementById('nameError').innerHTML = '';
                }

                // Validate Student ID
                if (studentId === '' || studentId.length !== 9) {
                    document.getElementById('idError').innerHTML = 'Please enter a valid 9-digit Student ID';
                    return false;
                } else {
                    document.getElementById('idError').innerHTML = '';
                }

                // Validate Mobile Number
                var mobileRegex = /^[0-9]{10}$/;
                if (!mobileRegex.test(studentMobileNumber)) {
                    document.getElementById('mobileError').innerHTML = 'Please enter a valid 10-digit Mobile Number';
                    return false;
                } else {
                    document.getElementById('mobileError').innerHTML = '';
                }

                // Validate Batch
                if (studentBatch === '' || isNaN(studentBatch)) {
                    document.getElementById('batchError').innerHTML = 'Please enter a valid Batch number';
                    return false;
                } else {
                    document.getElementById('batchError').innerHTML = '';
                }

                // Validate Section
                if (studentSection === '') {
                    document.getElementById('sectionError').innerHTML = 'Section is required';
                    return false;
                } else {
                    document.getElementById('sectionError').innerHTML = '';
                }

                // Validate Email
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(studentEmail)) {
                    document.getElementById('emailError').innerHTML = 'Please enter a valid email address';
                    return false;
                } else {
                    document.getElementById('emailError').innerHTML = '';
                }

                return true; // Form will be submitted if all validations pass
            }
        </script> -->

        <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js" integrity="sha384-bt49rJmkkqN5NqJ6znxpX3CEqYTJ8SFE1f7sPN7rql4R2FGgjwmKwN74DKtL4pa" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-l5KDTqHA5P70wF3UVDOjKcdgG5G8AVgvi0Tvs7sPgpbRHRvjswfeFQqDbMR5D2Pa" crossorigin="anonymous"></script>
    </body>
    </html>


        <!-- <script>
            function validateForm() {
                var studentName = document.getElementById('studentName').value;
                var studentId = document.getElementById('studentId').value;
                var studentMobileNumber = document.getElementById('studentMobileNumber').value;
                var studentBatch = document.getElementById('studentBatch').value;
                var studentSection = document.getElementById('studentSection').value;
                var studentEmail = document.getElementById('studentEmail').value;

                // Validate Name
                if (studentName === '') {
                    document.getElementById('nameError').innerHTML = 'Name is required';
                    return false;
                } else {
                    document.getElementById('nameError').innerHTML = '';
                }

                // Validate Student ID
                if (studentId === '' || studentId.length !== 9) {
                    document.getElementById('idError').innerHTML = 'Please enter a valid 9-digit Student ID';
                    return false;
                } else {
                    document.getElementById('idError').innerHTML = '';
                }

                // Validate Mobile Number
                var mobileRegex = /^[0-9]{10}$/;
                if (!mobileRegex.test(studentMobileNumber)) {
                    document.getElementById('mobileError').innerHTML = 'Please enter a valid 10-digit Mobile Number';
                    return false;
                } else {
                    document.getElementById('mobileError').innerHTML = '';
                }

                // Validate Batch
                if (studentBatch === '' || isNaN(studentBatch)) {
                    document.getElementById('batchError').innerHTML = 'Please enter a valid Batch number';
                    return false;
                } else {
                    document.getElementById('batchError').innerHTML = '';
                }

                // Validate Section
                if (studentSection === '') {
                    document.getElementById('sectionError').innerHTML = 'Section is required';
                    return false;
                } else {
                    document.getElementById('sectionError').innerHTML = '';
                }

                // Validate Email
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(studentEmail)) {
                    document.getElementById('emailError').innerHTML = 'Please enter a valid email address';
                    return false;
                } else {
                    document.getElementById('emailError').innerHTML = '';
                }

                return true; // Form will be submitted if all validations pass
            }
        </script> -->

        <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"
            integrity="sha384-bt49rJmkkqN5NqJ6znxpX3CEqYTJ8SFE1f7sPN7rql4R2FGgjwmKwN74DKtL4pa"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-l5KDTqHA5P70wF3UVDOjKcdgG5G8AVgvi0Tvs7sPgpbRHRvjswfeFQqDbMR5D2Pa"
            crossorigin="anonymous"></script>
    </body>

    </html>
    <?PHP
    // if (empty($studentName) || empty($studentId) || empty($studentMobileNumber) || empty($studentBatch) || empty($studentSection) || empty($studentEmail) || empty($paymentMethod) || empty($paymentId)) {
    //     echo 'All fields are required.';
    //     exit;
    // }
    // if (!ctype_alpha($studentName)) {
    //     echo 'Please enter a valid 9-digit Student ID.';
    //     exit;
    // }

    // if (!ctype_digit($studentId) || strlen($studentId) <20 ) {
    //     echo 'Please enter a valid 9-digit Student ID.';
    //     exit;
    // }
    
    // if (!ctype_digit($studentMobileNumber) || strlen($studentMobileNumber) > 11) {
    //     echo 'Please enter a valid 10-digit Mobile Number.';
    //     exit;
    // }
    
    // if (!ctype_digit($studentBatch)) {
    //     echo 'Please enter a valid Batch number.';
    //     exit;
    // }
    
    // if (!filter_var($studentEmail, FILTER_VALIDATE_EMAIL)) {
    //     echo 'Please enter a valid email address.';
    //     exit;
    // }
    // if (!preg_match('/^[a-zA-Z0-9]+$/', $paymentId)) {
    //     echo 'Invalid input. The field should contain only letters and digits.';
    //     exit;
    // }
    // if (!ctype_alpha($paymentMethod)) {
    //     echo 'Please enter a valid 9-digit Student ID.';
    //     exit;
    // }
    // $table_name = $wpdb->prefix . 'lucc_registration_form';

    // if (
    //     function ($studentId) {
    //         global $wpdb;
    //         global $table_name;
    //         $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE studentid = %s", $studentId);
    //         return $wpdb->get_var($query) > 0;
    //     }
    // ) {
    //     echo "Student ID $studentId already exists in the database.";
    //     exit;
    // }

    // if (
    //     function ($mobileNumber) {
    //         global $wpdb;
    //         global $table_name;
    //         $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE mobilenumber = %s", $mobileNumber);
    //         return $wpdb->get_var($query) > 0;
    //     }
    // ) {
    //     echo "Mobile Number $studentMobileNumber already exists in the database.";
    //     exit;
    // }

    // if (
    //     function ($email) {
    //         global $wpdb;
    //         global $table_name;
    //         $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE email = %s", $email);
    //         return $wpdb->get_var($query) > 0;
    //     }
    // ) {
    //     echo "Email $studentEmail already exists in the database.";
    //     exit;
    // }
    ?>