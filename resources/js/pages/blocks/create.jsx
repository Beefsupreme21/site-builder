import { PageHeader } from '@/components/page-header';
import { layoutBlocks, layouts, pageBlocks, sitePages, sites } from '@/lib/routes';
import { btnPrimary, formSectionTitle, linkTitle } from '@/lib/ui';
import { Form, Head, Link } from '@inertiajs/react';

export default function PageBlocksCreate({
    site,
    page,
    layout,
    target = 'page',
    category,
    activeCategory,
    categories,
    groups,
    templates,
}) {
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

    const storeAction = isLayout
        ? layoutBlocks.store(layout)
        : pageBlocks.store(page);

    const addLabel = isLayout ? 'Add to layout' : 'Add to page';

    return (
        <>
            <Head
                title={
                    viewingCategory
                        ? `${activeCategory.name} · Add block`
                        : isLayout
                          ? `Add layout block · ${layout.name}`
                          : `Add block · ${page.title}`
                }
            />
            <PageHeader
                backHref={backHref}
                backLabel={backLabel}
                title={
                    viewingCategory
                        ? activeCategory.name
                        : isLayout
                          ? 'Add a layout block'
                          : 'Add a block'
                }
                subtitle={
                    viewingCategory
                        ? isLayout
                            ? 'Pick a block to add to this layout.'
                            : 'Pick a block to add to this page.'
                        : isLayout
                          ? 'Choose a layout block type.'
                          : 'Choose a section type.'
                }
            />

            {viewingCategory ? (
                templates.length === 0 ? (
                    <p className="rounded-lg border border-neutral-200 bg-white p-6 text-sm text-neutral-600">
                        No blocks in this section yet.
                    </p>
                ) : (
                    <ul className="space-y-6">
                        {templates.map((template) => (
                            <li
                                key={template.id}
                                className="overflow-hidden rounded-lg border border-neutral-200 bg-white"
                            >
                                <div className="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 px-4 py-3">
                                    <div>
                                        <p className="text-sm font-semibold text-neutral-900">
                                            {template.name}
                                        </p>
                                        <p className="text-xs text-neutral-500">
                                            {template.type}
                                        </p>
                                    </div>
                                    <Form action={storeAction} method="post">
                                        {({ processing }) => (
                                            <>
                                                <input
                                                    type="hidden"
                                                    name="template_id"
                                                    value={template.id}
                                                />
                                                <button
                                                    type="submit"
                                                    disabled={processing}
                                                    className={btnPrimary}
                                                >
                                                    {addLabel}
                                                </button>
                                            </>
                                        )}
                                    </Form>
                                </div>
                                <div
                                    className="bg-neutral-50"
                                    dangerouslySetInnerHTML={{
                                        __html: template.default_content,
                                    }}
                                />
                            </li>
                        ))}
                    </ul>
                )
            ) : (
                <div className="space-y-8">
                    {groups.map((group) => (
                        <section key={group}>
                            <h2 className={formSectionTitle}>{group}</h2>
                            <ul className="mt-4 divide-y divide-neutral-200 overflow-hidden rounded-lg border border-neutral-200 bg-white">
                                {categories
                                    .filter((item) => item.group === group)
                                    .map((item) => (
                                        <li key={item.slug}>
                                            <Link
                                                href={
                                                    isLayout
                                                        ? layoutBlocks.create(
                                                              layout,
                                                          )
                                                        : pageBlocks.create(
                                                              page,
                                                              item.slug,
                                                          )
                                                }
                                                className="flex items-center justify-between gap-4 px-4 py-3 hover:bg-neutral-50"
                                            >
                                                <span
                                                    className={
                                                        item.count > 0
                                                            ? linkTitle
                                                            : 'font-medium text-neutral-500'
                                                    }
                                                >
                                                    {item.name}
                                                </span>
                                                <span className="shrink-0 text-sm text-neutral-500">
                                                    {item.count}{' '}
                                                    {item.count === 1
                                                        ? 'component'
                                                        : 'components'}
                                                </span>
                                            </Link>
                                        </li>
                                    ))}
                            </ul>
                        </section>
                    ))}
                </div>
            )}
        </>
    );
}
