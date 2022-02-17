<?php

namespace App\DataTables\Operations;

use App\Models\Favorite;
use App\Models\CustomField;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class FavoriteDataTable extends DataTable
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
            ->editColumn('updated_at', function ($favorite) {
                return getDateColumn($favorite, 'updated_at');
            })
            ->editColumn('extras', function ($favorite) {
                return getArrayColumn($favorite->extras, 'name');
            })
            ->addColumn('restaurant', function ($favorite) {
                return $favorite->food->restaurant->name;
            })
            ->addColumn('action', 'operations.settings.favorite.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Favorite $model)
    {
        return $model->newQuery()->with("food")->with("user")
        ->where('user_id', $this->userId);   
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
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
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
                'data' => 'food.name',
                'title' => trans('lang.favorite_food_id'),

            ],
            [
                'data' => 'extras',
                'title' => trans('lang.favorite_extras'),
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'restaurant',
                'title' => trans('lang.restaurant'),
                'searchable' => false,
            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.favorite_updated_at'),
                'searchable' => false,
            ]
        ];
        $columns = array_filter($columns);
        $hasCustomField = in_array(Favorite::class, setting('custom_field_models', []));
        // if ($hasCustomField) {
        //     $customFieldsCollection = CustomField::where('custom_field_model', Favorite::class)->where('in_table', '=', true)->get();
        //     foreach ($customFieldsCollection as $key => $field) {
        //         array_splice($columns, $field->order - 1, 0, [[
        //             'data' => 'custom_fields.' . $field->name . '.view',
        //             'title' => trans('lang.favorite_' . $field->name),
        //             'orderable' => false,
        //             'searchable' => false,
        //         ]]);
        //     }
        // }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'favoritesdatatable_' . time();
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
}
