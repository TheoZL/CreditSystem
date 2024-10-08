@extends(backpack_view('blank'))

@php
    $breadcrumbs = [
        'Admin' => backpack_url('dashboard'),
        'Creditos' => route('credit.index'),
        'Historial de Pagos' => false
    ];
@endphp

<div>
    <button class="btn btn-secondary mb-1"  id="editBtn" type="button" data-toggle="modal" data-target="#largeModal" style="display: none" >Launch large modal</button>
    <div class="modal fade" id="largeModal" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Pago</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div style="display: none;">
                            <input id="id_modal" type="number" value="" class="form-control">
                            <input id="current_amount" type="number" value="" class="form-control">
                        </div>
                        <div class="form-group col-sm-12" >
                            <label>Fecha</label> <input type="date" id="date" name="date" value="" class="form-control" >
                        </div>
                        <div class="form-group col-sm-12" >
                            <label>Monto</label> <input type="number" id="amount" name="amount" value="" min="0" class="form-control" >
                        </div>
                        <div class="form-group col-sm-12" element="div" bp-field-wrapper="true" bp-field-name="route_id" bp-field-type="select">
                            <label>Tipo de Pago</label>
                            <select  id="type" name="type" class="form-control">
                                @foreach($paymentType as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12" >
                            <div class="alert alert-danger" id='error' role='alert' ></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary"  onclick="updatePayment()" type="button">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('content')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">Credito #{{$credit->id}}</span>
            <small>Historial de Pagos.</small>
            <small><a href="{{ route('credit.index') }}" class="d-print-none font-sm"><i class="la la-angle-double-left"></i> Volver al listado de <span>Creditos</span></a></small>
        </h2>
    </section>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row">
        <div class="col-md-8 bold-labels">
            <div class="card">
                <div class="card-body row">
                    <div class="form-group col-sm-12" >
                        <label>Cliente</label> <input type="text" name="customer" value="{{ $credit->customer->name }}" class="form-control" disabled="">
                    </div>
                    <div class="form-group col-sm-12" >
                        <label>Fecha de Inicio</label> <input type="text" name="customer" value="{{ Carbon\Carbon::parse($credit->date)->format('d-m-Y')  }}" class="form-control" disabled="">
                    </div>
                    <div class="form-group col-sm-12 required" >
                        <label>Total</label>
                        <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">₡</span></div><input type="number" name="total" value="{{  $credit->total }}" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 required" >
                        <label>Prima</label>
                        <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">₡</span></div><input type="number" name="total" value="{{  $credit->first_payment }}" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 required" >
                        <label>Total de Credito</label>
                        <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">₡</span></div><input id="balance" type="number" name="balance" value="{{  $credit->balance }}" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group col-sm-12" >
                        <label>Ruta</label> <input type="text" name="customer" value="{{ $credit->route->name }}" class="form-control" disabled="">
                    </div>
                    <div class="form-group col-sm-12" >
                        <label>Tipo de Recaudación</label> <input type="text" name="customer" value="{{ $credit->billingSchedule->option }}" class="form-control" disabled="">
                    </div>
                    <div class="form-group col-sm-12" >
                        <label>Estado del Credito</label> <input type="text" name="customer" value="{{ $credit->creditStatus->name }}" class="form-control" disabled="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <table class="table table-responsive-sm  no-footer ">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha de Pago</th>
                    <th>Tipo de Pago</th>
                    <th>Monto</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @php($count = 1)
                @php($total = 0)
                @if( $paymentHistory->isEmpty() )
                    <tr>
                        <td colspan="5" class="text-center"> <b>No posee Historial de Pagos </b></td>
                    </tr>
                @else
                    @foreach($paymentHistory as $history)
                        <tr>
                            <td>{{ $count }}</td>
                            <td>{{ Carbon\Carbon::parse($history->day)->format('d-m-Y') }}</td>
                            <td>{{ $history->paymentType->name }}</td>
                            <td>₡{{ $history->amount  }}</td>
                            <th>
                                @if($credit->credit_status_id != 2 )
                                <div class="">
                                    <a href="javascript:void(0)" onclick="showUpdatePaymentModal( {{ json_encode($history->id) }}, {{ json_encode($history->amount) }}, {{ json_encode($history->type_id) }}, {{ json_encode( \Carbon\Carbon::parse($history->day)->format('d/m/Y')) }} )" class="btn btn-sm btn-link"><i class="la la-edit"></i> Editar</a>
                                    <a href="javascript:void(0)" onclick="deletePayment( {{ json_encode($history->id) }} )" class="btn btn-sm btn-link" ><i class="la la-trash"></i> Eliminar</a>
                                </div>
                                @else
                                    <div class="text-center">
                                        <b>El credito se encuenta cerrado no se puede modificar</b>
                                    </div>
                                @endif
                            </th>
                        </tr>
                        @php($count += 1)
                        @php($total = $total +$history->amount   )
                    @endforeach
                @endif
                </tbody>
                @if( !$paymentHistory->isEmpty() )
                <tfoot>
                    <tr>
                        <th colspan="5"> <div class="float-right"> <b>Total:₡ {{ $total }} </b> </div></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection

@section('before_scripts')
    <script>
        function showUpdatePaymentModal(id, amount, type, date){
            let formatDate = date.split('/').reverse().join('-');

            document.getElementById('id_modal').value = id;
            document.getElementById('amount').value = amount;
            document.getElementById('current_amount').value = amount;
            document.getElementById('type').value = type;
            document.getElementById('date').value = formatDate;

            clearErrors()
            document.getElementById('editBtn').click();
        }

        function updatePayment(){
            if( formValidation() ){
                let id = document.getElementById('id_modal').value;
                let url = window.location.origin + '/admin/credit/payment/update/' + id;
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                let data ={
                    id : document.getElementById('id_modal').value,
                    day: document.getElementById('date').value,
                    amount :  document.getElementById('amount').value,
                    type_id : document.getElementById('type').value,
                }
                console.log(data);

                fetch(url,{
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                    credentials: "same-origin",
                    body: JSON.stringify(data),
                }).then(result => result.json().then( data => {

                    swal({
                        title: "Exito!",
                        text: data.message,
                        icon: "success",
                    }).then( click => {
                        location.reload();
                    });

                })).catch(error =>{
                    sleep(5);
                    swal({
                        title: "Error!",
                        text: "Hubo un error interno",
                        icon: "error",
                    }).then( click => {
                        document.getElementById('modalClosebtn').click()
                    });
                });

            }
        }

        function formValidation(){
            clearErrors()
            let errorModal = document.getElementById('error');
            let validation = true;
            let balance = document.getElementById('balance').value ;
            let amount = document.getElementById('amount').value;
            let currentAmount =  document.getElementById('current_amount').value;

            if( amount > (Number(balance) + Number(currentAmount)) ){
                errorModal.innerHTML += '<li>El Pago no puede ser mayor al total de Credito</li>'
                errorModal.style.display = ''
                validation = false;
            }

            if(Number(amount) <1 ){
                errorModal.innerHTML += '<li>El Monto debe ser mayor a 1</li>'
                errorModal.style.display = ''
                validation = false;
            }

            //validation de la fecha

            return validation
        }

        function clearErrors(){
            let errorModal = document.getElementById('error')
            errorModal.innerHTML = '';
            errorModal.style.display = 'none'
        }

        function deletePayment(id){
            let url = window.location.origin + '/admin/credit/payment/delete/' + id;
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            swal({
                title: "Advertencia",
                text: 'Está seguro que desea eliminar este elemento?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    fetch(url, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json, text-plain, */*",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": token
                        },
                        credentials: "same-origin",
                    }).then(result => result.json().then( data => {
                        swal(data.message, {
                            icon: "success",
                        }).then(()=>{
                            location.reload();
                        });
                    })).catch(error =>{
                        sleep(5);
                        swal({
                            title: "Error!",
                            text: "Hubo un error interno",
                            icon: "error",
                        }).then( click => {
                           //Close Modal
                        });
                    });
                } else {
                   //Close the modal
                }
            });
        }

        function sleep (time) {
            return new Promise((resolve) => setTimeout(resolve, time));
        }
    </script>
@endsection


