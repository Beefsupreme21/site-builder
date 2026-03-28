import {
    index,
    preview,
    update,
} from '@/actions/App/Http/Controllers/SiteController';
import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { SITE_TEMPLATES } from '@/lib/site-templates';
import type { Site } from '@/types/site';
import { Head, Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';

const selectClass =
    'mt-1.5 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm text-neutral-900 shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-neutral-400/30';

const fieldWrap = 'space-y-6';
const sectionTitle =
    'border-b border-neutral-100 pb-2 text-xs font-semibold uppercase tracking-wide text-neutral-500';

export default function SitesEdit({ site }: { site: Site }) {
    const form = useForm({
        slug: site.slug,
        template: site.template,
        company_name: site.company_name,
        phone: site.phone ?? '',
        email: site.email ?? '',
        logo: site.logo ?? '',
    });

    function submit(e: FormEvent) {
        e.preventDefault();
        form.patch(update.url(site));
    }

    return (
        <>
            <Head title={`Edit ${site.company_name}`} />
            <div className="mx-auto max-w-xl px-4 py-8 sm:px-6">
                <header className="mb-8">
                    <p className="text-sm text-neutral-500">
                        <Link
                            href={index.url()}
                            className="font-medium text-neutral-700 hover:text-neutral-900"
                        >
                            ← Sites
                        </Link>
                    </p>
                    <h1 className="mt-3 text-2xl font-semibold tracking-tight text-neutral-900">
                        Edit site
                    </h1>
                    <p className="mt-1 text-sm text-neutral-600">
                        {site.company_name}
                    </p>
                </header>

                <form
                    onSubmit={submit}
                    className="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm sm:p-8"
                >
                    <FormErrors errors={form.errors} />

                    <div className={fieldWrap}>
                        <div>
                            <h2 className={sectionTitle}>Site</h2>
                            <div className="mt-4 space-y-4">
                                <div>
                                    <Label htmlFor="site-edit-slug">
                                        Slug
                                    </Label>
                                    <Input
                                        id="site-edit-slug"
                                        name="slug"
                                        value={form.data.slug}
                                        onChange={(e) =>
                                            form.setData(
                                                'slug',
                                                e.target.value,
                                            )
                                        }
                                        required
                                        className="max-w-full"
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="site-edit-template">
                                        Template
                                    </Label>
                                    <select
                                        id="site-edit-template"
                                        name="template"
                                        value={form.data.template}
                                        onChange={(e) =>
                                            form.setData(
                                                'template',
                                                e.target.value,
                                            )
                                        }
                                        className={selectClass}
                                    >
                                        {SITE_TEMPLATES.map((t) => (
                                            <option
                                                key={t.value}
                                                value={t.value}
                                            >
                                                {t.label}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div>
                                    <Label htmlFor="site-edit-company_name">
                                        Company name
                                    </Label>
                                    <Input
                                        id="site-edit-company_name"
                                        name="company_name"
                                        value={form.data.company_name}
                                        onChange={(e) =>
                                            form.setData(
                                                'company_name',
                                                e.target.value,
                                            )
                                        }
                                        required
                                        className="max-w-full"
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 className={sectionTitle}>Contact</h2>
                            <div className="mt-4 space-y-4">
                                <div>
                                    <Label htmlFor="site-edit-phone">
                                        Phone
                                    </Label>
                                    <Input
                                        id="site-edit-phone"
                                        name="phone"
                                        value={form.data.phone}
                                        onChange={(e) =>
                                            form.setData(
                                                'phone',
                                                e.target.value,
                                            )
                                        }
                                        className="max-w-full"
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="site-edit-email">
                                        Email
                                    </Label>
                                    <Input
                                        id="site-edit-email"
                                        name="email"
                                        type="email"
                                        value={form.data.email}
                                        onChange={(e) =>
                                            form.setData(
                                                'email',
                                                e.target.value,
                                            )
                                        }
                                        className="max-w-full"
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 className={sectionTitle}>Branding</h2>
                            <div className="mt-4">
                                <Label htmlFor="site-edit-logo">
                                    Logo URL or path
                                </Label>
                                <p className="mt-0.5 text-xs text-neutral-500">
                                    Full https URL or a path passed to{' '}
                                    <code className="rounded bg-neutral-100 px-1 py-0.5 text-[0.8rem]">
                                        asset()
                                    </code>
                                </p>
                                <Input
                                    id="site-edit-logo"
                                    name="logo"
                                    value={form.data.logo}
                                    onChange={(e) =>
                                        form.setData(
                                            'logo',
                                            e.target.value,
                                        )
                                    }
                                    className="max-w-full"
                                    placeholder="https://…"
                                />
                            </div>
                        </div>
                    </div>

                    <div className="mt-8 flex flex-wrap gap-3 border-t border-neutral-100 pt-6">
                        <button
                            type="submit"
                            disabled={form.processing}
                            className="rounded-lg bg-neutral-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-neutral-800 disabled:opacity-60"
                        >
                            Save changes
                        </button>
                        <Link
                            href={index.url()}
                            className="rounded-lg border border-neutral-300 bg-white px-5 py-2.5 text-sm font-medium text-neutral-800 shadow-sm hover:bg-neutral-50"
                        >
                            Cancel
                        </Link>
                        <a
                            href={preview.url(site)}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="ml-auto rounded-lg border border-neutral-200 px-5 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-50"
                        >
                            Open preview
                        </a>
                    </div>
                </form>
            </div>
        </>
    );
}
