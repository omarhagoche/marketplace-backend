<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\CustomField;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{

    /**
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
        $columns = array_column($this->getColumns(), 'data');
        return  datatables()
        ->eloquent($query)
            ->editColumn('activated_at', function ($user) {
                return getDateColumn($user, 'activated_at');
            })
            ->editColumn('updated_at', function ($user) {
                return getDateColumn($user, 'updated_at');
            })
            ->editColumn('role', function ($user) {
                return getArrayColumn($user->roles, 'name');
            })
            ->editColumn('email', function ($user) {
                return getEmailColumn($user, 'email');
            })
            ->editColumn('avatar', function ($user) {
                return getMediaColumn($user, 'avatar', 'img-circle elevation-2');
            })
            // ->addColumn('action', $this->getActionPage())
            ->addColumn('action', function ($user) {
                return view($this->getActionPage(), ['id'=>$user->id,'restaurant_id'=>$this->id]);
            })
            ->rawColumns(array_merge($columns, ['action']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        // return $model->newQuery()->with('roles');

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
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters([
                'dom'          => 'Bfrtip',
                'buttons'      => ['create'],
                'initComplete' => 'function () {
                    var columns = this.api().init().columns;
                    this.api().columns().every(function (index) {
                      var column = this;
                      var input = document.createElement("input");
                      input.setAttribute("style","width:150px;")
                      input.classList.add("form-control")
                      input.placeholder = columns[index]["name"]
                      console.log(columns[index]["name"])
                      if(columns[index].searchable){
                      $(input).
                        appendTo($(column.footer()).empty()).
                        on(\'change\', function () {
                          column.search($(this).val(), false, false, true).draw();
                        });
                    }
                    });
                  }'
            //     array_merge(
            //     config('datatables-buttons.parameters'), [
            //         'language' => json_decode(
            //             file_get_contents(base_path('resources/lang/'.app()->getLocale().'/datatable.json')
            //         ),true)
            //     ]
            // )
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        // TODO custom element generator
        $columns = [
            // [
            //     'data' => 'avatar',
            //     'title' => trans('lang.user_avatar'),
            //     'orderable' => false, 'searchable' => false,

            // ],
            [
                'data' => 'name',
                'title' => trans('lang.user_name'),

            ],
            [
                'data' => 'email',
                'title' => trans('lang.user_email'),

            ],
            [
                'data' => 'role',
                'title' => trans('lang.user_role_id'),
                'orderable' => false, 'searchable' => false,

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.user_updated_at'),
                'searchable' => false,
            ]
        ];

        // TODO custom element generator
        $hasCustomField = in_array(User::class, setting('custom_field_models',[]));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', User::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.user_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'usersdatatable_' . time();
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
    public function getActionPage()
    {
        switch (Route::currentRouteName()) {
            case "users.index":
                return 'settings.users.datatables_actions';
                break;
            case "operations.restaurant_profile.users":
                return 'operations.restaurantProfile.users.datatables_actions';
                break;
        }
    }
    public function getQuery($model,$id)
    { 
        switch (Route::currentRouteName()) {
            case "users.index":
                return $model->newQuery()->with('roles');
                break;
            case "operations.restaurant_profile.users":
               
                return $model->newQuery()->whereHas('restaurants', function ($query) use ($id){
                    return $query->where('restaurant_id', $id);
                });
                break;
        }
    }
}