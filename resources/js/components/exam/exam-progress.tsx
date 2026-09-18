type Props = {
    current: number;
    total: number;
};

export default function ExamProgress({ current, total }: Props) {
    const percent = total > 0 ? (current / total) * 100 : 0;

    return (
        <div className="space-y-2">
            <div className="flex items-center justify-between text-sm">
                <span className="font-medium">
                    السؤال {current} من {total}
                </span>
                <span className="text-muted-foreground">
                    {Math.round(percent)}%
                </span>
            </div>
            <div className="bg-muted h-2 overflow-hidden rounded-full">
                <div
                    className="bg-primary h-full rounded-full transition-all"
                    style={{ width: `${percent}%` }}
                />
            </div>
        </div>
    );
}
