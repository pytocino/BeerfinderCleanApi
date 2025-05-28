# API de Posts - Documentación Completa

## Descripción General

La API de Posts de Beerfinder permite a los usuarios crear, leer, actualizar y eliminar posts relacionados con cervezas. Incluye un sistema avanzado de etiquetas que permite mencionar usuarios, cervezas y ubicaciones en los posts.

## Características Principales

- ✅ CRUD completo de posts
- ✅ Sistema de etiquetas mixtas (usuarios, cervezas y ubicaciones)
- ✅ Soporte para múltiples imágenes
- ✅ Integración con reseñas de cervezas
- ✅ Paginación optimizada
- ✅ Gestión de privacidad
- ✅ Manejo transaccional de datos

---

## Estructura de Datos

### Objeto Post

```json
{
  "id": 1,
  "user": {
    "id": 123,
    "name": "Juan Pérez",
    "username": "juanperez",
    "profile_picture": "https://example.com/profile.jpg",
    "is_me": false,
    "is_followed": true
  },
  "beer": {
    "id": 456,
    "name": "IPA Artesanal",
    "style": {
      "id": 10,
      "name": "IPA"
    }
  },
  "location": {
    "id": 789,
    "name": "Bar Central"
  },
  "beer_review": {
    "id": 321,
    "rating": 4.5,
    "review_text": "Excelente cerveza con notas cítricas",
    "beer": {
      "id": 456,
      "name": "IPA Artesanal"
    },
    "location": {
      "id": 789,
      "name": "Bar Central"
    }
  },
  "content": "¡Probando esta increíble IPA en @Bar Central con @amigo123! La cerveza #IPA Artesanal está buenísima 🍺",
  "photo_url": "https://example.com/post-main.jpg",
  "additional_photos": [
    "https://example.com/post-1.jpg",
    "https://example.com/post-2.jpg"
  ],
  "all_photos": [
    "https://example.com/post-main.jpg",
    "https://example.com/post-1.jpg",
    "https://example.com/post-2.jpg"
  ],
  "photos_count": 3,
  "tags": {
    "users": [
      {
        "id": 124,
        "username": "amigo123",
        "name": "María García",
        "profile_picture": "https://example.com/maria.jpg"
      }
    ],
    "beers": [
      {
        "id": 457,
        "name": "Cerveza Rubia",
        "brewery": {        "id": 50,
        "name": "Cervecería Artesanal"
      }
    }
  ],
  "locations": [
    {
      "id": 789,
      "name": "Bar Central",
      "address": "Calle Principal 123",
      "city": "Madrid",
      "coordinates": {
        "latitude": 40.4168,
        "longitude": -3.7038
      }
    }
  ]
  },
  "comments_count": 15,
  "likes_count": 42,
  "is_liked": true,
  "edited": false,
  "created_at": "2025-01-15T10:30:00.000000Z",
  "updated_at": "2025-01-15T10:30:00.000000Z"
}
```

### Sistema de Etiquetas

Las etiquetas permiten mencionar usuarios, cervezas y ubicaciones en los posts. Cada etiqueta tiene la siguiente estructura:

```json
{
  "type": "user|beer|location",
  "id": 123
}
```

