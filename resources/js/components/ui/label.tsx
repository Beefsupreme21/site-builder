import { cn } from '@/lib/utils';
import type { ComponentProps } from 'react';

export function Label({ className, ...props }: ComponentProps<'label'>) {
    return (
        <label
            className={cn(
                'block text-sm font-medium text-neutral-700',
                className,
            )}
            {...props}
        />
    );
}
