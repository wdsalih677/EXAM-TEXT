import CategoryPerformance from '@/components/exam/category-performance';
import QuestionReview from '@/components/exam/question-review';

type CategoryStat = {
    category: string;
    label: string;
    total: number;
    correct: number;
    wrong: number;
    unanswered: number;
    percentage: number;
};

type ReviewQuestion = {
    id: number;
    order: number;
    scenario_text: string;
    category_label: string;
    explanation: string;
    selected_option_text: string | null;
    correct_option_text: string | null;
    status: 'correct' | 'wrong' | 'unanswered';
};

type Props = {
    examTitle: string;
    score: number;
    totalQuestions: number;
    correctAnswers: number;
    wrongAnswers: number;
    unanswered: number;
    categories: CategoryStat[];
    questions: ReviewQuestion[];
};

export default function ExamResult({
    examTitle,
    score,
    totalQuestions,
    correctAnswers,
    wrongAnswers,
    unanswered,
    categories,
    questions,
}: Props) {
    return (
        <div className="space-y-8">
            <section className="rounded-xl border p-6 text-center">
                <p className="text-muted-foreground text-sm">{examTitle}</p>
                <h1 className="mt-2 text-xl font-semibold">نتيجتك</h1>
                <p className="mt-4 text-5xl font-semibold">{score}%</p>
                <p className="text-muted-foreground mt-2 text-sm">
                    {totalQuestions} أسئلة
                </p>
                <div className="mt-6 grid grid-cols-3 gap-3 text-sm">
                    <div>
                        <p className="text-2xl font-semibold">
                            {correctAnswers}
                        </p>
                        <p className="text-muted-foreground">صحيح</p>
                    </div>
                    <div>
                        <p className="text-2xl font-semibold">{wrongAnswers}</p>
                        <p className="text-muted-foreground">خطأ</p>
                    </div>
                    <div>
                        <p className="text-2xl font-semibold">{unanswered}</p>
                        <p className="text-muted-foreground">غير مجاب</p>
                    </div>
                </div>
            </section>

            <section className="space-y-4">
                <h2 className="text-lg font-semibold">تحليل التصنيفات</h2>
                <CategoryPerformance categories={categories} />
            </section>

            <section className="space-y-4">
                <h2 className="text-lg font-semibold">مراجعة الإجابات</h2>
                <div className="space-y-4">
                    {questions.map((question) => (
                        <QuestionReview key={question.id} question={question} />
                    ))}
                </div>
            </section>
        </div>
    );
}
