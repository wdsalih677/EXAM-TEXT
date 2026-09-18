# اختبارات الأحوال الشخصية

منصة تدريبية لاختبارات الأحوال الشخصية، مبنية على Laravel مع واجهة React عبر Inertia.js.

## نبذة عن المشروع

النظام يتيح للمحامي إدارة المتدربين والاختبارات، ويتيح للمتدرب أداء اختبارات مؤقتة في أربعة مجالات:

- الحضانة
- النفقة
- الطلاق
- الزيارة

الهدف هو تقييم المتدربين عبر أسئلة سيناريو اختيار من متعدد، مع احتساب النتيجة من الخادم وليس من الواجهة فقط.

### المستخدمون الأساسيون

| الدور | القيمة في قاعدة البيانات | الوصف |
| ----- | ------------------------ | ----- |
| محامٍ / Admin | `lawyer` | يدير المتدربين والاختبارات ويشاهد لوحة التحكم الإدارية |
| متدرب | `trainee` | يشاهد الاختبارات المتاحة، يبدأ المحاولة، ويجيب ضمن الوقت المحدد لكل سؤال |

التسجيل العام عبر `/register` غير مفعّل في `config/fortify.php`. حسابات الدخول التجريبية تُنشأ عبر الـ Seeders، ويمكن للمحامي إضافة متدربين من لوحة الإدارة.

---

## Tech Stack

التقنيات التالية مستخرجة من ملفات المشروع:

| الطبقة | التقنية | المصدر |
| ------ | ------- | ------ |
| Backend | PHP `^8.3` + Laravel `^13.17` | `composer.json` |
| المصادقة | Laravel Fortify | `composer.json` و `config/fortify.php` |
| الصفحات | Inertia.js v3 + React 19 + TypeScript | `composer.json` و `package.json` |
| التوجيه الأمامي | Laravel Wayfinder | `composer.json` و `vite.config.ts` |
| الأنماط | Tailwind CSS v4 | `package.json` |
| البناء | Vite 8 عبر `vite-plus` (`vp`) | `package.json` و `vite.config.ts` |
| قاعدة البيانات الافتراضية | SQLite | `.env.example` |
| قاعدة بيانات مدعومة أيضاً | MySQL / MariaDB / PostgreSQL | `config/database.php` |
| الجلسات / الكاش / الطوابير | Database | `.env.example` |
| الاختبارات | Pest 4 | `composer.json` و `tests/Pest.php` |
| البريد الافتراضي | `log` | `.env.example` |

الواجهة ليست تطبيقاً منفصلاً. React يعمل داخل Laravel عبر Inertia.js: الخادم يعيد صفحة Inertia، والمتصفح يعرض مكوّن React المقابل.

لا يوجد في المشروع:

- Google OAuth / Socialite
- `docker-compose.yml` أو ملفات Docker جاهزة للتشغيل
- `yarn.lock` أو `pnpm-lock.yaml`
- Redis كإعداد افتراضي للجلسة أو الكاش أو الطابور

حزمة `laravel/sail` موجودة في `require-dev` داخل `composer.json`، لكن المستودع لا يحتوي ملفات Compose للتشغيل عبر Sail.

---

## المتطلبات

استخرج القيم التالية من المشروع. لا تعتمد على إصدارات غير مذكورة هنا.

### البرمجيات

| الأداة | المطلوب | المصدر |
| ------ | ------- | ------ |
| PHP | `^8.3` | `composer.json` |
| Composer | 2.2 أو أحدث | `composer-runtime-api` في اعتماد Laravel |
| Node.js | `^20.19.0` أو `>=22.12.0` | محرك Vite `8.3.0` في `package-lock.json` |
| npm | يوجد `package-lock.json` (`lockfileVersion` 3) | جذر المشروع |
| Git | للحصول على نسخة المشروع | — |
| قاعدة بيانات | SQLite بشكل افتراضي، أو MySQL إذا غيّرت `.env` | `.env.example` و `config/database.php` |

`package.json` لا يحدد حقل `engines`. رقم Node أعلاه مأخوذ من اعتماد Vite المستخدم فعلياً.

إضافات PHP التي يطلبها Laravel حسب `composer.lock`:

