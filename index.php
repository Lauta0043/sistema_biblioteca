<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Biblioteca</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <h1>Inicio de sesion</h1>
    <br><br>
    
    <form action="/login" method="POST">

        <label for="usuario">Usuario</label>
        <input
            type="text"
            id="usuario"
            name="usuario"
            placeholder="ej: usuario@gmail.com"
            required
        >
    <br><br>
        <label for="contrasenia">Contraeña</label>
        <input
            type="text"
            id="constrasenia"
            name="constrasenia"
            placeholder="ej: tuContraseña"
            required
            >
    <br><br>
        <p>Inicia sesion para continuar</p>
        <button>Iniciar</button>
    <a href="registro.html"></a>
        <button type="submit">Crear usuario </button>
    </a>
    </form>
    <br><br>

</body>
</html>