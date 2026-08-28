import { formSectionTitle } from '@/lib/ui';

export function BlockLivePreview({ content, mountKey = 'static' }) {
    return (
        <section>
            <h2 className={formSectionTitle}>Preview</h2>
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