- `ctype`
- `filter`
- `hash`
- `mbstring`
- `openssl`
- `session`
- `tokenizer`
- `pdo`
- `pdo_sqlite` عند استخدام SQLite
- `pdo_mysql` عند استخدام MySQL

### التحقق من الإصدارات

```bash
php -v
composer -V
node -v
npm -v
```

تأكد أن PHP يظهر 8.3 أو أحدث، وأن Node يطابق متطلب Vite أعلاه.

---

## Installation

### 1. الحصول على المشروع

إذا كان المشروع على Git:

```bash
git clone YOUR_REPOSITORY_URL
cd exam_test
```

استبدل `YOUR_REPOSITORY_URL` برابط المستودع الفعلي. هذا المستودع لا يحتوي رابط remote موثّقاً داخل الملفات المفحوصة.

إذا كانت الملفات منسوخة محلياً، ادخل مجلد المشروع مباشرة:

```bash
cd exam_test
```

### 2. تثبيت Backend Dependencies

```bash
composer install
```

يثبّت حزم PHP في `vendor/` حسب `composer.lock`، بما فيها Laravel و Fortify و Inertia و Wayfinder.

### 3. تثبيت Frontend Dependencies

المشروع يستخدم npm بسبب وجود `package-lock.json`:

```bash
npm install
```

يثبّت حزم Node في `node_modules/` حسب `package-lock.json`، بما فيها React و Vite و Tailwind و Inertia.

---

## Environment

### إنشاء ملف `.env`

من `.env.example`:

Linux / macOS:

```bash
cp .env.example .env
```

Windows Command Prompt:

```bat
copy .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

بديل يعمل على كل الأنظمة، وهو نفسه المستخدم في سكربت `composer run setup`:

```bash
php -r "file_exists('.env') || copy('.env.example', '.env');"
```

لا ترفع ملف `.env` إلى Git. هو موجود في `.gitignore`.

### توليد `APP_KEY`

```bash
php artisan key:generate
```

`APP_KEY` مطلوب لتشفير الجلسات والكوكيز والبيانات المحمية. إذا بقي فارغاً كما في `.env.example` لن يعمل التطبيق بشكل صحيح.

القيم الافتراضية المهمة في `.env.example`:

```env
APP_NAME="اختبارات الأحوال الشخصية"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar

DB_CONNECTION=sqlite

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log

VITE_APP_NAME="${APP_NAME}"
```

`APP_URL` يجب أن يطابق العنوان الذي تفتح منه المشروع في المتصفح. هذا مهم لروابط Fortify ولإعداد Passkeys (`relying_party_id` و `allowed_origins` في `config/fortify.php` يعتمدان على `APP_URL`).

متغيرات Redis و AWS موجودة في `.env.example` كقوالب Laravel الافتراضية. الإعداد الافتراضي للجلسة والكاش والطابور هو قاعدة البيانات، وليست Redis.

---

## Database

### الخيار الافتراضي: SQLite

`.env.example` يستخدم:

```env
DB_CONNECTION=sqlite
```

ملف SQLite غير مضمّن في Git لأن `database/.gitignore` يتجاهل `*.sqlite*`. أنشئه قبل تشغيل الـ migrations:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

هذا الأمر موجود أيضاً في `post-create-project-cmd` داخل `composer.json`.

لا تحتاج `DB_HOST` أو `DB_USERNAME` مع SQLite طالما بقيت أسطرها معلّقة كما في `.env.example`.

### خيار بديل: MySQL

`config/database.php` يدعم MySQL. إذا اخترت MySQL:

1. أنشئ قاعدة بيانات فارغة من عميل MySQL أو من أداة محلية مثل Laragon.
2. عدّل `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=exam_test
DB_USERNAME=root
DB_PASSWORD=
```

ضع اسم القاعدة واسم المستخدم وكلمة المرور حسب إعداد MySQL عندك. لا تستخدم قيماً من ملف `.env` المحلي لشخص آخر.

قيم MySQL المعلّقة في `.env.example` هي:

```env
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

### Migrations

```bash
php artisan migrate
```

ينشئ الجداول المطلوبة دون حذف البيانات الحالية. الجداول الأساسية في هذا المشروع:

| الجدول | الوظيفة |
| ------ | -------- |
| `users` | المستخدمون مع عمود `role` (`lawyer` أو `trainee`) |
| `exams` | الاختبارات المرتبطة بالمحامي عبر `lawyer_id` |
| `questions` | أسئلة السيناريو داخل الاختبار |
| `options` | خيارات الإجابة لكل سؤال |
| `attempts` | محاولات المتدرب (محاولة واحدة لكل متدرب لكل اختبار) |
| `answers` | إجابات المحاولة |
| `sessions` / `cache` / `jobs` | الجلسة والكاش والطابور لأن إعداداتها `database` |
| `passkeys` | مفاتيح المرور (Fortify Passkeys) |

ثم شغّل البيانات التجريبية:

```bash
php artisan db:seed
```

أو دفعة واحدة بعد إنشاء `.env` و`APP_KEY`:

```bash
php artisan migrate --seed
```

### الفرق بين أوامر الـ migrate

| الأمر | ماذا يفعل |
| ----- | --------- |
| `php artisan migrate` | يشغّل الـ migrations غير المنفذة فقط. لا يحذف البيانات الحالية. |
| `php artisan db:seed` | يشغّل `DatabaseSeeder` على القاعدة الحالية. |
| `php artisan migrate --seed` | يشغّل الـ migrations ثم الـ seeders. |
| `php artisan migrate:fresh --seed` | يحذف كل الجداول ثم يعيد إنشاءها ثم يشغّل الـ seeders. |

تحذير: `migrate:fresh` يحذف الجداول وجميع البيانات الحالية. لا تستخدمه على بيئة فيها بيانات تريد الاحتفاظ بها.

---

## Seeders

`database/seeders/DatabaseSeeder.php` يستدعي بالترتيب:

1. `AdminSeeder`
2. `TraineeSeeder`
3. `ExamSeeder`

العلاقات:

```text
User (lawyer)
  └── Exam
        └── Question
              └── Option

User (trainee)
  └── Attempt  (يُنشأ عند بدء الاختبار، وليس من الـ Seeder)
        └── Answer
```

`ExamSeeder` يعتمد على وجود `admin@example.com` بدور `lawyer`. لذلك يجب تشغيل `AdminSeeder` أولاً، وهو ما يفعله `DatabaseSeeder`.

### AdminSeeder

ينشئ محامياً تجريبياً:

| الحقل | القيمة |
| ----- | ------ |
| الاسم | المحامي التجريبي |
| البريد | `admin@example.com` |
| كلمة المرور | `password` |
| الدور | `lawyer` |
| التحقق من البريد | يتم تعيين `email_verified_at` |

### TraineeSeeder

ينشئ خمسة متدربين. كلمة المرور لجميعهم `password`، والدور `trainee`، مع تعيين `email_verified_at`.

| الاسم | البريد |
| ----- | ------ |
| أحمد محمد | `trainee1@example.com` |
| محمد عبدالله | `trainee2@example.com` |
| سارة أحمد | `trainee3@example.com` |
| خالد علي | `trainee4@example.com` |
| نورة محمد | `trainee5@example.com` |

### ExamSeeder

ينشئ أربعة اختبارات يملكها المحامي `admin@example.com`. مدة كل سؤال 60 ثانية.

| الاختبار | التصنيف | عدد الأسئلة |
| -------- | -------- | ----------- |
| اختبار الحضانة | `custody` | 5 |
| اختبار النفقة | `maintenance` | 5 |
| اختبار الطلاق | `divorce` | 5 |
| اختبار الزيارة | `visitation` | 5 |

كل سؤال يحتوي أربعة خيارات، واحد منها صحيح.

الـ Seeders تستخدم `firstOrCreate`، لذلك إعادة `php artisan db:seed` على قاعدة فيها هذه البيانات لا تكرر الحسابات أو الاختبارات الموجودة بنفس البريد أو العنوان.

---

## Running the project

أمامك طريقتان موثّقتان في المشروع.

### الطريقة الموصى بها أثناء التطوير

من `composer.json`:

```bash
composer run dev
```

يشغّل معاً عبر `concurrently`:

