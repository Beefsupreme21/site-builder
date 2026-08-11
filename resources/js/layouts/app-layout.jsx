import { sites } from '@/lib/routes';
import { Link, usePage } from '@inertiajs/react';

const widths = {
    wide: 'max-w-5xl',
    form: 'max-w-xl',
    narrow: 'max-w-3xl',
};

export default function AppLayout({ children, width = 'wide' }) {
    const { auth } = usePage().props;
    const contentWidth = widths[width] ?? widths.wide;

    return (
        <div className="min-h-screen bg-neutral-50">
            <header className="border-b border-neutral-200 bg-white">
                <div className="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
                    <Link
                        href={sites.index()}
                        className="flex shrink-0 items-center"
                    >
                        <img
                            src="/images/logo.png"
                            alt="SiteBuilder"
                            className="h-9 w-auto"
                        />
                    </Link>

                    {auth?.user ? (
                        <span className="text-sm text-neutral-600">
                            {auth.user.name ?? auth.user.email}
                        </span>
                    ) : (
                        <Link
                            href="/login"
                            className="text-sm font-medium text-neutral-700 hover:text-neutral-900"
                        >
                            Log in
                        </Link>
                    )}
                </div>
            </header>

            <main className={`mx-auto px-4 py-8 sm:px-6 ${contentWidth}`}>
                {children}
            </main>
        </div>
    );
}
