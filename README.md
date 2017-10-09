ReActive Versión 2
============

Autor: Francis Suniaga <br>
Fecha: 09/10/2017 <br>
email: franmaye@hotmail.com <br>

Proyecto creado en symfony 2, con BD de datos MySql, permite registrar un producto en la base de datos, el cual consiste en llenar un formulario indicando lo siguiente: <br>

Nombre de producto <br>
Tipo de producto (perecedero / no perecedero) <br>
Fecha de vencimiento en caso de ser perecedero <br>
Si cumple / no cumple / no aplica, un codigo de barra <br>
Si cumple se debe agregar el codigo de barra <br>
No cumple debe agregar observación <br>
Cantidad de productos <br>
Adicional internamente guarda el id del usuario que hace la operación <br>

A continuación se presentan los pasos a seguir para la instalación de la aplicación. <br>

Clonar el repositorio como se indica a continuacion:
===================================================
$ git clone https://github.com/fsuniaga/ReActiveV2.git <br>
$ cd ReActiveV2/ <br>
$ composer install --no-interaction <br>
$ php app/console server:run <br>


Crear Base de datos
=======================
1. Configurar el archivo app/config/parameters.yml  

parameters: <br> 
    database_host: 127.0.0.1 <br> 
    database_port: null <br> 
    database_name: reactivebd <br> 
    database_user: root <br> 
    database_password: null <br> 
    
# ...

2. Luego ejecutar los siguientes comando en la consola ubicado en el directorio del proyecto <br>
$ php app/console doctrine:database:create <br> 

$ php app/console doctrine:schema:update --force <br> 


Luego debe ingresar a la aplicacion en la siguiente ruta
=======================================================
http://localhost:8000 <br>


Para conectarse a la aplicación 
=================================================
Se debe crear el usuario indicando lo siguiente: <br>

Nombre <br>
Apellido <br>
Correo Electronico <br>
Contraseña <br>
Tipo de usuario (Administrador/Usuario) <br>

Luego de crear el usuario puede iniciar sesion y comenzar a usar la aplicación <br>


Librerias usadas en la aplicación
=================================================
jQuery <br>
    https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js <br>
Boostrap <br>
    https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js <br>
    https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css <br>
