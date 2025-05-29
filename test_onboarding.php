<?php

// Ejemplo de cómo testear el onboarding en Postman o con curl

/*
1. Hacer login con Google (mobile):
POST /api/v1/google/mobile-auth
Content-Type: application/json
{
    "id_token": "tu_google_id_token_aqui"
}

2. Usar el token recibido para verificar estado:
GET /api/v1/onboarding/status
Authorization: Bearer {token_del_paso_1}

3. Completar perfil:
POST /api/v1/onboarding/complete
Authorization: Bearer {token_del_paso_1}
Content-Type: application/json
{
    "bio": "Soy un amante de la cerveza artesanal",
    "location": "Barcelona, España",
    "birthdate": "1990-01-01",
    "instagram": "mi_instagram",
    "private_profile": false,
    "allow_mentions": true,
    "timezone": "Europe/Madrid"
}

4. Verificar que el perfil esté completo:
GET /api/v1/onboarding/status
Authorization: Bearer {token_del_paso_1}
*/

// Ejemplo con cURL:

// 1. Verificar estado del perfil
$check_status = '
curl -X GET "http://localhost:8000/api/v1/onboarding/status" \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Accept: application/json"
';

// 2. Completar perfil
$complete_profile = '
curl -X POST "http://localhost:8000/api/v1/onboarding/complete" \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d \'{
       "bio": "Amante de la cerveza artesanal",
       "location": "Madrid, España", 
       "birthdate": "1990-05-15",
       "instagram": "mi_instagram",
       "private_profile": false,
       "allow_mentions": true,
       "email_notifications": true,
       "timezone": "Europe/Madrid"
     }\'
';

// 3. Saltar onboarding
$skip_onboarding = '
curl -X POST "http://localhost:8000/api/v1/onboarding/skip" \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Accept: application/json"
';

echo "=== COMANDOS CURL PARA TESTEAR ONBOARDING ===\n\n";
echo "1. Verificar estado del perfil:\n";
echo $check_status . "\n\n";
echo "2. Completar perfil:\n";
echo $complete_profile . "\n\n";
echo "3. Saltar onboarding:\n";
echo $skip_onboarding . "\n\n";
