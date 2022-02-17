<?php

namespace App\DataTables\Operations;

use App\Models\DeliveryAddress;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class DeliveryAddressDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'operations.settings.client.profile.address.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DeliveryAddress $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DeliveryAddress $model)
    {
        return $model->newQuery()->with("user")->where('user_id',$this->userId);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    // ->setTableId('operations/deliveryaddress-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        // Button::make('create'),
                        // Button::make('export'),
                        // Button::make('print'),
                        // Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
            Column::make('id'),
            Column::make('address'),
            Column::make('description'),

            Column::make('action'),
            // Column::make('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Operations/DeliveryAddress_' . date('YmdHis');
    }
}
