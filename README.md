# Sistema de Gestión de Contactos

## Descripción General
Esta es una aplicación web básica desarrollada para aprender fundamentos de desarrollo web. La aplicación permite a los usuarios registrarse, iniciar sesión y gestionar contactos con sus respectivas direcciones.

## Tecnologías Utilizadas
- PHP
- HTML
- Bootstrap
- MySQL/SQL
- JavaScript
- AJAX

## Características
- **Autenticación de Usuarios**
  - Registro
  - Inicio de sesión
  - Cierre de sesión
  
- **Gestión de Contactos**
  - Agregar nuevos contactos con nombre y número de teléfono
  - Ver todos los contactos
  - Editar información de contactos
  - Eliminar contactos
  
- **Gestión de Direcciones**
  - Agregar múltiples direcciones para cada contacto
  - Editar direcciones
  - Eliminar direcciones
  - Visualizar direcciones en la página principal

## Primeros Pasos

### Requisitos Previos 
- PHP 7.0 o superior
- Servidor MySQL
- Servidor web (Apache/Nginx)

### Instalación
1. Clonar el repositorio
   ```
   git clone [url-del-repositorio]
   ```

2. Importar el esquema de la base de datos
   ```
   mysql -u nombre_usuario -p nombre_base_datos < sql/schema.sql
   ```

3. Configurar la conexión a la base de datos
   Editar el archivo `db.php` con tus credenciales de base de datos:
   ```php
   $host = 'localhost';
   $user = 'tu_usuario';
   $password = 'tu_contraseña';
   $database = 'tu_base_de_datos';
   ```

4. Iniciar tu servidor web y navegar a la aplicación

## Objetivos de Aprendizaje
Este proyecto fue creado para aprender y practicar:
- Desarrollo backend con PHP
- Operaciones de base de datos con SQL
- Diseño frontend con Bootstrap
- Funcionalidad del lado del cliente con JavaScript
- AJAX para carga asíncrona de datos
- Autenticación de usuarios y gestión de sesiones
- Operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
