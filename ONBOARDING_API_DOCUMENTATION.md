# API Onboarding - Completar Perfil

Esta documentación describe los endpoints para el proceso de onboarding después del login con Google.

## Flujo de Onboarding

### 1. Verificar estado del perfil
```http
GET /api/v1/onboarding/status
Authorization: Bearer {token}
```

**Respuesta cuando necesita onboarding:**
```json
{
  "profile_completed": false,
  "needs_onboarding": true,
  "user": {
    "id": 123,
    "name": "Juan Pérez",
    "username": "juan-perez",
    "email": "juan@gmail.com",
    "profile_completed": false,
    "profile_picture": null,
    "private_profile": false,
    "profile": {
      "bio": null,
      "location": null,
      "birthdate": null,
      "website": null,
      "phone": null,
      "instagram": null,
      "twitter": null,
      "facebook": null
    }
  }
}
```

**Respuesta cuando el perfil ya está completo:**
```json
{
  "profile_completed": true,
  "needs_onboarding": false,
  "user": {
    // datos del usuario completos
  }
}
```

### 2. Completar perfil
```http
POST /api/v1/onboarding/complete
Authorization: Bearer {token}
Content-Type: application/json

{
  "username": "nuevo_username", // opcional
  "bio": "Amante de la cerveza artesanal",
  "location": "Madrid, España",
  "birthdate": "1990-05-15",
  "website": "https://mipagina.com",
  "phone": "+34123456789",
  "instagram": "mi_instagram",
  "twitter": "mi_twitter",
  "facebook": "mi_facebook",
  "private_profile": false,
  "allow_mentions": true,
  "email_notifications": true,
  "timezone": "Europe/Madrid"
}
```

**Respuesta exitosa:**
```json
{
  "message": "Perfil completado exitosamente",
  "user": {
    "id": 123,
    "name": "Juan Pérez",
    "username": "nuevo_username",
    "email": "juan@gmail.com",
    "profile_completed": true,
    "profile_picture": null,
    "private_profile": false,
    "profile": {
      "bio": "Amante de la cerveza artesanal",
      "location": "Madrid, España",
      "birthdate": "1990-05-15",
      "website": "https://mipagina.com",
      "phone": "+34123456789",
      "instagram": "mi_instagram",
      "twitter": "mi_twitter",
      "facebook": "mi_facebook",
      "allow_mentions": true,
      "email_notifications": true,
      "timezone": "Europe/Madrid"
    }
  }
}
```

### 3. Saltar proceso de onboarding (opcional)
```http
POST /api/v1/onboarding/skip
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
  "message": "Proceso de onboarding omitido",
  "user": {
    "id": 123,
    "name": "Juan Pérez",
    "username": "juan-perez",
    "email": "juan@gmail.com",
    "profile_completed": true,
    // resto de datos del usuario
  }
}
```

## Validaciones

### Campos opcionales en completar perfil:
- `username`: string, máximo 255 caracteres, único
- `bio`: string, máximo 500 caracteres
- `location`: string, máximo 255 caracteres  
- `birthdate`: fecha válida, anterior a hoy
- `website`: URL válida, máximo 255 caracteres
- `phone`: string, máximo 20 caracteres
- `instagram`, `twitter`, `facebook`: string, máximo 255 caracteres
- `private_profile`: boolean
- `allow_mentions`: boolean
- `email_notifications`: boolean
- `timezone`: string, máximo 50 caracteres

## Estados de Error

### 422 - Datos de validación incorrectos:
```json
{
  "message": "Datos de validación incorrectos",
  "errors": {
    "username": ["El username ya está en uso"],
    "birthdate": ["La fecha debe ser anterior a hoy"]
  }
}
```

### 200 - Perfil ya completado:
```json
{
  "message": "El perfil ya está completo",
  "user": {
    // datos del usuario
  }
}
```

## Integración en el Frontend

### Flujo recomendado:

1. **Después del login con Google**: Llamar a `/api/v1/onboarding/status`
2. **Si `needs_onboarding` es `true`**: Mostrar pantallas de onboarding
3. **Completar datos**: Enviar a `/api/v1/onboarding/complete`
4. **Opcional**: Permitir saltar con `/api/v1/onboarding/skip`

```php
Route::middleware(['auth:sanctum', 'profile.completed'])->group(function () {
    // rutas que requieren perfil completo
});
```
