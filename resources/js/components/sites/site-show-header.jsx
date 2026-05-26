import { sitePreview, sites } from '@/lib/routes';
import { btnSecondary } from '@/lib/ui';
import { Link } from '@inertiajs/react';

export function SiteShowHeader({ site }) {
    return (
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
                    <p className="mt-1 text-sm text-neutral-600">{site.slug}</p>
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
                    <Link href={sites.edit(site)} className={btnSecondary}>
                        Site settings
                    </Link>
                </div>
            </div>
        </header>
    );
}
