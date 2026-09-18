type Props = {
    id: number;
    text: string;
    selected: boolean;
    disabled?: boolean;
    onSelect: (id: number) => void;
};

export default function AnswerOption({
    id,
    text,
    selected,
    disabled = false,
    onSelect,
}: Props) {
    return (
        <label
            className={`flex cursor-pointer items-start gap-3 rounded-lg border p-4 transition-colors ${
                selected
                    ? 'border-primary bg-primary/5'
                    : 'hover:bg-muted/40'
            } ${disabled ? 'cursor-not-allowed opacity-70' : ''}`}
        >
            <input
                type="radio"
                name="selected_option"
                className="mt-1 size-4"
                checked={selected}
                disabled={disabled}
                onChange={() => onSelect(id)}
            />
            <span className="text-sm leading-6">{text}</span>
        </label>
    );
}
