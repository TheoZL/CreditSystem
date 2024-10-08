<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreditRequest;
use App\Models\Credit;
use App\Models\PaymentHistory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CreditCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CreditCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Credit::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/credit');
        CRUD::setEntityNameStrings('Credito', 'Creditos');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause('where', 'credit_status_id', '!=', 2);

        Widget::add()->type('script')->content('js/payment.js');
        $this->crud->addButtonFromModelFunction('line', 'paymentHistory', 'buttonPaymentHistory', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'applyPayment', 'buttonApplyPayment', 'beginning');

        $this->crud->addColumn([
            'name'  => 'id', // The db column name
            'label' => '#', // Table column heading
            'type'  => 'number',
        ])->makeFirstColumn();
        $this->crud->addColumn([
            'label'     => 'Cliente', // Table column heading
            'type'      => 'select',
            'name'      => 'customer_id', // the column that contains the ID of that connected entity;
            'entity'    => 'customer', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\Customer ", // foreign key model
        ]);
        CRUD::column('date');
        $this->crud->addColumn([
            'name'  => 'first_payment', // The db column name
            'label' => 'Prima', // Table column heading
            'type'  => 'number',
            'prefix'        => '₡',
        ]);
        $this->crud->addColumn([
            'name'  => 'total', // The db column name
            'label' => 'Total', // Table column heading
            'type'  => 'number',
            'prefix'        => '₡',
        ]);
        $this->crud->addColumn([
            'name'  => 'balance', // The db column name
            'label' => 'Pendiente', // Table column heading
            'type'  => 'number',
            'prefix'        => '₡',
        ]);
        $this->crud->addColumn([
            'label'     => 'Tipo de Recaudación', // Table column heading
            'type'      => 'select',
            'name'      => 'billing_schedule_id', // the column that contains the ID of that connected entity;
            'entity'    => 'billingSchedule', // the method that defines the relationship in your Model
            'attribute' => 'option', // foreign key attribute that is shown to user
            'model'     => "App\Models\BillingSchedule ", // foreign key model
        ]);
        $this->crud->addColumn([
            'label'     => 'Estado', // Table column heading
            'type'      => 'select',
            'name'      => 'credit_status_id', // the column that contains the ID of that connected entity;
            'entity'    => 'creditStatus', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\CreditStatus ", // foreign key model
            'wrapper' =>[
                'element' => 'spam',
                'style' => 'font-size: medium',
                'class'   => function ($crud, $column, $entry, $related_key) {

                    if ($column['value'] == array(1=>'Activo') )  {
                        return 'badge badge-primary';
                    }elseif ($column['value'] == array(1=>'Cancelado') ){
                        return 'badge badge-info';
                    }else{
                        return 'badge badge-danger';
                    }

                },
            ],
        ]);
        CRUD::column('credit_status_id');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CreditRequest::class);
        Widget::add()->type('script')->content('js/credit.js');


        CRUD::field('customer_id')->label('Clientes');
        $this->crud->addField([
            'name' => 'date',
            'type'  => 'custom_html',
            'value'  => ' <label>Fecha de Inicio</label> <input type="text" name="date" value="'.Carbon::now('America/Costa_Rica')->format('d/m/Y').'" class="form-control" disabled>',
        ]);
        CRUD::field('total');
        $this->crud->addField([
            'name' => 'total',
            'label' => 'Total',
            'type' => 'number',
            'default'    => '0', // set a default value
            'prefix'     => '₡',
        ]);
        $this->crud->addField([   // CustomHTML
            'name'  => 'total_error',
            'type'  => 'custom_html',
            'value' => '<div class="invalid-feedback d-block">El Total no puede ser negativo.</div>'
        ]);
        $this->crud->addField([
            'name' => 'first_payment',
            'label' => 'Prima',
            'type' => 'number',
            'default'    => '0', // set a default value
            'prefix'     => '₡',
        ]);
        $this->crud->addField([   // CustomHTML
            'name'  => 'first_payment_error',
            'type'  => 'custom_html',
            'value' => '<div class="invalid-feedback d-block">La Prima no puede ser negativo.</div>'
        ]);
        $this->crud->addField([   // CustomHTML
            'name'  => 'balance_error',
            'type'  => 'custom_html',
            'value' => '<div class="invalid-feedback d-block">La Prima no puede ser mayor que el total</div>'
        ]);
        $this->crud->addField([
            'name' => 'balance',
            'label' => 'Total del Credito',
            'type' => 'number',
            'prefix'     => '₡',
            'default'    => '0', // set a default value
            'attributes' => [
                'disabled'    => 'disabled',
            ], // change the HTML attributes of your input
        ]);
        $this->crud->addField([
            'label'     => "Ruta",
            'type'      => 'select',
            'name'      => 'route_id', // the db column for the foreign key
            'entity'    => 'route',
            // optional - manually specify the related model and attribute
            'model'     => "App\Models\Route", // related model
        ]);
        CRUD::field('billing_schedule_id');
        $this->crud->addField([
            'label'     => "Tipo de Recaudación",
            'type'      => 'select',
            'name'      => 'billing_schedule_id', // the db column for the foreign key
            'model'     => "App\Models\BillingSchedule", // related model
            'attribute' => 'option'
        ]);
        $this->crud->addField([
            'label'     => "Estado del Credito",
            'type'      => 'select',
            'name'      => 'credit_status_id', // the db column for the foreign key
            'model'     => "App\Models\CreditStatus", // related model
            'attribute' => 'name',
            'default' => 1,
            'attributes' => [
                'disabled'    => 'disabled',
            ],
        ]);
        CRUD::field('credit_status_id');

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


    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        //Make a calculation of Balance
        $balance = $request->request->get('total') - $request->request->get('first_payment');
        $request->request->set('balance' , $balance);
        $request->request->set('date' , Carbon::now('America/Costa_Rica'));
        $request->request->set('credit_status_id' , 1);
        //dd($request->request);

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Update the specified resource in the database.
     *
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        //Make a calculation of Balance
        $balance = $request->request->get('total') - $request->request->get('first_payment');
        $request->request->set('balance' , $balance);

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // update the row in the db
        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

}
