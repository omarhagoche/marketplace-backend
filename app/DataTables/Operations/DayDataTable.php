<?php

namespace App\DataTables\Operations;

use App\Models\Day;
use App\Models\Restaurant;
use App\Models\CustomField;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class DayDataTable extends DataTable
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
            // ->editColumn('open_at', function ($day) {
            //     // return $day->pivot->open_at;
            //     return getDateColumn($day, 'pivot_open_at');
            // })
            ->addColumn('action', function ($day) {
                    return view('operations.restaurantProfile.days.datatables_actions',['id'=>$day->day_id,'restaurant_id'=>$this->id]);
                })
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Day $model)
    {
        
        return Restaurant::find($this->id)->days();
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
            ->addAction(['width' => '80px', 'printable' => false ,'responsivePriority'=>'100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/'.app()->getLocale().'/datatable.json')
                        ),true)
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
            // [
            //     'data' => 'day.id',
            //     'title' => '#',

            // ],
            [
                'data' => 'name',
                'title' => 'Day',

            ],
            // [
            //     'data' => 'order',
            //     'title' => "Order from",
            // ],
            [
                'data' => 'open_at',
                'title' => "Open",
            ],
            [
                'data' => 'close_at',
                'title' => "close",
            ],
            // [
            //     'data' => 'created_at',
            //     'title' => trans('lang.favorite_updated_at'),
            // ],
            // [
            //     'data' => 'action',
            //     'title' => trans('lang.actions'),
            // ]
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
        return 'daysdatatable_' . time();
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename().'.pdf');
    }
}