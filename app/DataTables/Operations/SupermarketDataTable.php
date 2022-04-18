<?php

namespace App\DataTables\Operations;

use App\Models\Restaurant;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class SupermarketDataTable extends DataTable
{
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
            ->editColumn('image', function ($restaurant) {
                return getMediaColumn($restaurant, 'image');
            })
            ->editColumn('updated_at', function ($restaurant) {
                return getDateColumn($restaurant, 'updated_at');
            })
            ->editColumn('closed', function ($supermarket) {
                return getNotBooleanColumn($supermarket, 'closed');
            })
            ->editColumn('featured', function ($food) {
                return getBooleanColumn($food, 'featured');
            })
            ->editColumn('available_for_delivery', function ($food) {
                return getBooleanColumn($food, 'available_for_delivery');
            })
            ->editColumn('active', function ($supermarket) {
                return getBooleanColumn($supermarket, 'active');
            })
            ->addColumn('action', 'operations.supermarkets.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Operations/Supermarket $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Restaurant $model)
    {
        $query = $model->where('merchant_type', 'SUPERMARKET')->newQuery();

        $query->when(request()->filled('name'), function ($q) {
            return $q->where('name', 'like',  '%' . request('name') . '%');
        });
        $query->when(request()->filled('address'), function ($q) {
            return $q->where('address', 'like',  '%' . request('address') . '%');
        });
        $query->when(request()->filled('phone'), function ($q) {
            return $q->where('phone', 'like',  '%' . request('phone') . '%');
        });
        $query->when(request()->filled('mobile'), function ($q) {
            return $q->where('mobile', 'like',  '%' . request('mobile') . '%');
        });

        return $query;
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'data' => 'image',
                'title' => trans('lang.logo'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
            ],
            [
                'data' => 'name',
                'title' => trans('lang.name'),

            ],
            [
                'data' => 'address',
                'title' => trans('lang.address'),

            ],
            [
                'data' => 'phone',
                'title' => trans('lang.phone'),

            ],
            [
                'data' => 'mobile',
                'title' => trans('lang.mobile'),

            ],
            [
                'data' => 'available_for_delivery',
                'title' => trans('lang.supermarket_available_for_delivery'),

            ],
            [
                'data' => 'closed',
                'title' => trans('lang.closed'),

            ],
            [
                'data' => 'featured',
                'title' => trans('lang.featured'),

            ],
            [
                'data' => 'active',
                'title' => trans('lang.active'),

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.restaurant_updated_at'),
                'searchable' => false,
            ]
        ];
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'supermarkets_datatable_' . time();
    }
}
