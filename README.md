# ZarinLink API – درگاه پرداخت سریع بدون نیاز به کد مالیاتی

این API به شما امکان می‌دهد با استفاده از توکن زرین‌لینک خود، لینک پرداخت بسازید و وضعیت تراکنش را بررسی کنید.

> ⚠️ برای دریافت access token و فعال‌سازی سرویس با پشتیبانی تماس بگیرید.

---

## 🔐 احراز هویت

همه درخواست‌ها باید دارای Header زیر باشند:

Authorization: Bearer {ACCESS_TOKEN}


---

## 1️⃣ ساخت درگاه پرداخت

POST https://my.miladrajabi.com/api/create-payment


### Headers:
Content-Type: application/json
Authorization: Bearer {ACCESS_TOKEN}


### Body:
```json
{
  "amount": 15000,
  "order_id": "ORD-12345"
}
Response:
json
Copy
Edit
{
  "success": true,
  "payment_link": "https://www.zarinpal.com/pg/StartPay/A00000000000000000001",
  "authority": "A00000000000000000001"
}
2️⃣ بررسی وضعیت پرداخت (اختیاری)
nginx
Copy
Edit
POST https://my.miladrajabi.com/api/verify-payment
Body:
json
Copy
Edit
{
  "authority": "A00000000000000000001"
}
Response:
{
  "success": true,
  "status": "success",
  "amount": 15000,
  "order_id": "ORD-12345"
}
🔄 صفحات بازگشتی (Callback)
پس از پرداخت، کاربر به یکی از صفحات زیر هدایت می‌شود:

موفق: https://my.miladrajabi.com/success

ناموفق: https://my.miladrajabi.com/failure

ℹ️ نکات امنیتی
هر access_token فقط مخصوص یک حساب زرین‌پال است.

مطمئن شوید توکن شما در سمت سرور نگهداری می‌شود (نه سمت کلاینت یا فرانت‌اند).

☎️ پشتیبانی
برای دریافت دسترسی و فعال‌سازی:

تلگرام: @milad_support_bot

