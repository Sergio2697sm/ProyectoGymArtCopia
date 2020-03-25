<?php
function iniciarSesion()
{
    $conexion = conectarUsuarios();
    $usuario = $_POST['usuario'];
    $contraseña = md5($_POST['contrasena']);

    $select_usuario = "SELECT Nombre FROM usuarios WHERE Nombre = '$usuario' AND Contrasena ='$contraseña'";
    $resultado = $conexion->query($select_usuario);


    if ($resultado->fetch_row()) {
        $_SESSION['usuario'] = $usuario;
        header('Location:index.php');
    } else {
        echo 'No se ha establecido conexion';
    }
}

function maximoCodigoUsuario()
{
    $conexion = conectarUsuarios();
    //para insertar el nuevo id
    //buscar en la BD el mayor id(max)
    $sql = "SELECT MAX(CodigoUsuario) FROM usuarios";
    $resultado = $conexion->query($sql);
    //hay que utilizar row porque no le hemos dado nombre a la columna seleccionada
    $fila = $resultado->fetch_row();
    $max_id = $fila[0];
    $nuevo_id = $max_id + 1;
    unset($conexion);
    return $nuevo_id;
}

function registrarUsuarios()
{
    $conexion = conectarUsuarios();
    $codigo = maximoCodigoUsuario();
    $nick = $_POST["nick"];
    $contraseña = md5($_POST["contrasena"]);
    $correo = $_POST["mail"];

    $insert = "INSERT INTO usuarios (CodigoUsuario,Nombre,Contrasena,Email) VALUES($codigo,'$nick','$contraseña','$correo')";
    $resultado = $conexion->query($insert);

    if ($resultado != null) {
        echo "<p>Usuario registrado correctamente<p>";
    } else {
        echo '<p>Error</p>';
    }
}

