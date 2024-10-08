
function applyPayment(credit_id,balance){

    const route = window.location.origin + '/admin/payment-type/json';
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let modalElement = "<div id='modalPayment' ><button class=\"btn btn-secondary mb-1\" id='payment' type=\"button\" data-toggle=\"modal\" data-target=\"#largeModal\" style='display: none'>Launch large modal</button>" +
        "<div class=\"modal fade\" id=\"largeModal\" tabindex=\"-1\" aria-labelledby=\"myModalLabel\" style=\"display: none;\" aria-hidden=\"true\">\n" +
        "            <div class=\"modal-dialog modal-lg\" role=\"document\">\n" +
        "              <div class=\"modal-content\">\n" +
        "                <div class=\"modal-header\">\n" +
        "                  <h4 class=\"modal-title\">Realizar un Pago</h4>\n" +
        "                  <button class=\"close\" type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>\n" +
        "                </div>\n" +
        "                <div class=\"modal-body\">\n" +
        "                  <input type=\"hidden\" id=\"credit\" name=\"credit\" value="+ credit_id+" />   " +
        "                  <div class=\"form-group row\">\n" +
        "                        <label class=\"col-md-3 col-form-label\" for=\"id\">Credito</label>\n" +
        "                        <div class=\"col-md-9\">\n" +
        "                          <input class=\"form-control\" id=\"id\"  name=\"id\" value="+  credit_id +" disabled >\n" +
        "                        </div>\n" +
        "                      </div>" +
        "                  <div class=\"form-group row\">\n" +
        "                        <label class=\"col-md-3 col-form-label\" for=\"balance\">Monto Adeudado</label>\n" +
        "                        <div class=\"col-md-9\">\n" +
        "                          <input class=\"form-control\" id=\"balance\"  name=\"balance\" value="+ "₡" + balance+" disabled >\n" +
        "                        </div>\n" +
        "                      </div>" +
        "                  <div class=\"form-group row\">\n" +
        "                        <label class=\"col-md-3 col-form-label\" for=\"amount\">Monto del Pago</label>\n" +
        "                        <div class=\"col-md-9\">\n" +
        "                          <input class=\"form-control\" id=\"amount\" type=\"number\" name=\"amount\" min='0' placeholder=\"Monto del Pago\">\n" +
        "                        </div>\n" +
        "                      </div>" +
        "                     <div class=\"form-group row\">\n" +
        "                        <label class=\"col-md-3 col-form-label\" for=\"select1\">Tipo de Pago</label>\n" +
        "                        <div class=\"col-md-9\">\n" +
        "                          <select class=\"form-control\" id=\"select1\" name=\"select1\">\n" +
        "                            <option value=\"0\">Selecciona una Opción</option>\n" +
        "                          </select>\n" +
        "                        </div>\n" +
        "                      </div>" +
        "                      <div class=\"alert alert-danger\" id='error' role='alert' ></div> "+
        "                </div>\n" +
        "                <div class=\"modal-footer\">\n" +
        "                  <button class=\"btn btn-secondary\" id='modalClosebtn' type=\"button\" onclick='closeModal()' data-dismiss=\"modal\">Cerrar</button>\n" +
        "                  <button class=\"btn btn-primary\" type=\"button\" onclick='applyPaymentBackEnd("+ credit_id +","+balance +")' >Realizar Pago</button>\n" +
        "                </div>\n" +
        "              </div>\n" +
        "              <!-- /.modal-content-->\n" +
        "            </div>\n" +
        "            <!-- /.modal-dialog-->\n" +
        "          </div></div>"
    document.body.innerHTML += modalElement;
    clearErrors()
    document.getElementById('payment').click();

    let paymentTypes = fetch(route, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token
        },
        credentials: "same-origin",
    }).then(result => result.json().then( data => {
        let select = document.getElementById('select1');

        data.forEach(element =>{
            let option = document.createElement("option");
            option.text = element.name;
            option.value = element.id;
            select.appendChild(option);
        })

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

function closeModal(){
    clearForm()
    let modal = document.getElementsByClassName('modal fade show');
    modal.className = 'modal fade';
    let divModal = document.getElementById('modalPayment').remove()
}

function applyPaymentBackEnd(credit_id,balance ){
    clearErrors()
    const route = window.location.origin + '/admin/credit/payment/' + credit_id;
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if(formValidation(balance)){
        let data = {
            credit_id : document.getElementById('credit').value ,
            amount : document.getElementById('amount').value,
            paymentType : document.getElementById('select1').options[document.getElementById('select1').selectedIndex].value
        }

        fetch(route, {
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

            document.getElementById('modalClosebtn').click()

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

function formValidation(balance){
    let validation = true
    let amount = document.getElementById('amount').value;
    let paymentType = document.getElementById('select1').options[document.getElementById('select1').selectedIndex].value;
    let errorModal = document.getElementById('error')

    if(paymentType === 0 || paymentType === '0'){
        errorModal.innerHTML += '<li>Debe seleccionar un metodo de Pago </li>'
        errorModal.style.display = ''
        validation = false;
    }

    if(amount === '' ||  Number(amount) < 1 ){
        errorModal.innerHTML += '<li>El Valor del pago debe de ser mayor a 0</li>'
        errorModal.style.display = ''
        validation = false
    }

    if(amount > balance){
        errorModal.innerHTML += '<li>El Pago no puede ser mayor al Pendiente de ₡' + balance + '</li>'
        errorModal.style.display = ''
        validation = false;
    }

    return validation;
}

function clearForm(){
    clearErrors()
    document.getElementById('amount').value = 0;

    function selectElement(id, valueToSelect) {
        let element = document.getElementById(id);
        element.value = valueToSelect;
    }

    selectElement('select1', '0');
}

function clearErrors(){
    let errorModal = document.getElementById('error')
    errorModal.innerHTML = '';
    errorModal.style.display = 'none'
}

function sleep (time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}
