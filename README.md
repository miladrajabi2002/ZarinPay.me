# ZarinLink API – درگاه پرداخت سریع بدون نیاز به کد مالیاتی

این API به شما اجازه می‌دهد با استفاده از **توکن اختصاصی زرین‌لینک**، لینک پرداخت ایجاد کرده و وضعیت تراکنش را بررسی کنید.

> ⚠️ برای دریافت `ACCESS_TOKEN` و فعال‌سازی سرویس، لطفاً با پشتیبانی تماس بگیرید.

---

## 🔐 احراز هویت

تمام درخواست‌ها باید دارای هدر زیر باشند:

```
Authorization: Bearer {ACCESS_TOKEN}
```

---

## 1️⃣ ساخت لینک پرداخت

**Endpoint:**

```
POST https://zarinpay.me/api/create-payment
```

**Headers:**
```
Content-Type: application/json  
Authorization: Bearer {ACCESS_TOKEN}
```

**Body:**

```json
{
  "amount": 15000,
  "order_id": "ORD-12345"
}
```

**Response:**

```json
{
  "success": true,
  "payment_link": "https://www.zarinpal.com/pg/StartPay/A00000000000000000001",
  "authority": "A00000000000000000001"
}
```

---

## 2️⃣ بررسی وضعیت پرداخت (اختیاری)

**Endpoint:**

```
POST https://zarinpay.me/api/verify-payment
```

**Body:**

```json
{
  "authority": "A00000000000000000001"
}
```

**Response:**

```json
{
  "success": true,
  "status": "success",
  "amount": 15000,
  "order_id": "ORD-12345"
}
```

---

## 🔄 صفحات بازگشتی (Callback URLs)

پس از انجام پرداخت، کاربر به یکی از صفحات زیر هدایت می‌شود:

- موفق: [`https://my.miladrajabi.com/success`](https://zarinpay.me/success)  
- ناموفق: [`https://my.miladrajabi.com/failure`](https://zarinpay.me/failure)

---

## ℹ️ نکات امنیتی

- هر `access_token` مختص یک حساب کاربری در زرین‌پال است.  
- توکن را فقط در **سمت سرور** ذخیره کنید. از نگهداری آن در سمت کلاینت یا فرانت‌اند جداً خودداری نمایید.

---

## ☎️ پشتیبانی

برای دریافت دسترسی یا هرگونه سوال:

- تلگرام: [@miladrajabi2002](https://t.me/miladrajabi2002)
