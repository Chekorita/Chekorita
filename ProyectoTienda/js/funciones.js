function SoloNumerosDecimales(e) {
    var key;
    if (window.event){ // IE/Chrome
        key = e.keyCode;
    }
    else if (e.which){ // Netscape/Firefox/Opera
        key = e.which;
    }
    
    if (key < 46 || key > 57) {
        return false;
    }
    return true;
}
    
function SoloNumeros(e) {
    var key;
    if (window.event){ // IE/Chrome
        key = e.keyCode;
    }
    else if (e.which){ // Netscape/Firefox/Opera
        key = e.which;
    }
    
    if (key < 48 || key > 57 ){
        return false;
    }
    e.preventDefault();
    return true;
}
    
function SoloLetras(letra) { 
    tecla = (document.all) ? letra.keyCode : letra.which; 
    //Tecla de retroceso para borrar, y espacio siempre la permite
    if (tecla == 8 || tecla == 32) {
        return true;
    }
    // Patrón de entrada
    patron = /[A-Za-zñÑáéíóúÁÉÍÓÚäëïöüÄËÏÖÜ\s]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);   
}
    
function SoloLetrasAndNumeros(letranum) {
    tecla = (document.all) ? letranum.keyCode : letranum.which; 
    //Tecla de retroceso para borrar, y espacio siempre la permite
    if (tecla == 8 || tecla == 32) {
        return true;
    }
    // Patrón de entrada
    patron = /[A-Za-z0-9ñÑáéíóúÁÉÍÓÚäëïöüÄËÏÖÜ\s]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}


//por revisar
function SoloLetrasConSimbolos(letra) { 
    tecla = (document.all) ? letra.keyCode : letra.which; 
    //Tecla de retroceso para borrar, y espacio siempre la permite
    if (tecla == 8 || tecla == 32) {
        return true;
    }
    // Patrón de entrada
    patron = /[A-Za-zñÑáéíóúÁÉÍÓÚäëïöüÄËÏÖÜ\s]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);   
}

function ParaEmail(letra) { 
    tecla = (document.all) ? letra.keyCode : letra.which; 
    //Tecla de retroceso para borrar, y espacio siempre la permite
    if (tecla == 8 || tecla == 32) {
        return true;
    }
    // Patrón de entrada
    patron = /[A-Za-zñÑáéíóúÁÉÍÓÚäëïöüÄËÏÖÜ\s]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);   
}