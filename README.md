# API de Gestión de Usuarios y Mascotas

Esta API permite gestionar usuarios y sus mascotas, utilizando autenticación JWT para proteger las rutas. Incluye funcionalidades de registro, login, gestión de usuarios (solo para administradores) y gestión de mascotas (para usuarios autenticados).

## Autenticación JWT

JWT (JSON Web Token) es un sistema de autenticación basado en tokens. Cuando un usuario inicia sesión correctamente, el servidor genera un token JWT y se lo envía al cliente. Este token debe ser enviado en cada petición protegida (normalmente en la cabecera `Authorization: Bearer <token>`). El servidor valida el token en cada petición para comprobar la identidad del usuario y sus permisos, sin necesidad de mantener sesiones en el servidor.

El token está formado por 3 partes separadas por puntos (.), que son: header, payload y signature. 
1. El header (cabecera) contiene información sobre el tipo de token y el algoritmo que se usó para firmarlo.
2. El payload es como el cuerpo del token, donde van los datos que queremos transmitir, como la id, email, rol o cuándo expira el token.
3. La signature (firma) es una mezcla entre el header, el payload y una clave secreta que solo el servidor conoce.


## Rutas de la API

### Autenticación

- `POST /api/register`  
  Registrar un nuevo usuario.  
  **Body:** `name`, `role` (`admin` o `user`), `email`, `password`, `password_confirmation`

- `POST /api/login`  
  Iniciar sesión y obtener un token JWT.  
  **Body:** `email`, `password`

- `POST /api/logout`  
  Cerrar sesión (requiere autenticación JWT).

- `GET /api/me`  
  Obtener los datos del usuario autenticado (requiere autenticación JWT).

---

### Gestión de Usuarios (solo admin)

- `GET /api/users`  
  Listar todos los usuarios.

- `GET /api/users/{id}`  
  Obtener un usuario por ID.

- `PUT /api/users/{id}`  
  Actualizar un usuario por ID.

- `DELETE /api/users/{id}`  
  Eliminar un usuario por ID.

---

### Gestión de Mascotas (usuario autenticado)

- `GET /api/pets`  
  Listar las mascotas del usuario autenticado.

- `POST /api/pets`  
  Crear una nueva mascota.  
  **Body:** `nombre`, `imagen`

- `PUT /api/pets/{id}`  
  Actualizar completamente una mascota.

- `PATCH /api/pets/{id}`  
  Actualizar parcialmente una mascota.

- `DELETE /api/pets/{id}`  
  Eliminar una mascota.

---

## Notas

- Para acceder a las rutas protegidas, debes incluir el token JWT en la cabecera `Authorization`.
- Solo los usuarios con rol `admin` pueden gestionar otros usuarios.
- Cada usuario solo puede gestionar sus propias mascotas.

---

## Ejemplo de uso de JWT

1. El usuario se registra o inicia sesión y recibe un token JWT.
2. El cliente guarda el token y lo envía en cada petición protegida.
3. El servidor valida el token y permite o deniega el acceso según los permisos del usuario.

---

## Usuario administrador

- email     -->     k@gmail.com
- password  -->     12345678
