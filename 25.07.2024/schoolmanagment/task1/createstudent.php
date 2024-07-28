<!-- APIبهذا الملف نعمل الرابط تبع ال  -->

<?php

require('config.php');

// نحدد ال headers
header('Access-Control-Allow-Origin: *');      
// تحدد الاجهزة المسموحة
header('Content-Type: application/json'); 
// تحدد صيغة الملف المدعوم و بهذه الحاله JSON
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); 
// تحدد ال method 
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
// تدعم ال 3 السابقات

// تعرف مكتبة ال method
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// نفحص ال method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // نحول المعلومات التي تم ادخالها من ملف JSON الى مصفوفة
    $data = json_decode(file_get_contents('php://input'), true); // أضف true لتحويلها إلى مصفوفة

    // نتأكد أنه تم ادخال كل المعلومات
    if (isset($data['name']) && isset($data['date_of_birth']) && isset($data['address']) && isset($data['email']) && isset($data['password']) && isset($data['phone'])) {
        // نعرفهم زي ال index
        $name = $data['name'];
        $date_of_birth = $data['date_of_birth'];
        $address = $data['address'];
        $email = $data['email'];
        $phone = $data['phone'];
        $password = $data['password'];

        // تحضير الامر
        $sql = "INSERT INTO students (name, date_of_birth, address, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)";

        // تجهيز الامر
        $statement = $conn->prepare($sql);
        $statement->bind_param('ssssis', $name, $date_of_birth, $address, $email, $phone, $password);

        // سوف ننفذ التحضير ونتأكد انه تم التنفيذ بطريقة صحيحة
        if ($statement->execute()) {
            $id = $conn->insert_id;
            echo json_encode(['id' => $id, 'name' => $name, 'date_of_birth' => $date_of_birth, 'email' => $email, 'phone' => $phone, 'password' => $password]);
        } else {
            echo json_encode(['error' => 'Error: ' . $sql . ' ' . $conn->error]);
        }
    } else {
        // نحضر الخطأ
        $data = [
            // الرقم و المسج
            'status' => 405,
            'message' => 'Invalid input'
        ];
        // نكتب رابط الخطأ
        header('HTTP/1.0 405 Invalid Input');
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