**Tipos de etiquetas:**
- `user`: Para etiquetar usuarios (@usuario)
- `beer`: Para etiquetar cervezas (#cerveza)
- `location`: Para etiquetar ubicaciones (@ubicacion)

---

## Endpoints

### 1. Obtener Posts

**GET** `/api/v1/posts`

Obtiene una lista paginada de posts visibles para el usuario autenticado.

#### Parámetros de Query

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `page` | integer | No | Número de página (por defecto: 1) |
| `per_page` | integer | No | Posts por página (1-50, por defecto: 15) |

#### Headers

```
Authorization: Bearer {token}
Accept: application/json
```

#### Respuesta Exitosa (200)

```json
{
  "data": [
    {
      // Objeto Post completo
    }
  ],
  "links": {
    "first": "https://api.example.com/v1/posts?page=1",
    "last": "https://api.example.com/v1/posts?page=10",
    "prev": null,
    "next": "https://api.example.com/v1/posts?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "per_page": 15,
    "to": 15,
    "total": 150
  }
}
```

---

### 2. Obtener Post Específico

**GET** `/api/v1/posts/{id}`

Obtiene un post específico por su ID.

#### Parámetros de Ruta

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `id` | integer | Sí | ID del post |

#### Respuesta Exitosa (200)

```json
{
  "post": {
    // Objeto Post completo
  }
}
```

#### Errores

- **404**: Post no encontrado
- **403**: Sin permisos para ver el post

---

### 3. Crear Post

**POST** `/api/v1/posts`

Crea un nuevo post.

#### Headers

```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

#### Parámetros del Body

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `content` | string | No | Contenido del post (máx. 2000 caracteres) |
| `photo_url` | file | No | Imagen principal (JPEG, PNG, JPG, GIF, WEBP, máx. 5MB) |
| `additional_photos` | file[] | No | Imágenes adicionales (máx. 5 archivos) |
| `additional_photos.*` | file | No | Cada imagen (JPEG, PNG, JPG, GIF, WEBP, máx. 5MB) |
| `tags` | array | No | Array de etiquetas |
| `tags.*.type` | string | Sí* | Tipo de etiqueta: "user", "beer" o "location" |
| `tags.*.id` | integer | Sí* | ID del usuario, cerveza o ubicación a etiquetar |
| `beer_id` | integer | No | ID de la cerveza asociada |
| `location_id` | integer | No | ID de la ubicación |

*Requerido si se envía el array `tags`

#### Ejemplo de Request

```javascript
const formData = new FormData();
formData.append('content', '¡Probando esta increíble IPA!');
formData.append('photo_url', file);
formData.append('tags[0][type]', 'user');
formData.append('tags[0][id]', '123');
formData.append('tags[1][type]', 'beer');
formData.append('tags[1][id]', '456');
formData.append('tags[2][type]', 'location');
formData.append('tags[2][id]', '789');
formData.append('beer_id', '456');
formData.append('location_id', '789');

fetch('/api/v1/posts', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token
  },
  body: formData
});
```

#### Respuesta Exitosa (201)

```json
{
  "success": true,
  "message": "Post creado correctamente",
  "post": {
    // Objeto Post completo
  }
}
```

#### Errores de Validación (422)

```json
{
  "message": "Los datos proporcionados no son válidos",
  "errors": {
    "content": ["El contenido no puede exceder 2000 caracteres"],
    "tags.0.type": ["El tipo de etiqueta debe ser user, beer o location"],
    "photo_url": ["El archivo debe ser una imagen"]
  }
}
```

---

### 4. Actualizar Post

**PUT/PATCH** `/api/v1/posts/{id}`

Actualiza un post existente. Solo el autor puede editar sus posts.

#### Parámetros de Ruta

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `id` | integer | Sí | ID del post a actualizar |

#### Parámetros del Body

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `content` | string | No | Nuevo contenido del post |
| `tags` | array | No | Nuevas etiquetas (reemplaza las existentes) |
| `tags.*.type` | string | Sí* | Tipo de etiqueta: "user", "beer" o "location" |
| `tags.*.id` | integer | Sí* | ID del usuario, cerveza o ubicación |
| `beer_id` | integer | No | Nuevo ID de cerveza asociada |
| `location_id` | integer | No | Nuevo ID de ubicación |

*Requerido si se envía el array `tags`

#### Respuesta Exitosa (200)

```json
{
  "success": true,
  "message": "Post actualizado correctamente",
  "post": {
    // Objeto Post actualizado con "edited": true
  }
}
```

#### Errores

- **403**: Sin permisos para editar el post
- **404**: Post no encontrado
- **422**: Errores de validación

---

### 5. Eliminar Post

**DELETE** `/api/v1/posts/{id}`

Elimina un post existente. Solo el autor puede eliminar sus posts.

#### Parámetros de Ruta

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `id` | integer | Sí | ID del post a eliminar |

#### Respuesta Exitosa (200)

```json
{
  "message": "Post eliminado correctamente."
}
```

#### Errores

- **403**: Sin permisos para eliminar el post
- **404**: Post no encontrado

---

### 6. Crear Post desde Reseña

**POST** `/api/v1/beer-reviews/{id}/post`

Crea un post asociado a una reseña de cerveza existente.

#### Parámetros de Ruta

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `id` | integer | Sí | ID de la reseña de cerveza |

#### Parámetros del Body

Mismos parámetros que crear post, pero `content` es requerido.

#### Respuesta Exitosa (201)

```json
{
  "success": true,
  "message": "Post creado desde review correctamente",
  "post": {
    // Objeto Post con datos heredados de la reseña
  }
}
```

#### Errores

- **403**: Sin permisos para crear post de esta reseña
- **404**: Reseña no encontrada
- **409**: Ya existe un post asociado a esta reseña

---

### 7. Obtener Usuarios Etiquetados

**GET** `/api/v1/posts/{id}/tagged-users`

Obtiene la lista de usuarios etiquetados en un post específico.

#### Respuesta Exitosa (200)

```json
{
  "tagged_users": [
    {
      "id": 123,
      "username": "usuario1",
      "name": "Usuario Uno",
      "profile_picture": "https://example.com/profile1.jpg"
    }
  ]
}
```

---

### 8. Obtener Fotos del Post

**GET** `/api/v1/posts/{id}/photos`

Obtiene todas las fotos de un post específico.

#### Respuesta Exitosa (200)

```json
{
  "photos": [
    "https://example.com/post-main.jpg",
    "https://example.com/post-1.jpg",
    "https://example.com/post-2.jpg"
  ],
  "photos_count": 3
}
```

---

## Rutas Públicas (Sin Autenticación)

### Obtener Posts Públicos

**GET** `/api/v1/public/posts`

Obtiene posts de usuarios con perfil público.

#### Parámetros de Query

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `page` | integer | No | Número de página |
| `per_page` | integer | No | Posts por página (1-50) |

### Obtener Post Público Específico

**GET** `/api/v1/public/posts/{id}`

Obtiene un post específico si es de un usuario público.

---

## Gestión de Privacidad

### Reglas de Visibilidad

1. **Usuarios públicos**: Sus posts son visibles para todos
2. **Usuarios privados**: Sus posts solo son visibles para:
   - Ellos mismos
   - Sus seguidores aceptados
3. **Posts con etiquetas**: Los usuarios etiquetados pueden ver el post independientemente de la privacidad

### Autenticación

Todas las rutas (excepto las públicas) requieren autenticación mediante Sanctum:

```
Authorization: Bearer {token}
```

---

## Códigos de Estado HTTP

| Código | Descripción |
|--------|-------------|
| 200 | Operación exitosa |
| 201 | Recurso creado exitosamente |
| 403 | Sin permisos |
| 404 | Recurso no encontrado |
| 422 | Errores de validación |
| 500 | Error interno del servidor |

---

## Ejemplos de Uso

### Crear un post simple

```javascript
const formData = new FormData();
formData.append('content', '¡Disfrutando una cerveza!');

