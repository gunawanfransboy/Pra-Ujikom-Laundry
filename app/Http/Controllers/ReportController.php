<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Transaksi yang statusnya "Sudah Diambil" (1) dianggap selesai dan masuk laporan penjualan
        $query = TransOrder::with(['customer', 'details.service'])
            ->where('order_status', 1)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->orderBy('order_date', 'asc');

        $orders = $query->get();
        $totalRevenue = $orders->sum('total');

        return view('report.index', compact('orders', 'startDate', 'endDate', 'totalRevenue'));
    }
}
