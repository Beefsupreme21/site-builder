/**
 * Lightweight URL builders for the app's named routes.
 *
 * Backed by routes/web.php; keep in sync when adding new routes.
 */

const idOf = (model) => (typeof model === 'object' ? model.id : model);
const slugOf = (model) => (typeof model === 'object' ? model.slug : model);

export const sites = {
    index: () => '/sites',
    create: () => '/sites/create',
    store: () => '/sites',
    show: (site) => `/sites/${idOf(site)}`,
    edit: (site) => `/sites/${idOf(site)}/edit`,
    update: (site) => `/sites/${idOf(site)}`,
    destroy: (site) => `/sites/${idOf(site)}`,
};

export const sitePages = {
    create: (site) => `/sites/${idOf(site)}/pages/create`,
    store: (site) => `/sites/${idOf(site)}/pages`,
    show: (site, page) => `/sites/${idOf(site)}/pages/${slugOf(page)}`,
    edit: (site, page) => `/sites/${idOf(site)}/pages/${slugOf(page)}/edit`,
    update: (site, page) => `/sites/${idOf(site)}/pages/${slugOf(page)}`,
    destroy: (site, page) => `/sites/${idOf(site)}/pages/${slugOf(page)}`,
};

export const pageBlocks = {
    create: (page, category) => {
        const url = `/pages/${idOf(page)}/blocks/create`;

        return category ? `${url}?category=${category}` : url;
    },
    store: (page) => `/pages/${idOf(page)}/blocks`,
    destroy: (page, block) => `/pages/${idOf(page)}/blocks/${idOf(block)}`,
    move: (block, direction) => `/blocks/${idOf(block)}/move/${direction}`,
};

export const sitePreview = {
    home: (site) => `/preview/sites/${slugOf(site)}`,
    page: (page) => `/preview/${idOf(page)}`,
};
