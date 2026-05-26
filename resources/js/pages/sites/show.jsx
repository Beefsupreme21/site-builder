import { SiteShowHeader } from '@/components/sites/site-show-header';
import { sitePages, sites } from '@/lib/routes';
import { btnDanger, btnPrimary, btnSecondary } from '@/lib/ui';
import { Form, Head, Link } from '@inertiajs/react';

export default function SitesShow({ site }) {
    const pages = site.pages ?? [];

    return (
        <>
            <Head title={site.company_name} />
            <div className="mx-auto max-w-5xl px-4 py-8 sm:px-6">
                <SiteShowHeader site={site} />

                <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <h2 className="text-lg font-semibold text-neutral-900">
                        Pages
                    </h2>
                    <Link href={sitePages.create(site)} className={btnPrimary}>
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
                                                href={sitePages.show(
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
                                        <td className="px-4 py-3 text-right">
                                            <div className="flex justify-end gap-2">
                                                <Link
                                                    href={sitePages.show(
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
                                                            className={
                                                                btnDanger
                                                            }
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
