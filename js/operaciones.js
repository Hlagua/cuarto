class suma{
    constructor(numero1,numero2){
        this.numero1=numero1;
        this.numero2=numero2;
    }
    sumarNumeros(){
        return this.numero1 + this.numero2;
    }
}

function sumar(){
    let n1 = parseFloat(document.getElementById("num1").value);
    let n2 = parseFloat(document.getElementById("num2").value);
    let operacion = new suma(n1,n2);
    document.getElementById("lblresultado").innerText = "Resultado = " + operacion.sumarNumeros();
}
