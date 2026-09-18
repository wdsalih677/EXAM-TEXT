import OptionEditor, {
    type OptionFormValue,
} from '@/components/exam/option-editor';
import InputError from '@/components/input-error';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

export type QuestionFormValue = {
    scenario_text: string;
    category: string;
    explanation: string;
    options: OptionFormValue[];
};

type Category = {
    value: string;
    label: string;
};

type Props = {
    index: number;
    question: QuestionFormValue;
    categories: Category[];
    errors: Record<string, string>;
    onChange: (question: QuestionFormValue) => void;
    onRemove: () => void;
    canRemove: boolean;
};

export default function QuestionEditor({
    index,
    question,
    categories,
    errors,
    onChange,
    onRemove,
    canRemove,
}: Props) {
    const prefix = `questions.${index}`;

    const updateOption = (optionIndex: number, option: OptionFormValue) => {
        const options = question.options.map((current, currentIndex) => {
            if (option.is_correct) {
                return currentIndex === optionIndex
                    ? option
                    : { ...current, is_correct: false };
            }

            return currentIndex === optionIndex ? option : current;
        });

        onChange({ ...question, options });
    };

    return (
        <section className="space-y-4 rounded-xl border p-4">
            <div className="flex items-center justify-between gap-3">
                <h3 className="font-medium">السؤال {index + 1}</h3>
                {canRemove && (
                    <button
                        type="button"
                        className="text-destructive text-sm"
                        onClick={onRemove}
                    >
                        حذف السؤال
                    </button>
                )}
            </div>

            <div className="grid gap-2">
                <Label htmlFor={`${prefix}-scenario`}>نص الحالة / السيناريو</Label>
                <Textarea
                    id={`${prefix}-scenario`}
                    value={question.scenario_text}
                    onChange={(event) =>
                        onChange({
                            ...question,
                            scenario_text: event.target.value,
                        })
                    }
                    rows={4}
                />
                <InputError message={errors[`${prefix}.scenario_text`]} />
            </div>

            <div className="grid gap-2">
                <Label>التصنيف</Label>
                <Select
                    value={question.category}
                    onValueChange={(value) =>
                        onChange({ ...question, category: value })
                    }
                >
                    <SelectTrigger className="w-full">
                        <SelectValue placeholder="اختر التصنيف" />
                    </SelectTrigger>
                    <SelectContent>
                        {categories.map((category) => (
                            <SelectItem
                                key={category.value}
                                value={category.value}
                            >
                                {category.label}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
                <InputError message={errors[`${prefix}.category`]} />
            </div>

            <div className="grid gap-3">
                <Label>الخيارات</Label>
                {question.options.map((option, optionIndex) => (
                    <OptionEditor
                        key={optionIndex}
                        index={optionIndex}
                        name={`correct-option-${index}`}
                        option={option}
                        error={
                            errors[`${prefix}.options.${optionIndex}.text`]
                        }
                        onChange={(next) => updateOption(optionIndex, next)}
                    />
                ))}
                <InputError message={errors[`${prefix}.options`]} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor={`${prefix}-explanation`}>تفسير الإجابة</Label>
                <Textarea
                    id={`${prefix}-explanation`}
                    value={question.explanation}
                    onChange={(event) =>
                        onChange({
                            ...question,
                            explanation: event.target.value,
                        })
                    }
                    rows={3}
                />
                <InputError message={errors[`${prefix}.explanation`]} />
            </div>
        </section>
    );
}
