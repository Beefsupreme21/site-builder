import { index, store } from '@/actions/App/Http/Controllers/SiteController';
import { FormErrors } from '@/components/ui/form-errors';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Form, Head, Link } from '@inertiajs/react';

export default function SitesCreate() {
    return (
        <>
            <Head title="New site" />
            <div className="mx-auto max-w-2xl p-6">
                <h1 className="text-xl font-semibold text-neutral-900">
                    New site
                </h1>
                <Form {...store.form()} className="mt-6 space-y-4">
                    {({ errors, processing }) => (
                        <>
                            <FormErrors errors={errors} />
                            <div>
                                <Label htmlFor="site-create-slug">Slug</Label>
                                <Input
                                    id="site-create-slug"
                                    name="slug"
                                    required
                                />
                            </div>
                            <div>
                                <Label htmlFor="site-create-template">
                                    Template
                                </Label>
                                <Input
                                    id="site-create-template"
                                    name="template"
                                    defaultValue="default"
                                />
                            </div>
                            <div>
                                <Label htmlFor="site-create-company_name">
                                    Company name
                                </Label>
                                <Input
                                    id="site-create-company_name"
                                    name="company_name"
                                    required
                                />
                            </div>
                            <div>
                                <Label htmlFor="site-create-phone">Phone</Label>
                                <Input id="site-create-phone" name="phone" />
                            </div>
                            <div>
                                <Label htmlFor="site-create-email">Email</Label>
                                <Input
                                    id="site-create-email"
                                    name="email"
                                    type="email"
                                />
                            </div>
                            <div>
                                <Label htmlFor="site-create-logo">Logo path</Label>
                                <Input id="site-create-logo" name="logo" />
                            </div>
                            <div className="flex gap-3 pt-2">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="rounded bg-neutral-900 px-4 py-2 text-sm text-white hover:bg-neutral-800"
                                >
                                    Save
                                </button>
                                <Link
                                    href={index.url()}
                                    className="rounded border border-neutral-300 bg-white px-4 py-2 text-sm text-neutral-800 hover:bg-neutral-50"
                                >
                                    Cancel
                                </Link>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}
