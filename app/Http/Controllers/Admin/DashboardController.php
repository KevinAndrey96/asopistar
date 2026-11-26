<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Reports\AssociationReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

final class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', ['report' => AssociationReport::build()]);
    }

    public function report(): Response
    {
        $report = AssociationReport::build();

        return Pdf::loadView('reports.association', ['report' => $report])
            ->setPaper('letter')
            ->stream('reporte-asociacion-'.$report->generatedOn->toDateString().'.pdf');
    }
}
