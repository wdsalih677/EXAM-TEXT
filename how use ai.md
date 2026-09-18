# AI Usage Documentation

## الأدوات المستخدمة

| الأداة         | أين تم استخدامها؟                                                   |
| -------------- | ------------------------------------------------------------------- |
| **Claude AI**  | تحليل متطلبات المشروع وفهم نطاق العمل والوظائف المطلوبة.            |
| **ChatGPT**    | توليد وتحسين الـ Prompts المستخدمة في تطوير المشروع.                |
| **Cursor IDE** | تنفيذ الأكواد والتعديلات البرمجية بناءً على المتطلبات والـ Prompts. |

---
سبب استخدام Laravel مع React بدلًا من Next.js

تم اختيار Laravel مع React بدلًا من Next.js لأن المشروع يعتمد بشكل أساسي على Laravel كـ Backend Framework، مع استخدام React لبناء واجهة المستخدم.

أسباب الاختيار:

فصل واضح بين الـ Backend والـ Frontend: Laravel مسؤول عن الـ Backend والـ Business Logic وواجهات الـ API، بينما React مسؤول عن واجهة المستخدم.
الاستفادة من Laravel بشكل كامل: المشروع يحتاج إلى إمكانيات Laravel مثل Authentication، Database، Validation، Authorization وEloquent.
سهولة التكامل: استخدام React داخل بيئة Laravel يوفر تكاملًا مباشرًا مع الـ Backend دون الحاجة إلى إضافة طبقة Backend أخرى بواسطة Next.js.
تقليل التعقيد: استخدام Next.js كان سيضيف طبقة إضافية من الـ Server-side functionality، بينما احتياجات المشروع الحالية يمكن تغطيتها بشكل أبسط باستخدام Laravel + React.
سهولة التطوير والصيانة: تقسيم المسؤوليات بين Laravel وReact يجعل هيكل المشروع واضحًا ويسهل تطويره وصيانته مستقبلًا.

بناءً على طبيعة المشروع ومتطلباته، تم اعتماد Laravel + React كاختيار تقني مناسب بدلًا من Next.js.
---

## أمثلة من الـ Prompts المستخدمة

### البرومبت الأول