fetch('/api/v1/posts', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token
  },
  body: formData
});
```

### Crear un post con etiquetas

```javascript
const formData = new FormData();
formData.append('content', 'Probando esta IPA en @Bar Central con @juan y @maria');
formData.append('tags[0][type]', 'user');
formData.append('tags[0][id]', '123');
formData.append('tags[1][type]', 'user');
formData.append('tags[1][id]', '124');
formData.append('tags[2][type]', 'beer');
formData.append('tags[2][id]', '456');
formData.append('tags[3][type]', 'location');
formData.append('tags[3][id]', '789');

fetch('/api/v1/posts', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token
  },
  body: formData
});
```

### Actualizar etiquetas de un post

```javascript
fetch('/api/v1/posts/123', {
  method: 'PUT',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    tags: [
      { type: 'user', id: 456 },
      { type: 'beer', id: 789 },
      { type: 'location', id: 101 }
    ]
  })
});
```

---

## Notas Técnicas

### Optimizaciones Implementadas

1. **Eager Loading**: Se cargan relaciones de forma optimizada para evitar queries N+1
2. **Paginación**: Todas las listas están paginadas para mejor rendimiento
3. **Transacciones**: Operaciones críticas usan transacciones de BD
4. **Validación**: Validación robusta en todos los endpoints
5. **Manejo de Errores**: Respuestas consistentes y logs detallados

### Consideraciones de Rendimiento

- Las imágenes se almacenan en `storage/app/public/posts/`
- Las etiquetas se validan en tiempo real contra la base de datos
- Los posts se ordenan por fecha de creación (más recientes primero)
- Se usan índices en la base de datos para consultas optimizadas

### Limitaciones

- Máximo 5 imágenes adicionales por post
- Máximo 5MB por imagen
- Máximo 2000 caracteres por contenido
- Las etiquetas deben referenciar recursos existentes

---

## Changelog

### v1.0.0 - 2025-01-15

- ✅ Sistema de etiquetas mixtas (usuarios + cervezas + ubicaciones)
- ✅ CRUD completo optimizado
- ✅ Soporte para múltiples imágenes
- ✅ Integración con reseñas de cervezas
- ✅ Rutas públicas para contenido abierto
- ✅ Gestión avanzada de privacidad
- ✅ Documentación completa de API
