
# درگاه پرداخت سریع بدون نیاز به کد مالیاتی

این API به شما اجازه می‌دهد با استفاده از **توکن اختصاصی زرین‌لینک**، لینک پرداخت ایجاد کرده و وضعیت تراکنش را بررسی کنید.

> ⚠️ برای دریافت `ACCESS_TOKEN` و فعال‌سازی سرویس، لطفاً با پشتیبانی تماس بگیرید.

---



## 1. معماری و مسیرها

* **Base URL**: `https://zarinpay.me/api`
* **مسیرها**:

  * `/create_payment`
  * `/verify_payment`

هر دو نقطه‌ٔ ورود از روش HTTP POST استفاده می‌کنند و داده‌ها را به صورت JSON ارسال و دریافت می‌کنند.

---

## 2. احراز هویت (Authentication)

* همهٔ درخواست‌ها باید هدر زیر را داشته باشند:

  ```http
  Authorization: Bearer <access_token>
  Content-Type: application/json
  ```
* توکن دسترسی (`access_token`) توسط متد `authenticate()` تأمین می‌شود.

---

## 3. خطاها و ساختار پاسخ

تمام پاسخ‌ها شامل فیلد زیر هستند:

```json
{
  "success": <boolean>,
  "error": "<error_message>"           // در صورت شکست
}
```

* `success: true` نشان‌دهندهٔ موفقیت عملیات است.
* `success: false` در صورت بروز خطا یا مشکل داخلی.

### کدهای پاسخ سیستم پرداخت (ZarinPay)

* `100`: پرداخت تازه تأیید شده (success: true)
* `101`: پرداخت قبلاً تأیید شده (success: true)
* `-1`: پرداخت ناموفق (success: false)
* `-53`: دسترسی غیرمجاز به تراکنش (success: false)
* `-54`: تراکنش یافت نشد (success: false)

---

## 4. Endpoints

### 4.1. ایجاد درخواست پرداخت (Create Payment)

* **مسیر**: `POST /create_payment`
* **بدنه درخواست** (JSON):

  ```json
  {
    "amount": 10000,        // مبلغ به تومان
    "order_id": "654321", // شناسه سفارش در سیستم شما
    "customer_user_id": "2326546856", // شناسه کاربر
    "description": "buy acconut 654321"   // توضیحات
  }
  ```
* **پاسخ موفقیت‌آمیز**:

  ```json
  {
    "success": true,
    "payment_link": "https://www.zarinpal.com/pg/StartPay/ABCDEF123456",
    "authority": "ABCDEF123456"
  }
  ```
* **پاسخ خطا**:

  ```json
  {
    "success": false,
    "error": "Invalid amount"
  }
  ```

---

### 4.2. تأیید پرداخت (Verify Payment)

* **مسیر**: `POST /verify_payment`
* **بدنه درخواست** (JSON):

  ```json
  {
    "authority": "ABCDEF123456"
  }
  ```
* **پاسخ موفقیت‌آمیز**:
  ```json
  {
    "success": true,
    "data": {
      "code": 100,
      "transaction": {
         "payment_id": payment_id,
         "amount": amount,
         "order_id": order_id,
         "authority": authority
      }
    }
  }
  ```
* **پاسخ خطا**:

  ```json
  {
    "success": false,
    "error": "Transaction not found"
  }
  ```

---

