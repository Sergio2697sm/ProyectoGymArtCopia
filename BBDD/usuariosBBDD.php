<?php
function iniciarSesion()
{
    $errores = [];
    if (empty($_POST['usuario'])) {
        $errores[] = 'Tiene que rellenar el campo';
    }

    if (empty($_POST['contrasena'])) {
        $errores[] = 'Tiene que rellenar el campo';
    }


    if ($errores) {
        mostrar_errores($errores);
        unset($errores);
    } else {
        $conexion = conectarUsuarios();
        $usuario = $_POST['usuario'];
        $contraseña = md5($_POST['contrasena']);

        $select_usuario = "SELECT Nombre FROM usuarios WHERE Nombre = '$usuario' AND Contrasena ='$contraseña'";
        $resultado = $conexion->query($select_usuario);


        if ($resultado->fetch_row()) {
            $_SESSION['usuario'] = $usuario;
            header('Location:/ProyectoGymArtCopia/index.php');
        } else {
            echo "<script> swal({
                title: 'Error',
                text: 'La conexion no se ha establecido con exito',
                type: 'error',
              });</script>";
        }
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
    $nick = $_POST["nick"];
    $contraseña = md5($_POST["contrasena"]);
    $contraseñaRepetida = md5($_POST["contrasena-repetida"]);
    $correo = $_POST["mail"];
    $errores = [];

    if (strlen($_POST['nick']) <= 3) {
        $errores[] = "<script> swal({
            title: 'Usuario',
            text: 'El usuario tiene que tener mas de 3 caracteres',
            type: 'error',
          });</script>";
    }

    if (strlen($_POST['contrasena']) <= 2) {
        $errores[] = "<script> swal({
            title: 'Contraseña',
            text: 'La contraseña tiene que tener mas de 2 caracteres',
            type: 'error',
          });</script>";
    }

    if ($contraseña != $contraseñaRepetida) {
        $errores[] = "<script> swal({
            title: 'Contraseña',
            text: 'La contraseña tienen que ser iguales',
            type: 'error',
          });</script>";
    }

    if (strlen($_POST['mail']) <= 2) {
        $errores[] = "<script> swal({
            title: 'Correo',
            text: 'El correo tiene que tener mas de 2 caracteres',
            type: 'error',
          });</script>";
    }

    if (!validad_email($correo)) {
        $errores[] = "<script> swal({
            title: 'Correo',
            text: 'Tiene que ser un correo valido',
            type: 'error',
          });</script>";
    }

    //comprueba que no se crea un usuario igual que el que esta registrado en la Base de Datos
    $usuario_unico = 'SELECT Nombre FROM usuarios where Nombre="' . $nick . '"';
    // echo $usuario_unico;
    $resultado_select = $conexion->query($usuario_unico);
    if ($resultado_select->fetch_array() != null) {
        $errores[] = "<script> swal({
                title: 'Usuario Repetido',
                     text: 'Tiene que ser un usuario nuevo',
                     type: 'error',
                   });</script>";
    }

    if ($errores) {
        mostrar_errores($errores);
        unset($errores);
    } else {
        $codigo = maximoCodigoUsuario();
        $insert = "INSERT INTO usuarios (CodigoUsuario,Nombre,Contrasena,Email) VALUES($codigo,'$nick','$contraseña','$correo')";
        $resultado = $conexion->query($insert);

        if ($resultado != null) {
            // echo "<script> swal({
            //     title: 'Usuario',
            //     text: 'Se ha creado el usuario correctamente',
            //     type: 'success',
            //   });</script>";
            // echo "  <script>
            //     swal({
            //         title: 'Usuario',
            //         text: 'Se ha creado el usuario correctamente',
            //         type: 'success'
            //     }),window.onload= function(redirigir) {
            //         if (redirigir) {
            //             window.location.href = '/ProyectoGymArtCopia/usuarios/inicioSesion.php';
            //         }
            //     };
            // </script>
            // ";

            ?>
<script>
                swal({
                    title: 'Usuario',
                    text: 'Se ha creado el usuario correctamente',
                    type: 'success'
                }).then((result) => {
                    if (result.value) {
                        window.location.href = '/ProyectoGymArtCopia/usuarios/inicioSesion.php';
                    }
                });
            </script>
            <?php
        } else {
            echo "<script> swal({
                title: '¡Error!',
                text: 'No se ha creado el usuario, intentelo mas tarde',
                type: 'error',
              });</script>";
        }
    }
}


function olvidarContraseña()
{
    $conexion = conectarUsuarios();
    $contraseña = md5($_POST["contrasena"]);
    $contraseñaRepetida = md5($_POST["contrasena-repetida"]);
    $nick = $_POST["nick"];


    $errores = [];

    if (strlen($_POST['contrasena']) <= 2) {
        $errores[] = '<p>La contraseña tiene que tener como minimo 2 caracteres</p>';
    }

    if ($contraseña != $contraseñaRepetida) {
        $errores[] = "La contraseñas tienen que ser identicas";
    }

    if ($errores) {
        mostrar_errores($errores);
        unset($errores);
    } else {
        $update_contrasena = "UPDATE usuarios SET Contrasena = '$contraseña' WHERE Nombre = '$nick' ";
        // echo $update_contrasena;
        $resultado = $conexion->query($update_contrasena);

        if ($resultado != null) {
            echo "<p>Se han actualizado los datos correctamente</p>";
        } else {
            echo '<p>Compruebe el correo o la contraseña</p>';
        }
    }
}
