import { PageBlockList } from '@/components/sites/page-block-list';
import { PageShowHeader } from '@/components/sites/page-show-header';
import { Head } from '@inertiajs/react';

export default function SitePagesShow({ site, page }) {
    return (
        <>
            <Head title={`${page.title} · ${site.company_name}`} />
            <div className="mx-auto max-w-5xl px-4 py-8 sm:px-6">
                <PageShowHeader site={site} page={page} />
                <PageBlockList site={site} page={page} />
            </div>
        </>
    );
}
