import { BlockList } from '@/components/block-list';
import { PageHeader } from '@/components/page-header';
import { layoutBlocks, sitePreview, sites } from '@/lib/routes';
import { btnSecondary } from '@/lib/ui';
import { Head } from '@inertiajs/react';

export default function LayoutsShow({ site, layout }) {
    const blocks = layout.blocks ?? [];

    return (
        <>
            <Head title={`Layout · ${site.company_name}`} />
            <PageHeader
                backHref={sites.show(site)}
                backLabel={`Back to ${site.company_name}`}
                title={layout.name}
                subtitle="Layout"
                actions={
                    <a
                        href={sitePreview.home(site)}
                        target="_blank"
                        rel="noopener noreferrer"
                        className={btnSecondary}
                    >
                        Preview
                    </a>
                }
            />
            <BlockList
                blocks={blocks}
                createHref={layoutBlocks.create(layout)}
                target="layout"
                layout={layout}
            />
        </>
    );
}