```text
____________________________________________________________________________
# Prompt — بناء منصة اختبارات تدريبية قانونية

## الدور والخبرة

بصفتك **Senior Laravel Engineer + Senior React/Inertia.js Developer + Software Architect**، ولديك خبرة قوية في بناء الأنظمة الإدارية، أنظمة الاختبارات والتقييم، أنظمة الصلاحيات، وإدارة الحالات الحساسة التي تتطلب أن يكون **الخادم Server هو مصدر الحقيقة الوحيد للمنطق والوقت والصلاحيات**؛ قم ببناء منصة إلكترونية متكاملة للاختبارات التدريبية القانونية باستخدام Laravel + React + Inertia.js.

أريد منك التعامل مع المشروع كمنتج حقيقي قابل للاستخدام، وليس مجرد Prototype.

يجب أن تلتزم بمبادئ:

* Clean Code
* SOLID
* Separation of Concerns
* Laravel Best Practices
* React Best Practices
* Inertia.js Best Practices
* Form Requests للـ Validation
* Policies / Authorization
* Database Transactions عند الحاجة
* Server-side validation
* Server-side enforcement لجميع قواعد الاختبار
* عدم الاعتماد على React وحده في أي قاعدة أمنية أو منطقية مهمة
* كتابة كود واضح وقابل للصيانة
* عدم إنشاء API منفصل طالما أن المشروع يستخدم Inertia.js
* عدم إدخال تعقيد معماري غير ضروري

---

# 1. فكرة المشروع

المنصة عبارة عن نظام اختبارات تدريبية قانونية مخصص لتقييم المتدربين في أربعة مجالات من الأحوال الشخصية:

1. الحضانة
2. النفقة
3. الطلاق
4. الزيارة

يوجد نوعان من المستخدمين:

* Lawyer / Admin
* Trainee

المحامي يستطيع إدارة المتدربين وإنشاء الاختبارات والأسئلة.

المتدرب يستطيع الدخول إلى النظام، رؤية الاختبار المتاح له، بدء الاختبار، الإجابة على الأسئلة ضمن وقت محدد لكل سؤال، ثم رؤية النتيجة والتحليل بعد انتهاء الاختبار.

---

# 2. Tech Stack

استخدم:

* Backend: Laravel
* Frontend: React
* Bridge: Inertia.js
* Database: MySQL أو PostgreSQL
* Authentication: Laravel Authentication
* Authorization: Policies / Gates أو نظام Roles مناسب
* Styling: استخدم النظام الموجود في المشروع إن وجد، وإلا استخدم Tailwind CSS
* Forms: React Forms + Inertia
* Validation: Laravel Form Requests
* Database migrations: Laravel migrations

المشروع يجب أن يكون:

**Laravel Monolith + React عبر Inertia.js**

ولا تقم بإنشاء REST API مستقل للمشروع.

استخدم Laravel Routes مباشرة مع:

```php
Inertia::render(...)
```

واستخدم Inertia Forms أو `router` في React لإرسال الطلبات.

---

# 3. الهيكل العام للنظام

قسّم النظام إلى منطقتين رئيسيتين:

## Admin / Lawyer

المحامي يستطيع:

* تسجيل الدخول
* الوصول إلى Dashboard
* إدارة المتدربين
* إنشاء الاختبارات
* تعديل الاختبارات
* حذف الاختبارات
* إضافة الأسئلة
* تعديل الأسئلة
* حذف الأسئلة
* ترتيب الأسئلة
* تحديد الإجابة الصحيحة
* تحديد تصنيف السؤال
* تحديد تفسير الإجابة

## Trainee

المتدرب يستطيع:

* تسجيل الدخول
* رؤية الاختبار المتاح
* بدء الاختبار
* حل سؤال واحد في كل مرة
* مشاهدة المؤقت
* اختيار الإجابة
* حفظ الإجابة فورًا
* الانتقال للسؤال التالي
* الانتقال تلقائيًا عند انتهاء الوقت
* عدم الرجوع إلى السؤال السابق
* رؤية النتيجة بعد انتهاء الاختبار
* مراجعة الأسئلة والإجابات والتفسيرات بعد التسليم

---

# 4. المرحلة الأولى — تحليل المشروع قبل كتابة الكود

قبل تنفيذ أي كود:

1. افحص بنية المشروع الحالية.
2. افحص Laravel version.
3. افحص React version.
4. افحص Inertia version.
5. افحص نظام Authentication الموجود.
6. افحص هل يوجد نظام Roles/Permissions.
7. افحص migrations الحالية.
8. افحص Models الموجودة.
9. افحص Routes.
10. افحص resources/js.
11. افحص layouts/components الموجودة.
12. افحص إعدادات Tailwind إن وجدت.

لا تقم بإعادة بناء أجزاء موجودة بالفعل بدون سبب.

إذا كان هناك implementation موجود يمكن إعادة استخدامه، استخدمه.

قبل أي تعديل معماري كبير، وضّح سبب الحاجة إليه.

---

# 5. المرحلة الثانية — تصميم قاعدة البيانات

أنشئ قاعدة البيانات بحيث تدعم:

## users

الحقول الأساسية:

```text
id
name
email nullable
password
role
created_at
updated_at
```

القيم:

```text
lawyer
trainee
```

إذا كان نظام Authentication الحالي يتطلب email، احتفظ به.

لكن تسجيل دخول المتدرب يجب أن يكون بالاسم أو بالطريقة التي يحددها النظام الحالي، دون إضافة تعقيد غير ضروري.

---

## exams

```text
id
lawyer_id
title
seconds_per_question
created_at
updated_at
```

العلاقات:

```text
Exam belongsTo Lawyer/User
Exam hasMany Questions
Exam hasMany Attempts
```

---

## questions

```text
id
exam_id
scenario_text
category
explanation
order
created_at
updated_at
```

التصنيفات:

```text
custody
maintenance
divorce
visitation
```

ويُعرض للمستخدم باللغة العربية:

```text
حضانة
نفقة
طلاق
زيارة
```

يفضل استخدام Enum أو قيمة ثابتة موثوقة بدل كتابة النصوص بشكل عشوائي في النظام.

---

## options

```text
id
question_id
text
is_correct
created_at
updated_at
```

كل سؤال يجب أن يحتوي على خيار صحيح واحد فقط.

يجب فرض هذا الشرط من جهة الخادم.

---

## attempts

```text
id
trainee_id
exam_id
started_at
submitted_at
status
score
current_question_index
created_at
updated_at
```

Status:

```text
in_progress
completed
```

يمكن إضافة:

```text
expired
```

إذا احتاج التصميم لذلك، ولكن لا تضف حالات لا يحتاجها النظام.

---

## answers

```text
id
attempt_id
question_id
selected_option_id
is_timed_out
question_started_at
answered_at
created_at
updated_at
```

يجب إضافة Foreign Keys مناسبة.

يجب إضافة Unique Constraint لمنع وجود أكثر من Answer لنفس السؤال داخل نفس Attempt.

مثلاً:

```text
unique(attempt_id, question_id)
```

---

# 6. قواعد قاعدة البيانات المهمة

يجب فرض العلاقات باستخدام Foreign Keys.

مثال:

```text
exams.lawyer_id -> users.id
questions.exam_id -> exams.id
options.question_id -> questions.id
attempts.trainee_id -> users.id
attempts.exam_id -> exams.id
answers.attempt_id -> attempts.id
answers.question_id -> questions.id
answers.selected_option_id -> options.id
```

استخدم Cascade أو Restrict حسب طبيعة العلاقة.

قبل حذف Exam يجب التعامل مع Questions وOptions وAttempts بطريقة آمنة.

لا تسمح بحذف بيانات مرتبطة بطريقة تؤدي إلى بيانات orphaned.

---

# 7. المرحلة الثالثة — Models & Relationships

أنشئ Models:

```text
User
Exam
Question
Option
Attempt
Answer
```

عرّف العلاقات بوضوح.

مثال:

```php
User hasMany exams
User hasMany attempts

Exam belongsTo lawyer
Exam hasMany questions
Exam hasMany attempts

Question belongsTo exam
Question hasMany options

Option belongsTo question

Attempt belongsTo trainee
Attempt belongsTo exam
Attempt hasMany answers

Answer belongsTo attempt
Answer belongsTo question
Answer belongsTo selectedOption
```

استخدم casts مناسبة للـ:

* datetime
* boolean
* enum إن تم استخدامه
* numeric score

---

# 8. المرحلة الرابعة — Authentication & Authorization

أنشئ نظام دخول موحد.

بعد تسجيل الدخول:

```text
lawyer -> Admin Dashboard

