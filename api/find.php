<?php

$query = isset($_POST['q']) ? $_POST['q'] : null;
$query = isset($_POST['ext']) ? $_POST['ext'] : null;
//if ($query == null) {
//	die("Missing parameter: q");
//}

$servername = "localhost";
$username = "root";
$password = "12345678@X";
$dbname = "finder";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

/* change character set to utf8 */
if (!$conn->set_charset("utf8")) {
    die("Error loading character set utf8: ". $conn->error);
}

$sql = "SELECT f.*, s.address FROM files f LEFT JOIN sources s ON f.source_id = s.id WHERE f.path COLLATE UTF8_GENERAL_CI LIKE '%".$query."%' AND f.path LIKE '%".$ext."';";
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
    }
}

header('Content-Type: application/json;charset=utf8;');
echo json_encode($data);

$conn->close();

?>