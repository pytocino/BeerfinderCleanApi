# Sistema de Notificaciones - BeerFinder API

## Resumen
El sistema de notificaciones permite a los usuarios recibir notificaciones en tiempo real sobre actividades relevantes en la plataforma.

## Tipos de Notificaciones

### 1. Follow Notifications (`follow`)
Se envía cuando un usuario empieza a seguir a otro.
```json
{
  "type": "follow",
  "message": "{follower_name} ha empezado a seguirte",
  "user_id": 123
}
```

### 2. Like en Posts (`like`)
Se envía cuando un usuario da like a un post.
```json
{
  "type": "like",
  "message": "{liker_name} ha dado like a tu post",
  "user_id": 123,
  "post_id": 456
}
```

### 3. Like en Comentarios (`comment_like`)
Se envía cuando un usuario da like a un comentario.
```json
{
  "type": "comment_like",
  "message": "{liker_name} ha dado like a tu comentario",
  "user_id": 123,
  "comment_id": 789,
  "post_id": 456
}
```

### 4. Comentarios (`comment`)
Se envía cuando un usuario comenta en un post.
```json
{
  "type": "comment",
  "message": "{commenter_name} ha comentado en tu post",
  "user_id": 123,
  "post_id": 456,
  "comment_id": 789
}
```

### 5. Tags/Menciones (`tag`)
Se envía cuando un usuario es etiquetado en un post.
```json
{
  "type": "tag",
  "message": "{tagger_name} te ha etiquetado en un post",
  "user_id": 123,
  "post_id": 456
}
```

## Endpoints de la API

### Obtener todas las notificaciones
```
GET /api/v1/notifications
```

### Obtener notificaciones no leídas
```
GET /api/v1/notifications/unread
```

### Obtener conteo de no leídas
```
GET /api/v1/notifications/unread-count
```

### Marcar notificación como leída
```
POST /api/v1/notifications/{id}/read
```

### Marcar todas como leídas
```
POST /api/v1/notifications/mark-all-read
```

### Eliminar notificación
```
DELETE /api/v1/notifications/{id}
```

### Eliminar todas las leídas
```
DELETE /api/v1/notifications/clear-read
```

## Configuración de Notificaciones en Perfil

Los usuarios pueden configurar qué tipos de notificaciones quieren recibir desde su perfil:

- `notify_new_followers`: Nuevos seguidores
- `notify_likes`: Likes en posts
- `notify_comments`: Comentarios
- `notify_mentions`: Menciones/Tags

## Implementación Técnica

### Eventos
- `UserFollowed`: Disparado cuando un usuario sigue a otro
- `PostLiked`: Disparado cuando se da like a un post
- `CommentLiked`: Disparado cuando se da like a un comentario
- `PostCommented`: Disparado cuando se comenta un post
- `UserTaggedInPost`: Disparado cuando se etiqueta a un usuario

### Listeners
- `SendFollowNotification`
- `SendLikeNotification`
- `SendCommentLikeNotification`
- `SendCommentNotification`
- `SendTagNotification`

### Notificaciones
- `UserFollowedNotification`
- `UserLikedPost`
- `UserLikedComment`
- `UserCommentedPost`
- `UserTaggedNotification`

## Ejemplo de Respuesta de Notificaciones

```json
{
  "notifications": [
    {
      "id": "9b7c8d9e-1234-4567-8901-234567890123",
      "type": "like",
      "message": "Juan Pérez ha dado like a tu post",
      "is_read": false,
      "read_at": null,
      "created_at": "2025-06-01T10:30:00.000000Z",
      "updated_at": "2025-06-01T10:30:00.000000Z",
      "user": {
        "id": 123,
        "name": "Juan Pérez",
        "username": "juanperez",
        "profile_picture": "https://example.com/avatar.jpg"
      },
      "post": {
        "id": 456,
        "content": "Esta cerveza está increíble...",
        "photo_url": "https://example.com/post.jpg"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 42,
    "unread_count": 5
  }
}
```

## Flujo de Trabajo

1. **Un usuario realiza una acción** (like, comentario, seguir, etiquetar)
2. **Se dispara un evento** correspondiente a la acción
3. **El listener escucha el evento** y verifica las configuraciones de privacidad
4. **Se crea la notificación** si el usuario receptor tiene habilitado este tipo
5. **La notificación se almacena** en la base de datos
6. **El usuario puede ver** sus notificaciones a través de la API

## Consideraciones de Rendimiento

- Las notificaciones se procesan de forma asíncrona
- Los listeners verifican que el usuario no se notifique a sí mismo
- Se respetan las configuraciones de privacidad del usuario
- Las notificaciones incluyen información contextual (usuario, post, comentario)
