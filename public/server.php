<?php
$mysqli = new mysqli("69.175.35.82",'fitemosc_dev','fn+4p]v_~,R}','fitemosc_devworkout');
// $mysqli = new mysqli("localhost",'root','1234','fitemos');
// printf("Host info: %s\n", $mysqli->host_info);
if($mysqli->connect_errno){
    echo "Failed to connect to MYSQL:".$mysqli->connect_error;
    exit();
}
// echo "success";die;
$sql = "SELECT * FROM customers limit 5";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["id"]. " - Name: " . $row["first_name"]. " " . $row["last_name"]. "<br>";
  }
} else {
  echo "0 results";
}
$mysqli->close();
die;