<?php
header("Content-Type: application/json");
include 'db.php';

$studentId = $_GET['studentId'];

$sql = "DELETE FROM student WHERE studentId = $studentId";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("message" => "Student deleted successfully"));
} else {
    echo json_encode(array("error" => $conn->error));
}

$conn->close();
