import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { sitePreview, sites } from '@/lib/routes';
import {
    backLink,
    btnCancel,
    btnSubmit,
    formActions,
    formCard,
    formSectionTitle,
    formWrap,
} from '@/lib/ui';
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
            <div className={formWrap}>
                <header className="mb-8">
                    <p className="text-sm text-neutral-500">
                        <Link href={sites.show(site)} className={backLink}>
                            ← {site.company_name}
                        </Link>
                    </p>
                    <h1 className="mt-3 text-2xl font-semibold tracking-tight text-neutral-900">
                        Site settings
                    </h1>
                    <p className="mt-1 text-sm text-neutral-600">
                        Slug, contact details, and branding.
                    </p>
                </header>

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
                                            form.setData(
                                                'phone',
                                                e.target.value,
                                            )
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
                                            form.setData(
                                                'email',
                                                e.target.value,
                                            )
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
                        <a
                            href={sitePreview.home(site)}
                            target="_blank"
                            rel="noopener noreferrer"
                            className={`${btnCancel} ml-auto`}
                        >
                            Open preview
                        </a>
                    </div>
                </form>
            </div>
        </>
    );
}
