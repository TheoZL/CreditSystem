/**
 * Make a Province Validation to show his Cantons that belong to this province
 */
crud.field('province').onChange(function(field) {
    if(! (crud.field('province').value === "")  ){
        let province = crud.field('province').input[crud.field('province').value].text

        $("select[name='canton']").children("optgroup").hide();
        $("select[name='canton']").children("optgroup[label='" + province + "']").show();

        crud.field('canton').enable();
        crud.field('canton').input.value = "";
        crud.field('district').disable();
        crud.field('district').input.value = "";
    }else{
        crud.field('canton').disable();
        crud.field('canton').input.value = "";
        crud.field('district').disable();
        crud.field('district').input.value = "";
    }
}).change();

/**
 * Make a Canton Validation to show his districts that belong to this Canton
 */
crud.field('canton').onChange(function(field) {
    if(! (crud.field('canton').value === "")  ){
        let canton = crud.field('canton').input[crud.field('canton').value].text

        $("select[name='district']").children("optgroup").hide();
        $("select[name='district']").children("optgroup[label='" + canton + "']").show();

        crud.field('district').input.value = "";
        crud.field('district').enable();
    }else{
        crud.field('district').input.value = "";
        crud.field('district').disable();
    }
}).change();

crud.field('canton').disable();
crud.field('district').disable();

