import QuestionRepeater, { emptyQuestion } from '@/components/exam/question-repeater';
import type { QuestionFormValue } from '@/components/exam/question-editor';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';

type Category = {
    value: string;
    label: string;
};

type ExamFormData = {
    title: string;
    seconds_per_question: number;
    questions: QuestionFormValue[];
};

type Props = {
    categories: Category[];
    exam?: ExamFormData;
    onSubmit: (
        data: ExamFormData,
        form: ReturnType<typeof useForm<ExamFormData>>,
    ) => void;
};

export default function ExamForm({ categories, exam, onSubmit }: Props) {
    const form = useForm<ExamFormData>({
        title: exam?.title ?? '',
        seconds_per_question: exam?.seconds_per_question ?? 60,
        questions: exam?.questions ?? [emptyQuestion()],
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        onSubmit(form.data, form);
    };

    return (
        <form onSubmit={submit} className="space-y-8">
            <div className="grid gap-4 md:grid-cols-2">
                <div className="grid gap-2">
                    <Label htmlFor="title">عنوان الاختبار</Label>
                    <Input
                        id="title"
                        value={form.data.title}
                        onChange={(event) =>
                            form.setData('title', event.target.value)
                        }
                        placeholder="اختبار الأحوال الشخصية - المستوى الأول"
                    />
                    <InputError message={form.errors.title} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="seconds_per_question">
                        مدة السؤال (بالثواني)
                    </Label>
                    <Input
                        id="seconds_per_question"
                        type="number"
                        min={10}
                        max={600}
                        value={form.data.seconds_per_question}
                        onChange={(event) =>
                            form.setData(
                                'seconds_per_question',
                                Number(event.target.value),
                            )
                        }
                    />
                    <InputError message={form.errors.seconds_per_question} />
                </div>
            </div>

            <div className="space-y-3">
                <div>
                    <h2 className="font-semibold">الأسئلة</h2>
                    <p className="text-muted-foreground text-sm">
                        حدد إجابة صحيحة واحدة لكل سؤال.
                    </p>
                </div>
                <InputError message={form.errors.questions} />
                <QuestionRepeater
                    questions={form.data.questions}
                    categories={categories}
                    errors={form.errors}
                    onChange={(questions) => form.setData('questions', questions)}
                />
            </div>

            <Button type="submit" disabled={form.processing}>
                {form.processing && <Spinner />}
                حفظ الاختبار
            </Button>
        </form>
    );
}