function verClientes()
{
?>
    <div id="contenedor">
        <h1 class="ListadoClientes">LISTADO DE CLIENTES</h1>
        <div id="contenidos">
            <div id="columna1">Nombre</div>
            <div id="columna2">Apellidos</div>
            <div id="columna3">Correo</div>
        </div>
        <?php
        $conexion = conectarUsuarios();
        $select_cliente = "SELECT * from clientes";
        $resultado = $conexion->query($select_cliente);
        while ($fila = $resultado->fetch_array()) {
        ?>
            <div id="contenidos1">
                <div id="columna1"><?php echo "${fila['Nombre']}"; ?></div>
                <div id="columna2"><?php echo "${fila['Apellidos']}"; ?></div>
                <div id="columna3"><?php echo "${fila['CorreoElectronico']}"; ?></div>
                <div id="boton">
                    <form action="modificarClientes.php" method="POST">
                        <input type='hidden' value="<?php echo "${fila['CodigoCliente']}" ?>" name="id">
                        <?php
                        if ($_POST) {
                            $_POST["id"];
                        }
                        ?>
                        <a href="modificarClientes.php"><input type="submit" name="editar_cliente" value="modificar"></a>
                    </form>
                    <form action="<?php echo $_SERVER["PHP_SELF"]  ?>" method="POST">
                        <input type='hidden' value="<?php echo "${fila['CodigoCliente']}" ?>" name="id">
                        <?php
                        if ($_POST) {
                            $_POST["id"];
                        }
                        ?>
                        <input type="submit" name="borrar" value="borrar">
                    </form>
                </div>
            </div>
    <?php
        };
        if (isset($_POST["borrar"])) {
            borrarClientes();
        }
    }
    ?>

    </div>

    <?php

    function modificarCLientes()
    {
        $conexion = conectarUsuarios();


        if ($_POST) {
            //si me piden que modifique los datos los modifico
            if (isset($_POST["modificar_datos_clientes"])) {

                //Guardo los parametros en variables
                $id = $_POST["id"];
                $nombre = $_POST["nombre"];
                $apellidos = $_POST["apellidos"];
                $domicilio = $_POST["domicilio"];
                $poblacion = $_POST["poblacion"];
                $correoElectronico = $_POST["mail"];
                $telefono = $_POST["movil"];
                $Observaciones = $_POST["Observaciones"];
                $peso = $_POST["peso"];
                $altura = $_POST["altura"];
                $edad = $_POST["edad"];
                $actividadFisica = $_POST["ActividadFisica"];
                $lesiones = $_POST["Lesiones"];

                //Vamos a realizar una consulta UPDATE para actuliazar los datos de los clientes
                $actualizarCliente =
                    "UPDATE clientes " .
                    "SET Nombre = '$nombre', Apellidos='$apellidos', Domicilio='$domicilio',Poblacion='$poblacion', CorreoElectronico='$correoElectronico', " .
                    " Telefono=$telefono, Observaciones= '$Observaciones', Peso=$peso, altura =$altura, edad=$edad, ActividadFisica='$actividadFisica', " .
                    " Lesiones='$lesiones' " .
                    "WHERE CodigoCliente=$id";
                //echo $actualizarCliente;
                //exit;
                $resultado = $conexion->query($actualizarCliente);

                if ($resultado) {
                    echo "<p>Se ha modificado $conexion->affected_rows registros con exito</p>";
                } else {
                    echo "Tuvimos problemas en la modificacion, intentelo de nuevo mas tarde";
                }
            }
        }

        visualizarDatosCliente();
    }

    function visualizarDatosCliente()
    {
        $conexion = conectarUsuarios();

        $select_cliente = "SELECT * from clientes WHERE CodigoCliente=$_POST[id]";
        $resultado = $conexion->query($select_cliente);

        $fila = $resultado->fetch_array();
    ?>

        <form class="Modificar" action="<?php echo $_SERVER["PHP_SELF"]  ?>" method="POST">
            <input type='hidden' value="<?php echo "${fila['CodigoCliente']}" ?>" name="id">
            <div class="datosPersonales">
                <h1>Datos Personales</h1>
                <div>
                    <label>Nombre:</label>
                    <input type="text" value="<?php echo "${fila['Nombre']}" ?>" id="nombre" name="nombre">
                </div>
                <div>
                    <label>Apellidos:</label>
                    <input type="text" value="<?php echo "${fila['Apellidos']}" ?>" id="apellidos" name="apellidos">
                </div>
                <div>
                    <label>Domicilio:</label>
                    <input type="text" value="<?php echo "${fila['Domicilio']}" ?>" id="domicilio" name="domicilio">
                </div>
                <div>
                    <label>Población:</label>
                    <input type="text" value="<?php echo "${fila['Poblacion']}" ?>" id="poblacion" name="poblacion">
                </div>
                <div>
                    <label>Correo Electronico:</label>
                    <input type="text" value="<?php echo "${fila['CorreoElectronico']}" ?>" id="mail" name="mail">
                </div>
                <div>
                    <label>Telefono:</label>
                    <input type="number" value="<?php echo "${fila['Telefono']}" ?>" id="movil" name="movil">
                </div>
                <label>Observaciones:</label>
                <input type="text" value="<?php echo "${fila['Observaciones']}" ?>" id="observaciones" name="Observaciones">
            </div>

            <div class="datosAdicionales">
                <h1>Información adicional</h1>
                <label>Peso:</label>
                <input type="number" value="<?php echo "${fila['Peso']}" ?>" id="peso" name="peso">

                <div>
                    <label>Altura: (* En metros)</label>
                    <input type="number" value="<?php echo "${fila['altura']}" ?>" id="altura" name="altura">
                </div>
                <div>
                    <label>Edad:</label>
                    <input type="number" value="<?php echo "${fila['edad']}" ?>" id="edad" name="edad">
                </div>
                <div>
                    <label>Actividad fisíca:</label>
                    <input type="text" value="<?php echo "${fila['ActividadFisica']}" ?>" id="actividad" name="actividad">
                </div>
                <div>
                    <label>Lesiones:</label>
                    <input type="text" value="<?php echo "${fila['Lesiones']}" ?>" id="lesiones" name="lesiones">
                </div>
            </div>
            <input type="submit" class="enviar" name="modificar_datos_clientes" value="Modificar">
        </form>
    <?php
    }

    function borrarClientes()
    {
        $conexion = conectarUsuarios();

        $borrar_cliente = "DELETE from clientes WHERE CodigoCliente=$_POST[id]";
        $resultado = $conexion->query($borrar_cliente);

        if ($resultado) {
            echo '<p>Se ha borrado un cliente' . $conexion->affected_rows . ' registro con exito</p>';
        } else {

            echo '<p>Tuvimos problemas con la eliminacion del clientes, intentalo de nuevo más tarde</p>';
        }
    }
    ?>