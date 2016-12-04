<?php

$query = isset($_POST['q']) ? $_POST['q'] : null;
$data = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);

header('Content-Type: application/json');
echo json_encode($data);

?>