- `php artisan serve` على المنفذ الافتراضي 8000
- `php artisan queue:listen --tries=1` لأن `QUEUE_CONNECTION=database`
- `npm run dev` لخادم Vite

اترك هذه الطرفية مفتوحة.

ثم افتح:

```text
http://127.0.0.1:8000
```

أو:

```text
http://localhost:8000
```

هذا يطابق `APP_URL=http://localhost:8000` في `.env.example`.

### التشغيل اليدوي

طرفية 1 — Laravel:

```bash
php artisan serve
```

طرفية 2 — Vite:

```bash
npm run dev
```

اختياري، طرفية 3 — الطابور:

```bash
php artisan queue:listen --tries=1
```

لا توجد Jobs مخصصة داخل `app/Jobs`، لكن إعداد المشروع الافتراضي يستخدم طابور قاعدة البيانات، وسكربت `dev` يشغّل العامل.

### التشغيل عبر Laragon

إذا وُضع المشروع داخل مجلد Laragon مثل `C:\laragon\www\exam_test`، يمكن لـ Laragon تقديم الموقع على نطاق `.test` بدل `php artisan serve`.

في هذه الحالة:

1. اضبط `APP_URL` ليطابق النطاق الفعلي، مثلاً `https://exam_test.test`.
2. أبقِ `npm run dev` شغالاً أثناء التطوير، أو نفّذ `npm run build` مرة واحدة.
3. لا تفترض أن النطاق يعمل إلا إذا كان Laragon (أو أي virtual host محلي) مضبوطاً فعلاً على مجلد المشروع.

المشروع لا يحتوي إعداد Laragon داخل الملفات. النطاق يعتمد على بيئتك المحلية.

### كيف تعرف أن المشروع يعمل

- الصفحة الرئيسية `/` تعرض اسم التطبيق ونص المنصة وزر تسجيل الدخول.
- مسار الصحة `/up` معرّف في `bootstrap/app.php`.
- بعد تسجيل الدخول بحساب المحامي تصل إلى `/admin`.
- بعد تسجيل الدخول بحساب المتدرب تصل إلى `/trainee`.

إذا ظهرت الصفحة بدون تنسيق أو ظهر خطأ Vite، فخادم Vite غير شغّال أو لم يتم بناء الأصول.

---

## Vite

Vite يبني ويحدّث ملفات CSS/JS الخاصة بـ React و Inertia أثناء التطوير.

أوامر `package.json`:

| الأمر | الوظيفة |
| ----- | -------- |
| `npm run dev` | تشغيل خادم التطوير (`vp dev`) |
| `npm run build` | بناء أصول الإنتاج (`vp build`) |
| `npm run build:ssr` | بناء العميل ثم بناء SSR |
| `npm run check` | فحص الواجهة |
| `npm run types:check` | فحص TypeScript (`tsc --noEmit`) |

أثناء التطوير اترك `npm run dev` مفتوحاً. بالإضافة إلى تجميع الأصول، إضافة Wayfinder في `vite.config.ts` تولّد دوال المسارات داخل `resources/js/actions` و `resources/js/routes`.

إذا أردت العمل بدون خادم Vite، ابنِ الأصول مرة واحدة:

```bash
npm run build
```

ثم قدّم المشروع عبر `php artisan serve` أو عبر الخادم المحلي. أي تعديل لاحق في ملفات React/CSS لن يظهر حتى تعيد البناء أو تشغّل `npm run dev`.

---

## Authentication

المصادقة عبر Laravel Fortify مع صفحات Inertia في `resources/js/pages/auth`.

### تسجيل الدخول

1. افتح `/login`.
2. أدخل البريد وكلمة المرور.
3. الخادم يعيد التوجيه حسب الدور عبر `app/Http/Responses/LoginResponse.php`:

| الدور | المسار بعد الدخول |
| ----- | ----------------- |
| `lawyer` | `admin.dashboard` → `/admin` |
| أي مستخدم آخر (المتدرب) | `trainee.dashboard` → `/trainee` |

مسار `/dashboard` يعيد التوجيه أيضاً حسب الدور من `DashboardController`.

### حسابات التجربة بعد الـ Seeders

محامٍ:

```text
Email: admin@example.com
Password: password
```

