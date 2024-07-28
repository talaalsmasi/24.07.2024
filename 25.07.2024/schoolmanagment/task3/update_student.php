<?php
header("Content-Type: application/json");
include 'db.php';

$studentId = $_GET['studentId'];
$data = json_decode(file_get_contents("php://input"), true);

$fields = array();
foreach ($data as $key => $value) {
    $fields[] = "$key = '$value'";
}
$setClause = implode(", ", $fields);

$sql = "UPDATE student SET $setClause WHERE studentId = $studentId";

if ($conn->query($sql) === TRUE) {
    $sql = "SELECT * FROM student WHERE studentId = $studentId";
    $result = $conn->query($sql);
    $student = $result->fetch_assoc();
    echo json_encode($student);
} else {
    echo json_encode(array("error" => $conn->error));
}

$conn->close();
