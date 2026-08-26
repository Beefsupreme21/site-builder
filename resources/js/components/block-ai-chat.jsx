import { Label } from '@/components/label';
import { parseJsonResponse } from '@/lib/parse-json-response';
import { btnSubmit, formCard, formSectionTitle } from '@/lib/ui';
import { usePage } from '@inertiajs/react';
import { useCallback, useState } from 'react';

export function BlockAiChat({
    prototypeUrl,
    content,
    onVariantsGenerated,
    provider,
    model,
    onMeta,
}) {
    const { props } = usePage();
    const csrfToken = props.csrf_token;
    const [prompt, setPrompt] = useState('');
    const [messages, setMessages] = useState([]);
    const [isGenerating, setIsGenerating] = useState(false);
    const [error, setError] = useState(null);

    const send = useCallback(async () => {
        const trimmed = prompt.trim();

        if (!trimmed || isGenerating) {
            return;
        }

        setError(null);
        setIsGenerating(true);
        setMessages((current) => [
            ...current,
            { role: 'user', content: trimmed },
        ]);
        setPrompt('');

        try {
            const response = await fetch(prototypeUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    prompt: trimmed,
                    content,
                }),
            });

            const data = await parseJsonResponse(response);

            if (!response.ok) {
                const message =
                    data?.message ??
                    data?.errors?.prompt?.[0] ??
                    data?.error ??
                    'Could not generate variants.';
                throw new Error(message);
            }

            onVariantsGenerated(data.variants ?? []);

            if (data.meta && onMeta) {
                onMeta(data.meta);
            }

            const usedModel = data.meta?.model ?? model;
            const usedProvider = data.meta?.provider ?? provider;

            setMessages((current) => [
                ...current,
                {
                    role: 'assistant',
                    content: `Generated ${data.variants?.length ?? 0} directions (${usedProvider} · ${usedModel}). Flip through with the picker, then apply your favorite.`,
                },
            ]);
        } catch (generationError) {
            setError(generationError.message ?? 'Something went wrong.');
        } finally {
            setIsGenerating(false);
        }
    }, [
        content,
        csrfToken,
        isGenerating,
        model,
        onMeta,
        onVariantsGenerated,
        prompt,
        provider,
        prototypeUrl,
    ]);

    function handleSubmit(event) {
        event.preventDefault();
        send();
    }

    return (
        <div className={formCard}>
            <div className="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 className={formSectionTitle}>AI prototype</h2>
                    <p className="mt-2 text-sm text-neutral-600">
                        Four directions via{' '}
                        <a
                            href="https://github.com/emilkowalski/skills/tree/main/skills/prototype"
                            className="font-medium text-neutral-800 underline-offset-2 hover:underline"
                            target="_blank"
                            rel="noreferrer"
                        >
                            Emil Kowalski&apos;s prototype skill
                        </a>
                        . Picker spec is vendored locally in{' '}
                        <code className="rounded bg-neutral-100 px-1 text-xs">
                            resources/ai/skills/emilkowalski/prototype/
                        </code>
                        .
                    </p>
                </div>
                <p className="text-xs text-neutral-500">
                    Config: {provider} · {model}
                </p>
            </div>

            {messages.length > 0 && (
                <ul className="mt-4 max-h-48 space-y-2 overflow-y-auto rounded-lg border border-neutral-100 bg-neutral-50 p-3 text-sm">
                    {messages.map((message, index) => (
                        <li
                            key={`${message.role}-${index}`}
                            className={
                                message.role === 'user'
                                    ? 'text-neutral-900'
                                    : 'text-neutral-600'
                            }
                        >
                            <span className="font-medium capitalize">
                                {message.role}:
                            </span>{' '}
                            {message.content}
                        </li>
                    ))}
                </ul>
            )}

            <form onSubmit={handleSubmit} className="mt-4 space-y-3">
                <div>
                    <Label htmlFor="ai-prompt">Prompt</Label>
                    <textarea
                        id="ai-prompt"
                        rows={3}
                        value={prompt}
                        onChange={(event) => setPrompt(event.target.value)}
                        placeholder="Give me a cool hero for my financial site…"
                        className="mt-1 block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-1 focus:ring-neutral-500"
                        disabled={isGenerating}
                    />
                </div>

                {error && (
                    <p className="text-sm text-red-700" role="alert">
                        {error}
                    </p>
                )}

                <button
                    type="submit"
                    disabled={isGenerating || !prompt.trim()}
                    className={btnSubmit}
                >
                    {isGenerating
                        ? 'Generating 4 directions… (can take 1–3 min on free models)'
                        : 'Generate 4 variants'}
                </button>
            </form>
        </div>
    );
}
