let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3; //const xq no cambia su valor nunca

//creamos el objeto para guardar los servicios sleccionado con el click
const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

//cuando todo el dom este cargado ejetumaos 
document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion();
    tabs();
    botonesPaginador(); //agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI() //consulta la api en el backend de PHP

    idCliente();
    nombreCliente();
    seleccionarFecha();
    seleccionarHora();

    mostrarResumen();
}

function mostrarSeccion() {

    //ocultar la seccion que tiene la clase mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    //seleccionar la seccion con el paso
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');

    //quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    //resalta el tabs actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', function(e) { //le pasamos el evento para saber a cual de dimos clic
            paso = parseInt(e.target.dataset.paso);
            
            mostrarSeccion();
            botonesPaginador();
        });
    });
}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguinte = document.querySelector('#siguiente');
    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguinte.classList.remove('ocultar');
    }else if(paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguinte.classList.add('ocultar');
        mostrarResumen();
    }else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguinte.classList.remove('ocultar');
    }
    mostrarSeccion(); //va atada a la funcion pagina anterior y siguiente
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {
        if(paso <= pasoInicial) return;
        paso--; //restamos de a 1
        botonesPaginador(); 
    });
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {
        if(paso >= pasoFinal) return;
        paso++; //restamos de a 1
        botonesPaginador(); 
    });
}

async function consultarAPI() {
    try {
        const url = '/api/servicios';
        const resultado = await fetch(url); //la funcion fetch nos permite consumir los servicios
        const servicios = await resultado.json();
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    //iteramos los servicos con un foreach xq es un arreglo
    servicios.forEach(servicio => {
        //aplicamos distructoring
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;//creamos un atrubto personalizado dataset llamado idServicio
        servicioDiv.onclick = function() {
            selecionarServicio(servicio); //lo hacemos asi xq si lo colocamos arriba despues del = nos trae todos los serv
        }

        //lo agregamos al html
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

function selecionarServicio(servicio) {
    const {id} = servicio; //este viene de la funcion seleccionarServicio
    const {servicios} = cita; //es del objeto que iene informacion de la cita que declaramos arriba

    //identificar al elemento que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //some itera sobre el arreglo y retorna true si un elemento ya existe en el arreglo
    if(servicios.some(agregado => agregado.id === id)) {
        //eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }else {
        cita.servicios = [...servicios, servicio]; //agregarlo
        divServicio.classList.add('seleccionado');
    }
}

function idCliente() {
    //agregamos el nombre  del ciente al objeto cita
    // const nombre = document.querySelector('#nombre').value;
    // cita.nombre = nombre;
    cita.id = document.querySelector('#id').value;
}

function nombreCliente() {
    //agregamos el nombre  del ciente al objeto cita
    // const nombre = document.querySelector('#nombre').value;
    // cita.nombre = nombre;
    cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        const dia = new Date(e.target.value).getUTCDay();
        //sabado es 6 y domingo 0
        if([6, 0].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('Sabados y Domingo no permitidos', 'error', '.formulario');
        }else {
            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if(hora < 9 || hora >21) {
            e.target.value = "";
            mostrarAlerta('Hora no valida', 'error', '.formulario');
        }else {
            cita.hora = e.target.value;
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }

    const alerta = document.createElement('P');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece){
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');
    //limiar el contenido de resumen
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0) {
        mostrarAlerta('Faltan datos', 'error', '.contenido-resumen', false);
        return;
    }

    //formatear el div de resumen
    const {nombre, fecha, hora, servicios } = cita;

    const headingServicio = document.createElement('H3');
    headingServicio.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicio);

    //iterando en los servicios
    servicios.forEach(servicio => {
        const {id, precio, nombre} = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML =  `<span>Nombre:</span> ${nombre}`;

    //formatear fecha a español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2; //le sumammos 2 xq cada vez que utilicemos new Date tenemo un desface de 1 dia (fechaUTC)
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year, mes, dia));

    //weekday dia largo de semana ej: jueves
    const opciones = {weekday:'long', year:'numeric', month:'long', day:'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones); // 23/09/2024

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML =  `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML =  `<span>Hora:</span> ${hora} hs`;

    //boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);

}

async function reservarCita() {
    const {id, fecha, hora, servicios, nombre} = cita;
    const idServicio = servicios.map(servicio => servicio.id);//las coindicencias las almacenas en la variable con el map

    const datos = new FormData();
    
    datos.append('fecha', fecha);//con apend agregamos los datos al from data
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicio);
    // console.log([...datos]); //comporbar que se envia por el formdata
    // return;

    try {
        const url = '/api/citas';          
            const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();

        if(resultado.resultado) {
        Swal.fire({
            icon: "success",
            title: "Cita Creada",
            text: "Tu cita fue creada correctamente!",
            button: 'OK'
          }).then(() => {
            window.location.reload();//recarga la pantalla
          });
        }
        //console.log(respuesta);
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar la cita"
          });
    }

    
}