trainee -> Trainee Dashboard
```

لا تعتمد على React لإخفاء الصفحات فقط.

كل Route حساس يجب حمايته من Laravel.

مثلاً:

```text
auth
role:lawyer
role:trainee
```

أو استخدم Policies / Gates حسب البنية الموجودة.

المحامي لا يستطيع الوصول إلى صفحات المتدرب التي لا تخصه.

المتدرب لا يستطيع:

* إنشاء Exam
* تعديل Exam
* حذف Exam
* إنشاء Question
* تعديل Question
* حذف Question
* رؤية لوحة المحامي
* الوصول إلى بيانات متدربين آخرين

---

# 9. المرحلة الخامسة — لوحة المحامي

أنشئ Dashboard عملية وبسيطة.

اعرض إحصائيات مثل:

```text
عدد المتدربين
عدد الاختبارات
عدد الاختبارات المكتملة
متوسط النتائج
```

لا تبالغ في تصميم Dashboard إذا لم تكن هناك حاجة.

---

# 10. إدارة المتدربين

أنشئ صفحة:

```text
Trainees
```

تحتوي على:

* قائمة المتدربين
* الاسم
* تاريخ الإنشاء
* حالة المستخدم
* الإجراءات

الإجراءات:

```text
إضافة متدرب
تعديل متدرب
حذف متدرب
```

عند إنشاء متدرب:

```text
role = trainee
```

ولا تسمح للمستخدم العادي باختيار Role بشكل مباشر إذا كان ذلك يمثل خطورة أمنية.

الـ Role يحدد من جهة الخادم.

---

# 11. المرحلة السادسة — إنشاء الاختبار

أنشئ صفحة:

```text
Create Exam
```

الحقول:

```text
عنوان الاختبار
مدة السؤال
```

مثال:

```text
عنوان الاختبار:
اختبار الأحوال الشخصية - المستوى الأول

مدة السؤال:
60 ثانية
```

المدة يتم تخزينها بالثواني.

---

# 12. Repeater Form للأسئلة

داخل نموذج الاختبار، أنشئ Repeater للأسئلة.

كل سؤال يحتوي على:

```text
نص الحالة / السيناريو
التصنيف
التفسير
الخيارات
```

الخيارات الافتراضية:

```text
Option 1
Option 2
Option 3
Option 4
```

لكن اجعل التصميم قابلًا لدعم عدد مختلف من الخيارات إذا كان ذلك سهلًا دون تعقيد.

لكل خيار:

```text
نص الخيار
اختيار الإجابة الصحيحة
```

يجب أن يكون هناك إجابة صحيحة واحدة فقط.

---

# 13. Validation للأسئلة

يجب منع حفظ السؤال إذا:

* نص السؤال فارغ
* التصنيف غير موجود
* لا يوجد Options
* لا يوجد Answer صحيح
* يوجد أكثر من Answer صحيح
* خيار يحتوي على نص فارغ

استخدم Laravel Form Requests.

مثال منطقي:

```text
questions.*.scenario_text required
questions.*.category required|in:...
questions.*.options required|array|min:2
questions.*.options.*.text required
```

ويجب تطبيق التحقق من وجود إجابة صحيحة واحدة على مستوى الـ Backend.

لا تعتمد على React فقط.

---

# 14. ترتيب الأسئلة

كل Question يجب أن يحتوي على:

```text
order
```

ويجب أن يكون ترتيب الأسئلة ثابتًا أثناء Attempt.

عند بدء الاختبار:

لا تعتمد على ترتيب React.

الخادم هو الذي يحدد:

```text
Question 1
Question 2
Question 3
...
```

ويجب أن يبقى ترتيب الأسئلة ثابتًا طوال المحاولة.

---

# 15. المرحلة السابعة — بدء الاختبار

عندما يضغط المتدرب:

```text
ابدأ الاختبار
```

يجب إرسال Request إلى Laravel.

الخادم يقوم بـ:

1. التحقق من صلاحية المستخدم.
2. التحقق من أن المستخدم Trainee.
3. التحقق من أن Exam موجود.
4. التحقق من أن Exam متاح.
5. التحقق من وجود Attempt نشط.
6. إنشاء Attempt إذا لم يكن موجودًا.
7. تحديد `started_at`.
8. تحديد أول Question.
9. تحديد `current_question_index`.
10. تحديد وقت بدء السؤال.

يجب ألا يبدأ المؤقت من React فقط.

الخادم هو مصدر الحقيقة.

---

# 16. قاعدة مهمة جدًا — Server-side Timer

المؤقت في React مجرد واجهة لعرض الوقت.

لا تثق في:

```javascript
setInterval()
setTimeout()
Date.now()
```

لتحديد صلاحية الإجابة.

الخادم يجب أن يحسب الوقت الحقيقي.

مثلاً:

```text
question_started_at
+
exam.seconds_per_question
```

ثم يقارن مع الوقت الحالي على الخادم.

إذا انتهى الوقت:

```text
is_timed_out = true
```

ويتم الانتقال للسؤال التالي.

---

# 17. المرحلة الثامنة — صفحة الاختبار للمتدرب

اعرض:

```text
اسم الاختبار
رقم السؤال
إجمالي الأسئلة
السؤال الحالي
الخيارات
المؤقت
زر التالي
```

مثال:

```text
السؤال 3 من 10

[نص الحالة]

○ الخيار الأول
○ الخيار الثاني
○ الخيار الثالث
○ الخيار الرابع

الوقت المتبقي: 37

