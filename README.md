# 🧠 Q&A Quiz API (Laravel + MySQL + JWT)

A backend API for a multiple-choice quiz application built with **Laravel**, using **JWT authentication**, **role-based access control**, and **enum-driven role management**.

---

## 🚀 Features

- User authentication using **JWT**
- Role-based access (`admin` and `user`)
- Admins can:
  - Create, update, and delete quizzes
  - Add questions and options
  - View correct answers
  - Change roles of other users (but not their own)
- Users can:
  - Take quizzes
  - Submit answers
  - View results (without seeing correct answers)
- Clean **API Resource structure** for JSON responses

---

## ⚙️ Tech Stack

- **Backend:** Laravel 12+
- **Database:** MySQL
- **Auth:** JWT (via `tymon/jwt-auth`)
- **Language:** PHP 8.1+ (Enums supported)

---

## 📦 Installation

```bash
git clone https://github.com/your-username/qna-api.git
cd qna-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed


