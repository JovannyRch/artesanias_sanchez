<?php
include_once('./validators.php');
include_once('./db.php');
include_once('./aoa_crear_cliente.php');
include_once('./aoa_editar_cliente.php');

session_start();

$db = new Database();

$tipos_membresias = [
    [
        'id' => "mensual",
        'nombre' => 'Mensual'
    ],
    [
        'id' => "anual",
        'nombre' => 'Anual'
    ]
];

$nombre = "";
$paterno = "";
$materno = "";
$rfc = "";
$curp = "";
$membresia = "";

$is_editing = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $cliente = $db->row("SELECT * FROM tblcliente WHERE id_cliente = $id");

    $nombre = $cliente['nombre'];
    $paterno = $cliente['ap_paterno'];
    $materno = $cliente['ap_materno'];
    $rfc = $cliente['rfc'];
    $curp = $cliente['curp'];
    $membresia = $cliente['tipo_membresia'];

    $is_editing = true;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $nombre = $_POST['nombre'];
        $paterno = $_POST['paterno'];
        $materno = $_POST['materno'];
        $rfc = $_POST['rfc'];
        $curp = $_POST['curp'];
        $membresia = $_POST['membresia'];

        if (isset($_POST['nombre']) && strlen($_POST['nombre']) > 0) {

            $validation = isOnlyCharacters($nombre);
            if (!$validation) {
                throw new Exception("El nombre solo puede contener letras");
            }
        } else {
            throw new Exception("El nombre es requerido");
        }

        if (isset($_POST['paterno']) && strlen($_POST['paterno']) > 0) {

            $validation = isOnlyCharacters($paterno);
            if (!$validation) {
                throw new Exception("El apellido paterno solo puede contener letras");
            }
        } else {
            throw new Exception("El apellido paterno es requerido");
        }

        if (isset($_POST['materno']) && strlen($_POST['materno']) > 0) {

            $validation = isOnlyCharacters($materno);
            if (!$validation) {
                throw new Exception("El apellido materno solo puede contener letras");
            }
        } else {
            throw new Exception("El apellido materno es requerido");
        }

        if (isset($_POST['rfc']) && strlen($_POST['rfc']) > 0) {

            $validation = isValidRFC($rfc);
            if (!$validation) {
                throw new Exception("El RFC no es válido, debe tener 13 caracteres");
            }
        } else {
            throw new Exception("El RFC es requerido");
        }

        if (isset($_POST['curp']) && strlen($_POST['curp']) > 0) {

            $validation = isValidCURP($curp);
            if (!$validation) {
                throw new Exception("El CURP no es válido, debe tener 18 caracteres");
            }
        } else {
            throw new Exception("El CURP es requerido");
        }

        if ($is_editing) {
            editarCliente($db, $id, $nombre, $paterno, $materno, $rfc, $curp, $membresia);
        } else {
            crearCliente($db, $nombre, $paterno, $materno, $rfc, $curp, $membresia);
        }

        header('Location: ./AOAAdminInicio.php');
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
    }
}

$actionPath = $is_editing ? "./AOARegistroCliente.php?id=$id" : "./AOARegistroCliente.php";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Registro cliente - PelisNow</title>
    <link rel="stylesheet" href="./../styles.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="navbar-brand">PelisNow</div>
            <div class="navbar-nav">
                <a href="./../AOAAdminInicio.php" class="nav-item">Inicio</a>
                <a href="../AOAAdminPeliculas.php" class="nav-item">Películas</a>
                <a href="../AOAAdminCategorias.php" class="nav-item">Categorías</a>
                <a href="../AOAAdminDirectores.php" class="nav-item">Directores</a>
                <a href="../AOAAdminActores.php" class="nav-item">Actores</a>
                <a href="../AOALogin.php" class="nav-item">Logout</a>
            </div>
        </nav>
    </header>
    <div class="form-container">
        <h1>
            <?php if ($is_editing) { ?>
                Editar cliente
            <?php } else { ?>
                Registro de cliente
            <?php } ?>

        </h1>
        <form class="login-form" method="POST" action="<?= $actionPath ?>">
            <label for="nombre">Nombre del cliente:</label>
            <input type="text" id="nombre" name="nombre" value="<?= $nombre ?>" required />

            <!-- Apellido paterno -->
            <label for="paterno">Apellido paterno:</label>
            <input type="text" id="paterno" name="paterno" value="<?= $paterno ?>" required />

            <!-- Apellido materno -->
            <label for="materno">Apellido materno:</label>
            <input type="text" id="materno" name="materno" value="<?= $materno ?>" required />

            <?php if (!$is_editing) { ?>
                <!-- Fecha de nacimiento -->
                <label for="fecha">Fecha de nacimiento:</label>
                <input class="text-black" type="date" id="fecha" name="fecha" required />

                <!-- Genero -->
                <label for="genero">Genero:</label>
                <select id="genero" name="genero" class="text-black" required>
                    <option value="H">Hombre</option>
                    <option value="M">Mujer</option>
                </select>
            <?php } ?>

            <br />
            <!-- Estado -->
            <label for="estado">Estado:</label>
            <select id="estado" class="text-black" name="estado" required>
            </select>


            <!-- RFC -->
            <br />
            <label for="rfc">RFC:</label>
            <input type="text" id="rfc" name="rfc" value="<?= $rfc ?>" required />

            <div class="flex gap-4">
                <div class="w-full">
                    <label for="curp">CURP:</label>
                    <input type="text" id="curp" name="curp" value="<?= $curp ?>" required />
                </div>

                <? if (!$is_editing) { ?>
                    <div>
                        <button type="button" id="generar">Generar CURP</button>
                    </div>
                <? } ?>
            </div>
            <!-- Membresia -->
            <label for="membresia">Membresia:</label>
            <select id="membresia" name="membresia" class="text-black" required>
                <?php foreach ($tipos_membresias as $membresia) { ?>
                    <option value="<?= $membresia['id'] ?>"><?= $membresia['nombre'] ?></option>
                <?php } ?>
            </select>

            <?php if (isset($_SESSION['message'])) { ?>
                <div class="alert">
                    <p> Error: <?php echo $_SESSION['message']; ?></p>
                </div>
            <?php
                unset($_SESSION['message']);
            } ?>

            <?php if (isset($_SESSION['success_message'])) { ?>
                <div class="success">
                    <br>
                    <br>
                    <p> <?php echo $_SESSION['success_message']; ?></p>
                </div>
            <?php
                unset($_SESSION['success_message']);
            } ?>

            <br />
            <br />

            <!-- Separator -->
            <div class="separator bg-white h-[1px] mb-4 ">

            </div>

            <!-- hide if is editing -->

            <?php if (!$is_editing) { ?>
                <div class="mb-4">
                    <span>Información de la tarjeta</span>
                </div>

                <!-- Numero de tarjeta -->
                <label for="numero_tarjeta">Número de tarjeta:</label>
                <input type="text" id="numero_tarjeta" name="numero_tarjeta" required />

                <!-- Precio a pagar -->
                <label for="precio">Precio a pagar:</label>
                <input type="number" id="precio" class="text-black" name="precio" required />


                <!-- Banco -->
                <label for="banco">Banco:</label>
                <input type="text" id="banco" name="banco" required />


                <!-- Fecha de expiracion -->
                <label for="fecha_expiracion">Fecha de expiración:</label>
                <input class="text-black" id="fecha_expiracion" name="fecha_expiracion" required placeholder="MM/AA" />


                <!-- Codigo de seguridad -->
                <label for="codigo_seguridad">Código de seguridad:</label>
                <input class="text-black" id="codigo_seguridad" name="codigo_seguridad" required placeholder="CVV" />
            <?php } ?>





            <br />
            <br />
            <button type="submit">
                <?php if ($is_editing) { ?>
                    Editar
                <?php } else { ?>
                    Registrar
                <?php } ?>
            </button>
            <br />
            <br />
        </form>
        <footer class="site-footer">
            <div class="footer-bottom">
                <p>AOA – PW1 – Noviembre/2023</p>
            </div>
        </footer>
    </div>