[التالي]
```

لا تعرض الإجابة الصحيحة أثناء الاختبار.

---

# 18. Auto Save

عند اختيار المتدرب لإجابة:

يجب إرسالها إلى Laravel مباشرة.

Laravel يتحقق من:

1. المستخدم يملك Attempt.
2. Attempt ما زال In Progress.
3. السؤال هو السؤال الحالي.
4. السؤال ينتمي للاختبار.
5. الخيار ينتمي للسؤال.
6. الوقت لم ينته.
7. السؤال لم تتم الإجابة عنه سابقًا.

بعد التحقق:

```text
save answer
```

ثم يمكن إظهار زر:

```text
التالي
```

---

# 19. منع الرجوع للسؤال السابق

هذه قاعدة أمنية أساسية.

لا تعتمد على:

```text
React state
Browser history
Disabled button
```

لمنع الرجوع.

الخادم يجب أن يرفض أي محاولة للوصول إلى سؤال سابق.

مثلاً إذا كان:

```text
current_question_index = 4
```

فلا يمكن للمتدرب إرسال Request للحصول على:

```text
question_index = 3
```

يجب أن يعيد Laravel خطأ واضحًا أو يعيد المتدرب إلى السؤال الحالي.

---

# 20. انتهاء الوقت

عندما ينتهي وقت السؤال:

إذا لم يقم المتدرب بالإجابة:

يجب إنشاء Answer:

```text
selected_option_id = null
is_timed_out = true
answered_at = now()
```

ثم الانتقال للسؤال التالي.

إذا قام المستخدم بإرسال إجابة في اللحظة التي انتهى فيها الوقت:

الخادم هو الذي يقرر.

إذا كان الوقت منتهيًا حسب Server Time:

```text
reject answer
mark timed out
move to next question
```

ولا تعتمد على وقت React.

---

# 21. Race Conditions

يجب التفكير في الحالات التي يحدث فيها:

* ضغط Next مرتين
* إرسال إجابة مرتين
* فتح أكثر من Tab
* إعادة إرسال Request
* ضعف الاتصال
* انتهاء الوقت أثناء إرسال الإجابة

استخدم Database Transactions عند الحاجة.

واستخدم Unique Constraints لمنع duplicate answers.

يجب أن تكون العمليات Idempotent قدر الإمكان.

---

# 22. منع التلاعب بالـ Request

لا تثق بأي قيمة قادمة من React مثل:

```text
question_id
question_index
correct_answer
score
time_remaining
```

الخادم يجب أن يتحقق من كل شيء.

المتدرب لا يستطيع إرسال:

```text
is_correct = true
score = 100
time_remaining = 50
```

لأن هذه القيم لا يجب أن تكون مصدر الحقيقة.

---

# 23. حساب النتيجة

بعد انتهاء آخر سؤال:

Laravel يقوم بحساب النتيجة من قاعدة البيانات.

لا ترسل React النتيجة التي يريدها.

الخادم يحسب:

```text
total_questions
correct_answers
wrong_answers
unanswered
score
percentage
```

مثلاً:

```text
10 Questions
7 Correct
2 Wrong
1 Unanswered

Score = 70%
```

يجب حساب النتيجة بناءً على:

```text
answers
+
options.is_correct
```

وليس بناءً على بيانات Frontend.

---

# 24. الأداء حسب التصنيف

بعد انتهاء الاختبار، اعرض:

```text
الحضانة
النفقة
الطلاق
الزيارة
```

ولكل تصنيف:

```text
عدد الأسئلة
عدد الصحيح
عدد الخطأ
غير المجاب
النسبة
```

مثال:

```text
الحضانة
3 أسئلة
2 صحيح
1 خطأ
66.7%

النفقة
2 سؤال
2 صحيح
100%
```

---

# 25. صفحة النتيجة

بعد انتهاء الاختبار:

اعرض:

```text
نتيجتك

70%

10 أسئلة

7 صحيح
2 خطأ
1 غير مجاب
```

ثم تحليل التصنيفات.

اجعل النتيجة واضحة بدون مبالغة في الـ UI.

---

# 26. مراجعة الأسئلة

بعد انتهاء الاختبار فقط يمكن للمتدرب مراجعة الأسئلة.

لكل سؤال:

```text
السؤال
إجابة المتدرب
الإجابة الصحيحة
التفسير
الحالة:
صحيح / خطأ / غير مجاب
```

مثال:

```text
السؤال:
...

إجابتك:
الخيار الثاني

الإجابة الصحيحة:
الخيار الثالث

التفسير:
...
```

إذا كان السؤال غير مجاب:

```text
إجابتك:
غير مجاب
```

---

# 27. منع كشف الإجابات أثناء الاختبار

أثناء الاختبار لا يجب إرسال:

```text
is_correct
correct_option_id
explanation
```

إلى React.

هذه البيانات يجب ألا تكون موجودة في Payload الخاص بصفحة الاختبار.

يجب أن تكون متاحة فقط بعد إكمال Attempt.

هذه نقطة أمنية مهمة جدًا.

---

# 28. Routes المقترحة

استخدم Routes منظمة مثل:

```text
/login

/dashboard

/admin
/admin/trainees
/admin/trainees/create
/admin/trainees/{trainee}/edit

/admin/exams
/admin/exams/create
/admin/exams/{exam}/edit

