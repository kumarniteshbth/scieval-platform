# 🔬 SciEval — Scientific Temper & Science Literacy Evaluation Platform

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel" alt="Laravel 12"/>
  <img src="https://img.shields.io/badge/PHP-8.3-blue?style=for-the-badge&logo=php" alt="PHP 8.3"/>
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38bdf8?style=for-the-badge&logo=tailwindcss" alt="TailwindCSS"/>
  <img src="https://img.shields.io/badge/MySQL-Production-orange?style=for-the-badge&logo=mysql" alt="MySQL"/>
</p>

> **B.Tech Major Project** — A full-stack Laravel 12 MVC web application to evaluate Scientific Temper & Science Literacy through adaptive quizzes, analytics, leaderboard and AI-powered recommendations.

---

## 🚀 Live Demo

> Deployed on Railway.app — [Coming Soon]

---

## ✨ Features

- 🎯 **Adaptive Quiz Engine** — Timed quizzes with instant feedback and scoring
- 📊 **Analytics Dashboard** — Sub-score breakdown (Scientific Temper vs Science Literacy)
- 🏆 **Leaderboard** — Ranked results among all participants
- 👨‍💼 **Admin Panel** — Full CRUD for quizzes, questions, categories, users, and results
- 🔐 **Role-Based Auth** — Admin and User roles with middleware protection
- 📄 **PDF Export** — Download result certificates via DomPDF
- 📱 **Responsive UI** — TailwindCSS + Alpine.js for smooth UX

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.3) |
| Frontend | Blade Templates + TailwindCSS 3 + Alpine.js |
| Build Tool | Vite |
| Database | MySQL (production), SQLite (local dev) |
| Auth | Laravel Breeze |
| PDF | barryvdh/laravel-dompdf |
| Hosting | Railway.app |

---

## ⚙️ Local Setup

```bash
# 1. Clone the repository
git clone https://github.com/kumarniteshbth/scieval-platform.git
cd scieval-platform

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Run migrations + seed data
php artisan migrate --seed

# 6. Install Node dependencies & build assets
npm install
npm run build

# 7. Start the development server
php artisan serve
```

Visit `http://localhost:8000`

**Default Admin Credentials:**
- Email: `admin@scieval.com`
- Password: `password`

---

## 📦 Database Schema

- `users` — Auth + role (admin/user)
- `categories` — Quiz categories (e.g., Physics, Biology)
- `quizzes` — Quiz metadata + time limit
- `questions` — Questions linked to quizzes
- `options` — Answer choices per question
- `quiz_attempts` — User quiz session tracking
- `attempt_answers` — Per-question answers per attempt
- `results` — Final scores + sub-scores

---

## 🚢 Deployment (Railway.app)

See the deployment guide in the project wiki or run:

```bash
# Set environment variables on Railway, then Railway auto-deploys from GitHub
# Required: APP_KEY, DB_CONNECTION=mysql, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

---

## 👨‍💻 Developer

**Nitesh Kumar**
B.Tech Student — [GitHub](https://github.com/kumarniteshbth)

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).
