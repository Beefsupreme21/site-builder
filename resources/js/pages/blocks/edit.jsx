import { BlockAiChat } from '@/components/block-ai-chat';
import { BlockLivePreview } from '@/components/block-live-preview';
import { FormErrors } from '@/components/form-errors';
import { HtmlEditor } from '@/components/html-editor';
import { PageHeader } from '@/components/page-header';
import {
    PrototypePicker,
    initialVariantIndexFromUrl,
} from '@/components/prototype-picker';
import { layoutBlocks, layouts, pageBlocks, sitePages } from '@/lib/routes';
import {
    btnCancel,
    btnPrimary,
    btnSubmit,
    formActions,
    formCard,
    formSectionTitle,
} from '@/lib/ui';
import { Head, Link, useForm } from '@inertiajs/react';
import { useCallback, useState } from 'react';

export default function BlocksEdit({
    site,
    page,
    layout,
    block,
    target = 'page',
    provider,
    model,
}) {
    const isLayout = target === 'layout';
    const backHref = isLayout
        ? layouts.show(site, layout)
        : sitePages.show(site, page);
    const backLabel = isLayout
        ? `Back to ${layout.name}`
        : `Back to ${page.title}`;
    const updateAction = isLayout
        ? layoutBlocks.update(layout, block)
        : pageBlocks.update(page, block);
    const prototypeUrl = isLayout
        ? layoutBlocks.prototype(layout, block)
        : pageBlocks.prototype(page, block);
    const blockLabel = block.template?.name ?? 'Block';

    const form = useForm({
        content: block.content ?? '',
    });
    const [variants, setVariants] = useState([]);
    const [activeVariantIndex, setActiveVariantIndex] = useState(0);
    const [previewMountKey, setPreviewMountKey] = useState(0);
    const [runMeta, setRunMeta] = useState({ provider, model });

    const handleVariantsGenerated = useCallback((nextVariants) => {
        setVariants(nextVariants);
        setActiveVariantIndex(initialVariantIndexFromUrl(nextVariants.length));
        setPreviewMountKey((key) => key + 1);
    }, []);

    const handleVariantChange = useCallback((index) => {
        setActiveVariantIndex(index);
    }, []);

    const handleReplay = useCallback(() => {
        setPreviewMountKey((key) => key + 1);
    }, []);

    function applyActiveVariant() {
        const html = variants[activeVariantIndex]?.html;

        if (!html) {
            return;
        }

        form.setData('content', html);
        setVariants([]);
        setActiveVariantIndex(0);

        const url = new URL(window.location.href);
        url.searchParams.delete('v');
        window.history.replaceState(null, '', url);
    }

    function submit(e) {
        e.preventDefault();
        form.put(updateAction);
    }

    return (
        <>
            <Head title={`Edit block · ${blockLabel}`} />
            <PageHeader
                backHref={backHref}
                backLabel={backLabel}
                title="Edit block"
                subtitle={blockLabel}
            />

            <div className="space-y-6">
                <BlockAiChat
                    prototypeUrl={prototypeUrl}
                    content={form.data.content}
                    onVariantsGenerated={handleVariantsGenerated}
                    onMeta={setRunMeta}
                    provider={runMeta.provider ?? provider}
                    model={runMeta.model ?? model}
                />

                <div className={formCard}>
                    <BlockLivePreview
                        content={form.data.content}
                        variants={variants.length > 0 ? variants : undefined}
                        activeIndex={activeVariantIndex}
                        onSelectVariant={handleVariantChange}
                        mountKey={
                            variants.length > 0
                                ? `variants-${previewMountKey}`
                                : 'editor'
                        }
                    />

                    {variants.length > 0 && (
                        <div className="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-neutral-100 pt-4">
                            <p className="text-sm text-neutral-600">
                                Compare all four above.{' '}
                                <span className="font-medium text-neutral-900">
                                    {variants[activeVariantIndex]?.name}
                                </span>
                                {' is selected — '}
                                <span className="text-neutral-500">
                                    picker or keys 1–4
                                </span>
                            </p>
                            <button
                                type="button"
                                onClick={applyActiveVariant}
                                className={btnPrimary}
                            >
                                Use this design
                            </button>
                        </div>
                    )}
                </div>

                <PrototypePicker
                    variants={variants}
                    activeIndex={activeVariantIndex}
                    onChange={handleVariantChange}
                    onReplay={handleReplay}
                    showReplay={false}
                />

                <form onSubmit={submit} className={formCard}>
                    <FormErrors errors={form.errors} />

                    <div>
                        <h2 className={formSectionTitle}>HTML</h2>
                        <p className="mt-2 text-sm text-neutral-600">
                            Fine-tune the markup manually after applying a
                            prototype, or edit directly.
                        </p>
                        <div className="mt-4">
                            <HtmlEditor
                                value={form.data.content}
                                onChange={(content) =>
                                    form.setData('content', content)
                                }
                                disabled={form.processing}
                            />
                        </div>
                    </div>

                    <div className={formActions}>
                        <button
                            type="submit"
                            disabled={form.processing}
                            className={btnSubmit}
                        >
                            {form.processing ? 'Saving…' : 'Save block'}
                        </button>
                        <Link href={backHref} className={btnCancel}>
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </>
    );
}
