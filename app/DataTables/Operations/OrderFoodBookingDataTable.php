<?php

/**
 * File name: OrderFoodBookingDataTable.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables\Operations;

use App\Models\Order;
use App\Models\CustomField;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class OrderFoodBookingDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');

        $dataTable = $dataTable
            ->editColumn('id', function ($order) {
                return "#" . $order->id;
            })
            ->addColumn('restaurant.name', function ($order) {
                if ($order->restaurant) {
                    return $order->restaurant->name;
                }
                return '-------';
            })->addColumn('driver.name', function ($order) {
                if (!$order->driver) {
                    return '---'; // trans('lang.order_driver_not_assigned');
                }
                return getLinksColumnByRouteName([$order->driver], "users.edit", 'id', 'name');
            })
            ->editColumn('created_at', function ($order) {
                return getDateColumn($order, 'created_at');
            })
            ->editColumn('delivery_datetime', function ($order) {
                return getDateColumn($order, 'delivery_datetime');
            })
            // ->addColumn('action', 'operations.settings.order.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                "name" => 'orders.id',
                'data' => 'id',
                'title' => trans('lang.order_id'),
            ],
            [
                'data' => 'user.name',
                'name' => 'user.name',
                'title' => trans('lang.order_user_id'),
            ],
            [
                'data' => 'driver.name',
                'name' => 'driver.name',
                'title' => trans('lang.order_driver_id'),
                'searchable' => true,
            ],
            [
                'data' => 'order_status.status',
                'name' => 'orderStatus.status',
                'title' => trans('lang.order_order_status_id'),
            ],
            [
                'name' => 'orders.created_at',
                'data' => 'created_at',
                'title' => trans('lang.order_date'),
                'searchable' => false,
                'orderable' => true,
            ],
            [
                'name' => 'delivery_datetime ',
                'data' => 'delivery_datetime ',
                'title' => trans('lang.delivery_datetime'),
                'searchable' => false,
                'orderable' => true,
            ],

        ];

        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {
        return $model->newQuery()->where('order_status_id',141)->with("user")->with('restaurant')->with("orderStatus")->with('payment')
            ->where('orders.restaurant_id', $this->id)
            ;
       
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('test-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('reload')
                    );
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ordersdatatable_' . time();
    }
}
