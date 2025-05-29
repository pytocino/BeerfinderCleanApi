# Sistema de Onboarding - Documentación Completa

## 📋 Índice
1. [Resumen del Sistema](#-resumen-del-sistema)
2. [API Reference](#-api-reference)
3. [Configuraciones Disponibles](#-configuraciones-disponibles)
4. [Ejemplos de Uso](#-ejemplos-de-uso)
5. [Implementación Técnica](#-implementación-técnica)
6. [Valores por Defecto](#-valores-por-defecto)

---

## 🎯 Resumen del Sistema

El sistema de onboarding permite a los usuarios completar su perfil después del login con Google, configurando preferencias de privacidad y notificaciones. Incluye **17 configuraciones** preparadas para futuras funcionalidades.

### Características Principales
- ✅ Control de usuarios que necesitan completar perfil
- ✅ 4 configuraciones de privacidad y seguridad
- ✅ 13 configuraciones específicas de notificaciones
- ✅ Opción de saltar el proceso de onboarding
- ✅ Valores por defecto inteligentes
- ✅ Validaciones completas

---

## 🚀 API Reference

### 1. Verificar Estado del Perfil

```http
GET /api/v1/onboarding/status
Authorization: Bearer {token}
```

**Respuesta:**
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
    "private_profile": false,
    "profile": {
      "bio": null,
      "location": null,
      // ... otros campos del perfil
    }
  }
}
```

### 2. Completar Perfil

```http
POST /api/v1/onboarding/complete
Authorization: Bearer {token}
Content-Type: application/json
```

**Respuesta exitosa:**
```json
{
  "message": "Perfil completado exitosamente",
  "user": {
    "id": 1,
    "profile_completed": true,
    // ... datos del usuario actualizado
  }
}
```

### 3. Saltar Onboarding

```http
POST /api/v1/onboarding/skip
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
  "message": "Proceso de onboarding omitido",
  "user": {
    "id": 1,
    "profile_completed": true,
    // ... datos del usuario
  }
}
```

---

## ⚙️ Configuraciones Disponibles

### 📋 Información Básica (Opcional)
```javascript
{
  "username": "string|max:255|unique", // Nombre de usuario único
  "bio": "string|max:500|nullable",    // Biografía del usuario
  "location": "string|max:255|nullable", // Ubicación
  "birthdate": "date|before:today|nullable", // Fecha de nacimiento
  "website": "url|max:255|nullable",   // Sitio web personal
  "phone": "string|max:20|nullable",   // Número de teléfono
  "timezone": "string|max:50|nullable" // Zona horaria
}
```

### 🔗 Redes Sociales (Opcional)
```javascript
{
  "instagram": "string|max:255|nullable", // Usuario de Instagram
  "twitter": "string|max:255|nullable",   // Usuario de Twitter
  "facebook": "string|max:255|nullable"   // Usuario de Facebook
}
```

### 🔒 Privacidad y Seguridad
```javascript
{
  "private_profile": "boolean",     // Perfil privado (default: false)
  "allow_mentions": "boolean",      // Permitir menciones (default: true)
  "show_online_status": "boolean",  // Mostrar estado en línea (default: true)
  "share_location": "boolean"       // Compartir ubicación (default: false)
}
```

### 📧 Notificaciones Generales
```javascript
{
  "email_notifications": "boolean" // Notificaciones por email (default: true)
}
```

### 🔔 Notificaciones Específicas
```javascript
{
  "notify_new_followers": "boolean",    // Nuevos seguidores (default: true)
  "notify_likes": "boolean",            // Likes en publicaciones (default: true)
  "notify_comments": "boolean",         // Comentarios (default: true)
  "notify_mentions": "boolean",         // Menciones (default: true)
  "notify_following_posts": "boolean",  // Publicaciones de seguidos (default: true)
  "notify_recommendations": "boolean",  // Recomendaciones (default: true)
  "notify_trends": "boolean",           // Tendencias (default: false)
  "notify_direct_messages": "boolean",  // Mensajes directos (default: true)
  "notify_group_messages": "boolean",   // Mensajes de grupo (default: true)
  "notify_events": "boolean",           // Eventos (default: true)
  "notify_updates": "boolean",          // Actualizaciones (default: true)
  "notify_security": "boolean",         // Alertas de seguridad (default: true)
  "notify_promotions": "boolean"        // Promociones (default: false)
}
```

---

## 💡 Ejemplos de Uso

### Ejemplo Completo
```json
{
  "username": "cervecero_madrid",
  "bio": "Amante de las cervezas artesanales 🍺",
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

### Ejemplo Mínimo
```json
{
  "username": "mi_nuevo_username",
  "private_profile": false,
  "email_notifications": true
}
```

### Uso en Frontend (JavaScript)
```javascript
// 1. Verificar si necesita onboarding después del login
const checkOnboarding = async () => {
  const response = await api.get('/api/v1/onboarding/status');
  if (response.data.needs_onboarding) {
    showOnboardingFlow();
  } else {
    navigateToMainApp();
  }
};

// 2. Completar perfil
const completeProfile = async (profileData) => {
  try {
    const response = await api.post('/api/v1/onboarding/complete', profileData);
    console.log('Perfil completado:', response.data.user);
    navigateToMainApp();
  } catch (error) {
    handleValidationErrors(error.response.data.errors);
  }
};

// 3. Saltar onboarding
const skipOnboarding = async () => {
  const response = await api.post('/api/v1/onboarding/skip');
  navigateToMainApp();
};
```

---

## 🔧 Implementación Técnica

### Cambios en Base de Datos
- ✅ Campo `profile_completed` en tabla `users`
- ✅ 15 nuevos campos boolean en tabla `user_profiles`
- ✅ Migraciones ejecutadas exitosamente

### Archivos Modificados
- ✅ `GoogleLoginController.php` - Crea perfiles con valores por defecto
- ✅ `ProfileCompletionController.php` - Maneja el onboarding
- ✅ `UserProfile.php` - Modelo actualizado con todos los campos
- ✅ `routes/api.php` - Rutas de onboarding agregadas

### Flujo del Sistema

1. **Login con Google (Primera vez)**
   - Se crea usuario con `profile_completed = false`
   - Se crea perfil básico con valores por defecto
   - Frontend recibe usuario con `profile_completed: false`

2. **Frontend detecta onboarding**
   - Muestra pantallas de configuración
   - Usuario completa o salta el proceso

3. **Perfil completado**
   - `profile_completed` se marca como `true`
   - Usuario accede a la aplicación principal

---

## 📊 Valores por Defecto

### Privacidad (Seguros por defecto)
| Campo | Valor | Motivo |
|-------|-------|--------|
| `private_profile` | `false` | Facilita el descubrimiento |
| `allow_mentions` | `true` | Experiencia social |
| `show_online_status` | `true` | Interacción en tiempo real |
| `share_location` | `false` | ⚠️ Privacidad |

### Notificaciones (Experiencia óptima)
| Campo | Valor | Motivo |
|-------|-------|--------|
| `email_notifications` | `true` | Comunicación importante |
| `notify_new_followers` | `true` | Crecimiento social |
| `notify_likes` | `true` | Engagement |
| `notify_comments` | `true` | Conversaciones |
| `notify_mentions` | `true` | Interacción directa |
| `notify_following_posts` | `true` | Contenido relevante |
| `notify_recommendations` | `true` | Descubrimiento |
| `notify_trends` | `false` | ⚠️ Evitar saturación |
| `notify_direct_messages` | `true` | Comunicación personal |
| `notify_group_messages` | `true` | Grupos importantes |
| `notify_events` | `true` | No perderse eventos |
| `notify_updates` | `true` | Novedades de la app |
| `notify_security` | `true` | Seguridad crítica |
| `notify_promotions` | `false` | ⚠️ Evitar spam |

> **⚠️ Nota**: Los campos marcados están desactivados por defecto para proteger la privacidad del usuario y evitar saturación de notificaciones.

---

## 🎯 Preparado para el Futuro

Aunque estas funcionalidades no se implementarán inmediatamente, la infraestructura está lista para:

### Funcionalidades Futuras
- 🔒 **Sistema de Privacidad**: Perfiles privados completos
- 🟢 **Estados en Línea**: Última actividad de usuarios
- 📍 **Geolocalización**: Compartir ubicación en posts
- 🔔 **Notificaciones Push**: Sistema completo de notificaciones
- 💬 **Mensajería**: Directa y grupal
- 📅 **Eventos**: Sistema de eventos sociales
- 🤖 **Recomendaciones**: IA para sugerir cervezas y usuarios
- 📈 **Tendencias**: Análisis de contenido popular

### Escalabilidad
- Base de datos preparada para todas las funcionalidades
- Validaciones implementadas
- Valores por defecto optimizados
- API extensible para nuevas características

---

*Documentación generada para Beerfinder API - Sistema de Onboarding*
