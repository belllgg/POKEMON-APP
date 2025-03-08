README - Pokemon Explorer

Introducción

Pokemon Explorer es una aplicación que permite buscar información sobre Pokémon utilizando la API de PokeAPI. La aplicación está dividida en un backend desarrollado en PHP y un frontend en React, los cuales interactúan para obtener y mostrar la información de cada Pokémon.

Funcionamiento

1. Backend (PHP)

El backend se encarga de hacer las solicitudes a la PokeAPI y devolver la información en un formato adecuado para el frontend. 

Sus principales funciones son:
-Recibir solicitudes desde el frontend.
-Consultar la API externa para obtener información de un Pokémon por ID o nombre.
-Implementar un sistema de caché para reducir las peticiones repetidas.
-Devolver los datos en formato JSON al frontend.

2. Frontend (React)

El frontend proporciona una interfaz interactiva para que el usuario busque Pokémon de manera sencilla. Sus características principales incluyen:

-Un campo de búsqueda donde se puede ingresar el ID o nombre del Pokémon.
-Botones para navegar entre Pokémon anteriores y siguientes.
-Tarjetas que muestran el nombre, imagen, tipos y habilidades del Pokémon.
-Manejo de errores en caso de que un Pokémon no exista o haya problemas con la API.

3. Flujo de la Aplicación

-El usuario abre la aplicación en el navegador.
-Introduce un ID o nombre de Pokémon y presiona "Buscar".
-El frontend envía la solicitud al backend.
-El backend consulta la PokeAPI (o el caché si el dato ya fue solicitado anteriormente).
-Se devuelve la información al frontend en formato JSON.
-El frontend muestra los datos en una tarjeta con la imagen y características del Pokémon.

4. Sistema de Caché

Para mejorar la eficiencia, el backend almacena en caché la información de los Pokémon ya consultados. Esto evita hacer demasiadas solicitudes a la PokeAPI y mejora la velocidad de respuesta.

5. Inicialización de la Aplicación

Para ejecutar la aplicación localmente, sigue estos pasos:

Requisitos Previos:
-Tener Docker instalado.
-Tener Node.js instalado (para ejecutar el frontend fuera de Docker).

Pasos para iniciar la aplicación con Docker:
-Clonar el repositorio:
-git clone https://github.com/belllgg/POKEMON-APP.git
-cd pokemon-explorer

Construir y levantar los contenedores con Docker Compose:
-docker-compose up --build
-La aplicación estará disponible en:
-Backend: http://localhost:80/
-Frontend: http://localhost:3000

Pasos para ejecutar sin Docker:

Iniciar el backend:
Instalar PHP y Composer.
Navegar al directorio del backend:
cd backend
Instalar dependencias:
composer install
Iniciar el servidor PHP:
php -S localhost:8000 -t public
Iniciar el frontend:
Navegar al directorio del frontend:
cd frontend
Instalar dependencias:
npm install
Ejecutar la aplicación:
npm start
Abrir http://localhost:3000 en el navegador para ver la aplicación en funcionamiento.

6. Despliegue con Docker
La aplicación se puede ejecutar fácilmente usando Docker con docker-compose up. Se configuran contenedores para el backend (PHP), el frontend (React) y la base de datos 