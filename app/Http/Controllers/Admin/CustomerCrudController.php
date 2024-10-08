<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest;
use App\Models\Distrit;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer');
        CRUD::setEntityNameStrings('Cliente', 'Clientes');
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
        CRUD::column('identification')->label('Identificación');
        CRUD::column('name')->label('Nombre');
        CRUD::column('phone')->label('Telefono');
        CRUD::column('email');
        $this->crud->addColumn([
            'label'     => 'Districto', // Table column heading
            'type'      => 'select',
            'name'      => 'district', // the column that contains the ID of that connected entity;
            'entity'    => 'getDistrict', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\District ", // foreign key model
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CustomerRequest::class);
        Widget::add()->type('script')->content('js/customer.js');

        CRUD::addField([
            'name'  => 'identification',
            'label' => 'Identificación'
        ]);

        CRUD::addField([
            'name'  => 'name',
            'label' => 'Nombre'
        ]);

        CRUD::addField([
            'name'  => 'phone',
            'label' => 'Telefono'
        ]);

        CRUD::addField([
            'name'  => 'email',
            'label' => 'Email',
             'type'  => 'email'
        ]);

        $this->crud->addField([
            'label'     => 'Provincia', // Table column heading
            'type'      => 'select',
            'name'      => 'province',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\Province", // foreign key model
            'fake'     => true,
        ]);

        $this->crud->addField([
            'label'     => 'Canton', // Table column heading
            'type'      => 'select_grouped',
            'name'      => 'canton',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\Canton", // foreign key model
            'fake'     => true,
            'group_by' => 'province',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'getCantons',
            'attributes' =>[
                'id' => 'canton'
            ]
        ]);

        CRUD::addField([
            'label'     => 'Districto', // Table column heading
            'type'      => 'select_grouped',
            'name'      => 'district', // the column that contains the ID of that connected entity;
            'entity'    => 'getDistrict', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => Distrit::class, // foreign key model
            'group_by' => 'canton',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'getDistricts'
        ]);

        CRUD::addField([
            'name'  => 'address',
            'label' => 'Dirección',
            'type'  => 'textArea'
        ]);

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
