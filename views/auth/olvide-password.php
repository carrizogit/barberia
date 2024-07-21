<h1 class="nombre-pagina">Olvide Contraseña</h1>
<p class="descripcion-pagina">Escribe tu email y se te enviaran las instrucciones</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form class="formulario" action="/olvide" method="POST">
<div class="campo">
        <input type="email"  id="email" name="email"> 
        <label for="email">Email</label>
    </div>

    <input type="submit" class="boton" value="Restablecer">
</form>


<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una GRATIS</a>
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>
</div>