import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { PageHeader } from '@/components/page-header';
import { sitePages, sitePreview } from '@/lib/routes';
import {
    btnCancel,
    btnSecondary,
    btnSubmit,
    formActions,
    formCard,
} from '@/lib/ui';
import AppLayout from '@/layouts/app-layout.jsx';
import { Head, Link, useForm } from '@inertiajs/react';

export default function SitePagesEdit({ site, page }) {
    const form = useForm({
        slug: page.slug,
        title: page.title,
        sort_order: page.sort_order,
    });

    function submit(e) {
        e.preventDefault();
        form.patch(sitePages.update(site, page));
    }

    return (
        <>
            <Head title={`Page settings · ${page.title}`} />
            <PageHeader
                backHref={sitePages.show(site, page)}
                backLabel={`Back to ${page.title}`}
                title="Page settings"
                subtitle="Slug, title, and nav order."
                actions={
                    <a
                        href={sitePreview.page(site, page)}
                        target="_blank"
                        rel="noopener noreferrer"
                        className={btnSecondary}
                    >
                        Preview
                    </a>
                }
            />

            <form onSubmit={submit} className={formCard}>
                <FormErrors errors={form.errors} />

                <div className="space-y-4">
                    <div>
                        <Label htmlFor="page-edit-slug">Slug</Label>
                        <Input
                            id="page-edit-slug"
                            value={form.data.slug}
                            onChange={(e) =>
                                form.setData('slug', e.target.value)
                            }
                            required
                        />
                    </div>
                    <div>
                        <Label htmlFor="page-edit-title">Title</Label>
                        <Input
                            id="page-edit-title"
                            value={form.data.title}
                            onChange={(e) =>
                                form.setData('title', e.target.value)
                            }
                            required
                        />
                    </div>
                    <div>
                        <Label htmlFor="page-edit-sort_order">
                            Sort order
                        </Label>
                        <Input
                            id="page-edit-sort_order"
                            type="number"
                            min={0}
                            value={form.data.sort_order}
                            onChange={(e) =>
                                form.setData(
                                    'sort_order',
                                    Number(e.target.value),
                                )
                            }
                        />
                    </div>
                </div>

                <div className={formActions}>
                    <button
                        type="submit"
                        disabled={form.processing}
                        className={btnSubmit}
                    >
                        Save changes
                    </button>
                    <Link
                        href={sitePages.show(site, page)}
                        className={btnCancel}
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </>
    );
}

SitePagesEdit.layout = (page) => (
    <AppLayout width="form">{page}</AppLayout>
);
