<?php
define('db_server', 'localhost');
define('db_username', 'root');
define('db_password', '');
define('db_name', '');

$conn = mysqli_connect(db_server, db_username, db_password, db_name);
if ($conn == false)
    die("ERROR: could not connect to the database." . mysqli_connect_error());
?>