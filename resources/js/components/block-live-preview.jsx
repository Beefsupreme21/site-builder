import { formSectionTitle } from '@/lib/ui';

export function BlockLivePreview({
    content,
    generatedLabel,
    mountKey = 'static',
}) {
    return (
        <section>
            <h2 className={formSectionTitle}>Preview</h2>
            {generatedLabel && (
                <p className="mt-2 text-sm text-neutral-600">
                    Last generated with{' '}
                    <span className="font-medium text-neutral-900">
                        {generatedLabel}
                    </span>
                </p>
            )}
            <div className="mt-4 overflow-hidden rounded-lg border border-neutral-200 bg-white">
                {content ? (
                    <div
                        key={mountKey}
                        className="block-preview-content"
                        dangerouslySetInnerHTML={{ __html: content }}
                    />
                ) : (
                    <p className="px-6 py-10 text-center text-sm text-neutral-500">
                        Preview will appear here as you edit or ask the AI.
                    </p>
                )}
            </div>
        </section>
    );
}
