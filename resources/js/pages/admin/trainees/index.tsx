import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { dashboard as adminDashboard } from '@/routes/admin';
import {
    create,
    destroy,
    edit,
    index,
} from '@/routes/admin/trainees';

type Trainee = {
    id: number;
    name: string;
    email: string;
    role_label: string;
    created_at: string | null;
};

type Props = {
    trainees: Trainee[];
};

export default function TraineesIndex({ trainees }: Props) {
    return (
        <div className="flex flex-col gap-6 p-4">
            <div className="flex flex-wrap items-center justify-between gap-3">
                <Heading
                    title="المتدربون"
                    description="إضافة المتدربين وتعديل بياناتهم."
                />
                <Button asChild>
                    <Link href={create()}>إضافة متدرب</Link>
                </Button>
            </div>

            <div className="overflow-x-auto rounded-xl border">
                <table className="w-full text-sm">
                    <thead className="bg-muted/50 text-right">
                        <tr>
                            <th className="px-4 py-3 font-medium">الاسم</th>
                            <th className="px-4 py-3 font-medium">البريد</th>
                            <th className="px-4 py-3 font-medium">الحالة</th>
                            <th className="px-4 py-3 font-medium">تاريخ الإنشاء</th>
                            <th className="px-4 py-3 font-medium">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        {trainees.length === 0 && (
                            <tr>
                                <td
                                    className="text-muted-foreground px-4 py-8 text-center"
                                    colSpan={5}
                                >
                                    لا يوجد متدربون بعد.
                                </td>
                            </tr>
                        )}
                        {trainees.map((trainee) => (
                            <tr key={trainee.id} className="border-t">
                                <td className="px-4 py-3">{trainee.name}</td>
                                <td className="px-4 py-3">{trainee.email}</td>
                                <td className="px-4 py-3">
                                    {trainee.role_label}
                                </td>
                                <td className="px-4 py-3">
                                    {trainee.created_at}
                                </td>
                                <td className="px-4 py-3">
                                    <div className="flex gap-2">
                                        <Button variant="outline" size="sm" asChild>
                                            <Link href={edit(trainee)}>
                                                تعديل
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            asChild
                                        >
                                            <Link
                                                href={destroy(trainee)}
                                                method="delete"
                                                as="button"
                                            >
                                                حذف
                                            </Link>
                                        </Button>
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

TraineesIndex.layout = {
    breadcrumbs: [
        { title: 'لوحة التحكم', href: adminDashboard() },
        { title: 'المتدربون', href: index() },
    ],
};
