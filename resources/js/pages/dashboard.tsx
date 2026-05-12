import { Head } from '@inertiajs/react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { dashboard } from '@/routes';
import { Button } from '@/components/ui/button';
import { ArrowUpRightIcon, Link } from 'lucide-react';
import { route } from 'ziggy-js';

export default function Dashboard({
    hasZohoAccount
}: {
    hasZohoAccount: boolean
}) {
    return (
        <>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                    {!hasZohoAccount ? (
                        <div className="flex justify-center items-center gap-2">
                            <Button onClick={() => window.location.href = route('zoho.connect')} size="sm" aria-label="Add Zoho Account" variant="outline">
                                Add Zoho Account <ArrowUpRightIcon />
                            </Button>
                        </div>
                    ) : (
                        <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                            <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                        </div>
                    )}
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
