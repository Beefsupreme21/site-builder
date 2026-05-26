import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { sites } from '@/lib/routes';
import {
    backLink,
    btnCancel,
    btnSubmit,
    formActions,
    formCard,
    formSectionTitle,
    formWrap,
} from '@/lib/ui';
import { Form, Head, Link } from '@inertiajs/react';

export default function SitesCreate() {
    return (
        <>
            <Head title="New site" />
            <div className={formWrap}>
                <header className="mb-8">
                    <p className="text-sm text-neutral-500">
                        <Link href={sites.index()} className={backLink}>
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
                                    <h2 className={formSectionTitle}>
                                        Contact
                                    </h2>
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
                                    <h2 className={formSectionTitle}>
                                        Branding
                                    </h2>
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
            </div>
        </>
    );
}
