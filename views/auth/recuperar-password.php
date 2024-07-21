<h1 class="nombre-pagina">Recuperar Contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>


<?php if(!$error === true ) : ?>
    <!-- no le colocamos el action="" porque la url tiene un token que necesitamos leer -->
    <form  class="formulario" method="POST">
        
        <div class="campo">
            <input type="password"  id="password" name="password"> 
            <label for="password">Contraseña</label>
        </div>

        <div class="campo">
            <input type="password"  id="password1" name="password1"> 
            <label for="password1">Repetir Contraseña</label>
        </div>

        <input type="submit" value="Guardar" class="boton">
    </form>

    <div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>
        <a href="/crear-cuenta">Crea una cuenta</a>
    </div>

<?php endif; ?>