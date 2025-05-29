# API de Onboarding - Configuraciones de Privacidad y Notificaciones

## Completar Perfil de Usuario

### Endpoint
```
POST /api/v1/onboarding/complete
```

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
```

### Parámetros disponibles

#### 📋 **Información Básica** (Opcional)
- `username` - Nombre de usuario único (string, max: 255)
- `bio` - Biografía del usuario (string, max: 500)
- `location` - Ubicación del usuario (string, max: 255)
- `birthdate` - Fecha de nacimiento (date, formato: YYYY-MM-DD, debe ser anterior a hoy)
- `website` - Sitio web personal (url, max: 255)
- `phone` - Número de teléfono (string, max: 20)
- `timezone` - Zona horaria (string, max: 50)

#### 🔗 **Redes Sociales** (Opcional)
- `instagram` - Usuario de Instagram (string, max: 255)
- `twitter` - Usuario de Twitter (string, max: 255)
- `facebook` - Usuario de Facebook (string, max: 255)

#### 🔒 **Configuraciones de Privacidad y Seguridad**
- `private_profile` - Perfil privado (boolean, default: false)
- `allow_mentions` - Permitir menciones (boolean, default: true)
- `show_online_status` - Mostrar estado en línea (boolean, default: true)
- `share_location` - Compartir ubicación (boolean, default: false)

#### 📧 **Configuraciones de Notificaciones Generales**
- `email_notifications` - Notificaciones por email (boolean, default: true)

#### 🔔 **Configuraciones de Notificaciones Específicas**
- `notify_new_followers` - Nuevos seguidores (boolean, default: true)
- `notify_likes` - Likes en publicaciones (boolean, default: true)
- `notify_comments` - Comentarios en publicaciones (boolean, default: true)
- `notify_mentions` - Menciones (boolean, default: true)
- `notify_following_posts` - Nuevas publicaciones de usuarios seguidos (boolean, default: true)
- `notify_recommendations` - Recomendaciones (boolean, default: true)
- `notify_trends` - Tendencias (boolean, default: false)
- `notify_direct_messages` - Mensajes directos (boolean, default: true)
- `notify_group_messages` - Mensajes de grupo (boolean, default: true)
- `notify_events` - Eventos (boolean, default: true)
- `notify_updates` - Actualizaciones de la app (boolean, default: true)
- `notify_security` - Alertas de seguridad (boolean, default: true)
- `notify_promotions` - Promociones y ofertas (boolean, default: false)

### Ejemplo de Request Completo

```json
{
  "username": "cervecero_madrid",
  "bio": "Amante de las cervezas artesanales y los maridajes únicos 🍺",
  "location": "Madrid, España",
  "birthdate": "1990-05-15",
  "website": "https://miblog-cervecero.com",
  "phone": "+34 666 123 456",
  "instagram": "cervecero_madrid",
  "twitter": "cervecero_mad",
  "timezone": "Europe/Madrid",
  
  "private_profile": false,
  "allow_mentions": true,
  "show_online_status": true,
  "share_location": false,
  
  "email_notifications": true,
  
  "notify_new_followers": true,
  "notify_likes": true,
  "notify_comments": true,
  "notify_mentions": true,
  "notify_following_posts": true,
  "notify_recommendations": true,
  "notify_trends": false,
  "notify_direct_messages": true,
  "notify_group_messages": true,
  "notify_events": true,
  "notify_updates": true,
  "notify_security": true,
  "notify_promotions": false
}
```

### Ejemplo de Request Mínimo

```json
{
  "username": "mi_nuevo_username",
  "private_profile": false,
  "email_notifications": true
}
```

### Respuesta Exitosa

```json
{
  "message": "Perfil completado exitosamente",
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "username": "cervecero_madrid",
    "email": "juan@gmail.com",
    "profile_completed": true,
    "private_profile": false,
    "profile_picture": null,
    "created_at": "2025-05-29T10:30:00.000000Z",
    "updated_at": "2025-05-29T10:35:00.000000Z",
    "profile": {
      "bio": "Amante de las cervezas artesanales y los maridajes únicos 🍺",
      "location": "Madrid, España",
      "birthdate": "1990-05-15",
      "website": "https://miblog-cervecero.com",
      "phone": "+34 666 123 456",
      "instagram": "cervecero_madrid",
      "twitter": "cervecero_mad",
      "facebook": null,
      "timezone": "Europe/Madrid",
      
      "allow_mentions": true,
      "show_online_status": true,
      "share_location": false,
      
      "email_notifications": true,
      "notify_new_followers": true,
      "notify_likes": true,
      "notify_comments": true,
      "notify_mentions": true,
      "notify_following_posts": true,
      "notify_recommendations": true,
      "notify_trends": false,
      "notify_direct_messages": true,
      "notify_group_messages": true,
      "notify_events": true,
      "notify_updates": true,
      "notify_security": true,
      "notify_promotions": false
    }
  }
}
```

## Verificar Estado del Perfil

### Endpoint
```
GET /api/v1/onboarding/status
```

### Respuesta
```json
{
  "profile_completed": false,
  "needs_onboarding": true,
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "username": "juan-perez-123",
    "email": "juan@gmail.com",
    "profile_completed": false,
    // ... resto del usuario
  }
}
```

## Saltar Onboarding

### Endpoint
```
POST /api/v1/onboarding/skip
```

### Respuesta
```json
{
  "message": "Proceso de onboarding omitido",
  "user": {
    "id": 1,
    "profile_completed": true,
    // ... resto del usuario
  }
}
```

## 📱 Uso en el Frontend

### Flujo de Onboarding Recomendado

```javascript
// 1. Después del login con Google
const loginResponse = await loginWithGoogle();
const user = loginResponse.user;

// 2. Verificar si necesita onboarding
if (!user.profile_completed) {
  // Mostrar pantallas de onboarding
  showOnboardingFlow();
} else {
  // Ir directamente a la app
  navigateToMainApp();
}

// 3. En las pantallas de onboarding
const completeProfile = async (profileData) => {
  try {
    const response = await api.post('/onboarding/complete', profileData);
    // Usuario completó el perfil
    navigateToMainApp();
  } catch (error) {
    // Manejar errores de validación
    showValidationErrors(error.response.data.errors);
  }
};

// 4. Opción de saltar
const skipOnboarding = async () => {
  await api.post('/onboarding/skip');
  navigateToMainApp();
};
```

## 🎯 Configuraciones por Defecto

Cuando un usuario hace login con Google por primera vez, se crean estos valores por defecto:

### Privacidad
- `private_profile`: false
- `allow_mentions`: true
- `show_online_status`: true
- `share_location`: false

### Notificaciones
- `email_notifications`: true
- `notify_new_followers`: true
- `notify_likes`: true
- `notify_comments`: true
- `notify_mentions`: true
- `notify_following_posts`: true
- `notify_recommendations`: true
- `notify_trends`: false
- `notify_direct_messages`: true
- `notify_group_messages`: true
- `notify_events`: true
- `notify_updates`: true
- `notify_security`: true
- `notify_promotions`: false