/trainee
/trainee/exam
/trainee/exam/{attempt}
/trainee/exam/{attempt}/answer
/trainee/exam/{attempt}/next
/trainee/exam/{attempt}/result
```

لا تستخدم هذه المسارات حرفيًا إذا كانت بنية المشروع الحالية تتطلب أسماء مختلفة.

المهم هو فصل صلاحيات ومسارات Admin عن Trainee.

---

# 29. Controllers

لا تضع Business Logic كبير داخل Controllers.

استخدم Controllers رفيعة.

مثلاً:

```text
ExamController
TraineeController
AttemptController
AnswerController
```

وإذا أصبح منطق الاختبار كبيرًا، أنشئ Service مثل:

```text
ExamAttemptService
ExamScoringService
```

المسؤوليات:

### ExamAttemptService

* start attempt
* get current question
* validate current question
* save answer
* timeout question
* move to next question
* complete attempt

### ExamScoringService

* calculate score
* calculate category performance
* generate result

---

# 30. Form Requests

أنشئ Form Requests مثل:

```text
StoreExamRequest
UpdateExamRequest
StoreTraineeRequest
SubmitAnswerRequest
```

كل Validation يجب أن يكون داخل Form Requests قدر الإمكان.

---

# 31. Policies

أنشئ Policies أو Authorization واضحًا لـ:

```text
Exam
Attempt
Trainee
```

مثلاً:

المحامي يستطيع تعديل الاختبار الذي أنشأه.

المتدرب يستطيع رؤية Attempt الخاص به فقط.

المتدرب لا يستطيع رؤية Attempt لمتدرب آخر حتى لو عرف ID.

---

# 32. React Components

قسّم الواجهة إلى Components صغيرة وقابلة لإعادة الاستخدام.

مثلاً:

```text
ExamForm
QuestionRepeater
QuestionEditor
OptionEditor
Timer
QuestionCard
AnswerOption
ExamProgress
ExamResult
CategoryPerformance
QuestionReview
```

لا تضع صفحة الاختبار كاملة داخل Component ضخم.

---

# 33. Timer Component

أنشئ:

```text
Timer
```

وظيفته:

* عرض الوقت المتبقي
* تحديث العرض بشكل مستمر
* إظهار انتهاء الوقت
* إبلاغ الواجهة عند انتهاء الوقت

لكن:

**Timer لا يقرر صلاحية الإجابة.**

الخادم هو المصدر النهائي.

إذا اختلف Timer الموجود في React عن Server Time:

```text
Server Time wins
```

---

# 34. التعامل مع Refresh

يجب أن يكون النظام قادرًا على التعامل مع:

```text
Browser Refresh
```

أثناء Attempt.

بعد Refresh:

الخادم يرجع:

```text
current question
server calculated remaining time
attempt status
```

ولا يبدأ السؤال من جديد.

---

# 35. التعامل مع فتح Tab جديد

إذا فتح المتدرب نفس Attempt في Tab آخر:

يجب ألا يستطيع تجاوز السؤال الحالي أو إنشاء Attempt جديد بطريقة تسمح بالتلاعب.

الخادم يجب أن يحافظ على:

```text
current_question_index
```

ويتحقق من كل Request.

---

# 36. تجربة المستخدم

استخدم واجهة:

* عربية RTL
* Responsive
* واضحة
* بسيطة
* عملية
* مناسبة لمنصة قانونية تدريبية

استخدم Labels عربية واضحة.

مثلاً:

```text
ابدأ الاختبار
السؤال التالي
انتهى الوقت
حفظ الإجابة
النتيجة
مراجعة الإجابات
الإجابة الصحيحة
إجابتك
غير مجاب
```

---

# 37. حالات الأخطاء

لا تعرض Stack Trace للمستخدم.

اعرض رسائل مفهومة مثل:

```text
انتهى وقت السؤال.
تم تسجيل السؤال كغير مجاب.
```

أو:

```text
لا يمكنك الرجوع إلى سؤال سابق.
```

أو:

```text
هذه المحاولة غير متاحة.
```

أو:

```text
تم إنهاء الاختبار بالفعل.
```

أما الأخطاء التقنية فيجب تسجيلها في Logs.

---

# 38. Database Transactions

استخدم Transactions في العمليات الحساسة، خصوصًا:

```text
Create Exam + Questions + Options
Start Attempt
Save Answer + Update Attempt
Complete Attempt + Calculate Result
```

بحسب الحاجة الفعلية.

لا تستخدم Transactions بشكل عشوائي حول كل Query.

---

# 39. اختبار النظام

أنشئ Tests تغطي أهم السيناريوهات.

يجب اختبار:

### Authentication

* Lawyer login
* Trainee login
* Unauthorized access

### Exams

* Create exam
* Update exam
* Delete exam
* Invalid question
* No correct answer
* Multiple correct answers

### Attempts

* Start attempt
* Resume attempt
* Prevent duplicate attempt إذا كانت السياسة تمنع ذلك
* Current question enforcement

### Answers

* Valid answer
* Invalid option
* Answer from another question
* Answer previous question
* Duplicate answer
* Answer after timeout

### Security

* Trainee cannot access another trainee's attempt
* Trainee cannot access correct answers before submission
* Trainee cannot modify score
* Trainee cannot modify timer
* Trainee cannot skip questions

### Scoring

* Correct answer
* Wrong answer
* Unanswered
* Category score
* Final score

---

# 40. Security Checklist

قبل اعتبار المشروع مكتملًا، تحقق من:

* CSRF protection
* Authorization
* Server-side validation
* Mass assignment protection
* SQL injection protection
* XSS protection
* Correct foreign keys
* Unique constraints
* No correct answers sent before completion
* No score accepted from frontend
* No timer value trusted from frontend
* No question navigation trusted from frontend
* No attempt ownership bypass
* No IDOR vulnerabilities
* No duplicate answers
* No duplicate submission

---

# 41. Laravel Code Quality

التزم بالآتي:

* استخدم Type Declarations.
* استخدم Return Types.
* استخدم Form Requests.
* استخدم Policies.
* استخدم Eloquent Relationships.
* استخدم Enums إذا كانت مناسبة.
* لا تستخدم raw SQL بدون سبب.
* لا تضع Queries كثيرة داخل React.
* لا تكرر Business Logic.
* لا تضع Business Logic في Blade/React.
* Controllers تكون Thin.
* Services تكون مسؤولة عن العمليات المعقدة.

---

# 42. Inertia Architecture

تذكر أن هذا ليس Next.js.

لا تنشئ:

```text
Laravel API
+
Next.js frontend
```

بل:

```text
Laravel
 ├── Routes
 ├── Controllers
 ├── Models
 ├── Services
 ├── Requests
 └── Inertia
      └── React
