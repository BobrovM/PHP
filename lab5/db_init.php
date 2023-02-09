<?php
$db = mysqli_connect("localhost", "root");
$query = "create database if not exists z5t";
mysqli_query($db, $query);
mysqli_select_db($db, "z5t");
$query = "create table if not exists guesttable
(
tele char(25) not null primary key,
surname char(25) not null,
name char(25) not null,
fathname char(25),
address text
)";
mysqli_query($db, $query);
mysqli_close($db);
?>