<h1 class="nombre-pagina">Actualizar Servicio</h1>

<?php
    include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>

<!-- no le colocamos el action xq estamos pasando un id por la url y el action funcionaria mal -->
<form method="POST" class="formulario"> 
    <?php include_once __DIR__ . '/formulario.php'; ?>
    <input type="submit" value="Actualizar" class="boton">
</form>

