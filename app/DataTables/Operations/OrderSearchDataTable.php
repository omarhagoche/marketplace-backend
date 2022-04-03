<?php

namespace App\DataTables\Operations;

use App\Models\Order;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderSearchDataTable extends DataTable
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
        return datatables()->eloquent($query)
            ->editColumn('orders.id', function ($order) {
                return "#" . $order->id;
            })
            ->editColumn('restaurant.name', function ($order) {
                // return $order->restaurant->name;

                if (!$order->restaurant) {
                    return '---';
                }
                return getLinksColumnByRouteName([$order->restaurant], 'restaurants.edit', 'id', 'name');
            })
            ->editColumn('user.name', function ($order) {
                // return $order->user.name;
                if (!$order->user) {
                    return $order->user->name ?? '----';
                }
                return getLinksColumnByRouteName([$order->user], "users.edit", 'id', 'name');
            })
            ->editColumn('driver.name', function ($order) {

                if (!$order->driver) {
                    return '---'; // trans('lang.order_driver_not_assigned');
                }
                return getLinksColumnByRouteName([$order->driver], "users.edit", 'id', 'name');
            })
            ->editColumn('date_of_order', function ($order) {
                return $order->created_at;
            })
            ->editColumn('created_at', function ($order) {
                // return $order->created_at->format('d/m/Y');
                return getDateColumn($order, 'created_at');
            })
            ->editColumn('updated_at', function ($order) {
                return getDateColumn($order, 'updated_at');
            })
            ->editColumn('delivery_fee', function ($order) {
                return getPriceColumn($order, 'delivery_fee');
            })
            ->editColumn('tax', function ($order) {
                return $order->tax . "%";
            })
            ->editColumn('payment.status', function ($order) {
                return getPayment($order->payment, 'status');
            })
            ->editColumn('active', function ($food) {
                return getBooleanColumn($food, 'active');
            })
            ->addColumn('action', 'operations.orders.datatables_actions')
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
                'data' => 'orders.id',
                'title' => trans('lang.order_id'),
            ],
            [
                'data' => 'restaurant.name',
                'name' => 'restaurant.name',
                'title' => trans('lang.restaurant'),
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
            ],
            [
                'data' => 'order_status.status',
                'name' => 'order_status.status',
                'title' => trans('lang.order_order_status_id'),
            ],
            /* [
                'data' => 'tax',
                'title' => trans('lang.order_tax'),
                'searchable' => false,

            ], */
            [
                'data' => 'delivery_fee',
                'title' => trans('lang.order_delivery_fee'),
                'searchable' => true,

            ],
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
                // 'searchable' => true,
                // 'orderable' => true,
            ],
            // [
            //     'name' => 'orders.updated_at',
            //     'data' => 'updated_at',
            //     'title' => trans('lang.order_updated_at'),
            //     'searchable' => false,
            //     'orderable' => false,
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
        $query=$model->newQuery();
  
        if (request()->filled('start_date') && request()->filled('end_date')) {
            $query->whereBetween('created_at', [request('start_date'), request('end_date')]);
        }
        if (request()->filled('start_date') && !request()->filled('end_date')) {
            $query->where('created_at', '>=', request('start_date'));
        }
        if (!request()->filled('start_date') && request()->filled('end_date')) {
            $query->where('created_at', '<=', request('end_date'));
        }
        if (request()->filled('restaurant')) {
            $query->whereHas('restaurant', function($q){
                $q->where('name', 'like',  '%'.request('restaurant').'%');
            });  
        }
        if (request()->filled('order_id')) {
            $query->where('id', request('order_id'));
        }
        if (request()->filled('client')) {
            $query->whereHas('user', function($q){
                $q->where('name', 'like',  '%'.request('client').'%');
            });  
        }
        if (request()->filled('driver')) {
            $query->whereHas('driver', function($q){
                $q->where('name', 'like',  '%'.request('driver').'%');
            });  
        }
        if (request()->filled('order_status')) {
            $query->whereHas('orderStatus', function($q){
                $q->where('id',  request('order_status'));

                // $q->where('status', 'like',  '%'.request('order_status').'%');
            });  
        }
        // if (request()->filled('client')) {
        //     $query->user()->where('name', 'like', '%'.request('client').'%');     
        // }
        // if (!request()->filled('client')) {
        //     $query->where('created_at', '<=', request('end_date'));
        // }
        // if (!request()->filled('driver')) {
        //     $query->where('created_at', '<=', request('end_date'));
        // }
        return $query
        ->orderByDesc('id')
        ->with("user:id,name", "restaurant:id,name", "driver:id,name","orderStatus:id,status","payment:id,status");

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['title' => trans('lang.actions'), 'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                [
                    'language' => json_decode(
                        file_get_contents(
                            base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ),
                        true
                    ),
                    'order' => [[0, 'desc']],
                ],
                config('datatables-buttons.parameters')
            ));
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
// {
//     /**
//      * Build DataTable class.
//      *
//      * @param mixed $query Results from query() method.
//      * @return \Yajra\DataTables\DataTableAbstract
//      */
//     public function dataTable($query)
//     {
//         return datatables()
//             ->eloquent($query)
//             ->addColumn('action', 'operations/ordersearch.action')
//             ;
//     }

//     /**
//      * Get query source of dataTable.
//      *
//      * @param \App\Models\Operations/OrderSearch $model
//      * @return \Illuminate\Database\Eloquent\Builder
//      */
//     public function query(Order $model)
//     {
//         return $model->newQuery();
//     }

//     /**
//      * Optional method if you want to use html builder.
//      *
//      * @return \Yajra\DataTables\Html\Builder
//      */
//     public function html()
//     {
//         return $this->builder()
//                     ->setTableId('operations/ordersearch-table')
//                     ->columns($this->getColumns())
//                     ->minifiedAjax()
//                     ->dom('Bfrtip')
//                     ->orderBy(1)
//                     ->buttons(
//                         Button::make('create'),
//                         Button::make('export'),
//                         Button::make('print'),
//                         Button::make('reset'),
//                         Button::make('reload')
//                     );
//     }

//     /**
//      * Get columns.
//      *
//      * @return array
//      */
//     protected function getColumns()
//     {
//         return [
//             Column::computed('action')
//                   ->exportable(false)
//                   ->printable(false)
//                   ->width(60)
//                   ->addClass('text-center'),
//             Column::make('id'),
//             Column::make('name'),
//             Column::make('created_at'),
//             Column::make('updated_at'),
//         ];
//     }

//     /**
//      * Get filename for export.
//      *
//      * @return string
//      */
//     protected function filename()
//     {
//         return 'Operations/OrderSearch_' . date('YmdHis');
//     }
// }
