import { Label } from '@/components/label';
import { parseJsonResponse } from '@/lib/parse-json-response';
import { btnSubmit, formCard, formSectionTitle } from '@/lib/ui';
import { usePage } from '@inertiajs/react';
import { useCallback, useState } from 'react';

export function BlockAiChat({
    skills,
    models,
    defaultModel,
    variantUrl,
    content,
    onGenerated,
    provider,
    model,
}) {
    const { props } = usePage();
    const csrfToken = props.csrf_token;
    const [skill, setSkill] = useState(skills[0]?.skill ?? '');
    const [selectedModel, setSelectedModel] = useState(
        models.some((entry) => entry.model === defaultModel)
            ? defaultModel
            : (models[0]?.model ?? defaultModel ?? ''),
    );
    const [prompt, setPrompt] = useState('');
    const [isGenerating, setIsGenerating] = useState(false);
    const [error, setError] = useState(null);
    const [lastMeta, setLastMeta] = useState(null);

    const selectedSkill = skills.find((entry) => entry.skill === skill);
    const selectedModelEntry = models.find(
        (entry) => entry.model === selectedModel,
    );

    const generate = useCallback(async () => {
        const trimmed = prompt.trim();

        if (!trimmed || !skill || !selectedModel || isGenerating) {
            return;
        }

        setError(null);
        setIsGenerating(true);

        try {
            const response = await fetch(variantUrl(skill), {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    prompt: trimmed,
                    content,
                    model: selectedModel,
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

            setLastMeta(data.meta ?? null);
            onGenerated(data.variant, data.meta);
        } catch (generationError) {
            setError(generationError.message ?? 'Something went wrong.');
        } finally {
            setIsGenerating(false);
        }
    }, [
        content,
        csrfToken,
        isGenerating,
        onGenerated,
        prompt,
        selectedModel,
        skill,
        variantUrl,
    ]);

    function handleSubmit(event) {
        event.preventDefault();
        generate();
    }

    const usedModel = lastMeta?.model ?? selectedModel ?? model;
    const usedProvider = lastMeta?.provider ?? provider;

    return (
        <div className={formCard}>
            <div className="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 className={formSectionTitle}>AI block</h2>
                    <p className="mt-2 text-sm text-neutral-600">
                        Pick a skill and model, prompt once, preview the
                        result. Compare combinations to find what works best.
                    </p>
                </div>
                <p className="text-xs text-neutral-500">
                    Last run: {usedProvider} · {usedModel}
                </p>
            </div>

            <form onSubmit={handleSubmit} className="mt-4 space-y-3">
                <div className="grid gap-3 sm:grid-cols-2">
                    <div>
                        <Label htmlFor="ai-skill">Skill</Label>
                        <select
                            id="ai-skill"
                            value={skill}
                            onChange={(event) => setSkill(event.target.value)}
                            disabled={isGenerating}
                            className="mt-1 block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-1 focus:ring-neutral-500"
                        >
                            {skills.map((entry) => (
                                <option key={entry.skill} value={entry.skill}>
                                    {entry.name} — {entry.description}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <Label htmlFor="ai-model">Model</Label>
                        <select
                            id="ai-model"
                            value={selectedModel}
                            onChange={(event) =>
                                setSelectedModel(event.target.value)
                            }
                            disabled={isGenerating}
                            className="mt-1 block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-1 focus:ring-neutral-500"
                        >
                            {models.map((entry) => (
                                <option key={entry.model} value={entry.model}>
                                    {entry.name} — {entry.description}
                                </option>
                            ))}
                        </select>
                    </div>
                </div>

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
                    {(selectedSkill || selectedModelEntry) && (
                        <p className="mt-1 text-xs text-neutral-500">
                            {selectedSkill && (
                                <>
                                    Skill{' '}
                                    <code className="rounded bg-neutral-100 px-1">
                                        {selectedSkill.skill}
                                    </code>
                                </>
                            )}
                            {selectedSkill && selectedModelEntry && ' · '}
                            {selectedModelEntry && (
                                <>
                                    model{' '}
                                    <code className="rounded bg-neutral-100 px-1">
                                        {selectedModelEntry.model}
                                    </code>
                                </>
                            )}
                        </p>
                    )}
                </div>

                {error && (
                    <p className="text-sm text-red-700" role="alert">
                        {error}
                    </p>
                )}

                <button
                    type="submit"
                    disabled={
                        isGenerating || !prompt.trim() || !skill || !selectedModel
                    }
                    className={btnSubmit}
                >
                    {isGenerating ? 'Generating…' : 'Generate'}
                </button>
            </form>
        </div>
    );
}
