import {
    BlockCategoryPicker,
    BlockTemplatePicker,
} from '@/components/block-create-pickers';
import { PageHeader } from '@/components/page-header';
import { layoutBlocks, layouts, pageBlocks, sitePages } from '@/lib/routes';
import { Head } from '@inertiajs/react';

function blockCreateMeta({ site, page, layout, target, category, activeCategory }) {
    const viewingCategory = Boolean(category && activeCategory);
    const isLayout = target === 'layout';

    const backHref = viewingCategory
        ? isLayout
            ? layoutBlocks.create(layout)
            : pageBlocks.create(page)
        : isLayout
          ? layouts.show(site, layout)
          : sitePages.show(site, page);

    const backLabel = viewingCategory
        ? 'All sections'
        : isLayout
          ? `Back to ${layout.name}`
          : `Back to ${page.title}`;

    const title = viewingCategory
        ? activeCategory.name
        : isLayout
          ? 'Add a layout block'
          : 'Add a block';

    const subtitle = viewingCategory
        ? isLayout
            ? 'Pick a block to add to this layout.'
            : 'Pick a block to add to this page.'
        : isLayout
          ? 'Choose a layout block type.'
          : 'Choose a section type.';

    const headTitle = viewingCategory
        ? `${activeCategory.name} · Add block`
        : isLayout
          ? `Add layout block · ${layout.name}`
          : `Add block · ${page.title}`;

    return {
        viewingCategory,
        isLayout,
        backHref,
        backLabel,
        title,
        subtitle,
        headTitle,
        storeAction: isLayout
            ? layoutBlocks.store(layout)
            : pageBlocks.store(page),
        addLabel: isLayout ? 'Add to layout' : 'Add to page',
    };
}

export default function PageBlocksCreate(props) {
    const meta = blockCreateMeta(props);

    return (
        <>
            <Head title={meta.headTitle} />
            <PageHeader
                backHref={meta.backHref}
                backLabel={meta.backLabel}
                title={meta.title}
                subtitle={meta.subtitle}
            />

            {meta.viewingCategory ? (
                <BlockTemplatePicker
                    templates={props.templates}
                    storeAction={meta.storeAction}
                    addLabel={meta.addLabel}
                />
            ) : (
                <BlockCategoryPicker
                    groups={props.groups}
                    categories={props.categories}
                    isLayout={meta.isLayout}
                    layout={props.layout}
                    page={props.page}
                />
            )}
        </>
    );
}
