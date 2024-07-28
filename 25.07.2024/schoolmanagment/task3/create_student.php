<?php
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];
include 'db.php';

if ($method === 'POST') {
    if (isset($_POST['name']) && isset($_POST['dateOfBirth']) && isset($_POST['class']) && isset($_POST['address']) && isset($_POST['contactInformation'])) {
        $name = $_POST['name'];
        $class = $_POST['class'];
        $dateOfBirth = $_POST['dateOfBirth'];
        $address = $_POST['address'];
        $contactInformation = $_POST['contactInformation'];

        $sql = "INSERT INTO student (name, class, dateOfBirth, address, contactInformation) 
        VALUES ('$name', '$class', '$dateOfBirth', '$address', '$contactInformation')";

        if ($conn->query($sql) === TRUE) {
            $studentId = $conn->insert_id;
            $response = array(
                "studentId" => $studentId,
                "name" => $name,
                "class" => $class,
                "dateOfBirth" => $dateOfBirth,
                "address" => $address,
                "contactInformation" => $contactInformation
            );
            echo json_encode($response);
        } else {
            echo json_encode(array("error" => $conn->error));
        }

        $conn->close();
    }
}
