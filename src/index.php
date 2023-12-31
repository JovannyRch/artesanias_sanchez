<?php
require './database.php';
session_start();





$db = new Database();

$productos = $db->getProductos();



$categorias = $db->getCategorias();

$id_categoria = null;

if (isset($_GET['id_categoria'])) {
    $id_categoria = $_GET['id_categoria'];
    $productos = $db->getProductosPorCategoria($id_categoria);
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="dark:text-slate-400 bg-white dark:bg-slate-900 min-h-screen">

    <nav class="sticky top-0 z-40 flex-none w-full mx-auto bg-white border-b border-gray-200 dark:border-gray-600 dark:bg-gray-800">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="./index.php" class="flex items-center">
                <img src="./assets/logo.jpeg" class="h-8 mr-3" alt="logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">
                    Artesanías de barro Sánchez
                </span>
            </a>
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">

                    <li>
                        <a href="./index.php" class="block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500" aria-current="page">
                            Inicio
                        </a>
                    </li>


                    <li>
                        <a href="./carrito.php" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                            <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 0 902.86 902.86" xml:space="preserve">
                                <g>
                                    <g>
                                        <path d="M671.504,577.829l110.485-432.609H902.86v-68H729.174L703.128,179.2L0,178.697l74.753,399.129h596.751V577.829z
			 M685.766,247.188l-67.077,262.64H131.199L81.928,246.756L685.766,247.188z" />
                                        <path d="M578.418,825.641c59.961,0,108.743-48.783,108.743-108.744s-48.782-108.742-108.743-108.742H168.717
			c-59.961,0-108.744,48.781-108.744,108.742s48.782,108.744,108.744,108.744c59.962,0,108.743-48.783,108.743-108.744
			c0-14.4-2.821-28.152-7.927-40.742h208.069c-5.107,12.59-7.928,26.342-7.928,40.742
			C469.675,776.858,518.457,825.641,578.418,825.641z M209.46,716.897c0,22.467-18.277,40.744-40.743,40.744
			c-22.466,0-40.744-18.277-40.744-40.744c0-22.465,18.277-40.742,40.744-40.742C191.183,676.155,209.46,694.432,209.46,716.897z
			 M619.162,716.897c0,22.467-18.277,40.744-40.743,40.744s-40.743-18.277-40.743-40.744c0-22.465,18.277-40.742,40.743-40.742
			S619.162,694.432,619.162,716.897z" />
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="./logout.php" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                            Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container p-8  gap-8 w-full">

        <div class="max-w-md mb-4">
            <label for="categorias" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoría</label>
            <select onchange="handleOnChange()" id="categorias" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option <?= !isset($id_categoria) ? 'selected' : '' ?> value="-1">Todas las categorías</option>

                <?php foreach ($categorias as $categoria) { ?>
                    <option <?= isset($id_categoria) && $id_categoria == $categoria['id'] ? 'selected' : '' ?> value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>
                <?php } ?>
            </select>

        </div>

        <?php if (sizeof($productos) == 0) { ?>
            <div class="w-full p-4 h-60 flex items-center justify-center text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
                    No hay productos
                </h5>
            </div>
        <?php } ?>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">


            <?php foreach ($productos as $producto) { ?>



                <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <a href="#">
                        <img class="p-8 rounded-t-lg" src="<?= $producto['ruta_imagen'] ?>" alt="product image" />
                    </a>
                    <div class="px-5 pb-5">
                        <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            <?= $producto['nombre'] ?>
                        </h5>
                        <h6>
                            <?= $producto['categoria'] ?>
                        </h6>

                        <div class="flex items-center justify-between">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">
                                $<?= $producto['precio'] ?>
                            </span>
                            <a href="./comprar.php?id=<?= $producto['id'] ?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Agregar al carrito</a>
                        </div>
                    </div>
                </div>

            <?php } ?>


        </div>
    </div>

    <script>
        function handleOnChange() {
            const id_categoria = document.getElementById('categorias').value;
            if (id_categoria == -1) {
                window.location.href = `./index.php`;
                return
            };
            window.location.href = `./index.php?id_categoria=${id_categoria}`;
        }
    </script>

</body>

</html>