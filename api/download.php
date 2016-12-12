<?php

$file_id = isset($_POST['f']) ? $_POST['f'] : null;
if ($file_id == null) {
	die("Missing parameter: f");
}

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

$sql = "SELECT * FROM files f WHERE f.id = " . $file_id . ";";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    // output data of each row
    $row = $result->fetch_assoc();
  	$remote_path = $row["path"];
}

// Copy file from remote host via FTP
$script = "/home/pi/Downloads/winexe-winexe-waf/source/build/winexe -U giang.dinh%gianQ@2001 //192.168.1.108 \"cmd /C cd C:\Users\giang.dinh\Downloads &echo pi>ftp1.txt&echo raspberry>>ftp1.txt&echo bin>>ftp1.txt&echo cd /tmp>>ftp1.txt&echo put files.txt>>ftp1.txt&echo bye>>ftp1.txt& ftp -s:ftp1.txt 192.168.1.3\"";
$output = shell_exec($script);
echo "<pre>$output</pre>";
// Send file to browser
$file = "/tmp/" . $file_id;

if (file_exists($file)) {
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename='.basename($file_id));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($file));
  ob_clean();
  flush();
  readfile($file);
  exit;
}

$conn->close();

?>