</body>

<script>
    function generarCURP() {
        abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        random09a = Math.floor(Math.random() * (9 - 1 + 1)) + 1;
        random09b = Math.floor(Math.random() * (9 - 1 + 1)) + 1;
        randomAZ = Math.floor(Math.random() * (26 - 0 + 1)) + 0;
        ano = Number($("#fecha").val().slice(0, 4));
        mes = Number($("#fecha").val().slice(5, 7));
        dia = Number($("#fecha").val().slice(8, 10));

        var CURP = [];
        CURP[0] = $("#paterno").val().charAt(0).toUpperCase();
        CURP[1] = $("#paterno").val().slice(1).replace(/\a\e\i\o\u/gi, "").charAt(0).toUpperCase();
        CURP[2] = $("#materno").val().charAt(0).toUpperCase();
        CURP[3] = $("#nombre").val().charAt(0).toUpperCase();
        CURP[4] = ano.toString().slice(2);
        CURP[5] = mes < 10 ? "0" + mes : mes;
        CURP[6] = dia < 10 ? "0" + dia : dia;
        CURP[7] = $("#genero").val().toUpperCase();
        CURP[8] = abreviacion[estados.indexOf($("#estado").val().toLowerCase())];
        CURP[9] = $("#paterno").val().slice(1).replace(/[aeiou]/gi, "").charAt(0).toUpperCase();
        CURP[10] = $("#materno").val().slice(1).replace(/[aeiou]/gi, "").charAt(0).toUpperCase();
        CURP[11] = $("#nombre").val().slice(1).replace(/[aeiou]/gi, "").charAt(0).toUpperCase();;
        CURP[12] = ano < 2000 ? random09a : abc[randomAZ];
        CURP[13] = random09b;
        return CURP.join("");
    }
    var estados = ["aguascalientes", "baja california", "baja california sur", "campeche", "chiapas", "chihuahua", "coahuila", "colima", "ciudad de mexico", "distrito federal", "durango", "guanajuato", "guerrero", "hidalgo", "jalisco", "estado de mexico", "michoacan", "morelos", "nayarit", "nuevo leon", "oaxaca", "puebla", "queretaro", "quintana roo", "san luis potosi", "sinaloa", "sonora", "tabasco", "tamaulipas", "tlaxcala", "veracruz", "yucatan", "zacatecas"];
    var abreviacion = ["AS", "BC", "BS", "CC", "CS", "CH", "CL", "CM", "CX", "DF", "DG", "GT", "GR", "HG", "JC", "MC", "MN", "MS", "NT", "NL", "OC", "PL", "QT", "QR", "SP", "SL", "SR", "TC", "TS", "TL", "VZ", "YN", "ZS"];

    $("#generar").click(function() {
        $("#curp").val(generarCURP());
    });

    // render estados
    // on load
    window.onload = function() {
        var select = document.getElementById("estado");
        for (var i = 0; i < estados.length; i++) {
            var opt = estados[i];
            var el = document.createElement("option");
            el.textContent = opt;
            el.value = opt;
            select.appendChild(el);
        }
    };
</script>

</html>