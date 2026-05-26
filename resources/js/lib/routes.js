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
    create: (site, page) =>
        `/sites/${idOf(site)}/pages/${slugOf(page)}/blocks/create`,
    store: (site, page) => `/sites/${idOf(site)}/pages/${slugOf(page)}/blocks`,
    destroy: (site, page, blockPage) =>
        `/sites/${idOf(site)}/pages/${slugOf(page)}/blocks/${idOf(blockPage)}`,
    move: (site, page, blockPage, direction) =>
        `/sites/${idOf(site)}/pages/${slugOf(page)}/blocks/${idOf(blockPage)}/move/${direction}`,
};

export const sitePreview = {
    home: (site) => `/preview/${slugOf(site)}`,
    page: (site, page) => `/preview/${slugOf(site)}/${slugOf(page)}`,
};

export const chat = {
    index: () => '/chat',
    store: () => '/chat',
    clear: () => '/chat/clear',
};
