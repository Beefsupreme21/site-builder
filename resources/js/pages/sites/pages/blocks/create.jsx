import { PageHeader } from '@/components/page-header';
import { pageBlocks, sitePages } from '@/lib/routes';
import { btnPrimary } from '@/lib/ui';
import AppLayout from '@/layouts/app-layout.jsx';
import { Form, Head } from '@inertiajs/react';

export default function PageBlocksCreate({ site, page, blocks }) {
    return (
        <>
            <Head title={`Add block · ${page.title}`} />
            <PageHeader
                backHref={sitePages.show(site, page)}
                backLabel={`Back to ${page.title}`}
                title="Add a block"
                subtitle="Pick a block to add to this page."
            />

            {blocks.length === 0 ? (
                <p className="text-neutral-600">
                    No blocks in the library yet.
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
                                    action={pageBlocks.store(site, page)}
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
            )}
        </>
    );
}

PageBlocksCreate.layout = (page) => <AppLayout>{page}</AppLayout>;
