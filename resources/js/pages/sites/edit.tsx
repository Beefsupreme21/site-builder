import { show, update } from '@/actions/App/Http/Controllers/SiteController';
import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Site } from '@/types/site';
import { Head, Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';

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
            <div className="mx-auto max-w-2xl p-6">
                <h1 className="text-xl font-semibold text-neutral-900">
                    Edit site
                </h1>
                <form onSubmit={submit} className="mt-6 space-y-4">
                    <FormErrors errors={form.errors} />
                    <div>
                        <Label htmlFor="site-edit-slug">Slug</Label>
                        <Input
                            id="site-edit-slug"
                            name="slug"
                            value={form.data.slug}
                            onChange={(e) =>
                                form.setData('slug', e.target.value)
                            }
                            required
                        />
                    </div>
                    <div>
                        <Label htmlFor="site-edit-template">Template</Label>
                        <Input
                            id="site-edit-template"
                            name="template"
                            value={form.data.template}
                            onChange={(e) =>
                                form.setData('template', e.target.value)
                            }
                        />
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
                                form.setData('company_name', e.target.value)
                            }
                            required
                        />
                    </div>
                    <div>
                        <Label htmlFor="site-edit-phone">Phone</Label>
                        <Input
                            id="site-edit-phone"
                            name="phone"
                            value={form.data.phone}
                            onChange={(e) =>
                                form.setData('phone', e.target.value)
                            }
                        />
                    </div>
                    <div>
                        <Label htmlFor="site-edit-email">Email</Label>
                        <Input
                            id="site-edit-email"
                            name="email"
                            type="email"
                            value={form.data.email}
                            onChange={(e) =>
                                form.setData('email', e.target.value)
                            }
                        />
                    </div>
                    <div>
                        <Label htmlFor="site-edit-logo">Logo path</Label>
                        <Input
                            id="site-edit-logo"
                            name="logo"
                            value={form.data.logo}
                            onChange={(e) =>
                                form.setData('logo', e.target.value)
                            }
                        />
                    </div>
                    <div className="flex gap-3 pt-2">
                        <button
                            type="submit"
                            disabled={form.processing}
                            className="rounded bg-neutral-900 px-4 py-2 text-sm text-white hover:bg-neutral-800"
                        >
                            Save
                        </button>
                        <Link
                            href={show.url(site)}
                            className="rounded border border-neutral-300 bg-white px-4 py-2 text-sm text-neutral-800 hover:bg-neutral-50"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </>
    );
}
