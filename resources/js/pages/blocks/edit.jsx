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
    const generateUrl = isLayout
        ? layoutBlocks.ai(layout, block)
        : pageBlocks.ai(page, block);
    const blockLabel = block.template?.name ?? 'Block';

    const form = useForm({
        content: block.content ?? '',
    });
    const [previewMountKey, setPreviewMountKey] = useState(0);

    const handleGenerated = useCallback(
        (html) => {
            form.setData('content', html);
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
                    generateUrl={generateUrl}
                    content={form.data.content}
                    onGenerated={handleGenerated}
                />

                <div className={formCard}>
                    <BlockLivePreview
                        content={form.data.content}
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
