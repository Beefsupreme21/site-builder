import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { sitePages, sites } from '@/lib/routes';
import { Head, Link, useForm } from '@inertiajs/react';

const textareaClass =
    'mt-1.5 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm text-neutral-900 shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-neutral-400/30';

export default function SitePagesCreate({ site, nextSortOrder }) {
    const form = useForm({
        slug: '',
        title: '',
        content: '',
        sort_order: nextSortOrder,
    });

    function submit(e) {
        e.preventDefault();
        form.post(sitePages.store(site));
    }

    return (
        <>
            <Head title={`Add page · ${site.company_name}`} />
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
                        Add page
                    </h1>
                </header>

                <form
                    onSubmit={submit}
                    className="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm sm:p-8"
                >
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
                                className="max-w-full"
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
                                className="max-w-full"
                            />
                        </div>
                        <div>
                            <Label htmlFor="page-content">Content</Label>
                            <textarea
                                id="page-content"
                                rows={6}
                                value={form.data.content}
                                onChange={(e) =>
                                    form.setData('content', e.target.value)
                                }
                                className={textareaClass}
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
                            Create page
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
