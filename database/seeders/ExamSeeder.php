<?php

namespace Database\Seeders;

use App\Enums\QuestionCategory;
use App\Enums\UserRole;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()
            ->where('email', 'admin@example.com')
            ->where('role', UserRole::Lawyer)
            ->firstOrFail();

        foreach ($this->exams() as $examData) {
            $exam = $admin->exams()->firstOrCreate(
                ['title' => $examData['title']],
                ['seconds_per_question' => $examData['seconds_per_question']],
            );

            $this->seedQuestions($exam, $examData['category'], $examData['questions']);
        }
    }

    /**
     * @param  list<array{scenario_text: string, explanation: string, options: list<array{text: string, is_correct: bool}>}>  $questions
     */
    private function seedQuestions(Exam $exam, QuestionCategory $category, array $questions): void
    {
        foreach ($questions as $index => $questionData) {
            $question = $exam->questions()->firstOrCreate(
                ['order' => $index + 1],
                [
                    'scenario_text' => $questionData['scenario_text'],
                    'category' => $category,
                    'explanation' => $questionData['explanation'],
                ],
            );

            $this->seedOptions($question, $questionData['options']);
        }
    }

    /**
     * @param  list<array{text: string, is_correct: bool}>  $options
     */
    private function seedOptions(Question $question, array $options): void
    {
        if ($question->options()->exists()) {
            return;
        }

        foreach ($options as $option) {
            $question->options()->create($option);
        }
    }

    /**
     * @return list<array{
     *     title: string,
     *     seconds_per_question: int,
     *     category: QuestionCategory,
     *     questions: list<array{scenario_text: string, explanation: string, options: list<array{text: string, is_correct: bool}>}>
     * }>
     */
    private function exams(): array
    {
        return [
            [
                'title' => 'اختبار الحضانة',
                'seconds_per_question' => 60,
                'category' => QuestionCategory::Custody,
                'questions' => $this->custodyQuestions(),
            ],
            [
                'title' => 'اختبار النفقة',
                'seconds_per_question' => 60,
                'category' => QuestionCategory::Maintenance,
                'questions' => $this->maintenanceQuestions(),
            ],
            [
                'title' => 'اختبار الطلاق',
                'seconds_per_question' => 60,
                'category' => QuestionCategory::Divorce,
                'questions' => $this->divorceQuestions(),
            ],
            [
                'title' => 'اختبار الزيارة',
                'seconds_per_question' => 60,
                'category' => QuestionCategory::Visitation,
                'questions' => $this->visitationQuestions(),
            ],
        ];
    }

    /**
     * @return list<array{scenario_text: string, explanation: string, options: list<array{text: string, is_correct: bool}>}>
     */
    private function custodyQuestions(): array
    {
        return [
            [
                'scenario_text' => 'ورد في حالة تدريبية افتراضية أن أحد الأطراف تقدم بطلب يتعلق بالحضانة، فما العامل الذي يجب النظر إليه ضمن تقييم الحالة؟',
                'explanation' => 'هذا سؤال تدريبي افتراضي يهدف إلى اختبار فهم المتدرب للعوامل المرتبطة بتقييم الحالة، وليس حكماً ملزماً.',
                'options' => [
                    ['text' => 'مصلحة المحضون وظروف الرعاية الفعلية.', 'is_correct' => true],
                    ['text' => 'رغبة الطرف الأقوى اقتصادياً فقط.', 'is_correct' => false],
                    ['text' => 'تجاهل عمر الطفل واحتياجاته.', 'is_correct' => false],
                    ['text' => 'إغلاق ملف الحضانة دون دراسة.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'في سيناريو تدريبي، انتقل أحد الوالدين إلى مدينة أخرى وطلب نقل الحضانة. ما المسألة التي تُراجع أولاً في ملف التدريب؟',
                'explanation' => 'السؤال يختبر ترتيب الأولويات في دراسة طلب نقل الحضانة ضمن حالة افتراضية.',
                'options' => [
                    ['text' => 'أثر الانتقال على استقرار الطفل وروتينه.', 'is_correct' => true],
                    ['text' => 'لون منزل الحاضن الجديد.', 'is_correct' => false],
                    ['text' => 'عدد السيارات لدى أحد الطرفين.', 'is_correct' => false],
                    ['text' => 'إلغاء الحضانة تلقائياً بسبب الانتقال.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'حالة تدريبية تعرض أن الحاضن يعمل ساعات طويلة، وطُلب إسقاط الحضانة لهذا السبب وحده. ما التقييم الأنسب في إطار التدريب؟',
                'explanation' => 'الهدف قياس ما إذا كان المتدرب يفرّق بين العمل بذاته وأثره على رعاية المحضون في سيناريو وهمي.',
                'options' => [
                    ['text' => 'العمل بذاته لا يحسم النتيجة دون أثره على الرعاية.', 'is_correct' => true],
                    ['text' => 'أي عمل للحاضن يسقط الحضانة فوراً.', 'is_correct' => false],
                    ['text' => 'الحضانة لا تُبحث إذا كان الحاضن موظفاً.', 'is_correct' => false],
                    ['text' => 'يُستبعد ملف الحضانة لمجرد وجود وظيفة.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'في تمرين محاكاة، اختلف الطرفان حول استمرار الحضانة بعد تغير ظروف السكن والدراسة. ما الذي يُوثَّق في المذكرة التدريبية؟',
                'explanation' => 'سؤال تدريبي يركّز على توثيق الوقائع المؤثرة في الاستقرار لا على إصدار حكم نهائي.',
                'options' => [
                    ['text' => 'تغيرات السكن والدراسة وعلاقتها باستقرار الطفل.', 'is_correct' => true],
                    ['text' => 'آراء الجيران غير المرتبطة بالرعاية.', 'is_correct' => false],
                    ['text' => 'مقارنات شكلية بين الطرفين دون وقائع.', 'is_correct' => false],
                    ['text' => 'إغلاق النقاش لأن الحضانة لا تتغير أبداً.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'سيناريو افتراضي يطلب فيه غير الحاضن نقل الحضانة بسبب تحسن ظروفه المالية فقط. ما الملاحظة التدريبية الأدق؟',
                'explanation' => 'المحتوى وهمي ويختبر وزن القدرة المالية ضمن مجموعة عوامل لا كمعيار وحيد.',
                'options' => [
                    ['text' => 'القدرة المالية عنصر يُدرس مع بقية عوامل الرعاية.', 'is_correct' => true],
                    ['text' => 'التفوق المالي ينقل الحضانة تلقائياً.', 'is_correct' => false],
                    ['text' => 'الظروف المالية لا تُذكر في أي ملف حضانة.', 'is_correct' => false],
                    ['text' => 'يُرفض الطلب دون قراءة ملف الطفل.', 'is_correct' => false],
                ],
            ],
        ];
    }

    /**
     * @return list<array{scenario_text: string, explanation: string, options: list<array{text: string, is_correct: bool}>}>
     */
    private function maintenanceQuestions(): array
    {
        return [
            [
                'scenario_text' => 'في حالة تدريبية افتراضية، طُلب تقدير نفقة بعد انتقال الحضانة. ما العنصر الذي يُدرج في ورقة العمل؟',
                'explanation' => 'سؤال تدريبي يختبر عناصر تقدير النفقة في محاكاة، دون تقديم فتوى.',
                'options' => [
                    ['text' => 'حاجة المستحق وقدرة الملزم بالنفقة.', 'is_correct' => true],
                    ['text' => 'لون الخاتم الذي اشتراه أحد الطرفين.', 'is_correct' => false],
                    ['text' => 'إلغاء النفقة لمجرد انتقال الحضانة.', 'is_correct' => false],
                    ['text' => 'تحديد مبلغ عشوائي دون أي معايير.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'سيناريو تدريبي لزوجة غير عاملة طلبت نفقة أثناء قيام العلاقة الزوجية في الملف الافتراضي. ما زاوية الدراسة المناسبة؟',
                'explanation' => 'الهدف تدريب المتدرب على تمييز سياق النفقة الزوجية داخل حالة وهمية.',
                'options' => [
                    ['text' => 'سياق قيام العلاقة واستحقاق النفقة المدعى به.', 'is_correct' => true],
                    ['text' => 'رفض الملف لأن النفقة لا تُدرس إلا بعد سنوات.', 'is_correct' => false],
                    ['text' => 'ربط النفقة بهواية أحد الطرفين فقط.', 'is_correct' => false],
                    ['text' => 'إغلاق الطلب دون سؤال عن الدخل أو الحاجة.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'ورد في تمرين أن أحد الأطراف يدّعي تغيّر الدخل بعد صدور تقدير سابق للنفقة. ما الخطوة التدريبية الأولى؟',
                'explanation' => 'المحتوى افتراضي ويختبر التعامل مع ادعاء تغيّر القدرة المالية.',
                'options' => [
                    ['text' => 'جمع ما يثبت تغيّر الدخل ومدى أثره على التقدير.', 'is_correct' => true],
                    ['text' => 'تعديل المبلغ فوراً دون مستندات.', 'is_correct' => false],
                    ['text' => 'تجاهل الدخل لأنه لا علاقة له بالنفقة.', 'is_correct' => false],
                    ['text' => 'إيقاف النفقة تلقائياً بمجرد الادعاء.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'حالة محاكاة تتضمن نفقات تعليم وعلاج ضمن طلب نفقة ابن. ما التوصيف الأدق لأغراض التدريب؟',
                'explanation' => 'سؤال يختبر تصنيف بنود النفقة المحتملة في ملف تدريبي وهمي.',
                'options' => [
                    ['text' => 'تُراجع كبنود مرتبطة بالحاجة الفعلية للمحضون.', 'is_correct' => true],
                    ['text' => 'تُستبعد دائماً لأنها كمالية في كل الأحوال.', 'is_correct' => false],
                    ['text' => 'تُضاف بمبلغ ثابت دون النظر للواقع.', 'is_correct' => false],
                    ['text' => 'لا تُذكر في أي دراسة لملف النفقة.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'في سيناريو تدريبي، تأخر سداد مبالغ نفقة متفق عليها داخل المحاكاة. ما المسألة التي تُسجَّل في الملاحظات؟',
                'explanation' => 'التمرين يختبر توثيق التأخر وآثاره الإجرائية داخل حالة افتراضية.',
                'options' => [
                    ['text' => 'تاريخ الاستحقاق والمبالغ غير المسددة وأثر التأخير.', 'is_correct' => true],
                    ['text' => 'شطب الملف لأن التأخير يلغي النفقة.', 'is_correct' => false],
                    ['text' => 'الاعتماد على الانطباع الشخصي دون أرقام.', 'is_correct' => false],
                    ['text' => 'تحويل الملف إلى موضوع زيارة فقط.', 'is_correct' => false],
                ],
            ],
        ];
    }

    /**
     * @return list<array{scenario_text: string, explanation: string, options: list<array{text: string, is_correct: bool}>}>
     */
    private function divorceQuestions(): array
    {
        return [
            [
                'scenario_text' => 'حالة تدريبية افتراضية تذكر وقوع طلاق ثم طلب مراجعة الوضع خلال العدة. ما الذي يُحدَّد في ورقة التدريب أولاً؟',
                'explanation' => 'سؤال وهمي يختبر تمييز نوع الواقعة وتوقيتها ضمن ملف تدريبي، وليس إفتاءً.',
                'options' => [
                    ['text' => 'وصف الواقعة وتاريخها وسياق العدة المدعى به.', 'is_correct' => true],
                    ['text' => 'إغلاق الملف لأن الطلاق لا تُدرس تفاصيله.', 'is_correct' => false],
                    ['text' => 'الاعتماد على إشاعة دون توثيق.', 'is_correct' => false],
                    ['text' => 'خلط ملف الطلاق بملف زيارة دون سبب.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'في محاكاة، اختلف الطرفان حول أثر الطلاق على حضانة الأطفال. ما المبدأ التدريبي المناسب؟',
                'explanation' => 'الهدف فصل آثار الطلاق عن مسائل الرعاية في سيناريو افتراضي.',
                'options' => [
                    ['text' => 'مسائل الأطفال تُدرس وفق أوضاعهم لا بوصفها نتيجة تلقائية للطلاق.', 'is_correct' => true],
                    ['text' => 'الطلاق ينقل الحضانة فوراً دون دراسة.', 'is_correct' => false],
                    ['text' => 'الأطفال لا يُذكرون في أي ملف بعد الطلاق.', 'is_correct' => false],
                    ['text' => 'يُؤجَّل كل شيء إلى أجل غير مسمى دون مبرر.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'سيناريو تدريبي يشير إلى حاجة لتوثيق واقعة الطلاق وإجراءاتها الشكلية. ما الخطوة المتوقعة من المتدرب؟',
                'explanation' => 'المحتوى تدريبي ويركّز على أهمية التوثيق الإجرائي داخل المحاكاة.',
                'options' => [
                    ['text' => 'جمع ما يوضح وقوع الواقعة وإجراءات توثيقها في الملف.', 'is_correct' => true],
                    ['text' => 'الاكتفاء بمحادثة شفهية غير موثقة.', 'is_correct' => false],
                    ['text' => 'تعديل التاريخ دون مستند.', 'is_correct' => false],
                    ['text' => 'إهمال الإجراءات لأنها شكلية دائماً.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'ورد في تمرين أن أحد الطرفين يطلب إنهاء العلاقة باتفاق، والآخر يعترض على الصيغة. ما الذي يُحدَّد في المذكرة التدريبية؟',
                'explanation' => 'سؤال يختبر تفريق المتدرب بين الاتفاق المتنازع عليه وبقية الطلبات في حالة وهمية.',
                'options' => [
                    ['text' => 'نطاق الاتفاق المدعى به ونقاط الخلاف الجوهرية.', 'is_correct' => true],
                    ['text' => 'اعتبار الاتفاق نافذاً رغم الإنكار الكامل.', 'is_correct' => false],
                    ['text' => 'تجاهل الخلاف لأنه غير مهم.', 'is_correct' => false],
                    ['text' => 'تحويل الموضوع إلى نفقة فقط دون سبب.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'حالة افتراضية تخلط بين آثار الطلاق المالية والمسكن المؤقت. ما الأسلوب الأدق في التدريب؟',
                'explanation' => 'التمرين يختبر فرز الطلبات المتداخلة داخل ملف وهمي.',
                'options' => [
                    ['text' => 'فرز كل طلب على حدة مع ربطه بوقائعه.', 'is_correct' => true],
                    ['text' => 'دمج كل الطلبات في نتيجة واحدة بلا تمييز.', 'is_correct' => false],
                    ['text' => 'حذف الجانب المالي دائماً.', 'is_correct' => false],
                    ['text' => 'معالجة المسكن بمعزل عن أي وقائع.', 'is_correct' => false],
                ],
            ],
        ];
    }

    /**
     * @return list<array{scenario_text: string, explanation: string, options: list<array{text: string, is_correct: bool}>}>
     */
    private function visitationQuestions(): array
    {
        return [
            [
                'scenario_text' => 'في حالة تدريبية افتراضية، رفض الحاضن الزيارة بشكل مطلق. ما المسألة التي تُبحث في ملف التدريب؟',
                'explanation' => 'سؤال وهمي يختبر فهم تنظيم الزيارة بما لا يضر مصلحة الطفل داخل المحاكاة.',
                'options' => [
                    ['text' => 'تنظيم الزيارة بما يراعي مصلحة الطفل وظروف الطرفين.', 'is_correct' => true],
                    ['text' => 'المنع المطلق حق شخصي للحاضن بلا قيد.', 'is_correct' => false],
                    ['text' => 'الزيارة لا تُناقش إلا بعد سنوات طويلة حتماً.', 'is_correct' => false],
                    ['text' => 'شطب حق الزيارة بمجرد وقوع خلاف.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'سيناريو تدريبي حدّد مواعيد زيارة ثم طُلب تعديلها لتعارضها مع دراسة الطفل. ما المبدأ المناسب للتدريب؟',
                'explanation' => 'المحتوى افتراضي ويختبر مرونة التنظيم عند تعارض المواعيد مع مصلحة الطفل.',
                'options' => [
                    ['text' => 'يجوز بحث تعديل المواعيد بما يراعي مصلحة الطفل.', 'is_correct' => true],
                    ['text' => 'الجدول لا يقبل أي مراجعة مهما تغيرت الظروف.', 'is_correct' => false],
                    ['text' => 'التعديل حق مطلق لطرف واحد دون ضوابط.', 'is_correct' => false],
                    ['text' => 'الدراسة ليست عنصراً يُذكر في تنظيم الزيارة.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'تمرين محاكاة يقترح زيارة في مكان عام بوقت محدد. ما الذي يُقيَّم أولاً؟',
                'explanation' => 'سؤال تدريبي يركّز على ملاءمة المكان والزمان لسلامة الطفل واستقراره.',
                'options' => [
                    ['text' => 'مدى ملاءمة المكان والزمان لسلامة الطفل وروتينه.', 'is_correct' => true],
                    ['text' => 'اختيار أبعد مكان ممكن لإرهاق الطرف الآخر.', 'is_correct' => false],
                    ['text' => 'إلغاء المكان العام في كل الحالات دون سبب.', 'is_correct' => false],
                    ['text' => 'تجاهل الوقت لأنه غير مؤثر.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'حالة افتراضية يدّعي فيها أحد الطرفين أن الزيارة تؤثر على انتظام نوم الطفل. ما الخطوة التدريبية؟',
                'explanation' => 'الهدف تدريب المتدرب على فحص أثر جدول الزيارة في الوقائع المدعى بها.',
                'options' => [
                    ['text' => 'فحص أوقات الزيارة وعلاقتها بروتين نوم الطفل.', 'is_correct' => true],
                    ['text' => 'رفض أي نقاش عن النوم لأنه موضوع طبي فقط.', 'is_correct' => false],
                    ['text' => 'إلغاء الزيارة فوراً دون تحقق.', 'is_correct' => false],
                    ['text' => 'تثبيت موعد ليلي دائماً دون مراجعة.', 'is_correct' => false],
                ],
            ],
            [
                'scenario_text' => 'في سيناريو تدريبي، طُلب ترتيب تواصل مرئي إضافة إلى الزيارة الحضورية. ما التوصيف الأدق؟',
                'explanation' => 'سؤال وهمي يختبر إمكانية بحث وسائل التواصل المكملة ضمن تنظيم العلاقة بالطفل.',
                'options' => [
                    ['text' => 'يُبحث التواصل المكمل بما لا يربك روتين الطفل.', 'is_correct' => true],
                    ['text' => 'التواصل المرئي يلغي الزيارة الحضورية حتماً.', 'is_correct' => false],
                    ['text' => 'يُمنع أي تواصل غير حضوري في كل الأحوال.', 'is_correct' => false],
                    ['text' => 'يُترك الأمر دون أي ضوابط زمنية.', 'is_correct' => false],
                ],
            ],
        ];
    }
}
