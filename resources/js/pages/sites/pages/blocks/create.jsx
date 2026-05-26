import { pageBlocks, sitePages } from '@/lib/routes';
import { Form, Head, Link } from '@inertiajs/react';

export default function PageBlocksCreate({ site, page, blocks }) {
    return (
        <>
            <Head title={`Add block · ${page.title}`} />
            <div className="mx-auto max-w-5xl px-4 py-8 sm:px-6">
                <header className="mb-8">
                    <p className="text-sm text-neutral-500">
                        <Link
                            href={sitePages.edit(site, page)}
                            className="font-medium text-neutral-700 hover:text-neutral-900"
                        >
                            ← {page.title}
                        </Link>
                    </p>
                    <h1 className="mt-3 text-2xl font-semibold tracking-tight text-neutral-900">
                        Add a block
                    </h1>
                    <p className="mt-1 text-sm text-neutral-600">
                        Pick a block to add to this page. Content can be edited
                        later.
                    </p>
                </header>

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
                                                    className="rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800 disabled:opacity-60"
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
            </div>
        </>
    );
}