متدرب:

```text
Email: trainee1@example.com
Password: password
```

يمكن استخدام `trainee2@example.com` حتى `trainee5@example.com` بنفس كلمة المرور.

### ماذا يستطيع كل دور

المحامي (`role:lawyer` + وسيط `role:lawyer`):

- لوحة `/admin`
- إدارة المتدربين: إنشاء، تعديل، حذف
- إدارة الاختبارات: إنشاء، تعديل، حذف
- الإعدادات الشخصية والأمنية

المتدرب (`role:trainee` + وسيط `role:trainee`):

- لوحة `/trainee` لعرض الاختبارات المتاحة
- بدء محاولة للاختبار
- الإجابة أو الانتقال أو انتهاء الوقت من الخادم
- عرض النتيجة بعد اكتمال المحاولة

محاولة الاختبار واحدة لكل متدرب لكل اختبار (`unique` على `trainee_id` و `exam_id`). منطق الوقت والنتيجة موجود في `app/Services/ExamAttemptService.php` و `app/Services/ExamScoringService.php`.

### ميزات Fortify المفعّلة

من `config/fortify.php`:

- إعادة تعيين كلمة المرور
- التحقق من البريد الإلكتروني
- المصادقة الثنائية (2FA)
- Passkeys

غير مفعّل: `Features::registration()`. صفحة `resources/js/pages/auth/register.tsx` موجودة من قالب البداية، لكن مسار التسجيل العام غير مفعّل. المتدربون يُنشؤون من لوحة المحامي، مع تعيين `email_verified_at` مباشرة.

البريد الافتراضي `MAIL_MAILER=log`. روابط إعادة التعيين أو التحقق تُكتب في `storage/logs` ولن تصل إلى صندوق بريد حقيقي ما لم تغيّر إعداد البريد.

صفحة تسجيل الدخول تدعم Passkeys عبر المكوّن `PasskeyVerify`. لإدارة 2FA و Passkeys بعد الدخول استخدم `/settings/security` (يتطلب مستخدماً متحققاً من بريده وتأكيد كلمة المرور).

لا يوجد Google OAuth في هذا المشروع.

---

## Laravel Cache / Config / Route Clear

```bash
php artisan optimize:clear
```

يمسح إعدادات Config و Route و View و Cache المجمّعة. استخدمه بعد تعديل `.env` أو ملفات الإعداد إذا بقيت قيم قديمة، أو عند ظهور سلوك غير متوقع بعد تغيير المسارات.

أوامر أدق عند الحاجة:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

لا تشغّل `php artisan config:cache` أو `route:cache` على بيئة التطوير إلا إذا كنت تختبر سلوك الإنتاج. بعد `config:cache` لن تُقرأ تغييرات `.env` حتى تمسح الكاش.

---

## هيكل المشروع

```text
app/                 منطق PHP للتطبيق
  Actions/Fortify/   إنشاء المستخدم وإعادة تعيين كلمة المرور
  Enums/             UserRole, QuestionCategory, AttemptStatus
  Http/Controllers/  Controllers الخاصة بالإدارة والمتدرب والإعدادات
  Http/Middleware/   Inertia، المظهر، والتحقق من الدور
  Http/Requests/     قواعد التحقق
  Http/Responses/    توجيه ما بعد تسجيل الدخول
  Models/            Eloquent models
  Policies/          ExamPolicy, AttemptPolicy
  Providers/         AppServiceProvider, FortifyServiceProvider
  Services/          منطق الاختبار والمحاولة والنتيجة
bootstrap/           إقلاع Laravel وتعريف المسارات والوسائط
config/              إعدادات الإطار والحزم
database/
  migrations/        هيكل الجداول
  seeders/           البيانات التجريبية
  factories/         بيانات الاختبارات الآلية
public/              نقطة الدخول index.php والأصول المبنية
resources/
  css/               Tailwind
  js/                تطبيق React / Inertia
  views/app.blade.php قالب Inertia الجذر (RTL للعربية)
routes/
  web.php            المسارات العامة والإدارة والمتدرب
  settings.php       الملف الشخصي والأمان
  console.php        أوامر Artisan
storage/             السجلات والكاش والملفات الخاصة
tests/               اختبارات Pest
vendor/              حزم Composer
```

