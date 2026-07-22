import { PageBlockList } from '@/components/page-block-list';
import { PageHeader } from '@/components/page-header';
import { sitePages, sitePreview, sites } from '@/lib/routes';
import { btnSecondary } from '@/lib/ui';
import { Head, Link } from '@inertiajs/react';

export default function SitePagesShow({ site, page }) {
    return (
        <>
            <Head title={`${page.title} · ${site.company_name}`} />
            <PageHeader
                backHref={sites.show(site)}
                backLabel={`Back to ${site.company_name}`}
                title={page.title}
                subtitle={`/${page.slug}`}
                actions={
                    <>
                        <a
                            href={sitePreview.page(page)}
                            target="_blank"
                            rel="noopener noreferrer"
                            className={btnSecondary}
                        >
                            Preview
                        </a>
                        <Link
                            href={sitePages.edit(site, page)}
                            className={btnSecondary}
                        >
                            Page settings
                        </Link>
                    </>
                }
            />
            <PageBlockList page={page} />
        </>
    );
}
