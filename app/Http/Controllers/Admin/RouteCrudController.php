<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RouteRequest;
use App\Models\Vehicle;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RouteCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RouteCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Route::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/route');
        CRUD::setEntityNameStrings('Ruta', 'Rutas');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $this->crud->addColumn([
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        CRUD::column('name')->label('Nombre');
        $this->crud->addColumn([
            'label'     => 'Vehiculo', // Table column heading
            'type'      => 'select',
            'name'      => 'vehicle', // the column that contains the ID of that connected entity;
            'entity'    => 'getVehicle', // the method that defines the relationship in your Model
            'attribute' => 'plate', // foreign key attribute that is shown to user
            'model'     => "App\Models\Vehicle", // foreign key model
        ]);
        CRUD::column('description')->label('Descripción');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(RouteRequest::class);
        CRUD::field('name')->label('Nombre');
        CRUD::addField([
            'label'     => 'Vehiculo', // Table column heading
            'name'      => 'vehicle_id', // the column that contains the ID of that connected entity;
            'entity'    => 'getVehicle', // the method that defines the relationship in your Model
            'attribute' => 'plate', // foreign key attribute that is shown to user
            'model'     => Vehicle::class, // foreign key model
        ]);
        CRUD::field('description');

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
