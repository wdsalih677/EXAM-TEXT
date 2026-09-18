import ExamForm from '@/components/exam/exam-form';
import Heading from '@/components/heading';
import { dashboard as adminDashboard } from '@/routes/admin';
import { create, index, store } from '@/routes/admin/exams';

type Category = {
    value: string;
    label: string;
};

type Props = {
    categories: Category[];
};

export default function CreateExam({ categories }: Props) {
    return (
        <div className="flex flex-col gap-6 p-4">
            <Heading
                title="إنشاء اختبار"
                description="أضف الأسئلة والخيارات وحدد الإجابة الصحيحة لكل سؤال."
            />
            <ExamForm
                categories={categories}
                onSubmit={(_data, form) => form.post(store.url())}
            />
        </div>
    );
}

CreateExam.layout = {
    breadcrumbs: [
        { title: 'لوحة التحكم', href: adminDashboard() },
        { title: 'الاختبارات', href: index() },
        { title: 'إنشاء', href: create() },
    ],
};
