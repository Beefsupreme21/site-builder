import { btnSecondary } from '@/lib/ui';
import { Link } from '@inertiajs/react';

export function PageHeader({ backHref, backLabel, title, subtitle, actions }) {
    return (
        <header className="mb-8">
            <div className="flex flex-wrap items-start justify-between gap-4">
                <div className="flex min-w-0 items-start gap-3">
                    {backHref ? (
                        <Link
                            href={backHref}
                            className={`${btnSecondary} mt-0.5 size-9 shrink-0 px-0`}
                            aria-label={backLabel ?? 'Back'}
                        >
                            ←
                        </Link>
                    ) : null}

                    <div className="min-w-0">
                        <h1 className="text-2xl font-semibold tracking-tight text-neutral-900">
                            {title}
                        </h1>
                        {subtitle ? (
                            <p className="mt-1 text-sm text-neutral-600">
                                {subtitle}
                            </p>
                        ) : null}
                    </div>
                </div>

                {actions ? (
                    <div className="flex flex-wrap gap-2">{actions}</div>
                ) : null}
            </div>
        </header>
    );
}
