<?php
include '../BBDD/conexionBBDD.php';
include '../BBDD/monitoresBBDD.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="../css/estilos_xs.css">
    <!--movil-->
    <link rel="stylesheet" media=" all and (min-device-width : 768px) and (max-device-width : 991px)" href="../css/estilos_sm.css">
    <!--IPAD vertical-->
    <link rel="stylesheet" media=" all and (min-device-width : 992px) and (max-device-width : 1199px) " href="../css/estilos_md.css">
    <!--IPAD horizontal-->
    <link rel="stylesheet" media=" all and (min-device-width : 1200px)" href="../css/estilos_lg.css">
    <!--monitor paronamico-->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    <?php
    include '../header.php';
    ?>

    <main>
        <section>
            <div class="menu">
                <h1 class="Titulo">LISTADO DE MONITORES(ACTIVOS)</h1>
                <div class="buscador">
                    <form action="buscador.php" method="POST">
                        <div class="input">
                            <input type="search" name="informacion" id="" class="i_buscar" placeholder="Buscar por apellido o nombre">
                            <button type="submit" name="buscarActivo"><img src="../imagenes/lupa.png" alt=""></button>
                        </div>
                    </form>
                </div>
                <?php
                include 'menuOpciones.php';
                ?>
                <div class="clientesAntiguos">
                    <button><a href="monitoresAntiguos.php">MONITORES INACTIVOS</a></button>
                </div>
                <div class="divTable cliente">
                    <div class="contenidos">
                        <div class="divTableRow">
                            <div class="divTableCabeza">Nombre</div>
                            <div class="divTableCabeza">Apellidos</div>
                            <div class="divTableCabeza">Telefono</div>
                            <div class="divTableCabeza">Accion</div>
                        </div>
                    </div>
                    <div class="divTableBody">
                        <?php
                        verMonitores();
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php
    include '../footer.php';
    ?>
</body>

</html>