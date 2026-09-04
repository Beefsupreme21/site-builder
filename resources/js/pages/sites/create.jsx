import { FormErrors } from '@/components/form-errors';
import { PageHeader } from '@/components/page-header';
import { SiteFormFields } from '@/components/site-form-fields';
import { sites } from '@/lib/routes';
import { btnCancel, btnSubmit, formActions, formCard } from '@/lib/ui';
import AppLayout from '@/layouts/app-layout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function SitesCreate() {
    const form = useForm({
        slug: '',
        company_name: '',
        phone: '',
        email: '',
        logo: '',
        primary_color: '#171717',
        secondary_color: '#525252',
    });

    function submit(e) {
        e.preventDefault();
        form.post(sites.store());
    }

    return (
        <>
            <Head title="New site" />
            <PageHeader
                backHref={sites.index()}
                backLabel="Back to sites"
                title="New site"
                subtitle="Add a site, then preview how it will look."
            />

            <form onSubmit={submit} className={formCard}>
                <FormErrors errors={form.errors} />

                <SiteFormFields
                    form={form}
                    logoHint={
                        <>
                            Optional. Full https URL or path for{' '}
                            <code className="rounded bg-neutral-100 px-1 py-0.5 text-[0.8rem]">
                                asset()
                            </code>
                            .
                        </>
                    }
                />

                <div className={formActions}>
                    <button
                        type="submit"
                        disabled={form.processing}
                        className={btnSubmit}
                    >
                        Create site
                    </button>
                    <Link href={sites.index()} className={btnCancel}>
                        Cancel
                    </Link>
                </div>
            </form>
        </>
    );
}

SitesCreate.layout = (page) => <AppLayout width="form">{page}</AppLayout>;
