import QuestionEditor, {
    type QuestionFormValue,
} from '@/components/exam/question-editor';
import { Button } from '@/components/ui/button';

type Category = {
    value: string;
    label: string;
};

type Props = {
    questions: QuestionFormValue[];
    categories: Category[];
    errors: Record<string, string>;
    onChange: (questions: QuestionFormValue[]) => void;
};

export function emptyQuestion(): QuestionFormValue {
    return {
        scenario_text: '',
        category: 'custody',
        explanation: '',
        options: [
            { text: '', is_correct: true },
            { text: '', is_correct: false },
            { text: '', is_correct: false },
            { text: '', is_correct: false },
        ],
    };
}

export default function QuestionRepeater({
    questions,
    categories,
    errors,
    onChange,
}: Props) {
    return (
        <div className="space-y-4">
            {questions.map((question, index) => (
                <QuestionEditor
                    key={index}
                    index={index}
                    question={question}
                    categories={categories}
                    errors={errors}
                    canRemove={questions.length > 1}
                    onChange={(next) => {
                        const copy = [...questions];
                        copy[index] = next;
                        onChange(copy);
                    }}
                    onRemove={() =>
                        onChange(questions.filter((_, current) => current !== index))
                    }
                />
            ))}
            <Button
                type="button"
                variant="outline"
                onClick={() => onChange([...questions, emptyQuestion()])}
            >
                إضافة سؤال
            </Button>
        </div>
    );
}
