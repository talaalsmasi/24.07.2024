<?php

require('config.php');

// نحدد ال headers
header('Access-Control-Allow-Origin: *');      
// تحدد الاجهزة المسموحة
header('Content-Type: application/json'); 
// تحدد صيغة الملف المدعوم و بهذه الحاله JSON
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT'); 
// تحدد ال method 
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
// تدعم ال 3 السابقات

// تعرف مكتبة ال method
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// نفحص ال method
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // نحول المعلومات التي تم ادخالها من ملف JSON الى مصفوفة
    $data = json_decode(file_get_contents('php://input'), true); // أضف true لتحويلها إلى مصفوفة

    // نتأكد أنه تم ادخال معرف السجل والحقول المطلوبة
    if (isset($data['id']) && isset($data['name']) && isset($data['date_of_birth']) && isset($data['address']) && isset($data['email']) && isset($data['password']) && isset($data['phone'])) {
        // نعرف الحقول
        $id = $data['id'];
        $name = $data['name'];
        $date_of_birth = $data['date_of_birth'];
        $address = $data['address'];
        $email = $data['email'];
        $phone = $data['phone'];
        $password = $data['password'];

        // تحضير الامر
        $sql = "UPDATE students SET name = ?, date_of_birth = ?, address = ?, email = ?, phone = ?, password = ? WHERE id = ?";

        // تجهيز الامر
        $statement = $conn->prepare($sql);
        $statement->bind_param('ssssisi', $name, $date_of_birth, $address, $email, $phone, $password, $id);

        // سوف ننفذ التحضير ونتأكد انه تم التنفيذ بطريقة صحيحة
        if ($statement->execute()) {
            if ($statement->affected_rows > 0) {
                echo json_encode(['message' => 'Record updated successfully']);
            } else {
                echo json_encode(['error' => 'No record found with the provided ID or no changes made']);
            }
        } else {
            echo json_encode(['error' => 'Error: ' . $sql . ' ' . $conn->error]);
        }
    } else {
        // نحضر الخطأ
        $data = [
            // الرقم و المسج
            'status' => 400,
            'message' => 'Invalid input: All fields are required'
        ];
        // نكتب رابط الخطأ
        header('HTTP/1.0 400 Invalid Input');
        // نحوله الى ملف ليتم عرضه
        echo json_encode($data);
    }
} else {
    // نحضر الخطأ
    $data = [
        // الرقم و المسج
        'status' => 405,
        'message' => 'Method Not Allowed'
    ];
    // نكتب رابط الخطأ
    header('HTTP/1.0 405 Method Not Allowed');
    // نحوله الى ملف ليتم عرضه
    echo json_encode($data);
}

?>
