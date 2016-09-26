# GenesisPHP

PHP code generator to make complete systems /  Generador de código PHP para hacer sistemas completos

### Historia

Comenzó con la común necesidad de ir estandarizando las librerías útiles para cualquier sistema web; en ese momento, elaborar cada módulo (altas, bajas, búsquedas, detalle, listados, etc.) para cada tabla de una base de datos nos ocupaba aproximadamente 4 horas de trabajo. Fue entonces, cuando en una charla de estacionamiento, llegó la visión de un patrón repetitivo que pudiera automatizarse. ¿Porqué no hacer un programa que haga programas con estas librerías? así comenzó allá por el 2012.

### Componentes principales

* **Tierra:** Son los "ladrillos" básicos para crear menajes, detalles, listados, la página, etc.
* **Eva:** Son los programas que crean los archivos ; entre los que destacan el inicio de sesión, bitácora, usuarios, etc.
* **Adán:** Las "semillas" son clases PHP que concentran todas las características de cada módulo a construir.
* **Serpiente:** Cuando una semilla necesita datos de otra semilla, recurre a Serpiente que comparte esta información.
* **Demostración:** Una prueba completa de GenesisPHP para un sistema de expedientes con fotos y domicilios.

### Capacidades en detalle

* Poder elaborar grandes sistemas web con rapidez.
* Gran compatibilidad de dispositivos y navegadores, usando páginas responsivas de [Twitter Bootstrap](http://getbootstrap.com/) versión 3.
* Definir las características del sistema por medio de:
    * _Semillas_ las cuales son clases con las columnas de una tabla; describen sus tipos, validaciones, aparición en listados, orden, relaciones, etc.
    * Y la _Serpiente_ contiene los datos que las semillas comparten entre sí y estructura la jerarquía entre ellas.
* Las semillas son la base para crear módulos, donde un módulo es un conjunto de clases, cada una con una finalidad:
    * Registro
    * DetalleWeb
    * FormularioWeb
    * EliminarWeb
    * RecuperarWeb
    * Listado
    * ListadoWeb
    * BusquedaWeb
    * OpcionesSelect
    * PaginaWeb
* Estandarizar las librerías de uso común en **Base2**, a lo que llamo _Tierra_; todas las clases de todos los módulos recurren a estos programas que hacen funciones específicas:
    * MensajeWeb
    * DetalleWeb
    * ListadoWebControlado
    * BarraWeb
    * ImagenWeb
    * AcordeonesWeb
    * LenguetasWeb
    * PlantillaWeb
    * PaginaWeb
* Ofrecer la administración de usuarios, parte de lo que hago llamar _Eva_:
    * AdmUsuarios: los nombres, nombres cortos, contraseñas cifradas, tiempo de expriración, conteo y límite de sesiones.
    * AdmSesiones: listado con los usuarios que están usando el sistema.
    * AdmBitacora: registro de cada nuevo registro, modificación, búsqueda, etc. que haya hecho cada usuario.
    * AdmAutentificaciones: registro de los intentos exitosos y fallidos al entrar, así como las direcciones IP de los clientes.
    * AdmDepartamentos: Un departamento es un conjunto de personas, a su vez, cada departamento debe tener uno más Roles.
    * AdmRoles: Un rol es un conjunto de permisos para cada módulo del sistema. Limita el uso desde ver hasta recuperar registros.
    * AdmIntegrantes: Define los usuarios que pertenecen a cada departamento. Es posible que un usuario pertenezca a más de un departamento e incremente sus privilegios.
* Tener la capacidad de _hackear_ el sistema resultante con programas propios.
* Ejecutar la destrucción y construcción del sistema web con:
    * CrearBaseDatos.sh - Destruir la base de datos, crearla, ejecutar los archivos SQL que crean cada tabla y agregan los registros iniciales.
    * Dios.sh - _su nombre lo dice todo_ y ejecuta estos scripts en este orden:
        * ProtegerArchivos.sh
        * CrearComun.sh
        * CrearGenesisPHP.php
        * CrearSobreescribir.sh
        * CrearExclusivos.sh
        * RestaurarArchivos.sh
    * IniciarNuevoSistema.sh debe estar en tu _PATH_ y ayuda creando los directorios, enlaces y archivos _por defecto_ para un hacer un nuevo sistema. Nota: por defecto espera que GenesisPHP se encuentre en $HOME/Documentos/GitHub/guivaloz/GenesisPHP

### Requerimientos

* [PHP](http://php.net/) versión 5 o mayor.
* Base de datos [PostgreSQL](https://www.postgresql.org/) versión 9 o mayor.
* [Apache servidor HTTP](https://httpd.apache.org/) versión 2.
* GNU/Linux con **Bash** y **Cron** estándard.

### Videos introductorios

* [Fragmento del taller impartido en el Congreso GULAG 2016](https://www.youtube.com/watch?v=35cEw8CTWL8)

### Por hacer

* Crear archivos **CSV** (en construcción)
* Crear archivos **PDF**
* Crear archivos **JSON**
* Crear gráficas por medio de [Google Charts](https://developers.google.com/chart/)
* Capacidad de mostrar mapas y registros georreferenciados con [OpenStreetMap](https://switch2osm.org/)
* Capacidad de ejecutar **Bash Scripts** desde el navegador web.
