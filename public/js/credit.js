
/*
Valid if the total value is less than 0, and make the calculation for the total balance
*/
crud.field('total').onChange(function(field) {

    if(crud.field('total').value < 0){
        crud.field('total_error').show();
        crud.field('balance').input.value = 0;
        crud.field('total').input.value = 0;
    }else{
        crud.field('total_error').hide();
        if(crud.field('total').value === 0 || crud.field('first_payment').value === 0){
            crud.field('balance').input.value = 0;
        }else{
            //Aqui hace la resta y muestra el total
            crud.field('balance').input.value = crud.field('total').value - crud.field('first_payment').value;
        }
    }

}).change();

/*
Valid if the firts payment  value is less than 0, and make the calculation for the total balance
*/
crud.field('first_payment').onChange(function(field) {
    if(crud.field('first_payment').value < 0){
        crud.field('first_payment_error').show();
        crud.field('balance').input.value = 0;
        crud.field('first_payment').input.value = 0;
    }else{
        crud.field('first_payment_error').hide();
        if(crud.field('total').value === 0 || crud.field('first_payment').value === 0){
            crud.field('balance').input.value = 0;
        }else{

            crud.field('balance_error').hide();
            //Aqui hace la resta y muestra el total
            crud.field('balance').input.value = crud.field('total').value - crud.field('first_payment').value;
        }
    }
}).change();


crud.field('total_error').hide();
crud.field('first_payment_error').hide();
crud.field('balance_error').hide();



