<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class NotificationDeduplicationService
{
    /**
     * Verifica si una notificación ya fue enviada recientemente
     *
     * @param string $type Tipo de notificación
     * @param int $userId ID del usuario que recibirá la notificación
     * @param array $context Contexto adicional (ej: post_id, comment_id)
     * @param int $ttl Tiempo en segundos para considerar como duplicado (default: 60)
     * @return bool true si es duplicado, false si puede enviarse
     */
    public static function isDuplicate(string $type, int $userId, array $context = [], int $ttl = 60): bool
    {
        // Crear clave única basada en tipo, usuario y contexto
        $key = self::generateKey($type, $userId, $context);
        
        // Verificar si existe en caché
        if (Cache::has($key)) {
            return true; // Es duplicado
        }
        
        // Marcar como enviado
        Cache::put($key, true, $ttl);
        
        return false; // No es duplicado
    }

    /**
     * Genera una clave única para la notificación
     */
    private static function generateKey(string $type, int $userId, array $context): string
    {
        $contextString = empty($context) ? '' : md5(json_encode($context));
        return "notification_dedup:{$type}:{$userId}:{$contextString}";
    }

    /**
     * Limpia todas las claves de deduplicación (útil para testing)
     */
    public static function clearAll(): void
    {
        // En una implementación real, podrías usar tags o un patrón más específico
        Cache::flush();
    }
}
