import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { dashboard as adminDashboard } from '@/routes/admin';
import { create, destroy, edit, index } from '@/routes/admin/exams';

type Exam = {
    id: number;
    title: string;
    seconds_per_question: number;
    questions_count: number;
    attempts_count: number;
    can_edit: boolean;
};

type Props = {
    exams: Exam[];
};

export default function ExamsIndex({ exams }: Props) {
    return (
        <div className="flex flex-col gap-6 p-4">
            <div className="flex flex-wrap items-center justify-between gap-3">
                <Heading
                    title="الاختبارات"
                    description="إنشاء الاختبارات وإدارة أسئلتها."
                />
                <Button asChild>
                    <Link href={create()}>إنشاء اختبار</Link>
                </Button>
            </div>

            <div className="overflow-x-auto rounded-xl border">
                <table className="w-full text-sm">
                    <thead className="bg-muted/50 text-right">
                        <tr>
                            <th className="px-4 py-3 font-medium">العنوان</th>
                            <th className="px-4 py-3 font-medium">مدة السؤال</th>
                            <th className="px-4 py-3 font-medium">الأسئلة</th>
                            <th className="px-4 py-3 font-medium">المحاولات</th>
                            <th className="px-4 py-3 font-medium">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        {exams.length === 0 && (
                            <tr>
                                <td
                                    className="text-muted-foreground px-4 py-8 text-center"
                                    colSpan={5}
                                >
                                    لا توجد اختبارات بعد.
                                </td>
                            </tr>
                        )}
                        {exams.map((exam) => (
                            <tr key={exam.id} className="border-t">
                                <td className="px-4 py-3">{exam.title}</td>
                                <td className="px-4 py-3">
                                    {exam.seconds_per_question} ثانية
                                </td>
                                <td className="px-4 py-3">
                                    {exam.questions_count}
                                </td>
                                <td className="px-4 py-3">
                                    {exam.attempts_count}
                                </td>
                                <td className="px-4 py-3">
                                    <div className="flex gap-2">
                                        {exam.can_edit ? (
                                            <>
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                    asChild
                                                >
                                                    <Link href={edit(exam)}>
                                                        تعديل
                                                    </Link>
                                                </Button>
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    asChild
                                                >
                                                    <Link
                                                        href={destroy(exam)}
                                                        method="delete"
                                                        as="button"
                                                    >
                                                        حذف
                                                    </Link>
                                                </Button>
                                            </>
                                        ) : (
                                            <span className="text-muted-foreground text-xs">
                                                لا يمكن التعديل بعد بدء المحاولات
                                            </span>
                                        )}
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}

ExamsIndex.layout = {
    breadcrumbs: [
        { title: 'لوحة التحكم', href: adminDashboard() },
        { title: 'الاختبارات', href: index() },
    ],
};
