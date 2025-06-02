# Documentación de Creación Automática de Reseñas de Cerveza

## Descripción General

La funcionalidad de creación automática permite a los usuarios crear reseñas de cerveza proporcionando nombres de cervezas y ubicaciones que pueden no existir previamente en la base de datos. El sistema automáticamente creará estos registros con `is_verified = false` para su posterior revisión administrativa.

## Endpoint

```
POST /api/beer-reviews/
```

## Parámetros de Entrada

### Opción 1: Usar IDs existentes
```json
{
    "beer_id": 123,
    "location_id": 456,
    "rating": 4.5,
    "review_text": "Excelente cerveza artesanal",
    "serving_type": "bottle",
    "purchase_price": 15.99,
    "purchase_currency": "USD",
    "is_public": true
}
```

### Opción 2: Crear nuevos registros automáticamente
```json
{
    "beer_name": "IPA Artesanal Local",
    "location_name": "Bar El Lúpulo",
    "location_latitude": -34.6037,
    "location_longitude": -58.3816,
    "location_address": "Av. Corrientes 1234, Buenos Aires",
    "rating": 4.5,
    "review_text": "Excelente cerveza artesanal",
    "serving_type": "draft",
    "purchase_price": 800,
    "purchase_currency": "ARS",
    "is_public": true
}
```

### Opción 3: Combinación (cerveza existente, ubicación nueva)
```json
{
    "beer_id": 123,
    "location_name": "Nuevo Bar",
    "location_latitude": -34.6037,
    "location_longitude": -58.3816,
    "rating": 4.0,
    "review_text": "Buena cerveza en nuevo lugar"
}
```

## Campos Obligatorios

- `rating`: Calificación de 0 a 5
- Al menos uno de: `beer_id` o `beer_name`

## Campos Opcionales

### Para cervezas:
- `beer_name`: Nombre de la cerveza (si no se proporciona `beer_id`)

### Para ubicaciones:
- `location_id`: ID de ubicación existente
- `location_name`: Nombre de la ubicación (si no se proporciona `location_id`)
- `location_latitude`: Latitud de la ubicación
- `location_longitude`: Longitud de la ubicación
- `location_address`: Dirección completa de la ubicación

### Para la reseña:
- `review_text`: Texto de la reseña
- `serving_type`: Tipo de servicio (bottle, can, draft, etc.)
- `purchase_price`: Precio de compra
- `purchase_currency`: Moneda (código ISO de 3 letras)
- `is_public`: Si la reseña es pública (default: true)

## Lógica de Creación Automática

### Cervezas
1. Si se proporciona `beer_id`, se usa directamente
2. Si se proporciona `beer_name`:
   - Se busca una cerveza existente con nombre similar
   - Si no existe, se crea una nueva con:
     - `name`: El nombre proporcionado
     - `brewery_id`: null
     - `style_id`: null
     - `is_verified`: false
     - `description`: "Cerveza creada automáticamente - Pendiente de verificación"

### Ubicaciones
1. Si se proporciona `location_id`, se usa directamente
2. Si se proporciona `location_name`:
   - Se busca ubicación existente por nombre y cercanía (radio de 100m)
   - Si no existe, se crea una nueva con:
     - `name`: El nombre proporcionado
     - `latitude`/`longitude`: Coordenadas proporcionadas
     - `address`: Dirección proporcionada
     - `verified`: false
     - `type`: "other"
     - `city`/`country`: Valores por defecto

### Cervecerías
- Se crean automáticamente cuando se crea una cerveza nueva
- Se asignan valores por defecto para país y ciudad

## Respuesta de Éxito

```json
{
    "data": {
        "id": 789,
        "rating": 4.5,
        "review_text": "Excelente cerveza artesanal",
        "serving_type": "draft",
        "purchase_price": 800,
        "purchase_currency": "ARS",
        "is_public": true,
        "created_at": "2025-06-01T10:30:00.000000Z",
        "updated_at": "2025-06-01T10:30:00.000000Z",
        "beer": {
            "id": 456,
            "name": "IPA Artesanal Local",
            "is_verified": false,
            "brewery": {
                "id": 123,
                "name": "Cervecería Nueva"
            },
            "style": {
                "id": 1,
                "name": "IPA"
            }
        },
        "location": {
            "id": 789,
            "name": "Bar El Lúpulo",
            "verified": false,
            "latitude": -34.6037,
            "longitude": -58.3816,
            "address": "Av. Corrientes 1234, Buenos Aires"
        },
        "user": {
            "id": 1,
            "name": "Usuario Test",
            "username": "usuario_test"
        }
    }
}
```

## Casos de Error

### Datos de validación incorrectos
```json
{
    "errors": {
        "rating": ["El campo rating es obligatorio."],
        "beer_name": ["Debe proporcionar un beer_id válido o un beer_name para crear una nueva cerveza"]
    }
}
```

### Error del servidor
```json
{
    "errors": {
        "general": ["Error al crear la reseña. Por favor, inténtelo de nuevo."]
    }
}
```

## Consideraciones para el Frontend

1. **Búsqueda inicial**: El frontend debe seguir ofreciendo búsqueda de cervezas y ubicaciones existentes
2. **Fallback automático**: Si el usuario no selecciona un resultado existente, enviar los nombres para creación automática
3. **Indicadores visuales**: Mostrar al usuario cuando se han creado registros nuevos no verificados
4. **Coordenadas**: Usar geolocalización o mapas para obtener coordenadas precisas de ubicaciones

## Logs y Debugging

El sistema registra automáticamente:
- Creación de nuevas cervezas
- Creación de nuevas ubicaciones  
- Creación de nuevas cervecerías
- Errores en el proceso de creación

Los logs se pueden revisar en `storage/logs/laravel.log`.

## Próximos Pasos

1. Crear interfaz de administración para revisar registros no verificados
2. Implementar proceso de verificación y aprobación
3. Notificar a administradores cuando se crean nuevos registros
4. Agregar validaciones adicionales para datos de calidad
