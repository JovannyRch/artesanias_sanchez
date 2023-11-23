<?php
include_once('./validators.php');
include_once('./aoa_crear_actor.php');
include_once('./aoa_editar_actor.php');
include_once('./db.php');
session_start();


$countries = [
  [
    'id' => 'japon',
    'name' => 'Japón'
  ],
  [
    'id' => 'mexico',
    'name' => 'México'
  ],
  [
    'id' => 'usa',
    'name' => 'Estados Unidos'
  ]
];


$db = new Database();


$nombre = "";
$paterno = "";
$materno = "";
$fecha = "";
$nacionalidad = "";


$is_edit = isset($_GET['id']);

$id = null;

if ($is_edit) {
  $id = $_GET['id'];
}

if ($is_edit) {
  $actor = $db->row("SELECT * FROM tblactor WHERE id_actor = $id");
  $nombre = $actor['nombre'];
  $paterno = $actor['ap_paterno'];
  $materno = $actor['ap_materno'];
  $fecha = $actor['fecha_nacimiento'];
  $nacionalidad = $actor['nacionalidad'];
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {

    $nombre = $_POST['nombre'];
    $paterno = $_POST['paterno'];
    $materno = $_POST['materno'];
    $fecha = $_POST['fecha'];
    $nacionalidad = $_POST['nacionalidad'];



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


    if (isset($_POST['fecha']) && strlen($_POST['fecha']) > 0) {

      $validation = isValidBirthDate($fecha);
      if (!$validation) {
        throw new Exception("La fecha de nacimiento no es válida, formatos válidos: AAAA-MM-DD o DD/MM/AAAA");
      }
    } else {
      throw new Exception("La fecha de nacimiento es requerida");
    }

    if (isset($_POST['id'])) {
      $id = $_POST['id'];
      editarActor($db, $id, $nombre, $paterno, $materno, $nacionalidad, $fecha);
    } else {
      crearActor($db, $nombre, $paterno, $materno, $nacionalidad, $fecha);
    }
    header("Location: ./AOAAdminActores.php");

    $_SESSION['success_message'] = "Registro exitoso";
  } catch (Exception $e) {
    $_SESSION['message'] = $e->getMessage();
  }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <title>Registro actor - PelisNow</title>
  <link rel="stylesheet" href="../styles.css" />
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
    <h1>Registro de actor</h1>
    <form class="login-form" method="POST" action="./AOARegistroActor.php">
      <?php if ($is_edit) { ?>
        <input type="hidden" name="id" value="<?= $id ?>" />
      <?php } ?>
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" value="<?= $nombre ?>" required />

      <label for="paterno">A. Paterno:</label>
      <input type="text" id="paterno" name="paterno" value="<?= $paterno ?>" required />

      <label for="materno">A. Materno:</label>
      <input type="text" id="materno" name="materno" value="<?= $materno ?>" required />

      <label for="nacionalidad">País de nacimiento:</label>
      <select id="nacionalidad" name="nacionalidad" required>
        <?php foreach ($countries as $country) { ?>
          <option value="<?= $country['id'] ?>" <?= $country['id'] === $nacionalidad ? 'selected' : '' ?>><?= $country['name'] ?></option>
        <?php } ?>
      </select>

      <label for="fecha">Fecha de nacimiento:</label>
      <input type="text" id="fecha" name="fecha" value="<?= $fecha ?>" required placeholder="AAA-MM-DD o DD/MM/AAA" />
      <?php if (isset($_SESSION['message'])) { ?>
        <div class="alert">
          <p> Error: <?php echo $_SESSION['message']; ?></p>
        </div>
      <?php
        unset($_SESSION['message']);
      } ?>

      <?php if (isset($_SESSION['success_message'])) { ?>
        <div class="success">
          <p> <?php echo $_SESSION['success_message']; ?></p>
        </div>
      <?php
        unset($_SESSION['success_message']);
      } ?>
      <br />
      <br />
      <button type="submit">
        <?php if ($is_edit) { ?>
          Editar
        <?php } else { ?>
          Registrar
        <?php } ?>
      </button>
    </form>
  </div>

  <footer class="site-footer">
    <div class="footer-bottom">
      <p>AOA – PW1 – Noviembre/2023</p>
    </div>
  </footer>
</body>

<script>
  function onSubmit(token) {
    window.location.href = "AOAInicio.php";
  }
</script>

</html>