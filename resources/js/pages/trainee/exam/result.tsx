import ExamResult from '@/components/exam/exam-result';
import { Button } from '@/components/ui/button';
import { Head, Link } from '@inertiajs/react';
import { dashboard } from '@/routes/trainee';

type Props = {
    exam: {
        id: number;
        title: string;
    };
    score: number;
    total_questions: number;
    correct_answers: number;
    wrong_answers: number;
    unanswered: number;
    categories: {
        category: string;
        label: string;
        total: number;
        correct: number;
        wrong: number;
        unanswered: number;
        percentage: number;
    }[];
    questions: {
        id: number;
        order: number;
        scenario_text: string;
        category_label: string;
        explanation: string;
        selected_option_text: string | null;
        correct_option_text: string | null;
        status: 'correct' | 'wrong' | 'unanswered';
    }[];
};

export default function ExamResultPage({
    exam,
    score,
    total_questions,
    correct_answers,
    wrong_answers,
    unanswered,
    categories,
    questions,
}: Props) {
    return (
        <>
            <Head title="النتيجة" />
            <div className="mx-auto flex max-w-3xl flex-col gap-6 p-4">
                <ExamResult
                    examTitle={exam.title}
                    score={score}
                    totalQuestions={total_questions}
                    correctAnswers={correct_answers}
                    wrongAnswers={wrong_answers}
                    unanswered={unanswered}
                    categories={categories}
                    questions={questions}
                />
                <Button asChild variant="outline" className="self-start">
                    <Link href={dashboard()}>العودة إلى لوحة المتدرب</Link>
                </Button>
            </div>
        </>
    );
}
