<?php
$servername = "web.edu";
   $username = "22110";
   $password = "apvbuq";
   $dbname = "22110_kursach";

   $conn = new mysqli($servername, $username, $password, $dbname);

   if ($conn->connect_error) {
       die("Connection failed: ". $conn->connect_error);
   }
       echo "Connected successfully";
       ?>
