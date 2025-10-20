<?php
// require config
require_once($_SERVER['DOCUMENT_ROOT']."/../config.php");

// require mailer function
require_once(ROOT.INC_CONFIG."inc.mailer.php");

$date = date('l M dS Y  h:i A',time());

$sql = "SELECT * FROM `myfirst-prospect-trello` WHERE Freetext2 = 'Bill Plant' and notified = 0";
$res_data = mysqli_query($conn,$sql) or die($conn->error);
$no_records = $res_data->num_rows;
if($no_records >= 1) {
    $subject = 'Billplant New Enquiry';
    while($row = mysqli_fetch_array($res_data)){
        $id = $row['id'];
        $body = '
            Name: '.$row['#Name'].'
            <br>Email: '.$row['Email'].'
            <br>Phone Number: '.$row['Tel'].'
            <br>Address Line 1: '.$row['Addr1'].'
            <br>Address Line 2: '.$row['Addr2'].'
            <br>Address Line 3: '.$row['Addr3'].'
            <br>Address Line 4: '.$row['Addr4'].'
            <br>Postcode: '.$row['Pcode'].'
            <br>DOB: '.$row['Dob'].'
            <br>OGI Client Reference: '.$row['PolicyRef@'].'
        ';
        $addresses = $notificationEmails;
        $addressstring = implode(", ", $addresses);
        $attachments = array();
        mailersend($addresses,$subject,$body,$attachments);
        if($mailstatus == true) {
            echo "Report email sent with $no_records records";
            $sql = "UPDATE `myfirst-prospect-trello` SET notified = 1, `sent`='$addressstring' where id = $id";
            if (mysqlUpdate($sql) === TRUE) {}
        } else {
            $type = "Error Sending Email";
            $message = $conn->escape_string("SMTP ERROR: $errmsg");
            $sql = "UPDATE `myfirst-prospect-trello` SET notified = 2, `sent`='$addressstring', error = '$message' where id = $id";
            if (mysqlUpdate($sql) === TRUE) {}
            logEntry($type,$message);
            echo "ERROR: " . $errmsg;
        }
    }
} else {
    echo "no records to send";
}

?>