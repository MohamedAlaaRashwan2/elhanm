
<?php
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
// الاتصال بقاعدة البيانات
include "connect.php";

// استعلام المنتجات
$stmt = $con->prepare("SELECT * FROM item ORDER BY ID DESC");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// تجهيز البيانات النهائية
$json_file = [];

foreach ($rows as $row) {
    // الصور المتعددة
    $images = $row['images'];
    $row['images'] = !empty($images) ? explode(",", $images) : [];

    // الألوان المتاحة: لو كانت مخزنة كـ "red,blue,green"
    if (!empty($row['color'])) {
        $row['availableColors'] = explode(",", $row['color']);
        $row['color'] = $row['availableColors'][0]; // أول لون افتراضي
    } else {
        $row['availableColors'] = [];
        $row['color'] = "";
    }

    $json_file[] = $row;
}

// طباعة JSON النهائي بشكل نظيف
echo json_encode($json_file, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
