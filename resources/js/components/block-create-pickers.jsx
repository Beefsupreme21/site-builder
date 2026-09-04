import { layoutBlocks, pageBlocks } from '@/lib/routes';
import { btnPrimary, formSectionTitle, linkTitle } from '@/lib/ui';
import { Form, Link } from '@inertiajs/react';

function categoriesInGroup(categories, group) {
    const items = [];

    for (const item of categories) {
        if (item.group === group) {
            items.push(item);
        }
    }

    return items;
}

export function BlockTemplatePicker({
    templates,
    storeAction,
    addLabel,
}) {
    if (templates.length === 0) {
        return (
            <p className="rounded-lg border border-neutral-200 bg-white p-6 text-sm text-neutral-600">
                No blocks in this section yet.
            </p>
        );
    }

    return (
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
    );
}

export function BlockCategoryPicker({
    groups,
    categories,
    isLayout,
    layout,
    page,
}) {
    return (
        <div className="space-y-8">
            {groups.map((group) => (
                <section key={group}>
                    <h2 className={formSectionTitle}>{group}</h2>
                    <ul className="mt-4 divide-y divide-neutral-200 overflow-hidden rounded-lg border border-neutral-200 bg-white">
                        {categoriesInGroup(categories, group).map((item) => (
                            <li key={item.slug}>
                                <Link
                                    href={
                                        isLayout
                                            ? layoutBlocks.create(layout)
                                            : pageBlocks.create(page, item.slug)
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
    );
}
