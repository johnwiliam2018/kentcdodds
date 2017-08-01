<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require ('library/admin_application_top.php');
$con=mysqli_connect("localhost","root","","upload");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$image_id = $_POST['id'];

$sql = "UPDATE " . TABLE_IMAGES . " SET `isupload` = 0 WHERE `id` = " . $image_id;

if (!mysqli_query($con,$sql))
{
    die('Error: ' . mysqli_error($con));
}

mysqli_close($con);

?>