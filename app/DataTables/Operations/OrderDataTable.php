<?php

/**
 * File name: OrderDataTable.php
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

class OrderDataTable extends DataTable
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
            ->editColumn('date_of_order', function ($order) {
                return $order->created_at;
            })
            ->editColumn('created_at', function ($order) {
                return getDateColumn($order, 'created_at');
            })
            ->editColumn('updated_at', function ($order) {
                return getDateColumn($order, 'updated_at');
            })
            ->editColumn('delivery_fee', function ($order) {
                return getPriceColumn($order, 'delivery_fee');
            })
            // ->editColumn('tax', function ($order) {
            //     return $order->tax . "%";
            // })
            // ->editColumn('payment.status', function ($order) {
            //     return getPayment($order->payment, 'status');
            // })
            // ->editColumn('active', function ($food) {
            //     return getBooleanColumn($food, 'active');
            // })
            ->addColumn('action', 'operations.settings.order.datatables_actions')
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
                'data' => 'restaurant.name',
                'name' => 'restaurant.name',
                'title' => trans('lang.restaurant'),
            ],
            // [
            //     'data' => 'user.name',
            //     'name' => 'user.name',
            //     'title' => trans('lang.order_user_id'),
            // ],
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
            /* [
                'data' => 'tax',
                'title' => trans('lang.order_tax'),
                'searchable' => false,

            ], */
            // [
            //     'data' => 'delivery_fee',
            //     'title' => trans('lang.order_delivery_fee'),
            //     'searchable' => false,

            // ],
            /*  [
                'data' => 'payment.status',
                'name' => 'payment.status',
                'title' => trans('lang.payment_status'),

            ],
            [
                'data' => 'payment.method',
                'name' => 'payment.method',
                'title' => trans('lang.payment_method'),

            ],
            [
                'data' => 'active',
                'title' => trans('lang.order_active'),

            ],*/
            [
                'name' => 'orders.delivery_datetime',
                'data' => 'delivery_datetime',
                'title' => trans('lang.delivery_datetime'),
                'searchable' => false,
                'orderable' => true,
            ],
            [
                'name' => 'orders.date_of_order',
                'data' => 'date_of_order',
                'title' => trans('lang.order_date'),
                'searchable' => false,
                'orderable' => true,
            ],
            [
                'name' => 'orders.created_at',
                'data' => 'created_at',
                'title' => trans('lang.order_date'),
                'searchable' => false,
                'orderable' => true,
            ],
            // [
            //     'name' => 'orders.updated_at',
            //     'data' => 'updated_at',
            //     'title' => trans('lang.order_updated_at'),
            //     'searchable' => false,
            //     'orderable' => true,
            // ]
        ];

        /* $hasCustomField = in_array(Order::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Order::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.order_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        } */
        // return[
        //     Column::make('id'),
        //     Column::make('restaurant.name'),
        //     Column::make('driver.name'),
        //     // Column::make('for'),
        // ];
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
        return $model->newQuery()->with("user")->with('restaurant')->with("orderStatus")->with('payment')
            ->where('orders.user_id', $this->id);
       
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
