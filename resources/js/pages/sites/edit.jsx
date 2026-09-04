import { FormErrors } from '@/components/form-errors';
import { PageHeader } from '@/components/page-header';
import { SiteFormFields } from '@/components/site-form-fields';
import { sitePreview, sites } from '@/lib/routes';
import {
    btnCancel,
    btnSecondary,
    btnSubmit,
    formActions,
    formCard,
} from '@/lib/ui';
import AppLayout from '@/layouts/app-layout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function SitesEdit({ site }) {
    const form = useForm({
        slug: site.slug,
        company_name: site.company_name,
        phone: site.phone ?? '',
        email: site.email ?? '',
        logo: site.logo ?? '',
        primary_color: site.primary_color,
        secondary_color: site.secondary_color,
    });

    function submit(e) {
        e.preventDefault();
        form.patch(sites.update(site));
    }

    return (
        <>
            <Head title={`Edit ${site.company_name}`} />
            <PageHeader
                backHref={sites.show(site)}
                backLabel={`Back to ${site.company_name}`}
                title="Site settings"
                subtitle="Slug, contact details, and branding."
                actions={
                    <a
                        href={sitePreview.home(site)}
                        target="_blank"
                        rel="noopener noreferrer"
                        className={btnSecondary}
                    >
                        Preview
                    </a>
                }
            />

            <form onSubmit={submit} className={formCard}>
                <FormErrors errors={form.errors} />

                <SiteFormFields
                    form={form}
                    logoHint={
                        <>
                            Full https URL or a path passed to{' '}
                            <code className="rounded bg-neutral-100 px-1 py-0.5 text-[0.8rem]">
                                asset()
                            </code>
                        </>
                    }
                />

                <div className={formActions}>
                    <button
                        type="submit"
                        disabled={form.processing}
                        className={btnSubmit}
                    >
                        Save changes
                    </button>
                    <Link href={sites.show(site)} className={btnCancel}>
                        Cancel
                    </Link>
                </div>
            </form>
        </>
    );
}

SitesEdit.layout = (page) => <AppLayout width="form">{page}</AppLayout>;
