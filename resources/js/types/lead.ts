import type { Site } from '@/types/site';

export type Lead = {
    id: number;
    site_id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string | null;
    message: string | null;
    created_at: string;
    updated_at: string;
    site: Pick<Site, 'id' | 'company_name' | 'slug'>;
};
