<?php

namespace App\DataTables\Operations;

use App\Models\Note;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class NoteDataTable extends DataTable
{
    private $datatables_actions_path;

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
            ->addColumn('created_at', function ($note) {
                return $note->created_at->diffForHumans();
            })
            ->addColumn('text', function ($note) {
                return $note->text;
            })
            ->addColumn('action', function ($note) {
                return view('operations.settings.note.datatables_actions', ['id'=>$note->id,'user_id'=>$this->id,'datatables_actions_path'=>$this->datatables_actions_path]);
            })
            ;


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
        return $this->getQuery($model,$this->id);
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
            Button::make('create')
        );
        
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
            // [
            //     'data' => 'order',
            //     'title' => "Order from",
            // ],
            [
                'data' => 'text',
                'title' => "Notes",
                'searchable' => false,
            ],
            [
                'data' => 'created_at',
                'title' => trans('lang.favorite_updated_at'),
            ],
            [
                'data' => 'action',
                'title' => trans('lang.actions'),
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
    public function getQuery($model,$id)
    { 
        switch (Route::currentRouteName()) {
            case "operations.users.profile.notes":
                $this->datatables_actions_path='operations.users.profile.destroyNote';
                return $model->newQuery()->where('to_user_id', $id)
                        ->with('fromUser:id,name');
            break;
            case "operations.restaurant_profile.note.index":
                $this->datatables_actions_path='operations.restaurant_profile.note.destroy';
                return $model->newQuery()->where('restaurant_id', $id)
                        ->with('fromUser:id,name');
                break;
        }
    }
}
