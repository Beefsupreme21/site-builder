import { PageHeader } from '@/components/page-header';
import { pageBlocks, sitePages } from '@/lib/routes';
import { btnPrimary, formSectionTitle, linkTitle } from '@/lib/ui';
import { Form, Head, Link } from '@inertiajs/react';

export default function PageBlocksCreate({
    site,
    page,
    category,
    activeCategory,
    categories,
    groups,
    blocks,
}) {
    const viewingCategory = Boolean(category && activeCategory);

    return (
        <>
            <Head
                title={
                    viewingCategory
                        ? `${activeCategory.name} · Add block`
                        : `Add block · ${page.title}`
                }
            />
            <PageHeader
                backHref={
                    viewingCategory
                        ? pageBlocks.create(page)
                        : sitePages.show(site, page)
                }
                backLabel={
                    viewingCategory ? 'All sections' : `Back to ${page.title}`
                }
                title={viewingCategory ? activeCategory.name : 'Add a block'}
                subtitle={
                    viewingCategory
                        ? 'Pick a block to add to this page.'
                        : 'Choose a section type.'
                }
            />

            {viewingCategory ? (
                blocks.length === 0 ? (
                    <p className="rounded-lg border border-neutral-200 bg-white p-6 text-sm text-neutral-600">
                        No blocks in this section yet.
                    </p>
                ) : (
                    <ul className="space-y-6">
                        {blocks.map((block) => (
                            <li
                                key={block.id}
                                className="overflow-hidden rounded-lg border border-neutral-200 bg-white"
                            >
                                <div className="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 px-4 py-3">
                                    <div>
                                        <p className="text-sm font-semibold text-neutral-900">
                                            {block.name}
                                        </p>
                                        <p className="text-xs text-neutral-500">
                                            {block.type}
                                        </p>
                                    </div>
                                    <Form
                                        action={pageBlocks.store(page)}
                                        method="post"
                                    >
                                        {({ processing }) => (
                                            <>
                                                <input
                                                    type="hidden"
                                                    name="block_id"
                                                    value={block.id}
                                                />
                                                <button
                                                    type="submit"
                                                    disabled={processing}
                                                    className={btnPrimary}
                                                >
                                                    Add to page
                                                </button>
                                            </>
                                        )}
                                    </Form>
                                </div>
                                <div
                                    className="bg-neutral-50"
                                    dangerouslySetInnerHTML={{
                                        __html: block.default_content,
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
                                                href={pageBlocks.create(
                                                    page,
                                                    item.slug,
                                                )}
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
