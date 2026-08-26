/**
 * Parse a fetch Response as JSON, with a clear error when the server returns HTML.
 */
export async function parseJsonResponse(response) {
    const contentType = response.headers.get('content-type') ?? '';
    const body = await response.text();

    if (!contentType.includes('application/json')) {
        const snippet = body.replace(/\s+/g, ' ').slice(0, 120);

        throw new Error(
            `Server returned ${response.status} (${contentType || 'unknown type'}), not JSON. ${snippet}`,
        );
    }

    try {
        return JSON.parse(body);
    } catch {
        throw new Error('Server returned invalid JSON.');
    }
}
