export const SITE_TEMPLATES = [
    { value: 'default', label: 'Default' },
    { value: 'alternate', label: 'Alternate' },
] as const;

export type SiteTemplate = (typeof SITE_TEMPLATES)[number]['value'];
