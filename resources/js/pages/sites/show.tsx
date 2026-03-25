import {
    destroy,
    edit,
    index,
} from '@/actions/App/Http/Controllers/SiteController';
import type { Site } from '@/types/site';
import { Form, Head, Link } from '@inertiajs/react';

export default function SitesShow({ site }: { site: Site }) {
    return (
        <>
            <Head title={site.company_name} />
            <div className="mx-auto max-w-2xl p-6">
                <div className="flex flex-wrap items-start justify-between gap-4">
                    <h1 className="text-xl font-semibold text-neutral-900">
                        {site.company_name}
                    </h1>
                    <div className="flex gap-2">
                        <Link
                            href={edit.url(site)}
                            className="rounded border border-neutral-300 bg-white px-3 py-1.5 text-sm text-neutral-800 hover:bg-neutral-50"
                        >
                            Edit
                        </Link>
                        <Form
                            {...destroy.form.delete(site)}
                            className="inline"
                        >
                            {({ processing }) => (
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="rounded border border-red-200 bg-white px-3 py-1.5 text-sm text-red-800 hover:bg-red-50"
                                    onClick={(e) => {
                                        if (!confirm('Delete this site?')) {
                                            e.preventDefault();
                                        }
                                    }}
                                >
                                    Delete
                                </button>
                            )}
                        </Form>
                    </div>
                </div>
                <dl className="mt-6 space-y-3 text-sm">
                    <div>
                        <dt className="font-medium text-neutral-500">Slug</dt>
                        <dd className="text-neutral-900">{site.slug}</dd>
                    </div>
                    <div>
                        <dt className="font-medium text-neutral-500">
                            Template
                        </dt>
                        <dd className="text-neutral-900">{site.template}</dd>
                    </div>
                    <div>
                        <dt className="font-medium text-neutral-500">Phone</dt>
                        <dd className="text-neutral-900">
                            {site.phone ?? '—'}
                        </dd>
                    </div>
                    <div>
                        <dt className="font-medium text-neutral-500">Email</dt>
                        <dd className="text-neutral-900">
                            {site.email ?? '—'}
                        </dd>
                    </div>
                    <div>
                        <dt className="font-medium text-neutral-500">Logo</dt>
                        <dd className="text-neutral-900">
                            {site.logo ?? '—'}
                        </dd>
                    </div>
                </dl>
                <p className="mt-8">
                    <Link
                        href={index.url()}
                        className="text-sm text-neutral-600 underline"
                    >
                        ← All sites
                    </Link>
                </p>
            </div>
        </>
    );
}
