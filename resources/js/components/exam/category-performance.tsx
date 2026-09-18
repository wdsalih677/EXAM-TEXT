type CategoryStat = {
    category: string;
    label: string;
    total: number;
    correct: number;
    wrong: number;
    unanswered: number;
    percentage: number;
};

type Props = {
    categories: CategoryStat[];
};

export default function CategoryPerformance({ categories }: Props) {
    if (categories.length === 0) {
        return null;
    }

    return (
        <div className="grid gap-4 md:grid-cols-2">
            {categories.map((category) => (
                <div
                    key={category.category}
                    className="rounded-xl border p-4"
                >
                    <h3 className="font-semibold">{category.label}</h3>
                    <p className="text-muted-foreground mt-1 text-sm">
                        {category.total} أسئلة
                    </p>
                    <p className="mt-3 text-2xl font-semibold">
                        {category.percentage}%
                    </p>
                    <div className="text-muted-foreground mt-2 space-y-1 text-sm">
                        <p>{category.correct} صحيح</p>
                        <p>{category.wrong} خطأ</p>
                        <p>{category.unanswered} غير مجاب</p>
                    </div>
                </div>
            ))}
        </div>
    );
}
