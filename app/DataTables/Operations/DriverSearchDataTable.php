<?php

namespace App\DataTables\Operations;

use App\Models\CustomField;
use App\Models\Driver;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class DriverSearchDataTable extends DataTable
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
            ->editColumn('id', function ($driver) {
                return getLinksColumnByRouteName([$driver], "operations.drivers.show", 'id', 'id');
            })
            ->editColumn('updated_at', function ($driver) {
                return getDateColumn($driver, 'updated_at');
            })
            ->editColumn('total_orders', function ($driver) {
                return $driver->orders->count();
            })
            ->editColumn('earning', function ($driver) {
                return $driver->totalEarning();
            })
            ->editColumn('delivery_fee', function ($driver) {
                return $driver->delivery_fee . "%";
            })
            ->editColumn('available', function ($driver) {
                return getBooleanColumn($driver, 'available');
            })
            ->editColumn('working_on_order', function ($driver) {
                return getBooleanColumn($driver, 'working_on_order');
            })
            ->addColumn('action', 'operations.drivers.datatables_actions')
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
                'data' => 'id',
                'title' => trans('lang.driver_user_id'),

            ],
            [
                'data' => 'user.name',
                'title' => trans('lang.user_name'),

            ],
            [
                'data' => 'user.phone_number',
                'title' => trans('lang.user_phone_number'),

            ],
            [
                'data' => 'total_orders',
                'title' => trans('lang.driver_total_orders'),

            ],
            [
                'data' => 'earning',
                'title' => trans('lang.driver_earning'),

            ],
            [
                'data' => 'type',
                'title' => trans('lang.driver_type'),

            ],
            [
                'data' => 'available',
                'title' => trans('lang.driver_available'),
            ],
            [
                'data' => 'working_on_order',
                'title' => trans('lang.driver_working_on_order'),
            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.driver_updated_at'),
                'searchable' => false,
            ]
        ];

        // $hasCustomField = in_array(Driver::class, setting('custom_field_models', []));
        // if ($hasCustomField) {
        //     $customFieldsCollection = CustomField::where('custom_field_model', Driver::class)->where('in_table', '=', true)->get();
        //     foreach ($customFieldsCollection as $key => $field) {
        //         array_splice($columns, $field->order - 1, 0, [[
        //             'data' => 'custom_fields.' . $field->name . '.view',
        //             'title' => trans('lang.driver_' . $field->name),
        //             'orderable' => false,
        //             'searchable' => false,
        //         ]]);
        //     }
        // }
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Driver $model)
    {
        $query = $model->newQuery();

        if (request()->filled('phone_number')) {
            $query->whereHas('user', function ($q) {
                $q->where('phone_number', 'like',  '%' . request('phone_number') . '%');
            });
        }
        if (request()->filled('name')) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like',  '%' . request('name') . '%');
            });
        }

        if (request()->filled('type')) {
            $query->where('type', 'like',  '%' . request('type') . '%');
        }
        if (request()->filled('id')) {
            $query->where('id', 'like',  '%' . request('id') . '%');
        }



        return $query->with(["user", 'driverType']);
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
                config('datatables-buttons.parameters'),
                [
                    'language' => json_decode(
                        file_get_contents(
                            base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ),
                        true
                    )
                ]
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
        return 'driversdatatable_' . time();
    }
}
