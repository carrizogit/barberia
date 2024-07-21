<h1 class="nombre-pagina">Iniciar Sesion</h1>
<p class="descripcion-pagina">Ingresa tu Email y Contraseña</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form class="formulario contenedor-small" method="POST" action="/" >
    <div class="campo">
        <!-- name nos permite leerlo con POST -->
        <input type="email"  id="email" name="email" value="<?php echo s($auth->email); ?>">
        <label for="email">Email</label>
    </div>

    <div class="campo">
        <input type="password" id="password" name="password"> 
        <label for="password">Contraseña</label>
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">

</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una GRATIS</a>
    <a href="/olvide">¿Olvidaste tu Contraseña?</a>
</div>