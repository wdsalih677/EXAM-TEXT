import InputError from '@/components/input-error';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export type OptionFormValue = {
    text: string;
    is_correct: boolean;
};

type Props = {
    index: number;
    name: string;
    option: OptionFormValue;
    error?: string;
    onChange: (option: OptionFormValue) => void;
};

export default function OptionEditor({
    index,
    name,
    option,
    error,
    onChange,
}: Props) {
    return (
        <div className="flex items-start gap-3">
            <input
                type="radio"
                name={name}
                className="mt-3 size-4"
                checked={option.is_correct}
                onChange={() => onChange({ ...option, is_correct: true })}
                aria-label={`تعيين الخيار ${index + 1} كإجابة صحيحة`}
            />
            <div className="grid flex-1 gap-1">
                <Label htmlFor={`option-${index}`}>الخيار {index + 1}</Label>
                <Input
                    id={`option-${index}`}
                    value={option.text}
                    onChange={(event) =>
                        onChange({ ...option, text: event.target.value })
                    }
                    placeholder={`الخيار ${index + 1}`}
                />
                <InputError message={error} />
            </div>
        </div>
    );
}