```

React يتعامل مع Laravel عبر Inertia.

---

# 43. البيانات التي ترسل إلى React

كن حذرًا جدًا في Props.

أثناء الاختبار:

أرسل فقط البيانات اللازمة:

```text
attempt
question
options
question number
total questions
server time
question deadline
```

ولا ترسل:

```text
correct option
is_correct
explanation
```

قبل انتهاء الاختبار.

---

# 44. Server Time

يفضل أن يعيد Laravel للواجهة معلومات مثل:

```text
question_started_at
question_deadline
server_now
```

حتى تستطيع React عرض Timer متزامن تقريبًا.

لكن الحساب النهائي يجب أن يتم على Laravel.

---

# 45. منع تخطي الأسئلة

إذا كان:

```text
current_question_index = 2
```

لا يمكن إرسال Request للوصول مباشرة إلى:

```text
question_index = 7
```

الخادم يجب أن يسمح فقط بالسؤال الحالي.

---

# 46. إكمال الاختبار

بعد آخر سؤال:

الخادم يقوم بـ:

```text
mark attempt completed
set submitted_at
calculate score
calculate category performance
```

ثم Redirect إلى:

```text
result page
```

لا تسمح بإرسال Attempt مكتمل مرة أخرى.

---

# 47. إعادة فتح النتيجة

إذا عاد المتدرب لاحقًا إلى:

```text
/result
```

يجب أن يستطيع رؤية نتيجته إذا كان Attempt مكتملًا.

لكن لا يمكنه:

```text
تغيير الإجابات
إعادة فتح الأسئلة
إعادة احتساب النتيجة من Frontend
```

---

# 48. Admin Exam Results

يمكن تجهيز البنية بحيث يستطيع المحامي لاحقًا رؤية:

```text
نتائج المتدربين
```

لكن لا تضف Features غير مطلوبة حاليًا إلا إذا كانت ضرورية للبنية.

---

# 49. Migration Strategy

أنشئ Migrations منفصلة ومنظمة.

لا تعدل Migration قديمة تم استخدامها في بيئة Production إلا إذا كان المشروع جديدًا وغير منشور.

يفضل:

```text
create_exams_table
create_questions_table
create_options_table
create_attempts_table
create_answers_table
```

وغيرها حسب الحاجة.

---

# 50. Seeders

أنشئ Seeder مبدئي يحتوي على:

```text
Lawyer user
Trainee user
Sample exam
Sample questions
Sample options
```

حتى يمكن اختبار النظام بسرعة.

لا تضع بيانات قانونية حقيقية أو معلومات حساسة.

استخدم بيانات تدريبية وهمية.

---

# 51. المرحلة النهائية — مراجعة Architecture

بعد تنفيذ المشروع:

راجع الكود بالكامل وابحث عن:

* Duplicate Logic
* Fat Controllers
* Missing Validation
* Missing Authorization
* Security vulnerabilities
* Incorrect relationships
* Race Conditions
* Timer vulnerabilities
* Incorrect score calculations
* Inconsistent naming
* Unnecessary API endpoints
* Unnecessary abstractions

إذا وجدت مشكلة، أصلحها قبل اعتبار المشروع مكتملًا.

---

# 52. طريقة تنفيذ المشروع

لا تحاول تنفيذ كل المشروع في خطوة واحدة.

نفذ المشروع على مراحل واضحة:

## Phase 1

تحليل المشروع الحالي + Architecture

## Phase 2

Database + Migrations + Models

## Phase 3

Authentication + Roles + Authorization

## Phase 4

Lawyer Dashboard

## Phase 5

Trainee Management

## Phase 6

Exam CRUD

## Phase 7

Questions + Options Repeater

## Phase 8

Attempt Engine

## Phase 9

Server-side Timer

## Phase 10

Answer Auto-save

## Phase 11

Prevent Back Navigation

## Phase 12

Scoring Engine

## Phase 13

Result + Category Analysis

## Phase 14

Question Review

## Phase 15

Error Handling

## Phase 16

Automated Tests

## Phase 17

Security Audit

## Phase 18

Final UI/UX Review

---

# 53. قاعدة مهمة أثناء التنفيذ

بعد كل Phase:

1. افحص الكود الذي تم إنشاؤه.
2. تأكد من عدم وجود أخطاء.
3. شغّل الاختبارات المناسبة.
4. أصلح المشاكل.
5. ثم انتقل للمرحلة التالية.

لا تنتقل للمرحلة التالية إذا كان هناك Bug يمنع المرحلة الحالية من العمل.

---

# 54. المطلوب في نهاية كل مرحلة

بعد تنفيذ كل Phase، أعطني تقريرًا مختصرًا يحتوي على:

```text
Phase:
ما تم تنفيذه:
Files created:
Files modified:
Database changes:
Routes added:
Tests added:
Potential issues:
Next Phase:
```

ولا تكتب شرحًا طويلًا غير ضروري.

---

# 55. قاعدة أخيرة مهمة

إذا واجهت قرارًا تقنيًا غير محدد في هذه الوثيقة:

اختر الحل:

1. الأبسط
2. الأكثر أمانًا
3. الأكثر توافقًا مع Laravel + Inertia
4. الأقل تعقيدًا في الصيانة
5. الذي لا يضيف Dependency غير ضرورية

ولا تقم بتغيير الـ Stack.

ولا تحول المشروع إلى:

```text
Laravel API + Next.js
```

ولا تنشئ Microservices.

ولا تضف Redis / WebSockets / Queues إلا إذا ظهرت حاجة فعلية لها.

الهدف هو بناء:

**منصة Laravel + React + Inertia متماسكة، آمنة، بسيطة وقابلة للتوسع.**

ابدأ الآن بـ **Phase 1 فقط**: تحليل المشروع الحالي وتحديد Architecture المناسبة، ثم توقف واعرض لي نتيجة التحليل قبل البدء في تنفيذ Phase 2.

____________________________________________________________________________
```

