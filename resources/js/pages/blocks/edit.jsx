import { BlockAiChat } from '@/components/block-ai-chat';
import { BlockLivePreview } from '@/components/block-live-preview';
import { FormErrors } from '@/components/form-errors';
import { HtmlEditor } from '@/components/html-editor';
import { PageHeader } from '@/components/page-header';
import { layoutBlocks, layouts, pageBlocks, sitePages } from '@/lib/routes';
import {
    btnCancel,
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
    blockSkills = [],
    blockModels = [],
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
    const variantUrlBuilder = isLayout
        ? layoutBlocks.variant
        : pageBlocks.variant;
    const blockLabel = block.template?.name ?? 'Block';

    const form = useForm({
        content: block.content ?? '',
    });
    const [generatedLabel, setGeneratedLabel] = useState(null);
    const [previewMountKey, setPreviewMountKey] = useState(0);

    const variantUrl = useCallback(
        (skill) => variantUrlBuilder(isLayout ? layout : page, block, skill),
        [block, isLayout, layout, page, variantUrlBuilder],
    );

    const handleGenerated = useCallback(
        (variant) => {
            form.setData('content', variant.html);
            setGeneratedLabel(`${variant.name} (${variant.skill})`);
            setPreviewMountKey((key) => key + 1);
        },
        [form],
    );

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
                    skills={blockSkills}
                    models={blockModels}
                    defaultModel={model}
                    variantUrl={variantUrl}
                    content={form.data.content}
                    onGenerated={handleGenerated}
                    provider={provider}
                    model={model}
                />

                <div className={formCard}>
                    <BlockLivePreview
                        content={form.data.content}
                        generatedLabel={generatedLabel}
                        mountKey={previewMountKey}
                    />
                </div>

                <form onSubmit={submit} className={formCard}>
                    <FormErrors errors={form.errors} />

                    <div>
                        <h2 className={formSectionTitle}>HTML</h2>
                        <p className="mt-2 text-sm text-neutral-600">
                            AI results and manual edits stay in sync with the
                            preview above.
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
