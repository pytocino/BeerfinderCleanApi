# Documentación: Creación Automática en Posts

## Funcionalidad de Etiquetas con Creación Automática

El sistema de posts ahora soporta la creación automática de cervezas y ubicaciones cuando se etiquetan elementos que no existen en la base de datos.

## Casos de Uso

### 1. Etiquetado de Usuarios Existentes
Para etiquetar usuarios que ya existen en la base de datos:

```json
{
  "content": "Probando cerveza con @usuario",
  "tags": [
    {
      "type": "user",
      "id": 123
    }
  ]
}
```

### 2. Etiquetado de Cervezas Existentes
Para etiquetar cervezas que ya existen:

```json
{
  "content": "Disfrutando una excelente cerveza",
  "tags": [
    {
      "type": "beer",
      "id": 456
    }
  ]
}
```

### 3. Creación Automática de Cervezas
Para crear automáticamente una cerveza nueva:

```json
{
  "content": "Probando una cerveza artesanal nueva",
  "tags": [
    {
      "type": "beer",
      "name": "IPA Experimental"
    }
  ]
}
```

**Nota**: La cerveza se crea solo con el nombre. Los campos `brewery_id` y `style_id` se establecen como `null` (pueden ser actualizados después por un administrador).

### 4. Etiquetado de Ubicaciones Existentes
Para etiquetar ubicaciones existentes:

```json
{
  "content": "En mi lugar favorito",
  "tags": [
    {
      "type": "location",
      "id": 789
    }
  ]
}
```

### 5. Creación Automática de Ubicaciones
Para crear automáticamente una ubicación nueva:

```json
{
  "content": "Descubriendo nuevos lugares",
  "tags": [
    {
      "type": "location",
      "name": "Bar Nuevo Centro",
      "latitude": -34.6037,
      "longitude": -58.3816,
      "address": "Av. Corrientes 1234, Buenos Aires"
    }
  ]
}
```

**Campos opcionales para ubicaciones**:
- `latitude`: Coordenada de latitud (-90 a 90)
- `longitude`: Coordenada de longitud (-180 a 180)
- `address`: Dirección completa del lugar

### 6. Combinación de Etiquetas
Puedes combinar diferentes tipos de etiquetas en un mismo post:

```json
{
  "content": "Gran noche con amigos probando cerveza nueva",
  "tags": [
    {
      "type": "user",
      "id": 123
    },
    {
      "type": "beer",
      "name": "Stout Chocolate"
    },
    {
      "type": "location",
      "name": "Pub Central",
      "latitude": -34.6037,
      "longitude": -58.3816
    }
  ]
}
```

## Endpoints que Soportan Esta Funcionalidad

### Crear Post
- **POST** `/api/posts`
- Permite crear posts con etiquetas que incluyan creación automática

### Actualizar Post
- **PUT** `/api/posts/{id}`
- Permite actualizar posts y modificar las etiquetas

### Crear Post desde Review
- **POST** `/api/posts/from-review/{review_id}`
- Crea un post basado en una review existente

## Comportamiento del Sistema

### Cervezas Creadas Automáticamente
- Se crean con `is_verified = false`
- Los campos `brewery_id` y `style_id` se establecen como `null`
- Los campos `abv`, `ibu` e `image_url` se establecen como `null`
- Pueden ser actualizadas después por un administrador

### Ubicaciones Creadas Automáticamente
- Se crean con `verified = false`
- Se buscan ubicaciones existentes en un radio de 100 metros si se proporcionan coordenadas
- Campos por defecto:
  - `type`: 'other'
  - `status`: 'active'
  - `city`: 'Ciudad Desconocida'
  - `country`: 'País Desconocido'

### Cervecerías Creadas Automáticamente
- ~~Ya no se crean automáticamente~~
- ~~Se crean automáticamente cuando se especifica `brewery_name` para una cerveza nueva~~
- ~~Campos por defecto:~~
  - ~~`country`: 'País Desconocido'~~
  - ~~`city`: 'Ciudad Desconocida'~~

**Nota**: Con la nueva implementación simplificada, las cervezas se crean sin cervecería asociada (`brewery_id = null`).

## Validación

### Reglas de Validación para Tags
```php
'tags' => 'nullable|array',
'tags.*.type' => 'required|in:user,beer,location',
'tags.*.id' => 'sometimes|integer',
'tags.*.name' => 'sometimes|string|max:255',
'tags.*.latitude' => 'sometimes|numeric|between:-90,90',
'tags.*.longitude' => 'sometimes|numeric|between:-180,180',
'tags.*.address' => 'sometimes|string|max:500',
```

### Lógica de Validación
- Para `type: user` → Es obligatorio proporcionar `id`
- Para `type: beer` → Se puede proporcionar `id` (existente) o `name` (crear nuevo)
- Para `type: location` → Se puede proporcionar `id` (existente) o `name` (crear nuevo)

## Logging

El sistema registra automáticamente:
- Creación exitosa de cervezas, cervecerías y ubicaciones
- Errores durante el proceso de creación
- Advertencias cuando no se puede procesar una etiqueta

## Administración

Todos los elementos creados automáticamente se marcan como no verificados:
- Cervezas: `is_verified = false`
- Ubicaciones: `verified = false`

Estos elementos aparecerán en la futura interfaz de administración para ser revisados y aprobados manualmente.

## Ejemplo Completo

```json
POST /api/posts
{
  "content": "Increíble noche cervecera con los amigos en un lugar nuevo",
  "photo_url": "imagen.jpg",
  "tags": [
    {
      "type": "user",
      "id": 123
    },
    {
      "type": "user", 
      "id": 456
    },
    {
      "type": "beer",
      "name": "Porter Ahumada"
    },
    {
      "type": "location",
      "name": "Brewpub Artesanal",
      "latitude": -34.6037,
      "longitude": -58.3816,
      "address": "Av. Libertador 1500, Buenos Aires"
    }
  ]
}
```

Este ejemplo:
1. Etiqueta a dos usuarios existentes
2. Crea automáticamente una nueva cerveza "Porter Ahumada" (sin cervecería asociada)
3. Crea automáticamente una nueva ubicación "Brewpub Artesanal" con coordenadas y dirección
