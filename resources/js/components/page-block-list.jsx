import { BlockPreview } from '@/components/block-preview';
import { pageBlocks } from '@/lib/routes';
import { btnPrimary, emptyState, linkTitle } from '@/lib/ui';
import { Link } from '@inertiajs/react';

export function PageBlockList({ site, page }) {
    const blocks = page.block_pages ?? [];

    return (
        <section>
            <div className="mb-4 flex flex-wrap items-center justify-between gap-4">
                <h2 className="text-lg font-semibold text-neutral-900">
                    Blocks
                </h2>
                <Link
                    href={pageBlocks.create(site, page)}
                    className={btnPrimary}
                >
                    Add block
                </Link>
            </div>

            {blocks.length === 0 ? (
                <p className={emptyState}>
                    No blocks yet.{' '}
                    <Link
                        href={pageBlocks.create(site, page)}
                        className={linkTitle}
                    >
                        Add one
                    </Link>
                    .
                </p>
            ) : (
                <ul className="divide-y divide-neutral-100 rounded-xl border border-neutral-200 bg-white shadow-sm">
                    {blocks.map((block, index) => (
                        <BlockPreview
                            key={block.id}
                            site={site}
                            page={page}
                            blocks={blocks}
                            block={block}
                            number={index + 1}
                        />
                    ))}
                </ul>
            )}
        </section>
    );
}
