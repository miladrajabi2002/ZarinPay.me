<h1 align="center">💳 زرین‌پی (ZarinPay)</h1>
<p align="center">پرداخت آسان، سریع و ایمن بدون نیاز به اینماد 🚀</p>

<p align="center">
زرین‌پی یک API ساده برای ساخت و تأیید تراکنش‌های آنلاین است که به شما اجازه می‌دهد در سایت، ربات تلگرام یا اپلیکیشن خود <b>پرداخت آنلاین</b> داشته باشید.
</p>

---

## 🚀 شروع سریع

برای استفاده از API نیاز به یک **توکن دسترسی (Access Token)** دارید.  
برای گرفتن توکن به آیدی @miladrajabi2002 در تلگرام پیام بدین.

این توکن باید در هدر هر درخواست به صورت زیر ارسال شود:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
```

---

## 🛒 ایجاد تراکنش (Create Payment)

**Endpoint:**

```
POST https://zarinpay.me/api/create-payment
```

### 📋 پارامترها

| نام | نوع | اجباری | توضیحات |
|-----|-----|---------|---------|
| `amount` | int | ✅ | مبلغ تراکنش (به **ریال**) |
| `order_id` | string/int | ✅ | شناسه سفارش شما (یکتا) |
| `callback_url` | string | ✅ | آدرس بازگشت بعد از پرداخت |
| `type` | string | ✅ | نوع پرداخت (فعلاً فقط `card`) |
| `customer_user_id` | string/int | ❌ | شناسه کاربر (مثلاً آیدی تلگرام) |
| `description` | string | ❌ | توضیحات سفارش |
| `store_id` | int | ❌ | شناسه فروشگاه (اگر خالی باشد قدیمی‌ترین فروشگاه انتخاب می‌شود) |

### ✅ نمونه پاسخ موفق

```json
{
  "success": true,
  "payment_link": "https://zarinpay.me/pay/A0000000000000000000000grjfza5o6",
  "authority": "A0000000000000000000grjfza5o6"
}
```

### ❌ نمونه پاسخ خطا

```json
{
  "success": false,
  "message": "Amount must be more than 1000 IR."
}
```

---

## 🔍 تأیید تراکنش (Verify Payment)

**Endpoint:**

```
POST https://zarinpay.me/api/verify-payment
```

### 📋 پارامترها

| نام | نوع | اجباری | توضیحات |
|-----|-----|---------|---------|
| `authority` | string | ✅ | کدی که از مرحله ایجاد تراکنش دریافت کرده‌اید |

### ✅ نمونه پاسخ موفق

```json
{
  "success": true,
  "data": {
    "code": 100,
    "transaction": {
      "payment_id": 123,
      "amount": 50000,
      "order_id": "ORD123",
      "authority": "A0000000000grjfza5o6"
    }
  }
}
```

#### 📌 کدهای بازگشتی

| کد | توضیح |
|----|-------|
| `100` | پرداخت موفق (برای اولین بار تایید شده) |
| `101` | پرداخت قبلاً تایید شده |
| `-1` | پرداخت ناموفق |
| `-53` | دسترسی غیرمجاز به تراکنش |
| `-54` | تراکنش یافت نشد |
| `-55` | تراکنش هنوز منقضی نشده است |

---

## ⏳ اعتبار تراکنش‌ها

- مدت اعتبار: **۳۰ دقیقه**
- اگر `order_id` تکراری باشد:
  - اگر تراکنش **PENDING** باشد همان لینک قبلی بازگردانده می‌شود
  - اگر پرداخت یا لغو شده باشد امکان ایجاد مجدد ندارد

---

## 🔄 اطلاعات ارسالی به `callback_url`

بعد از پرداخت موفق از طریق **POST**:

```json
{
  "authority": "A0000000000grjfza5o6",
  "order_id": "ORD123"
}
```

---

## 💻 نمونه کدها

### 🐘 PHP

```php
<?php
$accessToken = "YOUR_ACCESS_TOKEN";

$data = [
   "amount" => 50000,
   "order_id" => "ORD123",
   "callback_url" => "https://yoursite.com/callback",
   "type" => "card",
   "description" => "خرید اشتراک",
   "customer_user_id" => 123456789
];

$jsonData = json_encode($data);

$ch = curl_init("https://zarinpay.me/api/create-payment");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
   "Content-Type: application/json",
   "Authorization: Bearer " . $accessToken
]);

$response = curl_exec($ch);
echo $response;
?>
```

### 🐍 Python

```python
import requests

url = "https://zarinpay.me/api/create-payment"
headers = {
    "Content-Type": "application/json",
    "Authorization": "Bearer YOUR_ACCESS_TOKEN"
}
data = {
    "amount": 50000,
    "order_id": "ORD123",
    "callback_url": "https://yoursite.com/callback",
    "type": "card",
    "description": "خرید اشتراک"
}

response = requests.post(url, json=data, headers=headers)
print(response.json())
```

### 🟢 Node.js

```javascript
const axios = require("axios");

const data = {
  amount: 50000,
  order_id: "ORD123",
  callback_url: "https://yoursite.com/callback",
  type: "card",
  description: "خرید اشتراک"
};

axios.post("https://zarinpay.me/api/create-payment", data, {
  headers: {
    "Content-Type": "application/json",
    "Authorization": "Bearer YOUR_ACCESS_TOKEN"
  }
})
.then(res => console.log(res.data))
.catch(err => console.error(err.response.data));
```

### 🌐 JavaScript (Fetch)

```javascript
fetch("https://zarinpay.me/api/create-payment", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
    "Authorization": "Bearer YOUR_ACCESS_TOKEN"
  },
  body: JSON.stringify({
    amount: 50000,
    order_id: "ORD123",
    callback_url: "https://yoursite.com/callback",
    type: "card"
  })
})
.then(res => res.json())
.then(data => console.log(data));
```

### 💻 cURL

```bash
curl -X POST https://zarinpay.me/api/create-payment \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "amount": 50000,
    "order_id": "ORD123",
    "callback_url": "https://yoursite.com/callback",
    "type": "card"
  }'
```

---

## ⚠️ کدهای خطا و HTTP Status

| پیام | کد وضعیت |
|------|-----------|
| Amount must be more than X IR | `400 Bad Request` |
| Your balance is not sufficient | `402 Payment Required` |
| Store not found | `404 Not Found` |
| Previous transaction expired | `410 Gone` |
| Order ID already exists | `409 Conflict` |
| No active card available | `503 Service Unavailable` |
| Invalid payment type | `400 Bad Request` |
| Internal server error | `500 Internal Server Error` |
| Transaction not found | `404 Not Found` |
| Unauthorized access to transaction | `403 Forbidden` |

---

## 📌 نکات مهم

✔ مبلغ‌ها باید به **ریال** ارسال شوند  
✔ فعلاً فقط روش پرداخت `card` فعال است  
✔ تمام درخواست‌ها باید با **توکن معتبر** ارسال شوند  
✔ محدودیت ارسال درخواست (Rate Limit) اعمال شده است

---

## ❓ سوالات متداول

**آیا برای استفاده نیاز به اینماد دارم؟** ❌ خیر  
**تراکنش‌ها چند دقیقه معتبرند؟** ⏳ ۳۰ دقیقه  
**چه زمانی نیاز به `store_id` دارم؟** اگر چند فروشگاه دارید

---

## 🔗 لینک‌ها

🌐 [وبسایت زرین‌پی](https://zarinpay.me)  
💻 [ریپو گیت‌هاب](https://github.com/miladrajabi2002/ZarinPay.me)
