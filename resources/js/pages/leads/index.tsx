import { index as sitesIndex } from '@/actions/App/Http/Controllers/SiteController';
import type { Lead } from '@/types/lead';
import { Head, Link } from '@inertiajs/react';

const btnSecondary =
    'inline-flex items-center justify-center rounded-md border border-neutral-300 bg-white px-3 py-1.5 text-sm font-medium text-neutral-800 shadow-sm hover:bg-neutral-50';

export default function LeadsIndex({ leads }: { leads: Lead[] }) {
    return (
        <>
            <Head title="Leads" />
            <div className="mx-auto max-w-5xl p-6">
                <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <h1 className="text-2xl font-semibold text-neutral-900">
                        Leads
                    </h1>
                    <Link href={sitesIndex.url()} className={btnSecondary}>
                        Sites
                    </Link>
                </div>

                {leads.length === 0 ? (
                    <p className="text-neutral-600">
                        No leads yet. Submissions from the “alternate” preview
                        contact form will show up here.
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-lg border border-neutral-200 bg-white">
                        <table className="min-w-full divide-y divide-neutral-200 text-sm">
                            <thead className="bg-neutral-50">
                                <tr>
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Received
                                    </th>
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Site
                                    </th>
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Name
                                    </th>
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Email
                                    </th>
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Phone
                                    </th>
                                    <th className="px-4 py-3 text-left font-medium text-neutral-700">
                                        Message
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-neutral-200">
                                {leads.map((lead) => (
                                    <tr key={lead.id} className="bg-white">
                                        <td className="whitespace-nowrap px-4 py-3 text-neutral-600">
                                            {new Date(
                                                lead.created_at,
                                            ).toLocaleString()}
                                        </td>
                                        <td className="px-4 py-3 text-neutral-600">
                                            {lead.site.company_name}
                                        </td>
                                        <td className="px-4 py-3 text-neutral-900">
                                            {lead.first_name} {lead.last_name}
                                        </td>
                                        <td className="px-4 py-3 text-neutral-600">
                                            {lead.email}
                                        </td>
                                        <td className="px-4 py-3 text-neutral-600">
                                            {lead.phone ?? '—'}
                                        </td>
                                        <td className="max-w-xs truncate px-4 py-3 text-neutral-600">
                                            {lead.message ?? '—'}
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
