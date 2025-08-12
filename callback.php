<?php

if (isset($_POST['authority']) && isset($_POST['order_id'])) {
   $accessToken = ''; 
   $bot_username = 'ZarinPayMebot';
  
   $authority = $_POST['authority'];
   $order_id = $_POST['order_id'];
  
   try {

      $data = [
         "authority" => $authority
      ];

      $jsonData = json_encode($data);

      $ch = curl_init('https://zarinpay.me/api/verify-payment');
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

      if (isset($result['success']) && $result['success'] === true && $result['data']['code'] === 100) {
         
      } else {
         throw new Exception('پرداخت انجام نشد');
      }
   } catch (Exception $e) {
      header('Content-Type: application/json; charset=utf-8');
      http_response_code(500);
      echo json_encode(array(
         'success' => false,
         'error' => $e->getMessage()
      ));

      exit;
   }

   if (isset($_POST['authority']) && isset($_POST['order_id'])) {
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(['success' => true]);
      exit;
   }

?>

   <!DOCTYPE html>
   <html lang="en">

   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>پرداخت موفق</title>


      <style>
         * {
            font-family: "vazir";
            direction: rtl;
         }

         .card {
            box-shadow: 0 15px 16.8px rgba(0, 0, 0, 0.031), 0 100px 134px rgba(0, 0, 0, 0.05);
            background-color: white;
            border-radius: 15px;
            padding: 35px;
         }

         .top {
            padding-bottom: 25px;
            min-width: 250px;
            text-align: center;
            border-bottom: dashed #dfe4f3 2px;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            border-left: 0.18em dashed #fff;
            position: relative;
         }

         .top:before {
            background-color: #fafcff;
            position: absolute;
            content: "";
            display: block;
            width: 20px;
            height: 20px;
            border-radius: 100%;
            bottom: 0;
            right: -10px;
            margin-bottom: -10px;
         }

         svg,
         h3 {
            color: #17cca9;
         }

         svg {
            margin: 0 auto;
            width: 60px;
            height: 60px;
         }

         h3 {
            margin-top: 0px;
            margin-bottom: 10px;
         }

         span {
            color: #adb3c4;
            font-size: 12px;
         }

         .bottom {
            text-align: center;
            margin-top: 30px;
         }

         .key-value {
            display: flex;
            justify-content: space-between;
         }

         .key-value span:first-child {
            font-weight: 0;
         }

         a {
            padding: 8px 20px;
            background-color: #17cca9;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 20px;
            display: block;
         }

         .outer-container {
            background-color: #fafcff;
            position: absolute;
            display: table;
            width: 100%;
            height: 100%;
            top: 0;
            right: 0;
         }

         .inner-container {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
         }

         .centered-content {
            display: inline-block;
            text-align: left;
            background: #fff;
            margin-top: 10px;
         }
      </style>

      <link href="https://cdnjs.cloudflare.com/ajax/libs/vazir-font/27.2.0/font-face.css" rel="stylesheet" type="text/css">


   </head>

   <body>
      <div class="outer-container">
         <div class="inner-container">
            <div class="card centered-content">
               <div class="top">

                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                     <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <h3>
                     پرداخـت مـوفق!
                  </h3>
                  <span>شماره تراکنش: <?php echo htmlspecialchars($result['data']['transaction']['payment_id']); ?></span>
               </div>
               <div class="bottom">
                  <div class="key-value">
                     <span>مبلغ پرداختی</span>
                     <span><?php echo number_format($result['data']['transaction']['amount'] / 10); ?> تومان</span>
                  </div>
                  <a href="http://t.me/<?php echo $bot_username ?>"> برگشت به ربات</a>
               </div>
            </div>
         </div>

      </div>
   </body>

   </html>


<?php

} else {
   header('Content-Type: application/json; charset=utf-8');
   http_response_code(404);
   exit;
}

?>
