import {
    create,
    destroy,
    edit,
    preview,
} from '@/actions/App/Http/Controllers/SiteController';
import type { Site } from '@/types/site';
import { Form, Head, Link } from '@inertiajs/react';

const btnSecondary =
    'inline-flex items-center justify-center rounded-md border border-neutral-300 bg-white px-3 py-1.5 text-sm font-medium text-neutral-800 shadow-sm hover:bg-neutral-50';

export default function SitesIndex({ sites }: { sites: Site[] }) {
    return (
        <>
            <Head title="Sites" />
            <div className="mx-auto max-w-5xl p-6">
                <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <h1 className="text-2xl font-semibold text-neutral-900">
                        Sites
                    </h1>
                    <Link
                        href={create.url()}
                        className="rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800"
                    >
                        New site
                    </Link>
                </div>

                {sites.length === 0 ? (
                    <p className="text-neutral-600">
                        No sites yet.{' '}
                        <Link
                            href={create.url()}
                            className="font-medium text-neutral-900 underline"
                        >
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
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Template
                                    </th>
                                    <th className="px-4 py-3 text-right font-medium text-neutral-700">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-neutral-200">
                                {sites.map((site) => (
                                    <tr key={site.id} className="bg-white">
                                        <td className="px-4 py-3">
                                            <Link
                                                href={edit.url(site)}
                                                className="font-medium text-neutral-900 underline-offset-2 hover:underline"
                                            >
                                                {site.company_name}
                                            </Link>
                                        </td>
                                        <td className="px-4 py-3 text-neutral-600">
                                            {site.slug}
                                        </td>
                                        <td className="px-4 py-3 text-neutral-600">
                                            {site.template}
                                        </td>
                                        <td className="px-4 py-3 text-right">
                                            <div className="flex justify-end gap-2">
                                                <a
                                                    href={preview.url(site)}
                                                    className={btnSecondary}
                                                >
                                                    Preview
                                                </a>
                                                <Link
                                                    href={edit.url(site)}
                                                    className={btnSecondary}
                                                >
                                                    Edit
                                                </Link>
                                                <Form
                                                    {...destroy.form.delete(
                                                        site,
                                                    )}
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

                <p className="mt-8">
                    <Link
                        href="/"
                        className="text-sm text-neutral-600 underline"
                    >
                        ← Home
                    </Link>
                </p>
            </div>
        </>
    );
}
