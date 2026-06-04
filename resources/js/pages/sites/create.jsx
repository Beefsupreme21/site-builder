import { FormErrors } from '@/components/form-errors';
import { Input } from '@/components/input';
import { Label } from '@/components/label';
import { PageHeader } from '@/components/page-header';
import { sites } from '@/lib/routes';
import {
    btnCancel,
    btnSubmit,
    formActions,
    formCard,
    formSectionTitle,
} from '@/lib/ui';
import AppLayout from '@/layouts/app-layout';
import { Form, Head, Link } from '@inertiajs/react';

export default function SitesCreate() {
    return (
        <>
            <Head title="New site" />
            <PageHeader
                backHref={sites.index()}
                backLabel="Back to sites"
                title="New site"
                subtitle="Add a site, then preview how it will look."
            />

            <Form action={sites.store()} method="post">
                {({ errors, processing }) => (
                    <div className={formCard}>
                        <FormErrors errors={errors} />

                        <div className="space-y-8">
                            <section>
                                <h2 className={formSectionTitle}>Site</h2>
                                <div className="mt-4 space-y-4">
                                    <div>
                                        <Label htmlFor="slug">Slug</Label>
                                        <Input
                                            id="slug"
                                            name="slug"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <Label htmlFor="company_name">
                                            Company name
                                        </Label>
                                        <Input
                                            id="company_name"
                                            name="company_name"
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
                                        <Input id="phone" name="phone" />
                                    </div>
                                    <div>
                                        <Label htmlFor="email">Email</Label>
                                        <Input
                                            id="email"
                                            name="email"
                                            type="email"
                                        />
                                    </div>
                                </div>
                            </section>

                            <section>
                                <h2 className={formSectionTitle}>Branding</h2>
                                <div className="mt-4">
                                    <Label htmlFor="logo">
                                        Logo URL or path
                                    </Label>
                                    <p className="mt-0.5 text-xs text-neutral-500">
                                        Optional. Full https URL or path for{' '}
                                        <code className="rounded bg-neutral-100 px-1 py-0.5 text-[0.8rem]">
                                            asset()
                                        </code>
                                        .
                                    </p>
                                    <Input
                                        id="logo"
                                        name="logo"
                                        placeholder="https://…"
                                    />
                                </div>
                            </section>
                        </div>

                        <div className={formActions}>
                            <button
                                type="submit"
                                disabled={processing}
                                className={btnSubmit}
                            >
                                Create site
                            </button>
                            <Link href={sites.index()} className={btnCancel}>
                                Cancel
                            </Link>
                        </div>
                    </div>
                )}
            </Form>
        </>
    );
}

SitesCreate.layout = (page) => (
    <AppLayout width="form">{page}</AppLayout>
);
