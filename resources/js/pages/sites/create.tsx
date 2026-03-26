import { index, store } from '@/actions/App/Http/Controllers/SiteController';
import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { SITE_TEMPLATES } from '@/lib/site-templates';
import { Form, Head, Link } from '@inertiajs/react';

const selectClass =
    'mt-1.5 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm text-neutral-900 shadow-sm focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-neutral-400/30';

const sectionTitle =
    'border-b border-neutral-100 pb-2 text-xs font-semibold uppercase tracking-wide text-neutral-500';

export default function SitesCreate() {
    return (
        <>
            <Head title="New site" />
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
                        New site
                    </h1>
                    <p className="mt-1 text-sm text-neutral-600">
                        Add a site, then preview how it will look.
                    </p>
                </header>

                <Form {...store.form()}>
                    {({ errors, processing }) => (
                        <div className="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm sm:p-8">
                            <FormErrors errors={errors} />

                            <div className="space-y-8">
                                <div>
                                    <h2 className={sectionTitle}>Site</h2>
                                    <div className="mt-4 space-y-4">
                                        <div>
                                            <Label htmlFor="site-create-slug">
                                                Slug
                                            </Label>
                                            <Input
                                                id="site-create-slug"
                                                name="slug"
                                                required
                                                className="max-w-full"
                                            />
                                        </div>
                                        <div>
                                            <Label htmlFor="site-create-template">
                                                Template
                                            </Label>
                                            <select
                                                id="site-create-template"
                                                name="template"
                                                defaultValue="default"
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
                                            <Label htmlFor="site-create-company_name">
                                                Company name
                                            </Label>
                                            <Input
                                                id="site-create-company_name"
                                                name="company_name"
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
                                            <Label htmlFor="site-create-phone">
                                                Phone
                                            </Label>
                                            <Input
                                                id="site-create-phone"
                                                name="phone"
                                                className="max-w-full"
                                            />
                                        </div>
                                        <div>
                                            <Label htmlFor="site-create-email">
                                                Email
                                            </Label>
                                            <Input
                                                id="site-create-email"
                                                name="email"
                                                type="email"
                                                className="max-w-full"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h2 className={sectionTitle}>Branding</h2>
                                    <div className="mt-4">
                                        <Label htmlFor="site-create-logo">
                                            Logo URL or path
                                        </Label>
                                        <p className="mt-0.5 text-xs text-neutral-500">
                                            Optional. Full https URL or path
                                            for{' '}
                                            <code className="rounded bg-neutral-100 px-1 py-0.5 text-[0.8rem]">
                                                asset()
                                            </code>
                                            .
                                        </p>
                                        <Input
                                            id="site-create-logo"
                                            name="logo"
                                            className="max-w-full"
                                            placeholder="https://…"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div className="mt-8 flex flex-wrap gap-3 border-t border-neutral-100 pt-6">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="rounded-lg bg-neutral-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-neutral-800 disabled:opacity-60"
                                >
                                    Create site
                                </button>
                                <Link
                                    href={index.url()}
                                    className="rounded-lg border border-neutral-300 bg-white px-5 py-2.5 text-sm font-medium text-neutral-800 shadow-sm hover:bg-neutral-50"
                                >
                                    Cancel
                                </Link>
                            </div>
                        </div>
                    )}
                </Form>
            </div>
        </>
    );
}
