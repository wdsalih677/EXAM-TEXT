import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Link } from '@inertiajs/react';
import { result, show } from '@/routes/trainee/attempts';
import { start } from '@/routes/trainee/exams';
import { dashboard } from '@/routes/trainee';

type Exam = {
    id: number;
    title: string;
    questions_count: number;
    seconds_per_question: number;
    attempt: {
        id: number;
        status: 'in_progress' | 'completed';
        score: string | null;
    } | null;
};

type Props = {
    exams: Exam[];
    completed_count: number;
};

export default function TraineeDashboard({ exams, completed_count }: Props) {
    return (
        <div className="flex flex-col gap-6 p-4">
            <Heading
                title="لوحة المتدرب"
                description="الاختبارات المتاحة لك ونتائجك السابقة."
            />

            <Card className="max-w-xs">
                <CardHeader>
                    <CardTitle className="text-muted-foreground text-sm font-medium">
                        الاختبارات المكتملة
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <p className="text-3xl font-semibold">{completed_count}</p>
                </CardContent>
            </Card>

            <div className="grid gap-4">
                {exams.length === 0 && (
                    <p className="text-muted-foreground">
                        لا يوجد اختبار متاح حالياً.
                    </p>
                )}
                {exams.map((exam) => (
                    <div
                        key={exam.id}
                        className="flex flex-col gap-4 rounded-xl border p-5 md:flex-row md:items-center md:justify-between"
                    >
                        <div>
                            <h2 className="font-semibold">{exam.title}</h2>
                            <p className="text-muted-foreground mt-1 text-sm">
                                {exam.questions_count} أسئلة —{' '}
                                {exam.seconds_per_question} ثانية لكل سؤال
                            </p>
                            {exam.attempt?.status === 'completed' && (
                                <p className="mt-2 text-sm">
                                    نتيجتك: {exam.attempt.score}%
                                </p>
                            )}
                        </div>
                        <div>
                            {exam.attempt?.status === 'completed' ? (
                                <Button asChild>
                                    <Link href={result(exam.attempt)}>
                                        النتيجة
                                    </Link>
                                </Button>
                            ) : exam.attempt?.status === 'in_progress' ? (
                                <Button asChild>
                                    <Link href={show(exam.attempt)}>
                                        متابعة الاختبار
                                    </Link>
                                </Button>
                            ) : (
                                <Button asChild>
                                    <Link
                                        href={start(exam)}
                                        method="post"
                                        as="button"
                                    >
                                        ابدأ الاختبار
                                    </Link>
                                </Button>
                            )}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

TraineeDashboard.layout = {
    breadcrumbs: [{ title: 'لوحة المتدرب', href: dashboard() }],
};
