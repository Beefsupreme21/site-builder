import { BlockPreview } from '@/components/block-preview';
import { btnPrimary, emptyState, linkTitle } from '@/lib/ui';
import { Link } from '@inertiajs/react';

const EMPTY_BLOCKS = [];

export function BlockList({
    blocks = EMPTY_BLOCKS,
    createHref,
    target = 'page',
    page = null,
    layout = null,
}) {
    return (
        <section>
            <div className="mb-4 flex flex-wrap items-center justify-between gap-4">
                <h2 className="text-lg font-semibold text-neutral-900">
                    Blocks
                </h2>
                <Link href={createHref} className={btnPrimary}>
                    Add block
                </Link>
            </div>

            {blocks.length === 0 ? (
                <p className={emptyState}>
                    No blocks yet.{' '}
                    <Link href={createHref} className={linkTitle}>
                        Add one
                    </Link>
                    .
                </p>
            ) : (
                <ul className="divide-y divide-neutral-100 rounded-xl border border-neutral-200 bg-white shadow-sm">
                    {blocks.map((block, index) => (
                        <BlockPreview
                            key={block.id}
                            target={target}
                            page={page}
                            layout={layout}
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
