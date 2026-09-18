import ExamProgress from '@/components/exam/exam-progress';
import QuestionCard from '@/components/exam/question-card';
import Timer from '@/components/exam/timer';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { Head, router } from '@inertiajs/react';
import { useCallback, useState } from 'react';
import { answer, next, timeout } from '@/routes/trainee/attempts';

type Props = {
    attempt: {
        id: number;
        status: string;
        current_question_index: number;
    };
    exam: {
        id: number;
        title: string;
        seconds_per_question: number;
    };
    question: {
        id: number;
        number: number;
        total: number;
        scenario_text: string;
        category_label: string;
        options: { id: number; text: string }[];
    };
    selected_option_id: number | null;
    server_now: string;
    question_started_at: string;
    question_deadline: string;
    remaining_seconds: number;
};

export default function TakeExam({
    attempt,
    exam,
    question,
    selected_option_id,
    server_now,
    question_deadline,
}: Props) {
    const [saving, setSaving] = useState(false);
    const [advancing, setAdvancing] = useState(false);

    const handleExpire = useCallback(() => {
        router.post(
            timeout.url(attempt),
            { question_id: question.id },
            { preserveScroll: true },
        );
    }, [attempt, question.id]);

    const handleSelect = (optionId: number) => {
        if (saving || advancing || selected_option_id !== null) {
            return;
        }

        setSaving(true);
        router.post(
            answer.url(attempt),
            { option_id: optionId },
            {
                preserveScroll: true,
                onFinish: () => setSaving(false),
            },
        );
    };

    const handleNext = () => {
        if (advancing || selected_option_id === null) {
            return;
        }

        setAdvancing(true);
        router.post(next.url(attempt), {}, { onFinish: () => setAdvancing(false) });
    };

    return (
        <>
            <Head title={exam.title} />
            <div className="mx-auto flex max-w-3xl flex-col gap-6 p-4">
                <div>
                    <p className="text-muted-foreground text-sm">{exam.title}</p>
                    <h1 className="mt-1 text-xl font-semibold">الاختبار</h1>
                </div>

                <ExamProgress current={question.number} total={question.total} />

                <Timer
                    key={question.id}
                    deadline={question_deadline}
                    serverNow={server_now}
                    onExpire={handleExpire}
                />

                <QuestionCard
                    scenarioText={question.scenario_text}
                    categoryLabel={question.category_label}
                    options={question.options}
                    selectedOptionId={selected_option_id}
                    disabled={saving || advancing}
                    onSelect={handleSelect}
                />

                {selected_option_id !== null && (
                    <Button
                        className="self-start"
                        onClick={handleNext}
                        disabled={advancing}
                    >
                        {advancing && <Spinner />}
                        السؤال التالي
                    </Button>
                )}
            </div>
        </>
    );
}
