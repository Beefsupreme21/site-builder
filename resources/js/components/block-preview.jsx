import { BlockReorderButtons } from '@/components/block-reorder-buttons';

export function BlockPreview({ site, page, blocks, block, number }) {
    return (
        <li className="px-5 py-5">
            <div className="mb-3 flex items-center justify-between gap-3">
                <span className="text-xs font-medium text-neutral-500">
                    Block {number}
                </span>
                <BlockReorderButtons
                    site={site}
                    page={page}
                    blocks={blocks}
                    block={block}
                />
            </div>
            <div
                className="overflow-hidden rounded-lg border border-neutral-200"
                dangerouslySetInnerHTML={{ __html: block.content }}
            />
        </li>
    );
}
