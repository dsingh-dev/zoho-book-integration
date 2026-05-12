import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { dashboard } from '@/routes';
import { Head } from '@inertiajs/react';
import { ArrowUpRightIcon } from 'lucide-react';
import { route } from 'ziggy-js';

type ReportSection = {
    actual: number;
    budget: number;
    variance: number;
};

type MonthData = {
    sales: ReportSection;
    cogs: ReportSection;
    net_profit: ReportSection;
};
export default function Dashboard({
    hasZohoAccount,
    reports
}: {
    hasZohoAccount: boolean;
    reports: {
        may_2026: MonthData;
        april_2026: MonthData;
    };
}) {
    const months = Object.entries(reports);

    const sections = [
        {
            title: 'Operating Income',
            rows: [
                    {
                        key: 'sales',
                        label: 'Sales',
                        totalLabel: 'Total for Operating Income',
                    },
                ],
            },
            {
                title: 'Cost of Goods Sold',
                rows: [
                    {
                        key: 'cogs',
                        label: 'Cost of Goods Sold',
                        totalLabel: 'Total for Cost of Goods Sold',
                    },
                ],
            },
        ];


    const formatCurrency = (value: number) => {
        return new Intl.NumberFormat('en-IN').format(value);
    };

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
                        <div className="overflow-auto rounded-md border">
                        <Table className="min-w-[1000px] border-collapse">
                            <TableHeader>
                                <TableRow className="bg-[#002060] hover:bg-[#002060]">
                                    {months.map(([monthKey]) => (
                                        <>
                                            <TableHead
                                                key={`${monthKey}-actual`}
                                                className="border text-center text-white font-bold"
                                            >
                                                {monthKey.replace('_', ' ').toUpperCase()}
                                            </TableHead>

                                            <TableHead className="border text-center text-white font-bold">
                                                Budget
                                            </TableHead>

                                            <TableHead className="border text-center text-white font-bold">
                                                Variance
                                            </TableHead>
                                            {monthKey == 'may_2026' && (
                                                <TableHead className="border text-center text-white font-bold">
                                                    Profit & Loss
                                                </TableHead>
                                            )}
                                        </>
                                    ))}
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                {sections.map((section) => (
                                    <>
                                        <TableRow key={section.title}>
                                            {months.map(([monthKey]) => (
                                                <>
                                                    <TableCell className="border" />
                                                    <TableCell className="border" />
                                                    <TableCell className="border" />

                                                    {monthKey == 'may_2026' && (
                                                        <TableCell className="border text-center text-lg font-semibold">
                                                            {section.title}
                                                        </TableCell>
                                                    )}
                                                </>
                                            ))}


                                        </TableRow>

                                        {section.rows.map((row) => (
                                            <TableRow key={row.key}>
                                                {months.map(([monthKey, monthData]) => {
                                                    const data = monthData[row.key];
                                                    return (
                                                        <>
                                                            <TableCell className="border text-center">
                                                                <button className="font-semibold text-blue-600 underline">
                                                                    {formatCurrency(data.actual)}
                                                                </button>
                                                            </TableCell>

                                                            <TableCell className="border text-center">
                                                                {formatCurrency(data.budget)}
                                                            </TableCell>

                                                            <TableCell className="border text-center">
                                                                {formatCurrency(data.variance)}
                                                            </TableCell>

                                                            {monthKey == 'may_2026' && (
                                                                <TableCell className="border text-center">
                                                                    {row.label}
                                                                </TableCell>
                                                            )}
                                                        </>
                                                    );
                                                })}
                                            </TableRow>
                                        ))}

                                        {section.rows.map((row) => (
                                            <TableRow key={`${row.key}-total`} className="font-bold">
                                                {months.map(([monthKey, monthData]) => {
                                                    const data = monthData[row.key];

                                                    return (
                                                        <>
                                                            <TableCell className="border text-center">
                                                                {formatCurrency(data.actual)}
                                                            </TableCell>

                                                            <TableCell className="border text-center">
                                                                {formatCurrency(data.budget)}
                                                            </TableCell>

                                                            <TableCell className="border text-center">
                                                                {formatCurrency(data.variance)}
                                                            </TableCell>

                                                            {monthKey == 'may_2026' && (
                                                               <TableCell className="border text-center">
                                                                    {row.totalLabel}
                                                                </TableCell>
                                                            )}
                                                        </>
                                                    );
                                                })}
                                            </TableRow>
                                        ))}

                                        <TableRow>
                                            {months.map(([monthKey]) => (
                                                <>
                                                    <TableCell className="border h-6" />
                                                    <TableCell className="border" />
                                                    <TableCell className="border" />
                                                </>
                                            ))}

                                            <TableCell className="border" />
                                        </TableRow>
                                    </>
                                ))}

                                <TableRow className="text-lg font-bold">
                                    {months.map(([monthKey, monthData]) => (
                                        <>
                                            <TableCell className="border text-center">
                                                {formatCurrency(monthData.net_profit.actual)}
                                            </TableCell>

                                            <TableCell className="border text-center">
                                                {formatCurrency(monthData.net_profit.budget)}
                                            </TableCell>

                                            <TableCell className="border text-center">
                                                {formatCurrency(monthData.net_profit.variance)}
                                            </TableCell>

                                            {monthKey == 'may_2026' && (
                                                <TableCell className="border text-center">
                                                    Net Profit/Loss
                                                </TableCell>
                                            )}
                                        </>
                                    ))}
                                </TableRow>

                            </TableBody>
                        </Table>
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
