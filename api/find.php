<?php

$query = isset($_POST['q']) ? $_POST['q'] : null;
if ($query == null) {
	die("Missing parameter: q");
}

$servername = "localhost";
$username = "root";
$password = "12345678@X";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT f.*, s.address FROM files f LEFT JOIN sources s ON f.source_id = s.id;";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$data[] = array(
    		'id' => $row["id"],
    		'path' => $row["path"],
    		'source' => $row["address"],
    		'lastModifedTime' => $row["updated_at"]
    		);
        //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
}

//$data = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();

?>