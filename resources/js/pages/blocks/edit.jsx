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

    const form = useForm({
        content: block.content ?? '',
    });

    function submit(e) {
        e.preventDefault();
        form.put(updateAction);
    }

    return (
        <>
            <Head title={`Edit block · ${isLayout ? layout.name : page.title}`} />
            <PageHeader
                backHref={backHref}
                backLabel={backLabel}
                title="Edit block"
                subtitle="HTML content"
            />

            <form onSubmit={submit} className={formCard}>
                <FormErrors errors={form.errors} />

                <div>
                    <h2 className={formSectionTitle}>HTML</h2>
                    <p className="mt-2 text-sm text-neutral-600">
                        Edit the markup for this block. Changes appear in preview
                        after you save.
                    </p>
                    <div className="mt-4">
                        <HtmlEditor
                            value={form.data.content}
                            onChange={(content) => form.setData('content', content)}
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
        </>
    );
}
