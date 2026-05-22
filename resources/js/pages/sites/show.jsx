import { sitePages, sitePreview, sites } from '@/lib/routes';
import { Form, Head, Link } from '@inertiajs/react';

const btnSecondary =
    'inline-flex items-center justify-center rounded-md border border-neutral-300 bg-white px-3 py-1.5 text-sm font-medium text-neutral-800 shadow-sm hover:bg-neutral-50';

export default function SitesShow({ site }) {
    const pages = site.pages ?? [];

    return (
        <>
            <Head title={site.company_name} />
            <div className="mx-auto max-w-5xl px-4 py-8 sm:px-6">
                <header className="mb-8">
                    <p className="text-sm text-neutral-500">
                        <Link
                            href={sites.index()}
                            className="font-medium text-neutral-700 hover:text-neutral-900"
                        >
                            ← Sites
                        </Link>
                    </p>
                    <div className="mt-3 flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight text-neutral-900">
                                {site.company_name}
                            </h1>
                            <p className="mt-1 text-sm text-neutral-600">
                                {site.slug} · {site.template}
                            </p>
                        </div>
                        <div className="flex flex-wrap gap-2">
                            <a
                                href={sitePreview.home(site)}
                                target="_blank"
                                rel="noopener noreferrer"
                                className={btnSecondary}
                            >
                                Preview site
                            </a>
                            <Link
                                href={sites.edit(site)}
                                className={btnSecondary}
                            >
                                Site settings
                            </Link>
                        </div>
                    </div>
                </header>

                <div className="mb-4 flex flex-wrap items-center justify-between gap-4">
                    <h2 className="text-lg font-semibold text-neutral-900">
                        Pages
                    </h2>
                    <Link
                        href={sitePages.create(site)}
                        className="rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800"
                    >
                        Add page
                    </Link>
                </div>

                {pages.length === 0 ? (
                    <p className="rounded-lg border border-neutral-200 bg-white p-6 text-sm text-neutral-600">
                        No pages yet.{' '}
                        <Link
                            href={sitePages.create(site)}
                            className="font-medium text-neutral-900 underline"
                        >
                            Add one
                        </Link>
                        .
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-lg border border-neutral-200 bg-white">
                        <table className="min-w-full divide-y divide-neutral-200 text-sm">
                            <thead className="bg-neutral-50">
                                <tr>
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Title
                                    </th>
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Slug
                                    </th>
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Order
                                    </th>
                                    <th className="px-4 py-3 text-right font-medium text-neutral-700">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-neutral-200">
                                {pages.map((page) => (
                                    <tr key={page.id} className="bg-white">
                                        <td className="px-4 py-3">
                                            <Link
                                                href={sitePages.edit(
                                                    site,
                                                    page,
                                                )}
                                                className="font-medium text-neutral-900 underline-offset-2 hover:underline"
                                            >
                                                {page.title}
                                            </Link>
                                        </td>
                                        <td className="px-4 py-3 text-neutral-600">
                                            {page.slug}
                                        </td>
                                        <td className="px-4 py-3 text-neutral-600">
                                            {page.sort_order}
                                        </td>
                                        <td className="px-4 py-3 text-right">
                                            <div className="flex justify-end gap-2">
                                                <a
                                                    href={sitePreview.page(
                                                        site,
                                                        page,
                                                    )}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className={btnSecondary}
                                                >
                                                    Preview
                                                </a>
                                                <Link
                                                    href={sitePages.edit(
                                                        site,
                                                        page,
                                                    )}
                                                    className={btnSecondary}
                                                >
                                                    Edit
                                                </Link>
                                                <Form
                                                    action={sitePages.destroy(
                                                        site,
                                                        page,
                                                    )}
                                                    method="delete"
                                                    className="inline"
                                                >
                                                    {({ processing }) => (
                                                        <button
                                                            type="submit"
                                                            disabled={
                                                                processing
                                                            }
                                                            className={`${btnSecondary} border-red-200 text-red-800 hover:bg-red-50`}
                                                            onClick={(e) => {
                                                                if (
                                                                    !confirm(
                                                                        `Delete “${page.title}”?`,
                                                                    )
                                                                ) {
                                                                    e.preventDefault();
                                                                }
                                                            }}
                                                        >
                                                            Delete
                                                        </button>
                                                    )}
                                                </Form>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>
        </>
    );
}
