import { pageBlocks } from '@/lib/routes';
import { btnSecondary } from '@/lib/ui';
import { Link } from '@inertiajs/react';

const btn = `${btnSecondary} px-2.5 py-1 text-xs disabled:cursor-not-allowed disabled:opacity-40`;

export function BlockReorderButtons({ blocks, block }) {
    const index = blocks.findIndex((b) => b.id === block.id);

    return (
        <div className="flex gap-2">
            {[
                { label: 'Move up', direction: 'up', disabled: index <= 0 },
                {
                    label: 'Move down',
                    direction: 'down',
                    disabled: index === blocks.length - 1,
                },
            ].map(({ label, direction, disabled }) =>
                disabled ? (
                    <button
                        key={direction}
                        type="button"
                        disabled
                        className={btn}
                    >
                        {label}
                    </button>
                ) : (
                    <Link
                        key={direction}
                        href={pageBlocks.move(block, direction)}
                        method="patch"
                        preserveScroll
                        className={btn}
                        as="button"
                    >
                        {label}
                    </Link>
                ),
            )}
        </div>
    );
}
