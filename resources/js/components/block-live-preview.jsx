import { formSectionTitle } from '@/lib/ui';

export function BlockLivePreview({
    content,
    variants,
    activeIndex = 0,
    onSelectVariant,
    mountKey = 'static',
}) {
    return (
        <section>
            <h2 className={formSectionTitle}>Preview</h2>

            {variants?.length > 0 ? (
                <div className="mt-4 space-y-8">
                    {variants.map((variant, index) => {
                        const isActive = index === activeIndex;

                        return (
                            <div
                                key={`${variant.name}-${index}-${mountKey}`}
                                className={
                                    isActive
                                        ? 'overflow-hidden rounded-lg border-2 border-neutral-900 bg-white shadow-sm'
                                        : 'overflow-hidden rounded-lg border border-neutral-200 bg-white'
                                }
                            >
                                <button
                                    type="button"
                                    onClick={() => onSelectVariant?.(index)}
                                    className={`flex w-full items-baseline gap-2 border-b px-4 py-2.5 text-left text-sm transition-colors ${
                                        isActive
                                            ? 'border-neutral-900/10 bg-neutral-900 text-white'
                                            : 'border-neutral-100 bg-neutral-50 text-neutral-600 hover:bg-neutral-100'
                                    }`}
                                >
                                    <span
                                        className={
                                            isActive
                                                ? 'font-semibold text-white'
                                                : 'font-medium text-neutral-900'
                                        }
                                    >
                                        {variant.name}
                                    </span>
                                    <span
                                        className={
                                            isActive
                                                ? 'text-neutral-300'
                                                : 'text-neutral-500'
                                        }
                                    >
                                        {variant.axis}
                                    </span>
                                    {isActive && (
                                        <span className="ml-auto text-xs text-neutral-400">
                                            Selected
                                        </span>
                                    )}
                                </button>
                                <div
                                    className="block-preview-content"
                                    dangerouslySetInnerHTML={{
                                        __html: variant.html,
                                    }}
                                />
                            </div>
                        );
                    })}
                </div>
            ) : (
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
            )}
        </section>
    );
}
