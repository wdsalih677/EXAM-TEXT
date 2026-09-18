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
    question: ReviewQuestion;
};

const statusLabel: Record<ReviewQuestion['status'], string> = {
    correct: 'صحيح',
    wrong: 'خطأ',
    unanswered: 'غير مجاب',
};

export default function QuestionReview({ question }: Props) {
    return (
        <article className="space-y-4 rounded-xl border p-5">
            <div className="flex flex-wrap items-center justify-between gap-2">
                <span className="text-muted-foreground text-sm">
                    السؤال {question.order} — {question.category_label}
                </span>
                <span
                    className={`rounded-md px-2 py-1 text-xs font-medium ${
                        question.status === 'correct'
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200'
                            : question.status === 'wrong'
                              ? 'bg-destructive/10 text-destructive'
                              : 'bg-muted text-muted-foreground'
                    }`}
                >
                    {statusLabel[question.status]}
                </span>
            </div>
            <p className="leading-7 whitespace-pre-wrap">
                {question.scenario_text}
            </p>
            <div className="space-y-2 text-sm">
                <p>
                    <span className="text-muted-foreground">إجابتك: </span>
                    {question.selected_option_text ?? 'غير مجاب'}
                </p>
                <p>
                    <span className="text-muted-foreground">
                        الإجابة الصحيحة:{' '}
                    </span>
                    {question.correct_option_text}
                </p>
            </div>
            <div className="bg-muted/40 rounded-lg p-3 text-sm leading-6">
                <p className="mb-1 font-medium">التفسير</p>
                <p>{question.explanation}</p>
            </div>
        </article>
    );
}
