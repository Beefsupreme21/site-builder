import { FormErrors } from '@/components/form-errors';
import { Input } from '@/components/input';
import { Label } from '@/components/label';
import { PageHeader } from '@/components/page-header';
import { sitePages, sites } from '@/lib/routes';
import {
    btnCancel,
    btnSubmit,
    formActions,
    formCard,
} from '@/lib/ui';
import AppLayout from '@/layouts/app-layout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function SitePagesCreate({ site, nextSortOrder }) {
    const form = useForm({
        slug: '',
        title: '',
        sort_order: nextSortOrder,
    });

    function submit(e) {
        e.preventDefault();
        form.post(sitePages.store(site));
    }

    return (
        <>
            <Head title={`Add page · ${site.company_name}`} />
            <PageHeader
                backHref={sites.show(site)}
                backLabel={`Back to ${site.company_name}`}
                title="Add page"
            />

            <form onSubmit={submit} className={formCard}>
                <FormErrors errors={form.errors} />

                <div className="space-y-4">
                    <div>
                        <Label htmlFor="page-slug">Slug</Label>
                        <p className="mt-0.5 text-xs text-neutral-500">
                            URL segment, e.g.{' '}
                            <code className="rounded bg-neutral-100 px-1 py-0.5">
                                store
                            </code>{' '}
                            → /preview/{site.slug}/store
                        </p>
                        <Input
                            id="page-slug"
                            value={form.data.slug}
                            onChange={(e) =>
                                form.setData('slug', e.target.value)
                            }
                            required
                            placeholder="store"
                        />
                    </div>
                    <div>
                        <Label htmlFor="page-title">Title</Label>
                        <Input
                            id="page-title"
                            value={form.data.title}
                            onChange={(e) =>
                                form.setData('title', e.target.value)
                            }
                            required
                        />
                    </div>
                    <div>
                        <Label htmlFor="page-sort_order">Sort order</Label>
                        <Input
                            id="page-sort_order"
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
                        Create page
                    </button>
                    <Link href={sites.show(site)} className={btnCancel}>
                        Cancel
                    </Link>
                </div>
            </form>
        </>
    );
}

SitePagesCreate.layout = (page) => (
    <AppLayout width="form">{page}</AppLayout>
);
