<?php

namespace App\Services;

use App\Zoho\Client\TokenStore;
use App\Zoho\Client\ZohoAPI;
use Illuminate\Support\Facades\Cache;

class ZohoReportService
{
    protected ZohoAPI $api;

    public function __construct()
    {
        $token_store = new TokenStore;
        $this->api = new ZohoAPI($token_store);
    }

    public function getReports(array $parameters)
    {
        $cacheKey = 'zoho_reports_'.md5(json_encode($parameters));

        return Cache::remember($cacheKey, 300, function () use ($parameters) {
            return $this->api->get('reports/profitandloss', $parameters);
        });
    }

    public function getInvoices(array $parameters)
    {
        $response = $this->api->get('invoices', $parameters);

        return $response;
    }

    public function formatProfitLoss(array $datefilter, array $data, array $budgets = [])
    {
        $grossProfit = collect($data['profit_and_loss'])
            ->firstWhere('name', 'Gross Profit');

        $operatingIncome = collect($grossProfit['account_transactions'])
            ->firstWhere('name', 'Operating Income');

        $cogs = collect($grossProfit['account_transactions'])
            ->firstWhere('name', 'Cost of Goods Sold');
        $sales = $operatingIncome['total'];
        $costOfGoodsSold = $cogs['total'];
        $netProfit = $grossProfit['total'];

        $salesAccountId = $operatingIncome['account_transactions'][0]['account_id'] ?? null;
        $cogsAccountId = $cogs['account_transactions'][0]['account_id'] ?? null;

        return [
            'sales' => [
                'actual' => $sales,
                'budget' => $budgets['sales'] ?? 0,
                'variance' => $sales - ($budgets['sales'] ?? 0),
                'transactions' => $operatingIncome['account_transactions'],
                'report_url' => $salesAccountId
                ? $this->buildZohoReportUrl(
                    $salesAccountId,
                    $datefilter
                )
                : null,
            ],

            'cogs' => [
                'actual' => $costOfGoodsSold,
                'budget' => $budgets['cogs'] ?? 0,
                'variance' => $costOfGoodsSold - ($budgets['cogs'] ?? 0),
                'transactions' => $cogs['account_transactions'],
                'report_url' => $cogsAccountId
                    ? $this->buildZohoReportUrl(
                        $cogsAccountId,
                        $datefilter
                    ) : null,
            ],

            'net_profit' => [
                'actual' => $netProfit,
                'budget' => ($budgets['sales'] ?? 0) -
                    ($budgets['cogs'] ?? 0),

                'variance' => $netProfit -
                    (
                        ($budgets['sales'] ?? 0) -
                        ($budgets['cogs'] ?? 0)
                    ),
            ],
        ];
    }

    private function buildZohoReportUrl(
        string $accountId,
        array $datefilter
    ): string {

        $rule = json_encode([
            'columns' => [
                [
                    'index' => 1,
                    'field' => 'account_id',
                    'value' => [$accountId],
                    'comparator' => 'in',
                    'group' => 'report',
                ],
            ],
            'criteria_string' => '1',
        ]);

        return sprintf(
            'https://books.zoho.in/app/%s#/reports/accounttxns?cash_based=false&from_date=%s&to_date=%s&rule=%s&sort_column=date&sort_order=A',
            config('app.zoho.organization_id'),
            $datefilter['from_date'],
            $datefilter['to_date'],
            urlencode($rule)
        );
    }
}
