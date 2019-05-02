/**
 * Copyright (C) 2019 Paladin Business Solutions Inc.
 *
 */ 
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}