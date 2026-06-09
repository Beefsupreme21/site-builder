import { Input } from '@/components/input';
import { Label } from '@/components/label';

const DEFAULT_COLOR = '#171717';

export function ColorInput({ id, label, value, onChange }) {
    const hex = value || DEFAULT_COLOR;

    function updateFromPicker(next) {
        onChange(next.toUpperCase());
    }

    return (
        <div>
            <Label htmlFor={id}>{label}</Label>
            <div className="mt-1 flex items-center gap-3">
                <input
                    type="color"
                    id={`${id}-picker`}
                    value={hex}
                    onChange={(e) => updateFromPicker(e.target.value)}
                    className="h-10 w-14 cursor-pointer rounded border border-neutral-300 bg-white p-1"
                />
                <Input
                    id={id}
                    value={value}
                    onChange={(e) => onChange(e.target.value.toUpperCase())}
                    placeholder="#2563EB"
                    className="font-mono"
                    maxLength={7}
                    required
                />
            </div>
        </div>
    );
}
