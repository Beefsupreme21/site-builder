import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { sitePages, sites } from '@/lib/routes';
import { Head, Link, useForm } from '@inertiajs/react';

const textareaClass =
    'mt-1.5 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm text-neutral-900 shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-neutral-400/30';

export default function SitePagesEdit({ site, page }) {
    const form = useForm({
        slug: page.slug,
        title: page.title,
        content: page.content ?? '',
        sort_order: page.sort_order,
    });

    function submit(e) {
        e.preventDefault();
        form.patch(sitePages.update(site, page));
    }

    return (
        <>
            <Head title={`Edit ${page.title}`} />
            <div className="mx-auto max-w-xl px-4 py-8 sm:px-6">
                <header className="mb-8">
                    <p className="text-sm text-neutral-500">
                        <Link
                            href={sites.show(site)}
                            className="font-medium text-neutral-700 hover:text-neutral-900"
                        >
                            ← {site.company_name}
                        </Link>
                    </p>
                    <h1 className="mt-3 text-2xl font-semibold tracking-tight text-neutral-900">
                        Edit page
                    </h1>
                    <p className="mt-1 text-sm text-neutral-600">
                        {page.title}
                    </p>
                </header>

                <form
                    onSubmit={submit}
                    className="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm sm:p-8"
                >
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
                                className="max-w-full"
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
                                className="max-w-full"
                            />
                        </div>
                        <div>
                            <Label htmlFor="page-edit-content">Content</Label>
                            <textarea
                                id="page-edit-content"
                                rows={6}
                                value={form.data.content}
                                onChange={(e) =>
                                    form.setData('content', e.target.value)
                                }
                                className={textareaClass}
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
                                className="max-w-full"
                            />
                        </div>
                    </div>

                    <div className="mt-8 flex flex-wrap gap-3 border-t border-neutral-100 pt-6">
                        <button
                            type="submit"
                            disabled={form.processing}
                            className="rounded-lg bg-neutral-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-neutral-800 disabled:opacity-60"
                        >
                            Save changes
                        </button>
                        <Link
                            href={sites.show(site)}
                            className="rounded-lg border border-neutral-300 bg-white px-5 py-2.5 text-sm font-medium text-neutral-800 shadow-sm hover:bg-neutral-50"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </>
    );
}
