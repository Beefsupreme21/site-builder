export function FormErrors({ errors }) {
    const entries = Object.entries(errors).filter(([, message]) =>
        Boolean(message),
    );

    if (entries.length === 0) {
        return null;
    }

    return (
        <div
            role="alert"
            className="rounded-md border border-red-200 bg-red-50 p-4"
        >
            <p className="text-sm font-medium text-red-800">
                Please fix the following:
            </p>
            <ul className="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                {entries.map(([key, message]) => (
                    <li key={key}>{message}</li>
                ))}
            </ul>
        </div>
    );
}
