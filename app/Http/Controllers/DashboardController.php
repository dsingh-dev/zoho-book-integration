<?php

namespace App\Http\Controllers;

use App\Services\ZohoReportService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(protected ZohoReportService $zoho_report_service) {}

    public function index(): Response
    {
        $hasZohoAccount = auth()->user()->zohoAccount !== null;

        $reports = $this->getReports();

        return Inertia::render('dashboard', [
            'hasZohoAccount' => $hasZohoAccount,
            'reports' => $reports,
        ]);
    }

    public function getReports()
    {
        $reports_april = $this->zoho_report_service->getReports([
            'from_date' => '2026-04-01',
            'to_date' => '2026-04-01',
        ]);
        $reports_may = $this->zoho_report_service->getReports([
            'from_date' => '2026-05-01',
            'to_date' => '2026-05-01',
        ]);

        return [
            'may_2026' => $this->zoho_report_service->formatProfitLoss($reports_may, [
                'sales' => 225000,
                'cogs' => 50000,
            ]),

            'april_2026' => $this->zoho_report_service->formatProfitLoss($reports_april, [
                'sales' => 115000,
                'cogs' => 80000,
            ]),
        ];
    }
}
