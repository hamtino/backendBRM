# backendBRM

## Base de datos


se implemento mysql con los siguientes parametros:

`nombre de la base de datos`

name = 'mysql';

username = 'root';

password = '';

`nombre de la tabla`

TABLE `productos`

## estructura de la base de datos

  `id` int(10) UNSIGNED NOT NULL

  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL

  `cantidad` int(11)NOT NULL

  `lote` int(11) NOT NULL

  `vencimiento` date NOT NULL DEFAULT
  
  `precio` double(8,2) NOT NULL

## implementacion

pegar los archivos existentes en la carpeta www o carpeta Xampp htdocs.

