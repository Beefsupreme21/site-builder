import { ColorInput } from '@/components/color-input';
import { Input } from '@/components/input';
import { Label } from '@/components/label';
import { formSectionTitle } from '@/lib/ui';

export function SiteFormFields({ form, logoHint }) {
    return (
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
                        <Label htmlFor="company_name">Company name</Label>
                        <Input
                            id="company_name"
                            value={form.data.company_name}
                            onChange={(e) =>
                                form.setData('company_name', e.target.value)
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
                <div className="mt-4 space-y-4">
                    <div>
                        <Label htmlFor="logo">Logo URL or path</Label>
                        <p className="mt-0.5 text-xs text-neutral-500">
                            {logoHint}
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
                    <ColorInput
                        id="primary_color"
                        label="Primary color"
                        value={form.data.primary_color}
                        onChange={(value) =>
                            form.setData('primary_color', value)
                        }
                    />
                    <ColorInput
                        id="secondary_color"
                        label="Secondary color"
                        value={form.data.secondary_color}
                        onChange={(value) =>
                            form.setData('secondary_color', value)
                        }
                    />
                </div>
            </section>
        </div>
    );
}
