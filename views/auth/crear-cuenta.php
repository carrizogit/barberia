<h1 class="nombre-pagina">Crea tu cuenta</h1>
<p class="descripcion-pagina">Completa el siguiente formulario</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form class="formulario grid" method="POST" action="/crear-cuenta">
    <div class="campo">
        <input type="text" id="nombre" name="nombre" value="<?php echo s($usuario->nombre); ?>"> 
        <label for="nombre">Nombre</label>
    </div>

    <div class="campo">
        <input type="text"  id="apellido" name="apellido" value="<?php echo s($usuario->apellido); ?>"> 
        <label for="apellido">Apellido</label>
    </div>

    <div class="campo">
        <input type="tel" id="telefono" name="telefono" maxlength="10" value="<?php echo s($usuario->telefono); ?>"> 
        <label for="telefono">Telefono</label>
    </div>

    <div class="campo">
        <input type="email"  id="email" name="email" value="<?php echo s($usuario->email); ?>"> 
        <label for="email">Email</label>
    </div>

    <div class="campo">
        <input type="password"  id="password" name="password"> 
        <label for="password">Contraseña</label>
    </div>

    <div class="campo">
        <input type="password"  id="password1" name="password1"> 
        <label for="password1">Repetir Contraseña</label>
    </div>

    <input type="submit" class="boton" value="Crear Cuenta">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>
    <a href="/olvide">¿Olvidaste tu Contraseña?</a>
</div>