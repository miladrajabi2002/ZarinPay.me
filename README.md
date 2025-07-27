# درگاه پرداخت سریع بدون نیاز به کد مالیاتی

برای شروع کار با این سرویس و دریافت `ACCESS_TOKEN`، لطفاً دو اسکریپت `successful.php` و `failed.php` را در مسیر دلخواه سرور خود (مثلاً `/pay/`) قرار دهید و URLهای callback نهایی را به همراه `ACCESS_TOKEN` زرین پال خود را به تیم پشتیبانی ما ارسال کنید:

* **successful.php**: مسیر بازگشت پرداخت‌های موفق

  * مثال: `https://your-domain.com/pay/successful.php`
* **failed.php**: مسیر بازگشت پرداخت‌های ناموفق

  * مثال: `https://your-domain.com/pay/failed.php`

پس از دریافت `ACCESS_TOKEN`، برای همه درخواست‌های API، هدر زیر را ارسال نمایید:

```
Authorization: Bearer <ACCESS_TOKEN>
```

با این API می‌توانید:

1. **ایجاد لینک پرداخت** با توکن اختصاصی زرین‌لینک
2. **بررسی وضعیت تراکنش** و دریافت جزئیات پرداخت

> ⚠️ برای فعال‌سازی سرویس و دریافت توکن، پس از ارسال URLهای callback، با پشتیبانی تماس بگیرید.




## 1. معماری و مسیرها

* **Base URL**: `https://zarinpay.me/api`
* **مسیرها**:

  * `/create-payment`
  * `/verify-payment`

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

* **مسیر**: `POST /create-payment`
* **بدنه درخواست** (JSON):

  ```json
  {
    "amount": 10000,        // مبلغ به تومان
    "order_id": "654321", // شناسه سفارش در سیستم شما
    "customer_user_id": "2326546856", // شناسه کاربر
    "description": "buy acconut 654321"   // توضیحات
  }
  ```

مدت زمان فعال بودن تراکنش هم 20 دقیقه از زمان ساخت می باشد
در صورتی که وضعیت سفارشتون `PENDING` باشد اگر با یک `order_id` دوبار برای ساخت درگاه درخواست بدین همون نتیجه و لینک قبل را میگیرید و بعد از کنسل شدن یا پرداخت امکان ساخت درگاه با اون `order_id` نمیباشد
  
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

* **مسیر**: `POST /verify-payment`
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

