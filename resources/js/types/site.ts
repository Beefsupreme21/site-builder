import type { SiteTemplate } from '@/lib/site-templates';

export type Site = {
    id: number;
    slug: string;
    template: SiteTemplate;
    company_name: string;
    phone: string | null;
    email: string | null;
    logo: string | null;
    created_at: string;
    updated_at: string;
};
