import { cn } from '@/lib/utils';
import { forwardRef } from 'react';

export const Input = forwardRef(function Input(
    { className, type = 'text', ...props },
    ref,
) {
    return (
        <input
            ref={ref}
            type={type}
            className={cn(
                'mt-1 w-full max-w-md rounded border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-neutral-500 focus:ring-1 focus:ring-neutral-400 focus:outline-none',
                className,
            )}
            {...props}
        />
    );
});
