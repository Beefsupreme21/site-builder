import { BlockReorderButtons } from '@/components/block-reorder-buttons';
import { pageBlocks } from '@/lib/routes';
import { btnDanger } from '@/lib/ui';
import { Form } from '@inertiajs/react';

export function BlockPreview({ page, blocks, block, number }) {
    return (
        <li className="px-5 py-5">
            <div className="mb-3 flex items-center justify-between gap-3">
                <span className="text-xs font-medium text-neutral-500">
                    Block {number}
                </span>
                <div className="flex flex-wrap items-center gap-2">
                    <BlockReorderButtons blocks={blocks} block={block} />
                    <Form
                        action={pageBlocks.destroy(page, block)}
                        method="delete"
                        className="inline"
                    >
                        {({ processing }) => (
                            <button
                                type="submit"
                                disabled={processing}
                                className={`${btnDanger} px-2.5 py-1 text-xs disabled:cursor-not-allowed disabled:opacity-40`}
                                onClick={(e) => {
                                    if (!confirm(`Delete block ${number}?`)) {
                                        e.preventDefault();
                                    }
                                }}
                            >
                                Delete
                            </button>
                        )}
                    </Form>
                </div>
            </div>
            <div
                className="overflow-hidden rounded-lg border border-neutral-200"
                dangerouslySetInnerHTML={{ __html: block.content }}
            />
        </li>
    );
}
