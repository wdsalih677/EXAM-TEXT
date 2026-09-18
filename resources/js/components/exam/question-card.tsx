import AnswerOption from '@/components/exam/answer-option';

type Option = {
    id: number;
    text: string;
};

type Props = {
    scenarioText: string;
    categoryLabel: string;
    options: Option[];
    selectedOptionId: number | null;
    disabled?: boolean;
    onSelect: (optionId: number) => void;
};

export default function QuestionCard({
    scenarioText,
    categoryLabel,
    options,
    selectedOptionId,
    disabled = false,
    onSelect,
}: Props) {
    return (
        <div className="space-y-6">
            <div className="space-y-3">
                <span className="bg-secondary text-secondary-foreground inline-flex rounded-md px-2 py-1 text-xs font-medium">
                    {categoryLabel}
                </span>
                <p className="text-lg leading-8 whitespace-pre-wrap">
                    {scenarioText}
                </p>
            </div>
            <div className="grid gap-3">
                {options.map((option) => (
                    <AnswerOption
                        key={option.id}
                        id={option.id}
                        text={option.text}
                        selected={selectedOptionId === option.id}
                        disabled={disabled}
                        onSelect={onSelect}
                    />
                ))}
            </div>
        </div>
    );
}
