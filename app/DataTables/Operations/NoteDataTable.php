<?php

namespace App\DataTables\Operations;

use App\Models\Note;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Barryvdh\DomPDF\Facade as PDF;

class NoteDataTable extends DataTable
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
            ->addColumn('user', function ($note) {
                // return getDateColumn($note->user, 'name');

                return $note->fromUser->name;
            })
            ->addColumn('order', function ($note) {
                // return getDateColumn($note->user, 'name');

               return $note->order->restaurant?$note->order->restaurant->name:"لا يوجد مطعم";
            })
            ->addColumn('text', function ($note) {
                return $note->text;
            });


        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Operations/Note $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Note $model)
    {
        return $model->newQuery()->where('to_user_id', $this->userId);
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
        ->minifiedAjax();
        
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
                'data' => 'user',
                'title' => 'From User ',

            ],
            [
                'data' => 'order',
                'title' => "Order from",
            ],
            [
                'data' => 'text',
                'title' => "Notes",
                'searchable' => false,
            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.favorite_updated_at'),
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
        return 'Operations/Note_' . date('YmdHis');
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
