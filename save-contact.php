<?php

include 'db.php';

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$company = $_POST['company'];
$interests = $_POST['interests'];
$message = $_POST['message'];
$source = $_POST['source'];

$sql = "INSERT INTO contacts
(first_name,last_name,email,phone,company,interests,message,source)
VALUES
('$fname','$lname','$email','$phone','$company','$interests','$message','$source')";

if($conn->query($sql) === TRUE){
    echo "success";
}else{
    echo "error";
}

?>