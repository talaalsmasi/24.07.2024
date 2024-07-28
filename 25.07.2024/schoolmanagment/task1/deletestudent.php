<?php

require('config.php');

// نحدد ال headers
header('Access-Control-Allow-Origin: *');      
// تحدد الاجهزة المسموحة
header('Content-Type: application/json'); 
// تحدد صيغة الملف المدعوم و بهذه الحاله JSON
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE'); 
// تحدد ال method 
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
// تدعم ال 3 السابقات

// تعرف مكتبة ال method
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// نفحص ال method
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // نحول المعلومات التي تم ادخالها من ملف JSON الى مصفوفة
    $data = json_decode(file_get_contents('php://input'), true); // أضف true لتحويلها إلى مصفوفة

    // نتأكد أنه تم ادخال معرف السجل
    if (isset($data['id'])) {
        // نعرف المعرف
        $id = $data['id'];

        // تحضير الامر
        $sql = "DELETE FROM students WHERE id = ?";

        // تجهيز الامر
        $statement = $conn->prepare($sql);
        $statement->bind_param('i', $id);

        // سوف ننفذ التحضير ونتأكد انه تم التنفيذ بطريقة صحيحة
        if ($statement->execute()) {
            if ($statement->affected_rows > 0) {
                echo json_encode(['message' => 'Record deleted successfully']);
            } else {
                echo json_encode(['error' => 'No record found with the provided ID']);
            }
        } else {
            echo json_encode(['error' => 'Error: ' . $sql . ' ' . $conn->error]);
        }
    } else {
        // نحضر الخطأ
        $data = [
            // الرقم و المسج
            'status' => 400,
            'message' => 'Invalid input: ID is required'
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