### البرومبت الثاني

```text
____________________________________________________________________________
بصفتك **Senior Laravel Developer وخبير في Database Seeding وEloquent Relationships**، قم بإنشاء **3 Seeders مستقلة ومنظمة** لمشروع منصة الاختبارات القانونية الحالية.

قبل التنفيذ، افحص Models وMigrations الحالية للتأكد من أسماء الجداول والحقول والعلاقات الفعلية، ولا تفترض أسماء مختلفة عن المشروع.

## المطلوب

إنشاء 3 Seeders:

1. `AdminSeeder`
2. `TraineeSeeder`
3. `ExamSeeder`

ويجب أن تكون الـ Seeders متوافقة بالكامل مع قاعدة البيانات الحالية.

---

# 1. AdminSeeder

أنشئ Seeder باسم:

```text
database/seeders/AdminSeeder.php
```

يقوم بإنشاء مستخدم Admin / Lawyer واحد على الأقل.

استخدم Role الموجود فعليًا في المشروع، سواء كان:

```text
lawyer
```

أو أي Enum / Role implementation مستخدم حاليًا.

بيانات تجريبية:

```text
Name: المحامي التجريبي
Email: admin@example.com
Password: password
Role: lawyer
```

يجب استخدام:

```php
Hash::make(...)
```

لتشفير كلمة المرور.

يجب أن يكون Seeder آمنًا عند تشغيله أكثر من مرة، أي لا يقوم بإنشاء Admin مكرر في كل مرة.

استخدم:

```php
firstOrCreate()
```

أو الطريقة المناسبة حسب بنية User الحالية.

---

# 2. TraineeSeeder

أنشئ Seeder باسم:

```text
database/seeders/TraineeSeeder.php
```

أنشئ مجموعة من المتدربين التجريبيين، مثلاً **5 متدربين**.

استخدم بيانات عربية وهمية مثل:

```text
أحمد محمد
محمد عبدالله
سارة أحمد
خالد علي
نورة محمد
```

إذا كان النظام الحالي يتطلب Email لكل مستخدم، أنشئ Emails وهمية:

```text
trainee1@example.com
trainee2@example.com
...
```

واجعل جميع المستخدمين:

```text
role = trainee
```

كلمة المرور:

```text
password
```

مع استخدام:

```php
Hash::make('password')
```

يجب أن يكون Seeder Idempotent ولا ينشئ Duplicate Users عند إعادة تشغيله.

---

# 3. ExamSeeder

أنشئ Seeder باسم:

```text
database/seeders/ExamSeeder.php
```

وهذا هو الـ Seeder الأهم.

يجب أن يقوم بإنشاء **عدة اختبارات قانونية تجريبية** مرتبطة بالأدمن الذي تم إنشاؤه في `AdminSeeder`.

استخدم المجالات الأربعة:

```text
الحضانة
النفقة
الطلاق
الزيارة
```

أنشئ مثلاً **4 اختبارات**، بحيث يكون لكل مجال اختبار مستقل.

مثال:

### الاختبار الأول

```text
عنوان الاختبار:
اختبار الحضانة

seconds_per_question:
60
```

### الاختبار الثاني

```text
اختبار النفقة
seconds_per_question:
60
```

### الاختبار الثالث

```text
اختبار الطلاق
seconds_per_question:
60
```

### الاختبار الرابع

```text
اختبار الزيارة
seconds_per_question:
60
```

---

# 4. إنشاء الأسئلة

داخل `ExamSeeder` لا تقم بإنشاء الاختبارات فقط.

يجب إنشاء **أسئلة كاملة مرتبطة بكل اختبار**.

لكل اختبار أنشئ مثلاً **5 أسئلة**.

أي إجمالي:

```text
4 Exams
×
5 Questions
=
20 Questions
```

استخدم بيانات قانونية **تدريبية وهمية** وليست فتوى أو استشارة قانونية حقيقية.

كل سؤال يجب أن يحتوي على:

```text
scenario_text
category
explanation
order
```

والـ category يجب أن تتوافق مع Enum / values الموجودة فعليًا في المشروع.

---

# 5. خيارات الأسئلة

لكل سؤال أنشئ **4 خيارات**.

مثال:

```text
Option 1
Option 2
Option 3
Option 4
```

لكن يجب أن تكون الخيارات مرتبطة بالسؤال من خلال العلاقة الفعلية في Model.

لكل Option:

```text
text
is_correct
```

ويجب أن يكون هناك:

```text
1 correct option
3 incorrect options
```

بالضبط.

لا يوجد سؤال بدون إجابة صحيحة.

ولا يوجد سؤال بأكثر من إجابة صحيحة.

---

# 6. مثال محتوى السؤال

استخدم محتوى عربي واقعي من ناحية صياغة أسئلة التدريب، لكن لا تقدم أحكامًا قانونية على أنها حقائق ملزمة.

مثال:

```text
scenario_text:
ورد في حالة تدريبية افتراضية أن أحد الأطراف تقدم بطلب يتعلق بالحضانة، فما العامل الذي يجب النظر إليه ضمن تقييم الحالة؟

category:
custody

explanation:
هذا سؤال تدريبي افتراضي يهدف إلى اختبار فهم المتدرب للعوامل المرتبطة بتقييم الحالة.
```

ثم:

```text
Option 1
Option 2
Option 3
Option 4
```

واختر إجابة صحيحة واحدة لأغراض الاختبار التجريبي.

يمكنك استخدام محتوى مشابه للمجالات:

* الحضانة
* النفقة
* الطلاق
* الزيارة

---

# 7. العلاقات

تأكد أن البيانات مرتبطة بشكل صحيح:

```text
Admin
   ↓