أهم أجزاء التطبيق:

| المسار | الوظيفة |
| ------ | -------- |
| `app/Models` | `User`, `Exam`, `Question`, `Option`, `Attempt`, `Answer` |
| `app/Http/Controllers/Admin` | لوحة المحامي والمتدربين والاختبارات |
| `app/Http/Controllers/Trainee` | لوحة المتدرب ومحاولة الاختبار |
| `app/Http/Requests` | التحقق من نماذج الإدارة والمتدرب والإعدادات |
| `app/Services` | `ExamService`, `ExamAttemptService`, `ExamScoringService` |
| `resources/js/pages` | صفحات Inertia: `welcome`, `auth/*`, `admin/*`, `trainee/*`, `settings/*` |
| `resources/js/components/exam` | مكوّنات الاختبار والنتيجة والمؤقت |

---

## Architecture

```text
Laravel
   ↓
Routes (web.php / Fortify / settings.php)
   ↓
Middleware (auth, role:lawyer|trainee)
   ↓
Controllers
   ↓
Form Requests + Policies
   ↓
Services
   ↓
Models
   ↓
Database
```

```text
Laravel (Inertia::render)
   ↓
Inertia.js
   ↓
React pages in resources/js/pages
```

React ليس frontend منفصلاً على منفذ آخر ولا يتحدث عبر REST API خاص بالتطبيق. الطلب يمر عبر مسارات Laravel، ثم Inertia يحدّث الصفحة في المتصفح.

Wayfinder يولّد دوال TypeScript للمسارات أثناء تشغيل Vite، وتُستورد من `@/routes` و `@/actions`.

---

## Testing

المشروع يستخدم Pest 4 مع إعداد PHPUnit في `phpunit.xml`. بيئة الاختبار تستخدم SQLite في الذاكرة (`DB_DATABASE=:memory:`).

تشغيل اختبارات التطبيق:

```bash
php artisan test
```

سكربت Composer `test` أشمل، ويشغّل بالترتيب:

1. `php artisan config:clear`
2. فحص Pint
3. تحليل PHPStan (`level` 7 حسب `phpstan.neon`)
4. `php artisan test`

```bash
composer test
```

اختبارات المجال الموجودة فعلياً:

- `tests/Feature/Auth/*` المصادقة و Fortify
- `tests/Feature/Admin/ExamManagementTest.php`
- `tests/Feature/Admin/TraineeManagementTest.php`
- `tests/Feature/Trainee/AttemptTest.php`
- `tests/Feature/Trainee/ScoringTest.php`
- `tests/Feature/DashboardTest.php`
- `tests/Feature/Settings/*`

---

## Troubleshooting

### APP_KEY missing

`.env.example` يضع `APP_KEY=` فارغاً. نفّذ:

```bash
php artisan key:generate
```

### Database connection error

1. تحقق أن `.env` موجود وأن قيم `DB_*` تطابق محركك الفعلي.
2. مع SQLite: تأكد أن `database/database.sqlite` موجود وقابل للكتابة.
3. مع MySQL: تأكد أن القاعدة موجودة، وأن المستخدم وكلمة المرور والمنفذ صحيحة، وأن إضافة `pdo_mysql` مفعّلة.
4. بعد تعديل `.env` نفّذ `php artisan optimize:clear`.

### Vite manifest / Vite connection error

في التطوير شغّل:

```bash
npm run dev
```

إذا كنت لا تريد خادم Vite:

```bash
npm run build
```

ثم حدّث الصفحة. الخطأ الشائع يظهر عندما لا يوجد `public/hot` (خادم التطوير) ولا يوجد `public/build/manifest.json` (بناء الإنتاج).

### Class not found

```bash
composer dump-autoload
```

ثم تأكد أن `composer install` اكتمل بدون أخطاء.

### Migration error

1. اقرأ رسالة الخطأ كاملة من الطرفية.
2. تحقق أن اتصال قاعدة البيانات يعمل: `php artisan db:show` إن توفر، أو حاول `php artisan migrate:status`.
3. مع SQLite تأكد من وجود الملف وصلاحية الكتابة على `database/`.
4. لا تستخدم `migrate:fresh` كحل أول إذا كانت لديك بيانات.
5. إذا فشل Seeder بعد migrate، تأكد أن `AdminSeeder` نجح قبل `ExamSeeder` لأن الأخير يطلب `admin@example.com`.

