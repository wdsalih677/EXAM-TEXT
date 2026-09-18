import ExamForm from '@/components/exam/exam-form';
import Heading from '@/components/heading';
import { setLayoutProps } from '@inertiajs/react';
import { dashboard as adminDashboard } from '@/routes/admin';
import { edit, index, update } from '@/routes/admin/exams';
import type { QuestionFormValue } from '@/components/exam/question-editor';

type Category = {
    value: string;
    label: string;
};

type Props = {
    categories: Category[];
    exam: {
        id: number;
        title: string;
        seconds_per_question: number;
        questions: QuestionFormValue[];
    };
};

export default function EditExam({ categories, exam }: Props) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'لوحة التحكم', href: adminDashboard() },
            { title: 'الاختبارات', href: index() },
            { title: 'تعديل', href: edit(exam) },
        ],
    });

    return (
        <div className="flex flex-col gap-6 p-4">
            <Heading
                title="تعديل الاختبار"
                description="لا يمكن تعديل اختبار بدأ متدرب بحلّه."
            />
            <ExamForm
                categories={categories}
                exam={{
                    title: exam.title,
                    seconds_per_question: exam.seconds_per_question,
                    questions: exam.questions,
                }}
                onSubmit={(_data, form) => form.put(update.url(exam))}
            />
        </div>
    );
}
