import { BlockReorderButtons } from '@/components/block-reorder-buttons';
import { layoutBlocks, pageBlocks } from '@/lib/routes';
import { btnDanger } from '@/lib/ui';
import { Form } from '@inertiajs/react';

const actionBtn = `${btnDanger} px-2.5 py-1 text-xs disabled:cursor-not-allowed disabled:opacity-40`;

function SlotPlaceholder({ name }) {
    return (
        <div className="rounded-lg border-2 border-dashed border-sky-200 bg-sky-50 px-6 py-10 text-center">
            <p className="text-sm font-semibold text-sky-900">{name}</p>
            <p className="mt-2 text-sm text-sky-700">
                Each page&apos;s blocks render here in preview.
            </p>
        </div>
    );
}

export function BlockPreview({
    target = 'page',
    page,
    layout,
    blocks,
    block,
    number,
}) {
    const isSlot = block.template?.type === 'slot';
    const label = isSlot
        ? (block.template?.name ?? 'Content slot')
        : `Block ${number}`;

    const destroyAction =
        target === 'layout'
            ? layoutBlocks.destroy(layout, block)
            : pageBlocks.destroy(page, block);

    return (
        <li className="px-5 py-5">
            <div className="mb-3 flex items-center justify-between gap-3">
                <span className="text-xs font-medium text-neutral-500">
                    {label}
                </span>
                <div className="flex flex-wrap items-center gap-2">
                    <BlockReorderButtons blocks={blocks} block={block} />
                    {isSlot ? (
                        <button type="button" disabled className={actionBtn}>
                            Delete
                        </button>
                    ) : (
                        <Form
                            action={destroyAction}
                            method="delete"
                            className="inline"
                        >
                            {({ processing }) => (
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className={actionBtn}
                                    onClick={(e) => {
                                        if (
                                            !confirm(`Delete block ${number}?`)
                                        ) {
                                            e.preventDefault();
                                        }
                                    }}
                                >
                                    Delete
                                </button>
                            )}
                        </Form>
                    )}
                </div>
            </div>
            {isSlot ? (
                <SlotPlaceholder name={block.template?.name ?? 'Content slot'} />
            ) : (
                <div
                    className="overflow-hidden rounded-lg border border-neutral-200"
                    dangerouslySetInnerHTML={{ __html: block.content }}
                />
            )}
        </li>
    );
}