### Permission / Storage

التطبيق يستخدم `FILESYSTEM_DISK=local` في `.env.example`. تدفق الاختبارات الحالي لا يعتمد على رفع ملفات عامة عبر `public/storage`. لا تشغّل `php artisan storage:link` إلا إذا احتجت تقديم ملفات من `storage/app/public`.

سجلات الأخطاء تكون في:

```text
storage/logs/laravel.log
```

تأكد أن مجلدات `storage/` و `bootstrap/cache/` قابلة للكتابة.

### 403 بعد تسجيل الدخول

وسيط `role` في `app/Http/Middleware/EnsureUserHasRole.php` يمنع المحامي من مسارات المتدرب ويمنع المتدرب من مسارات الإدارة. استخدم الحساب المناسب لكل لوحة.

### روابط البريد لا تصل

`MAIL_MAILER=log`. افتح `storage/logs/laravel.log` بدلاً من انتظار رسالة بريد حقيقية.

### Passkeys لا تعمل على localhost بعنوان مختلف

`config/fortify.php` يأخذ `APP_URL` كـ relying party. اجعل `APP_URL` مطابقاً للنطاق والمنفذ والبروتوكول الذي تستخدمه في المتصفح.

---

## أوامر التطوير المهمة

| الأمر | الاستخدام |
| ----- | --------- |
| `composer install` | تثبيت Backend dependencies |
| `npm install` | تثبيت Frontend dependencies |
| `php artisan key:generate` | توليد `APP_KEY` |
| `php artisan migrate` | تشغيل migrations |
| `php artisan db:seed` | تشغيل seeders |
| `php artisan migrate --seed` | migrations ثم seeders |
| `php artisan migrate:fresh --seed` | إعادة إنشاء الجداول من الصفر ثم seeders (يحذف البيانات) |
| `php artisan serve` | تشغيل خادم Laravel التطويري |
| `php artisan queue:listen --tries=1` | عامل الطابور المحلي |
| `php artisan optimize:clear` | تنظيف Laravel caches |
| `composer dump-autoload` | إعادة توليد Autoload |
| `composer run dev` | تشغيل السيرفر والطابور و Vite معاً |
| `composer run setup` | تثبيت الاعتماديات، نسخ `.env` إن لم يوجد، توليد المفتاح، migrate، ثم `npm install` و `npm run build` |
| `npm run dev` | تشغيل Vite |
| `npm run build` | بناء Frontend للإنتاج |
| `php artisan test` | تشغيل اختبارات Pest |
| `composer test` | Pint + PHPStan + الاختبارات |
| `composer run lint` | تنسيق PHP عبر Pint |
| `composer run types:check` | تحليل PHPStan |

ملاحظة: `composer run setup` يشغّل `php artisan migrate --force` **بدون** seeders. بعد استخدامه نفّذ `php artisan db:seed` إذا كنت تحتاج حسابات التجربة والاختبارات.

---

## التشغيل السريع

من صفر حتى فتح النظام:

```text
1. git clone YOUR_REPOSITORY_URL
2. cd exam_test
3. composer install
4. npm install
5. نسخ .env.example إلى .env
6. php artisan key:generate
7. إنشاء database/database.sqlite  (أو إنشاء قاعدة MySQL وتعديل DB_*)
8. php artisan migrate --seed
9. composer run dev
   أو: php artisan serve في طرفية، و npm run dev في طرفية أخرى
10. فتح http://127.0.0.1:8000
11. تسجيل الدخول بـ admin@example.com / password
    أو trainee1@example.com / password
```

أوامر يمكن نسخها مباشرة مع SQLite الافتراضي:

```bash
composer install
npm install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
composer run dev
```

بعد ظهور الصفحة الرئيسية:

- المحامي: `/login` ثم `/admin` لإدارة المتدربين والاختبارات.
- المتدرب: `/login` ثم `/trainee` لبدء اختبار تجريبي ومراجعة النتيجة.
