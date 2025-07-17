<?php

$accessToken = "";


$data = [
   "amount" => $order['amount'], // قیمت به ریال
   "order_id" => $order_id, // شناسه تراکنش
   "customer_user_id" => $order['telegram_id'], // شناسه کاربر
   "description" => "خرید اشتراک ..."
];

$jsonData = json_encode($data);

$ch = curl_init('https://zarinpay.me/api/create-payment');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
   'Content-Type: application/json',
   'Authorization: Bearer ' . $accessToken
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
   echo "خطا در اتصال: " . curl_error($ch);
   curl_close($ch);
   exit;
}

curl_close($ch);

$result = json_decode($response, true);

if (isset($result['success']) && $result['success'] === true) {
   session_start();
   $_SESSION['authority'] = $result['authority'];
   $_SESSION['order_id'] = $order_id;
   header('Location: ' . 'https://www.zarinpal.com/pg/StartPay/' . $result['authority']);
   exit;
} else {
   echo "خطا در ایجاد درگاه پرداخت:\n";
   print_r($result);
}
