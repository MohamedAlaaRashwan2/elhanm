<?php
// السماح بالطلبات من أي دومين (قد تحتاج لتحديدها بالأمان في المشاريع الحقيقية)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// الاتصال بقاعدة البيانات
include "connect.php";

// التحقق من نجاح الاتصال بقاعدة البيانات
if (!$con) {
    die(json_encode(["error" => "فشل الاتصال بقاعدة البيانات: " . $con->errorInfo()[2]]));
}

// التحقق من وجود كود الخصم في الطلب
if (!isset($_GET['code'])) {
    echo json_encode(["error" => "لم يتم تقديم كود الخصم"]);
    exit;
}

$couponCode = $_GET['code'];

var_dump($couponCode);

// البحث عن الكود في قاعدة البيانات
$stam = $con->prepare("SELECT * FROM coupons WHERE TRIM(code) = ?");
$stam->execute([trim($couponCode)]);
$coupon = $stam->fetch(PDO::FETCH_ASSOC);

if ($coupon) {
    echo json_encode([
        "valid" => true,
        "discount" => (float) $coupon["discount"] // تحويل إلى رقم
    ]);
} else {
    echo json_encode(["valid" => false]);
}

?>
