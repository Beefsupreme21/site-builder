import { FormErrors } from '@/components/form-errors';
import { Label } from '@/components/label';
import { PageHeader } from '@/components/page-header';
import { testSummarize } from '@/lib/routes';
import {
    btnSubmit,
    formActions,
    formCard,
    formSectionTitle,
} from '@/lib/ui';
import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';

export default function SummarizeTest({
    defaultArticle,
    article,
    summary,
    provider,
    model,
}) {
    const form = useForm({
        article: article ?? defaultArticle,
    });

    function submit(e) {
        e.preventDefault();
        form.post(testSummarize.store());
    }

    return (
        <>
            <Head title="AI summarize test" />
            <PageHeader
                title="AI summarize test"
                subtitle={`Provider: ${provider} · Model: ${model}`}
            />

            <form onSubmit={submit} className={formCard}>
                <FormErrors errors={form.errors} />

                <div className="space-y-6">
                    <section>
                        <h2 className={formSectionTitle}>Input</h2>
                        <div className="mt-4">
                            <Label htmlFor="article">Article to summarize</Label>
                            <textarea
                                id="article"
                                rows={8}
                                value={form.data.article}
                                onChange={(e) =>
                                    form.setData('article', e.target.value)
                                }
                                className="mt-1 block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-1 focus:ring-neutral-500"
                                required
                            />
                        </div>
                    </section>

                    <div className={formActions}>
                        <button
                            type="submit"
                            disabled={form.processing}
                            className={btnSubmit}
                        >
                            {form.processing ? 'Summarizing…' : 'Summarize'}
                        </button>
                    </div>

                    {summary && (
                        <section>
                            <h2 className={formSectionTitle}>Output</h2>
                            <div className="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-950">
                                {summary}
                            </div>
                        </section>
                    )}
                </div>
            </form>
        </>
    );
}

SummarizeTest.layout = (page) => (
    <AppLayout width="narrow">{page}</AppLayout>
);
