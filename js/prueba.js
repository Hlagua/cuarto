console.log("Archivo prueba.js cargado correctamente.");

let a=5;
let b=8;
let suma=a+b;

console.log("El resultado de la suma es: " + suma);
console.log(typeof(suma));

if (a > b) 
{

    alert("a es mayor que b")

} else {

    alert("b es mayor que a")

}

for(let i=1; i<=10; i++) {

    console.log(i);

}

function msg(mensaje) 
{
    return mensaje;
}

console.log(msg("Cuarto software"));

function sumarTrad(a,b)
{
    return a + b;
} 
// Funcion tradicional

let sumar=(a,b) => a + b; 
// Funcion flecha

console.log(sumar(8,7));

function cambiarTexto() {

    document.getElementById("lbltitulo").innerHTML="Texto cambiado con JS"

}

function sumarNumeros()
{
    let n1 = parseFloat(document.getElementById("num1").value);
    let n2 = parseFloat(document.getElementById("num2").value);
    let resultado = n1 + n2;
    document.getElementById("lblresultado").innerText = "Resultado = " + resultado;
}