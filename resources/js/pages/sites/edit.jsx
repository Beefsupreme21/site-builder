import { FormErrors } from '@/components/form-errors';
import { Input } from '@/components/input';
import { Label } from '@/components/label';
import { PageHeader } from '@/components/page-header';
import { sitePreview, sites } from '@/lib/routes';
import {
    btnCancel,
    btnSecondary,
    btnSubmit,
    formActions,
    formCard,
    formSectionTitle,
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

                <div className="space-y-8">
                    <section>
                        <h2 className={formSectionTitle}>Site</h2>
                        <div className="mt-4 space-y-4">
                            <div>
                                <Label htmlFor="slug">Slug</Label>
                                <Input
                                    id="slug"
                                    value={form.data.slug}
                                    onChange={(e) =>
                                        form.setData('slug', e.target.value)
                                    }
                                    required
                                />
                            </div>
                            <div>
                                <Label htmlFor="company_name">
                                    Company name
                                </Label>
                                <Input
                                    id="company_name"
                                    value={form.data.company_name}
                                    onChange={(e) =>
                                        form.setData(
                                            'company_name',
                                            e.target.value,
                                        )
                                    }
                                    required
                                />
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 className={formSectionTitle}>Contact</h2>
                        <div className="mt-4 space-y-4">
                            <div>
                                <Label htmlFor="phone">Phone</Label>
                                <Input
                                    id="phone"
                                    value={form.data.phone}
                                    onChange={(e) =>
                                        form.setData('phone', e.target.value)
                                    }
                                />
                            </div>
                            <div>
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={form.data.email}
                                    onChange={(e) =>
                                        form.setData('email', e.target.value)
                                    }
                                />
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 className={formSectionTitle}>Branding</h2>
                        <div className="mt-4">
                            <Label htmlFor="logo">Logo URL or path</Label>
                            <p className="mt-0.5 text-xs text-neutral-500">
                                Full https URL or a path passed to{' '}
                                <code className="rounded bg-neutral-100 px-1 py-0.5 text-[0.8rem]">
                                    asset()
                                </code>
                            </p>
                            <Input
                                id="logo"
                                value={form.data.logo}
                                onChange={(e) =>
                                    form.setData('logo', e.target.value)
                                }
                                placeholder="https://…"
                            />
                        </div>
                    </section>
                </div>

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
