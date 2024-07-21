<h1 class="nombre-pagina">Crea una Cita</h1>
<p class="descripcion-pagina">Selecciona tus servicios</p>

<?php include_once __DIR__ . '/../templates/barra.php';?>

<div id="app">

    <nav class="tabs">
        <button type="button" class="actual" data-paso = "1">Servicios</button>
        <button type="button" data-paso = "2">Informacion Cita</button>
        <button type="button" data-paso = "3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <div id="servicios" class="listado-servicios"></div>
    </div>

    <div id="paso-2" class="seccion">
        <h2>Coloca tus datos y fecha de cita</h2>

        <form class="formulario contenedor-small">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" value="<?php echo $nombre; ?>" disabled>
            </div>

            <div class="dos-campos">
                <div class="campo">
                    <label for="fecha" id="fecha1">Fecha:</label>
                    <input type="date" id="fecha"  min="<?php echo date('Y-m-d', strtotime('+1 day'));?>" >
                </div>

                <div class="campo">
                    <label for="hora">Hora:</label>
                    <input type="time" id="hora" value="00:00" step="3600">
                </div>
                <input type="hidden" value="<?php echo $id; ?>" id="id">
            </div>


        </form>

    </div>

    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton">&laquo; Anterior</button>
        <button id="siguiente" class="boton">&raquo; Siguiente</button>
    </div>
</div>

<?php
    $script = "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    "
?>