<?php
include '../funciones/funciones.php';

//parte de mensualidades
function verMensualidades()
{
    $conexion = conectarUsuarios();
    $select_mensualidades = "SELECT * from mensualidades";
    $resultado = $conexion->query($select_mensualidades);
    $contador = 0;
    while ($fila = $resultado->fetch_array()) {
        $contador++;
?>

        <div class="divTableRow">
            <div class="divTableCelda"><?php echo "${fila['Nombre']}"; ?></div>
            <div class="divTableCelda"><?php echo "${fila['DiasSemanas']}"; ?></div>

            <div class="divTableCelda">
                <input type="checkbox" class="boton-checkbox" id="eChkUsuario<?php echo $contador ?>">
                <label for="eChkUsuario<?php echo $contador ?>" class="tresbotones">...</label>
                <div class="a-ocultar"><?php echo "${fila['Precio']}"; ?>€</div>
            </div>
            <div class="divTableCelda">
                <div class="boton">

                    <form class="a-ocultar" name="editar" action="modificarMensualidad.php" method="POST">
                        <input type='hidden' value="<?php echo "${fila['CodigoMensualidad']}" ?>" name="id">
                        <!-- <input type="submit" name="editar_cliente" value="modificar"> -->
                        <button type="submit" name="ediar_cliente"><img src="../imagenes/editar.png" alt=""></button>
                    </form>

                    <form class="a-ocultar" action="<?php echo $_SERVER["PHP_SELF"]  ?>" method="POST">
                        <input type='hidden' value="<?php echo "${fila['CodigoMensualidad']}" ?>" name="id">
                        <!-- <input type="submit" name="borrar" value="borrar"> -->
                        <button type="submit" name="borrar"><img src="../imagenes/delete.png" alt=""></button>
                    </form>
                </div>
            </div>
        </div>
<?php
    };
    if (isset($_POST["borrar"])) {
        borrarMensualidades();
    }
}
?>


<?php
function modificarMensualidades()
{
    $conexion = conectarUsuarios();
    if ($_POST) {
        //si me piden que modifique los datos los modifico
        if (!isset($_POST["modificar_mensualidades"])) {

            //Guardo los parametros en variables
            $id = $_POST["id"];
            $nombre = $_POST["nombreMen"];
            $diasSemana = $_POST["diasSemana"];
            $precio = $_POST["precio"];

            //Vamos a realizar una consulta UPDATE para actuliazar los datos de los clientes
            $actualizarMensualidades =
                "UPDATE mensualidades SET id=$id,nombreMen='$nombre',diasSemana=$diasSemana,precio=$precio WHERE id=$id";
            //echo $actualizarMensualidades;
            //exit;
            $resultado = $conexion->query($actualizarMensualidades);

            if ($resultado) {
                header("Location:verMensualidades.php");
                echo "<p>Se ha modificado $conexion->affected_rows registros con exito</p>";
            } else {
                echo "Tuvimos problemas en la modificacion, intentelo de nuevo mas tarde";
            }
        }
    }

    visualizarDatosPorMensualidad();
}
function visualizarDatosPorMensualidad()
{
    $conexion = conectarUsuarios();

    $select_cliente = "SELECT * from mensualidades WHERE id=$_POST[id]";
    $resultado = $conexion->query($select_cliente);

    $fila = $resultado->fetch_array();
?>

    <form class="ModificarMensualidades" action="<?php echo $_SERVER["PHP_SELF"]  ?>" method="POST">
        <input type='hidden' value="<?php echo "${fila['id']}" ?>" name="id">
        <div class="datosPersonales">
            <h1>Datos Personales</h1>
            <div>
                <label>Nombre de la mensualidad:</label>
                <input type="text" value="<?php echo "${fila['nombreMen']}" ?>" name="nombreMen">
            </div>
            <div>
                <label>Días:</label>
                <input type="number" value="<?php echo "${fila['diasSemana']}" ?>" name="diasSemana">
            </div>
            <div>
                <label>Precio:</label>
                <input type="number" value="<?php echo "${fila['precio']}" ?>" name="precio">
            </div>
            <input type="submit" class="enviar" name="modificar_datos_clientes" value="Modificar">
    </form>
<?php
}

function borrarMensualidades()
{
    $conexion = conectarUsuarios();

    $borrar_cliente = "DELETE from mensualidades WHERE id=$_POST[id]";
    $resultado = $conexion->query($borrar_cliente);

    if ($resultado) {
        header("Location:verMensualidades.php");
        echo '<p>Se ha borrado un cliente' . $conexion->affected_rows . ' registro con exito</p>';
    } else {
        echo '<p>Tuvimos problemas con la eliminacion del clientes, intentalo de nuevo más tarde</p>';
    }
}

function anadirMensualidad()
{
    $conexion = conectarUsuarios();

    //Guardo los parametros en variables
    $id = maximoCodigoTabla("mensualidades", "CodigoMensualidad");
    $nombre = $_POST["nombreMen"];
    $diasSemana = $_POST["diasSemana"];
    $precio = $_POST["precio"];
    $anio = $_POST["Anio"];

    $anadir_mensualidad = "INSERT INTO mensualidades (id,nombreMen,diasSemana,precio,Anio) 
            VALUES($id,'$nombre',$diasSemana,$precio,$anio)";
    $resultado = $conexion->query($anadir_mensualidad);

    if ($resultado) {
        echo "<p>Se ha añadido $conexion->affected_rows registros con exito</p>";
    } else {
        echo "Tuvimos problemas en la insercion, intentelo de nuevo mas tarde";
    }
}
?>