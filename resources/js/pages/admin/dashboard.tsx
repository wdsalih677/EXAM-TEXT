import Heading from '@/components/heading';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { dashboard as adminDashboard } from '@/routes/admin';

type Props = {
    stats: {
        trainees_count: number;
        exams_count: number;
        completed_attempts_count: number;
        average_score: number;
    };
};

export default function AdminDashboard({ stats }: Props) {
    return (
        <div className="flex flex-col gap-6 p-4">
            <Heading
                title="لوحة المحامي"
                description="ملخص المتدربين والاختبارات والنتائج."
            />
            <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <StatCard title="المتدربون" value={stats.trainees_count} />
                <StatCard title="الاختبارات" value={stats.exams_count} />
                <StatCard
                    title="الاختبارات المكتملة"
                    value={stats.completed_attempts_count}
                />
                <StatCard
                    title="متوسط النتائج"
                    value={`${stats.average_score}%`}
                />
            </div>
        </div>
    );
}

function StatCard({ title, value }: { title: string; value: string | number }) {
    return (
        <Card>
            <CardHeader>
                <CardTitle className="text-muted-foreground text-sm font-medium">
                    {title}
                </CardTitle>
            </CardHeader>
            <CardContent>
                <p className="text-3xl font-semibold">{value}</p>
            </CardContent>
        </Card>
    );
}

AdminDashboard.layout = {
    breadcrumbs: [
        {
            title: 'لوحة التحكم',
            href: adminDashboard(),
        },
    ],
};
