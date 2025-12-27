function tratarClickCookiesPolicy() {
    if (document.querySelector('.cookies-policy')) {
        const botonAceptar = document.querySelector('.cookies-policy__accept');
        const botonRechazar = document.querySelector('.cookies-policy__reject');
        botonAceptar.addEventListener('click', () => {
            document.querySelector('.cookies-policy').style.display = 'none';
        });
        botonRechazar.addEventListener('click', () => {
            document.querySelector('.cookies-policy').style.display = 'none';
        });
    }
}

function main() {
    const todasLasCookies = document.cookie;
    console.log(todasLasCookies); 
    // Ejemplo de salida: "nombre=valor; idioma=es; sesion=abcd123"
    tratarClickCookiesPolicy();
}

main();