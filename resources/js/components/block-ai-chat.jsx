import { Label } from '@/components/label';
import { parseJsonResponse } from '@/lib/parse-json-response';
import { btnSubmit, formCard, formSectionTitle } from '@/lib/ui';
import { usePage } from '@inertiajs/react';
import { useCallback, useState } from 'react';

export function BlockAiChat({ generateUrl, content, onGenerated }) {
    const { props } = usePage();
    const csrfToken = props.csrf_token;
    const [prompt, setPrompt] = useState('');
    const [isGenerating, setIsGenerating] = useState(false);
    const [error, setError] = useState(null);

    const generate = useCallback(async () => {
        const trimmed = prompt.trim();

        if (!trimmed || isGenerating) {
            return;
        }

        setError(null);
        setIsGenerating(true);

        try {
            const response = await fetch(generateUrl, {
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
                throw new Error(
                    data?.message ??
                        data?.error ??
                        'Could not generate block HTML.',
                );
            }

            onGenerated(data.html);
        } catch (generationError) {
            setError(generationError.message ?? 'Something went wrong.');
        } finally {
            setIsGenerating(false);
        }
    }, [content, csrfToken, generateUrl, isGenerating, onGenerated, prompt]);

    function handleSubmit(event) {
        event.preventDefault();
        generate();
    }

    return (
        <div className={formCard}>
            <div>
                <h2 className={formSectionTitle}>AI block</h2>
                <p className="mt-2 text-sm text-neutral-600">
                    Describe the block you want. Refactoring UI design rules
                    apply automatically.
                </p>
            </div>

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
                    {isGenerating ? 'Generating…' : 'Generate'}
                </button>
            </form>
        </div>
    );
}
