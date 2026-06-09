import { PageBlockList } from '@/components/page-block-list';
import { PageHeader } from '@/components/page-header';
import { sitePages, sitePreview, sites } from '@/lib/routes';
import { btnSecondary } from '@/lib/ui';
import { Head, Link } from '@inertiajs/react';

export default function SitePagesShow({ site, page, brandStyles }) {
    return (
        <>
            <Head title={`${page.title} · ${site.company_name}`} />
            <style dangerouslySetInnerHTML={{ __html: brandStyles }} />
            <PageHeader
                backHref={sites.show(site)}
                backLabel={`Back to ${site.company_name}`}
                title={page.title}
                subtitle={`/${page.slug}`}
                actions={
                    <>
                        <a
                            href={sitePreview.page(site, page)}
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
                            Edit
                        </Link>
                    </>
                }
            />
            <PageBlockList site={site} page={page} />
        </>
    );
}
