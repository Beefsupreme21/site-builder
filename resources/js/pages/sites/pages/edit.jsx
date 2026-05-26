import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { pageBlocks, sitePages, sitePreview, sites } from '@/lib/routes';
import { Form, Head, Link, useForm } from '@inertiajs/react';

const btnSecondary =
    'inline-flex items-center justify-center rounded-md border border-neutral-300 bg-white px-3 py-1.5 text-sm font-medium text-neutral-800 shadow-sm hover:bg-neutral-50';

export default function SitePagesEdit({ site, page }) {
    const blocks = page.block_pages ?? [];

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
            <Head title={`Edit ${page.title}`} />
            <div className="mx-auto max-w-3xl px-4 py-8 sm:px-6">
                <header className="mb-8">
                    <p className="text-sm text-neutral-500">
                        <Link
                            href={sites.show(site)}
                            className="font-medium text-neutral-700 hover:text-neutral-900"
                        >
                            ← {site.company_name}
                        </Link>
                    </p>
                    <div className="mt-3 flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight text-neutral-900">
                                {page.title}
                            </h1>
                            <p className="mt-1 text-sm text-neutral-600">
                                /{page.slug}
                            </p>
                        </div>
                        <a
                            href={sitePreview.page(site, page)}
                            target="_blank"
                            rel="noopener noreferrer"
                            className={btnSecondary}
                        >
                            Preview page
                        </a>
                    </div>
                </header>

                <form
                    onSubmit={submit}
                    className="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm sm:p-8"
                >
                    <h2 className="border-b border-neutral-100 pb-2 text-xs font-semibold tracking-wide text-neutral-500 uppercase">
                        Page settings
                    </h2>

                    <FormErrors errors={form.errors} />

                    <div className="mt-4 space-y-4">
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

                    <div className="mt-6 flex flex-wrap gap-3 border-t border-neutral-100 pt-6">
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

                <section className="mt-10">
                    <div className="mb-4 flex flex-wrap items-center justify-between gap-4">
                        <h2 className="text-lg font-semibold text-neutral-900">
                            Blocks
                        </h2>
                        <Link
                            href={pageBlocks.create(site, page)}
                            className="rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800"
                        >
                            Add block
                        </Link>
                    </div>

                    {blocks.length === 0 ? (
                        <p className="rounded-lg border border-neutral-200 bg-white p-6 text-sm text-neutral-600">
                            No blocks yet.{' '}
                            <Link
                                href={pageBlocks.create(site, page)}
                                className="font-medium text-neutral-900 underline"
                            >
                                Add one
                            </Link>
                            .
                        </p>
                    ) : (
                        <ul className="space-y-3">
                            {blocks.map((block, index) => (
                                <li
                                    key={block.id}
                                    className="rounded-lg border border-neutral-200 bg-white"
                                >
                                    <div className="flex items-center justify-between gap-4 border-b border-neutral-100 px-4 py-3">
                                        <p className="text-sm font-medium text-neutral-800">
                                            Block {index + 1}
                                        </p>
                                        <Form
                                            action={pageBlocks.destroy(
                                                site,
                                                page,
                                                block,
                                            )}
                                            method="delete"
                                            className="inline"
                                        >
                                            {({ processing }) => (
                                                <button
                                                    type="submit"
                                                    disabled={processing}
                                                    className={`${btnSecondary} border-red-200 text-red-800 hover:bg-red-50`}
                                                    onClick={(e) => {
                                                        if (
                                                            !confirm(
                                                                'Remove this block?',
                                                            )
                                                        ) {
                                                            e.preventDefault();
                                                        }
                                                    }}
                                                >
                                                    Remove
                                                </button>
                                            )}
                                        </Form>
                                    </div>
                                    <div
                                        className="overflow-hidden"
                                        dangerouslySetInnerHTML={{
                                            __html: block.content,
                                        }}
                                    />
                                </li>
                            ))}
                        </ul>
                    )}
                </section>
            </div>
        </>
    );
}
