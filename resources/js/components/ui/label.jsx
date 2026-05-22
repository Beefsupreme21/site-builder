import { cn } from '@/lib/utils';

export function Label({ className, ...props }) {
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
