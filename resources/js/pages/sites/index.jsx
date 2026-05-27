import { PageHeader } from '@/components/page-header';
import { sites } from '@/lib/routes';
import {
    btnDanger,
    btnPrimary,
    btnSecondary,
    emptyState,
    linkTitle,
} from '@/lib/ui';
import AppLayout from '@/layouts/app-layout.jsx';
import { Form, Head, Link } from '@inertiajs/react';

export default function SitesIndex({ sites: siteList }) {
    return (
        <>
            <Head title="Sites" />
            <PageHeader
                title="Sites"
                actions={
                    <Link href={sites.create()} className={btnPrimary}>
                        New site
                    </Link>
                }
            />

            {siteList.length === 0 ? (
                <p className={emptyState}>
                    No sites yet.{' '}
                    <Link href={sites.create()} className={linkTitle}>
                        Create one
                    </Link>
                    .
                </p>
            ) : (
                <div className="overflow-x-auto rounded-lg border border-neutral-200 bg-white">
                    <table className="min-w-full divide-y divide-neutral-200 text-sm">
                        <thead className="bg-neutral-50">
                            <tr>
                                <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                    Company
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
                            {siteList.map((site) => (
                                <tr key={site.id}>
                                    <td className="px-4 py-3">
                                        <Link
                                            href={sites.show(site)}
                                            className={linkTitle}
                                        >
                                            {site.company_name}
                                        </Link>
                                    </td>
                                    <td className="px-4 py-3 text-neutral-600">
                                        {site.slug}
                                    </td>
                                    <td className="px-4 py-3 text-right">
                                        <div className="flex justify-end gap-2">
                                            <Link
                                                href={sites.show(site)}
                                                className={btnSecondary}
                                            >
                                                Edit
                                            </Link>
                                            <Form
                                                action={sites.destroy(site)}
                                                method="delete"
                                                className="inline"
                                            >
                                                {({ processing }) => (
                                                    <button
                                                        type="submit"
                                                        disabled={processing}
                                                        className={btnDanger}
                                                        onClick={(e) => {
                                                            if (
                                                                !confirm(
                                                                    'Delete this site?',
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
        </>
    );
}

SitesIndex.layout = (page) => <AppLayout>{page}</AppLayout>;
