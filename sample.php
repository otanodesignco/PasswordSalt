<?php
include 'salt.php';

$salt = new Salt();


$salt->Type('MIXED');
$salt->Length(16);
echo $salt->GenerateMixed();
?>
