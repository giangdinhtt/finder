<?php

$file_id = isset($_GET['f']) ? $_GET['f'] : null;
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

$sql = "SELECT * FROM files WHERE id = " . $file_id . ";";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    $row = $result->fetch_assoc();
  	$remote_path = $row["path"];
    $result = $conn->query("SELECT * FROM sources WHERE id = " . $row["source_id"] . ";");

    $row = $result->fetch_assoc();
    $host = $row["address"];
    $host_user = $row["username"];
    $host_password = $row["password"];
}

//$result = $conn->query("SELECT * FROM files f WHERE f.id = " . $file_id . ";");

// Copy file from remote host via FTP
$script = "/home/pi/Downloads/winexe-winexe-waf/source/build/winexe -U ";
$script .= $host_user . "%" . $host_password . " //" . $host;
$script .= " \"cmd /C cd %temp% ";
$script .= "&echo pi>%temp%\\ftp1.txt";
$script .= "&echo raspberry>>%temp%\\ftp1.txt";
$script .= "&echo bin>>%temp%\\ftp1.txt";
$script .= "&echo cd /tmp>>%temp%\\ftp1.txt";
$script .= "&echo put \"" . str_replace('\\', '\\\\', $remote_path) . "\">>%temp%\\ftp1.txt";
$script .= "&echo bye>>%temp%\\ftp1.txt";
$script .= "& ftp -s:%temp%\\ftp1.txt 192.168.1.3\"";

echo $script;
$output = shell_exec($script);
echo $output;

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
