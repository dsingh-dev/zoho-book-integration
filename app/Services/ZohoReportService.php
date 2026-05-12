<?php

namespace App\Services;

use App\Models\ZohoAccount;
use App\Zoho\Client\TokenStore;
use App\Zoho\Client\ZohoAPI;

class ZohoReportService
{
    protected ZohoAPI $api;

    public function __construct(protected ZohoAccount $account)
    {
        $token_store = new TokenStore;
        $this->api = new ZohoAPI($token_store);
    }

    public function getReports(array $parameters)
    {
        $response = $this->api->get('reports/profitandloss', $parameters);

        return $response;
    }

    public function getInvoices(array $parameters)
    {
        $response = $this->api->get('invoices', $parameters);

        return $response;
    }

    public function formatProfitLoss(array $data, array $budgets = [])
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

        return [
            'sales' => [
                'actual' => $sales,
                'budget' => $budgets['sales'] ?? 0,
                'variance' => $sales - ($budgets['sales'] ?? 0),
                'transactions' => $operatingIncome['account_transactions'],
            ],

            'cogs' => [
                'actual' => $costOfGoodsSold,
                'budget' => $budgets['cogs'] ?? 0,
                'variance' => $costOfGoodsSold - ($budgets['cogs'] ?? 0),
                'transactions' => $cogs['account_transactions'],
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
}
