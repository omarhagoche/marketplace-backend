<?php

namespace App\Http\Controllers\Operations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\OrderStatusRepository;
use App\DataTables\Operations\OrderSearchDataTable;

class Dashboard extends Controller
{
    public function __construct(OrderStatusRepository $orderStatusRepo)
    {
        parent::__construct();
        $this->orderStatusRepository = $orderStatusRepo;
        
    }
    public function index(OrderSearchDataTable $orderDataTable)
    {
        // dd(request(),request()->filled('order_status'),request('order_status'));
        $orderStatuses=$this->orderStatusRepository->all();
        return $orderDataTable->render('operations.dashboard.order',compact('orderStatuses'));
    }
}