Exam
   ↓
Question
   ↓
Option
```

أي:

```text
exam.lawyer_id
question.exam_id
option.question_id
```

يجب عدم إدخال IDs ثابتة مثل:

```php
lawyer_id = 1;
```

بل احصل على المستخدم من قاعدة البيانات:

```php
$admin = User::where(...)->firstOrFail();
```

ثم استخدم:

```php
$admin->id
```

وبالمثل، أنشئ Questions وOptions باستخدام العلاقات الموجودة في Models إذا كان ذلك مناسبًا:

```php
$exam->questions()->create(...)
```

ثم:

```php
$question->options()->create(...)
```

---

# 8. ترتيب الأسئلة

يجب أن يكون لكل سؤال:

```text
order = 1
order = 2
order = 3
order = 4
order = 5
```

داخل كل اختبار.

لا تستخدم ترتيبًا عشوائيًا.

---

# 9. Seeder DatabaseSeeder

بعد إنشاء الـ 3 Seeders، قم بتعديل:

```text
database/seeders/DatabaseSeeder.php
```

ليتم تشغيلهم بالترتيب الصحيح:

```text
AdminSeeder
↓
TraineeSeeder
↓
ExamSeeder
```

مثلاً:

```php
$this->call([
    AdminSeeder::class,
    TraineeSeeder::class,
    ExamSeeder::class,
]);
```

الترتيب مهم لأن:

```text
ExamSeeder
```

يعتمد على وجود Admin.

---

# 10. Idempotency

جميع Seeders يجب أن تكون قابلة لإعادة التشغيل بدون إنشاء بيانات مكررة.

عند تشغيل:

```bash
php artisan db:seed
```

ثم تشغيله مرة أخرى:

لا يجب أن تحصل على:

```text
Duplicate Admin
Duplicate Trainees
Duplicate Exams
Duplicate Questions
Duplicate Options
```

استخدم `firstOrCreate()` أو استراتيجية مناسبة تعتمد على البيانات الفريدة الموجودة فعليًا في المشروع.

---

# 11. لا تغير Database Structure

لا تقم بإنشاء Migration جديدة.

لا تغير:

* أسماء الجداول
* أسماء الأعمدة
* العلاقات
* Enums

إلا إذا اكتشفت أن هناك مشكلة حقيقية تمنع الـ Seeder من العمل.

في هذه الحالة توقف ووضح المشكلة قبل تعديل الـ Database Structure.

---

# 12. التحقق بعد التنفيذ

بعد إنشاء الـ Seeders:

شغّل:

```bash
php artisan migrate:fresh --seed
```

إذا كان استخدام `migrate:fresh` آمنًا في بيئة المشروع الحالية.

ثم تحقق من:

### Users

```text
1 Lawyer/Admin
5 Trainees
```

### Exams

```text
4 Exams
```

### Questions

```text
20 Questions
```

### Options

```text
80 Options
```

ويجب أن يكون:

```text
20 correct options
60 incorrect options
```

أي سؤال يحتوي على إجابة صحيحة واحدة فقط.

---

# 13. تحقق من العلاقات

بعد الـ Seeding، تحقق برمجيًا من:

```text
كل Exam لديه Lawyer
كل Exam لديه 5 Questions
كل Question مرتبط بـ Exam صحيح
كل Question لديه 4 Options
كل Question لديه Correct Option واحدة
كل Option مرتبط بـ Question صحيح
```

إذا كانت Models تحتوي على Relationships، استخدمها في التحقق.

---

# 14. المطلوب النهائي

لا تقم بتغيير أي جزء غير متعلق بالمهمة.

أنشئ فقط:

```text
AdminSeeder.php
TraineeSeeder.php
ExamSeeder.php
```

وقم بتعديل:

```text
DatabaseSeeder.php
```

ثم شغّل الاختبارات / Seeder للتحقق من صحة البيانات.

في النهاية أعطني تقريرًا مختصرًا:

```text
Seeders created:
- AdminSeeder
- TraineeSeeder
- ExamSeeder

Data created:
- 1 Admin
- 5 Trainees
- 4 Exams
- 20 Questions
- 80 Options

Relationships:
- Admin → Exams
- Exam → Questions
- Question → Options

Validation:
- 1 correct option per question
- 4 options per question
- No duplicate seed data
```

إذا كانت أسماء Models أو الحقول أو العلاقات في المشروع مختلفة عن المذكورة هنا، **استخدم البنية الموجودة فعليًا في المشروع ولا تنشئ بنية موازية**.

____________________________________________________________________________
```

---

## تعديلات ومراجعات تم تنفيذها يدويًا

بعد مراجعة اقتراحات وأكواد الذكاء الاصطناعي، تم إجراء تعديلات يدوية حسب متطلبات المشروع، ومن أبرزها:

* تم تغيير قاعدة البيانات من **SQLite** إلى **MySQL** بما يتناسب مع بيئة تشغيل المشروع ومتطلبات التطوير.
* تمت مراجعة التعديلات الناتجة عن أدوات الذكاء الاصطناعي والتأكد من توافقها مع احتياجات المشروع قبل اعتمادها.

---

## ملخص استخدام الذكاء الاصطناعي

تم استخدام أدوات الذكاء الاصطناعي كمساعد في مراحل مختلفة من عملية التطوير، بدايةً من **تحليل المتطلبات**، مرورًا بـ **توليد الـ Prompts**، ووصولًا إلى **تنفيذ الكود**.

كما تمت مراجعة مخرجات الذكاء الاصطناعي وتعديلها يدويًا عند الحاجة لضمان توافقها مع المتطلبات التقنية وبيئة تشغيل المشروع.
