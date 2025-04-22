<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
        href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
        body .content .bash-example code {
            display: none;
        }

        body .content .javascript-example code {
            display: none;
        }
    </style>

    <script>
        var tryItOutBaseUrl = "http://127.0.0.1:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.2.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.2.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

    <a href="#" id="nav-button">
        <span>
            MENU
            <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image" />
        </span>
    </a>
    <div class="tocify-wrapper">

        <div class="lang-selector">
            <button type="button" class="lang-button" data-language-name="bash">bash</button>
            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
        </div>

        <div class="search">
            <input type="text" class="search" id="input-search" placeholder="Search">
        </div>

        <div id="toc">
            <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
            </ul>
            <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
            </ul>
            <ul id="tocify-header-autenticacion" class="tocify-header">
                <li class="tocify-item level-1" data-unique="autenticacion">
                    <a href="#autenticacion">Autenticación</a>
                </li>
                <ul id="tocify-subheader-autenticacion" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="autenticacion-POSTapi-v1-auth-register">
                        <a href="#autenticacion-POSTapi-v1-auth-register">Registrar un nuevo usuario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autenticacion-POSTapi-v1-auth-login">
                        <a href="#autenticacion-POSTapi-v1-auth-login">Iniciar sesión</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autenticacion-POSTapi-v1-auth-forgot-password">
                        <a href="#autenticacion-POSTapi-v1-auth-forgot-password">Solicitar restablecimiento de contraseña</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autenticacion-POSTapi-v1-auth-reset-password">
                        <a href="#autenticacion-POSTapi-v1-auth-reset-password">Restablecer contraseña</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autenticacion-POSTapi-v1-auth-logout">
                        <a href="#autenticacion-POSTapi-v1-auth-logout">Cerrar sesión</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autenticacion-GETapi-v1-auth-me">
                        <a href="#autenticacion-GETapi-v1-auth-me">Obtener usuario autenticado</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autenticacion-PUTapi-v1-auth-update-profile">
                        <a href="#autenticacion-PUTapi-v1-auth-update-profile">Actualizar perfil</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="autenticacion-PUTapi-v1-auth-change-password">
                        <a href="#autenticacion-PUTapi-v1-auth-change-password">Cambiar contraseña</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-busqueda" class="tocify-header">
                <li class="tocify-item level-1" data-unique="busqueda">
                    <a href="#busqueda">Búsqueda</a>
                </li>
                <ul id="tocify-subheader-busqueda" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="busqueda-GETapi-v1-search">
                        <a href="#busqueda-GETapi-v1-search">Búsqueda global</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-cervecerias" class="tocify-header">
                <li class="tocify-item level-1" data-unique="cervecerias">
                    <a href="#cervecerias">Cervecerías</a>
                </li>
                <ul id="tocify-subheader-cervecerias" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="cervecerias-GETapi-v1-breweries">
                        <a href="#cervecerias-GETapi-v1-breweries">Listar cervecerías</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervecerias-GETapi-v1-breweries--id-">
                        <a href="#cervecerias-GETapi-v1-breweries--id-">Ver cervecería</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervecerias-POSTapi-v1-breweries">
                        <a href="#cervecerias-POSTapi-v1-breweries">Crear cervecería</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervecerias-PUTapi-v1-breweries--id-">
                        <a href="#cervecerias-PUTapi-v1-breweries--id-">Actualizar cervecería</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervecerias-DELETEapi-v1-breweries--id-">
                        <a href="#cervecerias-DELETEapi-v1-breweries--id-">Eliminar cervecería</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervecerias-GETapi-v1-breweries--id--beers">
                        <a href="#cervecerias-GETapi-v1-breweries--id--beers">Cervezas de la cervecería</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-cervezas" class="tocify-header">
                <li class="tocify-item level-1" data-unique="cervezas">
                    <a href="#cervezas">Cervezas</a>
                </li>
                <ul id="tocify-subheader-cervezas" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="cervezas-GETapi-v1-beers">
                        <a href="#cervezas-GETapi-v1-beers">Listar cervezas</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervezas-GETapi-v1-beers--id-">
                        <a href="#cervezas-GETapi-v1-beers--id-">Ver cerveza</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervezas-POSTapi-v1-beers">
                        <a href="#cervezas-POSTapi-v1-beers">Crear cerveza</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervezas-PUTapi-v1-beers--id-">
                        <a href="#cervezas-PUTapi-v1-beers--id-">Actualizar cerveza</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervezas-DELETEapi-v1-beers--id-">
                        <a href="#cervezas-DELETEapi-v1-beers--id-">Eliminar cerveza</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervezas-POSTapi-v1-beers--id--favorite">
                        <a href="#cervezas-POSTapi-v1-beers--id--favorite">Marcar como favorita</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervezas-DELETEapi-v1-beers--id--unfavorite">
                        <a href="#cervezas-DELETEapi-v1-beers--id--unfavorite">Quitar de favoritos</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="cervezas-GETapi-v1-beers--id--similar">
                        <a href="#cervezas-GETapi-v1-beers--id--similar">Cervezas similares</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-check-ins" class="tocify-header">
                <li class="tocify-item level-1" data-unique="check-ins">
                    <a href="#check-ins">Check-ins</a>
                </li>
                <ul id="tocify-subheader-check-ins" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="check-ins-GETapi-v1-check-ins">
                        <a href="#check-ins-GETapi-v1-check-ins">Listar check-ins</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="check-ins-POSTapi-v1-check-ins">
                        <a href="#check-ins-POSTapi-v1-check-ins">Crear check-in</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="check-ins-GETapi-v1-check-ins--id-">
                        <a href="#check-ins-GETapi-v1-check-ins--id-">Ver check-in</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="check-ins-PUTapi-v1-check-ins--id-">
                        <a href="#check-ins-PUTapi-v1-check-ins--id-">Actualizar check-in</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="check-ins-DELETEapi-v1-check-ins--id-">
                        <a href="#check-ins-DELETEapi-v1-check-ins--id-">Eliminar check-in</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="check-ins-GETapi-v1-users--id--check-ins">
                        <a href="#check-ins-GETapi-v1-users--id--check-ins">Check-ins de un usuario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="check-ins-GETapi-v1-beers--id--check-ins">
                        <a href="#check-ins-GETapi-v1-beers--id--check-ins">Check-ins de una cerveza</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-comentarios" class="tocify-header">
                <li class="tocify-item level-1" data-unique="comentarios">
                    <a href="#comentarios">Comentarios</a>
                </li>
                <ul id="tocify-subheader-comentarios" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="comentarios-POSTapi-v1-check-ins--id--comments">
                        <a href="#comentarios-POSTapi-v1-check-ins--id--comments">Crear comentario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="comentarios-PUTapi-v1-comments--id-">
                        <a href="#comentarios-PUTapi-v1-comments--id-">Actualizar comentario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="comentarios-DELETEapi-v1-comments--id-">
                        <a href="#comentarios-DELETEapi-v1-comments--id-">Eliminar comentario</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-status">
                        <a href="#endpoints-GETapi-v1-status">GET api/v1/status</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-estilos-de-cerveza" class="tocify-header">
                <li class="tocify-item level-1" data-unique="estilos-de-cerveza">
                    <a href="#estilos-de-cerveza">Estilos de Cerveza</a>
                </li>
                <ul id="tocify-subheader-estilos-de-cerveza" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="estilos-de-cerveza-GETapi-v1-beer-styles">
                        <a href="#estilos-de-cerveza-GETapi-v1-beer-styles">Listar estilos de cerveza</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="estilos-de-cerveza-GETapi-v1-beer-styles--id-">
                        <a href="#estilos-de-cerveza-GETapi-v1-beer-styles--id-">Ver estilo de cerveza</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="estilos-de-cerveza-POSTapi-v1-beer-styles">
                        <a href="#estilos-de-cerveza-POSTapi-v1-beer-styles">Crear estilo de cerveza</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="estilos-de-cerveza-PUTapi-v1-beer-styles--id-">
                        <a href="#estilos-de-cerveza-PUTapi-v1-beer-styles--id-">Actualizar estilo de cerveza</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="estilos-de-cerveza-DELETEapi-v1-beer-styles--id-">
                        <a href="#estilos-de-cerveza-DELETEapi-v1-beer-styles--id-">Eliminar estilo de cerveza</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="estilos-de-cerveza-GETapi-v1-beer-styles--id--beers">
                        <a href="#estilos-de-cerveza-GETapi-v1-beer-styles--id--beers">Cervezas de un estilo</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-feed-de-actividad" class="tocify-header">
                <li class="tocify-item level-1" data-unique="feed-de-actividad">
                    <a href="#feed-de-actividad">Feed de Actividad</a>
                </li>
                <ul id="tocify-subheader-feed-de-actividad" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="feed-de-actividad-GETapi-v1-feed">
                        <a href="#feed-de-actividad-GETapi-v1-feed">Feed principal</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="feed-de-actividad-GETapi-v1-feed-friends">
                        <a href="#feed-de-actividad-GETapi-v1-feed-friends">Feed de amigos</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="feed-de-actividad-GETapi-v1-feed-popular">
                        <a href="#feed-de-actividad-GETapi-v1-feed-popular">Feed popular</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-gestion-de-reportes" class="tocify-header">
                <li class="tocify-item level-1" data-unique="gestion-de-reportes">
                    <a href="#gestion-de-reportes">Gestión de Reportes</a>
                </li>
                <ul id="tocify-subheader-gestion-de-reportes" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="gestion-de-reportes-GETapi-v1-reports">
                        <a href="#gestion-de-reportes-GETapi-v1-reports">Listar reportes</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="gestion-de-reportes-GETapi-v1-reports--id-">
                        <a href="#gestion-de-reportes-GETapi-v1-reports--id-">Obtener detalles de un reporte</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="gestion-de-reportes-PUTapi-v1-reports--id-">
                        <a href="#gestion-de-reportes-PUTapi-v1-reports--id-">Actualizar estado de un reporte</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-likes" class="tocify-header">
                <li class="tocify-item level-1" data-unique="likes">
                    <a href="#likes">Likes</a>
                </li>
                <ul id="tocify-subheader-likes" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="likes-POSTapi-v1-check-ins--id--like">
                        <a href="#likes-POSTapi-v1-check-ins--id--like">Dar like a un check-in</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="likes-DELETEapi-v1-check-ins--id--unlike">
                        <a href="#likes-DELETEapi-v1-check-ins--id--unlike">Quitar like de un check-in</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-notificaciones" class="tocify-header">
                <li class="tocify-item level-1" data-unique="notificaciones">
                    <a href="#notificaciones">Notificaciones</a>
                </li>
                <ul id="tocify-subheader-notificaciones" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="notificaciones-GETapi-v1-notifications">
                        <a href="#notificaciones-GETapi-v1-notifications">Listar notificaciones</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="notificaciones-PUTapi-v1-notifications--id--read">
                        <a href="#notificaciones-PUTapi-v1-notifications--id--read">Marcar notificación como leída</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="notificaciones-PUTapi-v1-notifications-read-all">
                        <a href="#notificaciones-PUTapi-v1-notifications-read-all">Marcar todas las notificaciones como leídas</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-ubicaciones" class="tocify-header">
                <li class="tocify-item level-1" data-unique="ubicaciones">
                    <a href="#ubicaciones">Ubicaciones</a>
                </li>
                <ul id="tocify-subheader-ubicaciones" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="ubicaciones-GETapi-v1-locations">
                        <a href="#ubicaciones-GETapi-v1-locations">Listar ubicaciones</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="ubicaciones-GETapi-v1-locations--id-">
                        <a href="#ubicaciones-GETapi-v1-locations--id-">Ver ubicación</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="ubicaciones-POSTapi-v1-locations">
                        <a href="#ubicaciones-POSTapi-v1-locations">Crear ubicación</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="ubicaciones-PUTapi-v1-locations--id-">
                        <a href="#ubicaciones-PUTapi-v1-locations--id-">Actualizar ubicación</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="ubicaciones-DELETEapi-v1-locations--id-">
                        <a href="#ubicaciones-DELETEapi-v1-locations--id-">Eliminar ubicación</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="ubicaciones-GETapi-v1-locations-nearby">
                        <a href="#ubicaciones-GETapi-v1-locations-nearby">Ubicaciones cercanas</a>
                    </li>
                </ul>
            </ul>
            <ul id="tocify-header-usuarios" class="tocify-header">
                <li class="tocify-item level-1" data-unique="usuarios">
                    <a href="#usuarios">Usuarios</a>
                </li>
                <ul id="tocify-subheader-usuarios" class="tocify-subheader">
                    <li class="tocify-item level-2" data-unique="usuarios-GETapi-v1-users--id-">
                        <a href="#usuarios-GETapi-v1-users--id-">Ver usuario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="usuarios-GETapi-v1-users">
                        <a href="#usuarios-GETapi-v1-users">Listar usuarios</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="usuarios-PUTapi-v1-users--id-">
                        <a href="#usuarios-PUTapi-v1-users--id-">Actualizar usuario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="usuarios-DELETEapi-v1-users--id-">
                        <a href="#usuarios-DELETEapi-v1-users--id-">Eliminar usuario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="usuarios-POSTapi-v1-users--id--follow">
                        <a href="#usuarios-POSTapi-v1-users--id--follow">Seguir usuario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="usuarios-DELETEapi-v1-users--id--unfollow">
                        <a href="#usuarios-DELETEapi-v1-users--id--unfollow">Dejar de seguir usuario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="usuarios-GETapi-v1-users--id--followers">
                        <a href="#usuarios-GETapi-v1-users--id--followers">Seguidores del usuario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="usuarios-GETapi-v1-users--id--following">
                        <a href="#usuarios-GETapi-v1-users--id--following">Usuarios seguidos</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="usuarios-GETapi-v1-users--id--stats">
                        <a href="#usuarios-GETapi-v1-users--id--stats">Estadísticas de usuario</a>
                    </li>
                    <li class="tocify-item level-2" data-unique="usuarios-GETapi-v1-recommendations">
                        <a href="#usuarios-GETapi-v1-recommendations">Recomendaciones para el usuario</a>
                    </li>
                </ul>
            </ul>
        </div>

        <ul class="toc-footer" id="toc-footer">
            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
            <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
        </ul>

        <ul class="toc-footer" id="last-updated">
            <li>Last updated: April 19, 2025</li>
        </ul>
    </div>

    <div class="page-wrapper">
        <div class="dark-box"></div>
        <div class="content">
            <h1 id="introduction">Introduction</h1>
            <aside>
                <strong>Base URL</strong>: <code>http://127.0.0.1:8000</code>
            </aside>
            <pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

            <h1 id="authenticating-requests">Authenticating requests</h1>
            <p>This API is not authenticated.</p>

            <h1 id="autenticacion">Autenticación</h1>

            <p>APIs para gestionar la autenticación de usuarios</p>

            <h2 id="autenticacion-POSTapi-v1-auth-register">Registrar un nuevo usuario</h2>

            <p>
            </p>

            <p>Crea una nueva cuenta de usuario en el sistema.</p>

            <span id="example-requests-POSTapi-v1-auth-register">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/auth/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Juan Pérez\",
    \"email\": \"juan@example.com\",
    \"password\": \"secreto123\",
    \"password_confirmation\": \"secreto123\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/auth/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "secreto123",
    "password_confirmation": "secreto123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-auth-register">
                <blockquote>
                    <p>Example response (201):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Juan P&eacute;rez&quot;,
        &quot;email&quot;: &quot;juan@example.com&quot;
    },
    &quot;access_token&quot;: &quot;1|laravel_sanctum_token&quot;,
    &quot;token_type&quot;: &quot;Bearer&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The email has already been taken.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;Este correo ya est&aacute; registrado.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-auth-register" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-auth-register"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-auth-register" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-auth-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-auth-register" data-method="POST"
                data-path="api/v1/auth/register"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-register', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-auth-register"
                        onclick="tryItOut('POSTapi-v1-auth-register');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-auth-register"
                        onclick="cancelTryOut('POSTapi-v1-auth-register');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-auth-register"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/auth/register</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-auth-register"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-auth-register"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="POSTapi-v1-auth-register"
                        value="Juan Pérez"
                        data-component="body">
                    <br>
                    <p>El nombre del usuario. Example: <code>Juan Pérez</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="email" data-endpoint="POSTapi-v1-auth-register"
                        value="juan@example.com"
                        data-component="body">
                    <br>
                    <p>Email del usuario. Example: <code>juan@example.com</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="password" data-endpoint="POSTapi-v1-auth-register"
                        value="secreto123"
                        data-component="body">
                    <br>
                    <p>Contraseña (mínimo 8 caracteres). Example: <code>secreto123</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="password_confirmation" data-endpoint="POSTapi-v1-auth-register"
                        value="secreto123"
                        data-component="body">
                    <br>
                    <p>Confirmación de la contraseña. Example: <code>secreto123</code></p>
                </div>
            </form>

            <h2 id="autenticacion-POSTapi-v1-auth-login">Iniciar sesión</h2>

            <p>
            </p>

            <p>Autentica al usuario y devuelve un token de acceso.</p>

            <span id="example-requests-POSTapi-v1-auth-login">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"juan@example.com\",
    \"password\": \"secreto123\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/auth/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "juan@example.com",
    "password": "secreto123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-auth-login">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Juan P&eacute;rez&quot;,
        &quot;email&quot;: &quot;juan@example.com&quot;
    },
    &quot;access_token&quot;: &quot;1|laravel_sanctum_token&quot;,
    &quot;token_type&quot;: &quot;Bearer&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Las credenciales proporcionadas son incorrectas.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-auth-login" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-auth-login"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-auth-login" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-auth-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-auth-login" data-method="POST"
                data-path="api/v1/auth/login"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-login', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-auth-login"
                        onclick="tryItOut('POSTapi-v1-auth-login');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-auth-login"
                        onclick="cancelTryOut('POSTapi-v1-auth-login');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-auth-login"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/auth/login</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-auth-login"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-auth-login"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="email" data-endpoint="POSTapi-v1-auth-login"
                        value="juan@example.com"
                        data-component="body">
                    <br>
                    <p>Email del usuario. Example: <code>juan@example.com</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="password" data-endpoint="POSTapi-v1-auth-login"
                        value="secreto123"
                        data-component="body">
                    <br>
                    <p>Contraseña del usuario. Example: <code>secreto123</code></p>
                </div>
            </form>

            <h2 id="autenticacion-POSTapi-v1-auth-forgot-password">Solicitar restablecimiento de contraseña</h2>

            <p>
            </p>

            <p>Envía un correo con un enlace para restablecer la contraseña.</p>

            <span id="example-requests-POSTapi-v1-auth-forgot-password">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/auth/forgot-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"juan@example.com\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/auth/forgot-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "juan@example.com"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-auth-forgot-password">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Se ha enviado un enlace para restablecer la contrase&ntilde;a&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No podemos encontrar un usuario con ese correo electr&oacute;nico.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-auth-forgot-password" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-auth-forgot-password"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-forgot-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-auth-forgot-password" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-auth-forgot-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-auth-forgot-password" data-method="POST"
                data-path="api/v1/auth/forgot-password"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-forgot-password', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-auth-forgot-password"
                        onclick="tryItOut('POSTapi-v1-auth-forgot-password');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-auth-forgot-password"
                        onclick="cancelTryOut('POSTapi-v1-auth-forgot-password');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-auth-forgot-password"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/auth/forgot-password</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-auth-forgot-password"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-auth-forgot-password"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="email" data-endpoint="POSTapi-v1-auth-forgot-password"
                        value="juan@example.com"
                        data-component="body">
                    <br>
                    <p>Email del usuario. Example: <code>juan@example.com</code></p>
                </div>
            </form>

            <h2 id="autenticacion-POSTapi-v1-auth-reset-password">Restablecer contraseña</h2>

            <p>
            </p>

            <p>Restablece la contraseña del usuario usando el token recibido por email.</p>

            <span id="example-requests-POSTapi-v1-auth-reset-password">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/auth/reset-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"token\": \"abcdef123456\",
    \"email\": \"juan@example.com\",
    \"password\": \"nuevaContraseña123\",
    \"password_confirmation\": \"nuevaContraseña123\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/auth/reset-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "token": "abcdef123456",
    "email": "juan@example.com",
    "password": "nuevaContraseña123",
    "password_confirmation": "nuevaContraseña123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-auth-reset-password">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;La contrase&ntilde;a ha sido restablecida correctamente&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;El token es inv&aacute;lido.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-auth-reset-password" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-auth-reset-password"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-reset-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-auth-reset-password" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-auth-reset-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-auth-reset-password" data-method="POST"
                data-path="api/v1/auth/reset-password"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-reset-password', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-auth-reset-password"
                        onclick="tryItOut('POSTapi-v1-auth-reset-password');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-auth-reset-password"
                        onclick="cancelTryOut('POSTapi-v1-auth-reset-password');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-auth-reset-password"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/auth/reset-password</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-auth-reset-password"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-auth-reset-password"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="token" data-endpoint="POSTapi-v1-auth-reset-password"
                        value="abcdef123456"
                        data-component="body">
                    <br>
                    <p>El token de reset recibido por email. Example: <code>abcdef123456</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="email" data-endpoint="POSTapi-v1-auth-reset-password"
                        value="juan@example.com"
                        data-component="body">
                    <br>
                    <p>Email del usuario. Example: <code>juan@example.com</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="password" data-endpoint="POSTapi-v1-auth-reset-password"
                        value="nuevaContraseña123"
                        data-component="body">
                    <br>
                    <p>Nueva contraseña (mínimo 8 caracteres). Example: <code>nuevaContraseña123</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="password_confirmation" data-endpoint="POSTapi-v1-auth-reset-password"
                        value="nuevaContraseña123"
                        data-component="body">
                    <br>
                    <p>Confirmación de la nueva contraseña. Example: <code>nuevaContraseña123</code></p>
                </div>
            </form>

            <h2 id="autenticacion-POSTapi-v1-auth-logout">Cerrar sesión</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Revoca el token de acceso actual del usuario.</p>

            <span id="example-requests-POSTapi-v1-auth-logout">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/auth/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/auth/logout"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-auth-logout">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Sesi&oacute;n cerrada correctamente&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-auth-logout" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-auth-logout"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-auth-logout" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-auth-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-auth-logout" data-method="POST"
                data-path="api/v1/auth/logout"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-logout', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-auth-logout"
                        onclick="tryItOut('POSTapi-v1-auth-logout');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-auth-logout"
                        onclick="cancelTryOut('POSTapi-v1-auth-logout');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-auth-logout"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/auth/logout</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-auth-logout"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-auth-logout"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="autenticacion-GETapi-v1-auth-me">Obtener usuario autenticado</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Devuelve la información del usuario actualmente autenticado.</p>

            <span id="example-requests-GETapi-v1-auth-me">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/auth/me" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/auth/me"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-auth-me">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;id&quot;: 1,
    &quot;name&quot;: &quot;Juan P&eacute;rez&quot;,
    &quot;email&quot;: &quot;juan@example.com&quot;,
    &quot;bio&quot;: &quot;Amante de las cervezas artesanales&quot;,
    &quot;location&quot;: &quot;Madrid, Espa&ntilde;a&quot;,
    &quot;profile_picture&quot;: &quot;https://example.com/avatars/juan.jpg&quot;,
    &quot;created_at&quot;: &quot;2023-01-01T00:00:00.000000Z&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-auth-me" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-auth-me"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-auth-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-auth-me" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-auth-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-auth-me" data-method="GET"
                data-path="api/v1/auth/me"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-me', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-auth-me"
                        onclick="tryItOut('GETapi-v1-auth-me');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-auth-me"
                        onclick="cancelTryOut('GETapi-v1-auth-me');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-auth-me"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/auth/me</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-auth-me"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-auth-me"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h2 id="autenticacion-PUTapi-v1-auth-update-profile">Actualizar perfil</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Actualiza la información del perfil del usuario autenticado.</p>

            <span id="example-requests-PUTapi-v1-auth-update-profile">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/auth/update-profile" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Juan Pérez Actualizado\",
    \"bio\": \"Cervecero aficionado desde 2010\",
    \"location\": \"Barcelona, España\",
    \"profile_picture\": \"https:\\/\\/example.com\\/avatars\\/nuevo.jpg\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/auth/update-profile"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Juan Pérez Actualizado",
    "bio": "Cervecero aficionado desde 2010",
    "location": "Barcelona, España",
    "profile_picture": "https:\/\/example.com\/avatars\/nuevo.jpg"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-auth-update-profile">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Perfil actualizado correctamente&quot;,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Juan P&eacute;rez Actualizado&quot;,
        &quot;bio&quot;: &quot;Cervecero aficionado desde 2010&quot;,
        &quot;location&quot;: &quot;Barcelona, Espa&ntilde;a&quot;,
        &quot;profile_picture&quot;: &quot;https://example.com/avatars/nuevo.jpg&quot;
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-auth-update-profile" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-auth-update-profile"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-auth-update-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-auth-update-profile" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-auth-update-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-auth-update-profile" data-method="PUT"
                data-path="api/v1/auth/update-profile"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-auth-update-profile', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-auth-update-profile"
                        onclick="tryItOut('PUTapi-v1-auth-update-profile');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-auth-update-profile"
                        onclick="cancelTryOut('PUTapi-v1-auth-update-profile');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-auth-update-profile"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/auth/update-profile</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-auth-update-profile"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-auth-update-profile"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="PUTapi-v1-auth-update-profile"
                        value="Juan Pérez Actualizado"
                        data-component="body">
                    <br>
                    <p>El nombre del usuario. Example: <code>Juan Pérez Actualizado</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>bio</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="bio" data-endpoint="PUTapi-v1-auth-update-profile"
                        value="Cervecero aficionado desde 2010"
                        data-component="body">
                    <br>
                    <p>La biografía del usuario. Example: <code>Cervecero aficionado desde 2010</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>location</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="location" data-endpoint="PUTapi-v1-auth-update-profile"
                        value="Barcelona, España"
                        data-component="body">
                    <br>
                    <p>La ubicación del usuario. Example: <code>Barcelona, España</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>profile_picture</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="profile_picture" data-endpoint="PUTapi-v1-auth-update-profile"
                        value="https://example.com/avatars/nuevo.jpg"
                        data-component="body">
                    <br>
                    <p>La URL de la imagen de perfil. Example: <code>https://example.com/avatars/nuevo.jpg</code></p>
                </div>
            </form>

            <h2 id="autenticacion-PUTapi-v1-auth-change-password">Cambiar contraseña</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Permite al usuario autenticado cambiar su contraseña.</p>

            <span id="example-requests-PUTapi-v1-auth-change-password">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/auth/change-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"current_password\": \"contraseñaActual123\",
    \"password\": \"nuevaContraseña123\",
    \"password_confirmation\": \"nuevaContraseña123\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/auth/change-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "current_password": "contraseñaActual123",
    "password": "nuevaContraseña123",
    "password_confirmation": "nuevaContraseña123"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-auth-change-password">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Contrase&ntilde;a cambiada correctamente&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;La contrase&ntilde;a actual es incorrecta.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-auth-change-password" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-auth-change-password"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-auth-change-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-auth-change-password" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-auth-change-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-auth-change-password" data-method="PUT"
                data-path="api/v1/auth/change-password"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-auth-change-password', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-auth-change-password"
                        onclick="tryItOut('PUTapi-v1-auth-change-password');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-auth-change-password"
                        onclick="cancelTryOut('PUTapi-v1-auth-change-password');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-auth-change-password"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/auth/change-password</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-auth-change-password"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-auth-change-password"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>current_password</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="current_password" data-endpoint="PUTapi-v1-auth-change-password"
                        value="contraseñaActual123"
                        data-component="body">
                    <br>
                    <p>La contraseña actual. Example: <code>contraseñaActual123</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="password" data-endpoint="PUTapi-v1-auth-change-password"
                        value="nuevaContraseña123"
                        data-component="body">
                    <br>
                    <p>Nueva contraseña (mínimo 8 caracteres). Example: <code>nuevaContraseña123</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="password_confirmation" data-endpoint="PUTapi-v1-auth-change-password"
                        value="nuevaContraseña123"
                        data-component="body">
                    <br>
                    <p>Confirmación de la nueva contraseña. Example: <code>nuevaContraseña123</code></p>
                </div>
            </form>

            <h1 id="busqueda">Búsqueda</h1>

            <p>APIs para buscar diferentes entidades en el sistema</p>

            <h2 id="busqueda-GETapi-v1-search">Búsqueda global</h2>

            <p>
            </p>

            <p>Permite buscar cervezas, cervecerías, estilos, ubicaciones y usuarios según los criterios especificados.</p>

            <span id="example-requests-GETapi-v1-search">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/search?q=IPA&amp;type=beers&amp;country=Espa%C3%B1a&amp;city=Madrid&amp;style_id=1&amp;min_rating=4&amp;max_distance=5&amp;lat=40.416775&amp;lng=-3.70379&amp;sort=rating&amp;order=desc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"q\": \"b\",
    \"type\": \"beers\",
    \"country\": \"n\",
    \"city\": \"g\",
    \"style_id\": 16,
    \"min_rating\": 2,
    \"max_distance\": 7,
    \"lat\": 4326.41688,
    \"lng\": 4326.41688,
    \"sort\": \"created_at\",
    \"order\": \"asc\",
    \"per_page\": 17
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/search"
);

const params = {
    "q": "IPA",
    "type": "beers",
    "country": "España",
    "city": "Madrid",
    "style_id": "1",
    "min_rating": "4",
    "max_distance": "5",
    "lat": "40.416775",
    "lng": "-3.70379",
    "sort": "rating",
    "order": "desc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "q": "b",
    "type": "beers",
    "country": "n",
    "city": "g",
    "style_id": 16,
    "min_rating": 2,
    "max_distance": 7,
    "lat": 4326.41688,
    "lng": 4326.41688,
    "sort": "created_at",
    "order": "asc",
    "per_page": 17
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-search">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;beers&quot;: {
        &quot;data&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Mahou Cl&aacute;sica&quot;,
                &quot;brewery&quot;: {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Cervecer&iacute;a Mahou&quot;
                },
                &quot;style&quot;: {
                    &quot;id&quot;: 2,
                    &quot;name&quot;: &quot;Lager&quot;
                },
                &quot;abv&quot;: 4.8,
                &quot;ibu&quot;: 20,
                &quot;description&quot;: &quot;Cerveza rubia tipo Lager, suave y refrescante.&quot;,
                &quot;rating_avg&quot;: 3.75,
                &quot;check_ins_count&quot;: 42
            }
        ],
        &quot;total&quot;: 1
    },
    &quot;breweries&quot;: {
        &quot;data&quot;: [],
        &quot;total&quot;: 0
    },
    &quot;styles&quot;: {
        &quot;data&quot;: [],
        &quot;total&quot;: 0
    },
    &quot;locations&quot;: {
        &quot;data&quot;: [],
        &quot;total&quot;: 0
    },
    &quot;users&quot;: {
        &quot;data&quot;: [],
        &quot;total&quot;: 0
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-search" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-search"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-search"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-search" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-search">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-search" data-method="GET"
                data-path="api/v1/search"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-search', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-search"
                        onclick="tryItOut('GETapi-v1-search');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-search"
                        onclick="cancelTryOut('GETapi-v1-search');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-search"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/search</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-search"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-search"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>q</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="q" data-endpoint="GETapi-v1-search"
                        value="IPA"
                        data-component="query">
                    <br>
                    <p>Término de búsqueda general. Example: <code>IPA</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-search"
                        value="beers"
                        data-component="query">
                    <br>
                    <p>Tipo de entidad a buscar (beers, breweries, styles, locations, users). Si no se especifica, busca en todas. Example: <code>beers</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="GETapi-v1-search"
                        value="España"
                        data-component="query">
                    <br>
                    <p>Filtrar resultados por país (para cervezas, cervecerías y ubicaciones). Example: <code>España</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="GETapi-v1-search"
                        value="Madrid"
                        data-component="query">
                    <br>
                    <p>Filtrar resultados por ciudad (para cervecerías y ubicaciones). Example: <code>Madrid</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>style_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="style_id" data-endpoint="GETapi-v1-search"
                        value="1"
                        data-component="query">
                    <br>
                    <p>Filtrar cervezas por estilo. Example: <code>1</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-search"
                        value="4"
                        data-component="query">
                    <br>
                    <p>Filtrar cervezas por calificación mínima (1.0-5.0). Example: <code>4</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>max_distance</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="max_distance" data-endpoint="GETapi-v1-search"
                        value="5"
                        data-component="query">
                    <br>
                    <p>Filtrar ubicaciones por distancia máxima en km (requiere lat y lng). Example: <code>5</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>lat</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="lat" data-endpoint="GETapi-v1-search"
                        value="40.416775"
                        data-component="query">
                    <br>
                    <p>Latitud para búsqueda por proximidad (para ubicaciones). Example: <code>40.416775</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>lng</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="lng" data-endpoint="GETapi-v1-search"
                        value="-3.70379"
                        data-component="query">
                    <br>
                    <p>Longitud para búsqueda por proximidad (para ubicaciones). Example: <code>-3.70379</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-search"
                        value="rating"
                        data-component="query">
                    <br>
                    <p>Criterio de ordenamiento (name, rating, distance, created_at). Example: <code>rating</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-search"
                        value="desc"
                        data-component="query">
                    <br>
                    <p>Dirección de ordenamiento (asc, desc). Example: <code>desc</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-search"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Número de resultados por página. Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>q</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="q" data-endpoint="GETapi-v1-search"
                        value="b"
                        data-component="body">
                    <br>
                    <p>Must be at least 2 characters. Must not be greater than 100 characters. Example: <code>b</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-search"
                        value="beers"
                        data-component="body">
                    <br>
                    <p>Example: <code>beers</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>beers</code></li>
                        <li><code>breweries</code></li>
                        <li><code>styles</code></li>
                        <li><code>locations</code></li>
                        <li><code>users</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="GETapi-v1-search"
                        value="n"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>n</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="GETapi-v1-search"
                        value="g"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>g</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>style_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="style_id" data-endpoint="GETapi-v1-search"
                        value="16"
                        data-component="body">
                    <br>
                    <p>The <code>id</code> of an existing record in the beer_styles table. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-search"
                        value="2"
                        data-component="body">
                    <br>
                    <p>Must be at least 1. Must not be greater than 5. Example: <code>2</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>max_distance</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="max_distance" data-endpoint="GETapi-v1-search"
                        value="7"
                        data-component="body">
                    <br>
                    <p>Must be at least 1. Must not be greater than 100. Example: <code>7</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lat</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="lat" data-endpoint="GETapi-v1-search"
                        value="4326.41688"
                        data-component="body">
                    <br>
                    <p>Example: <code>4326.41688</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>lng</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="lng" data-endpoint="GETapi-v1-search"
                        value="4326.41688"
                        data-component="body">
                    <br>
                    <p>Example: <code>4326.41688</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-search"
                        value="created_at"
                        data-component="body">
                    <br>
                    <p>Example: <code>created_at</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>name</code></li>
                        <li><code>rating</code></li>
                        <li><code>distance</code></li>
                        <li><code>created_at</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-search"
                        value="asc"
                        data-component="body">
                    <br>
                    <p>Example: <code>asc</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>asc</code></li>
                        <li><code>desc</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-search"
                        value="17"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>17</code></p>
                </div>
            </form>

            <h1 id="cervecerias">Cervecerías</h1>

            <p>APIs para gestionar cervecerías</p>

            <h2 id="cervecerias-GETapi-v1-breweries">Listar cervecerías</h2>

            <p>
            </p>

            <p>Obtiene un listado paginado de cervecerías con opciones de filtrado y ordenamiento.</p>

            <span id="example-requests-GETapi-v1-breweries">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/breweries?name=Mahou&amp;country=Espa%C3%B1a&amp;city=Madrid&amp;sort=name&amp;order=asc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"country\": \"n\",
    \"city\": \"g\",
    \"sort\": \"created_at\",
    \"order\": \"desc\",
    \"per_page\": 16
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/breweries"
);

const params = {
    "name": "Mahou",
    "country": "España",
    "city": "Madrid",
    "sort": "name",
    "order": "asc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "country": "n",
    "city": "g",
    "sort": "created_at",
    "order": "desc",
    "per_page": 16
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-breweries">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 1,
     &quot;name&quot;: &quot;Cervecer&iacute;a Mahou&quot;,
     &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
     &quot;city&quot;: &quot;Madrid&quot;,
     &quot;address&quot;: &quot;Calle de Tit&aacute;n, 15, 28045 Madrid&quot;,
     &quot;founded_year&quot;: 1890,
     &quot;description&quot;: &quot;Una de las cervecer&iacute;as m&aacute;s antiguas de Espa&ntilde;a&quot;,
     &quot;website&quot;: &quot;https://www.mahou.es&quot;,
     &quot;logo_url&quot;: &quot;https://example.com/logos/mahou.png&quot;,
     &quot;beers_count&quot;: 12,
     &quot;created_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;,
     &quot;updated_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-breweries" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-breweries"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-breweries"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-breweries" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-breweries">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-breweries" data-method="GET"
                data-path="api/v1/breweries"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-breweries', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-breweries"
                        onclick="tryItOut('GETapi-v1-breweries');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-breweries"
                        onclick="cancelTryOut('GETapi-v1-breweries');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-breweries"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/breweries</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-breweries"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-breweries"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-breweries"
                        value="Mahou"
                        data-component="query">
                    <br>
                    <p>Filtrar por nombre. Example: <code>Mahou</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="GETapi-v1-breweries"
                        value="España"
                        data-component="query">
                    <br>
                    <p>Filtrar por país. Example: <code>España</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="GETapi-v1-breweries"
                        value="Madrid"
                        data-component="query">
                    <br>
                    <p>Filtrar por ciudad. Example: <code>Madrid</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-breweries"
                        value="name"
                        data-component="query">
                    <br>
                    <p>Ordenar por: name, country, city, founded_year, created_at. Example: <code>name</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-breweries"
                        value="asc"
                        data-component="query">
                    <br>
                    <p>Dirección: asc, desc. Example: <code>asc</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-breweries"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-breweries"
                        value="b"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>b</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="GETapi-v1-breweries"
                        value="n"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>n</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="GETapi-v1-breweries"
                        value="g"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>g</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-breweries"
                        value="created_at"
                        data-component="body">
                    <br>
                    <p>Example: <code>created_at</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>name</code></li>
                        <li><code>country</code></li>
                        <li><code>city</code></li>
                        <li><code>founded_year</code></li>
                        <li><code>created_at</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-breweries"
                        value="desc"
                        data-component="body">
                    <br>
                    <p>Example: <code>desc</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>asc</code></li>
                        <li><code>desc</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-breweries"
                        value="16"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>16</code></p>
                </div>
            </form>

            <h2 id="cervecerias-GETapi-v1-breweries--id-">Ver cervecería</h2>

            <p>
            </p>

            <p>Muestra información detallada de una cervecería específica.</p>

            <span id="example-requests-GETapi-v1-breweries--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/breweries/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/breweries/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-breweries--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Cervecer&iacute;a Mahou&quot;,
        &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
        &quot;city&quot;: &quot;Madrid&quot;,
        &quot;address&quot;: &quot;Calle de Tit&aacute;n, 15, 28045 Madrid&quot;,
        &quot;founded_year&quot;: 1890,
        &quot;description&quot;: &quot;Una de las cervecer&iacute;as m&aacute;s antiguas de Espa&ntilde;a&quot;,
        &quot;website&quot;: &quot;https://www.mahou.es&quot;,
        &quot;logo_url&quot;: &quot;https://example.com/logos/mahou.png&quot;,
        &quot;beers_count&quot;: 12,
        &quot;created_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cervecer&iacute;a solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-breweries--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-breweries--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-breweries--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-breweries--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-breweries--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-breweries--id-" data-method="GET"
                data-path="api/v1/breweries/{id}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-breweries--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-breweries--id-"
                        onclick="tryItOut('GETapi-v1-breweries--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-breweries--id-"
                        onclick="cancelTryOut('GETapi-v1-breweries--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-breweries--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/breweries/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-breweries--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-breweries--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-breweries--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cervecería. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="cervecerias-POSTapi-v1-breweries">Crear cervecería</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Crea una nueva cervecería en el sistema.</p>

            <span id="example-requests-POSTapi-v1-breweries">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/breweries" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=Guinness"\
    --form "country=Irlanda"\
    --form "city=Dublín"\
    --form "address=St. James's Gate, Dublín 8"\
    --form "founded_year=1759"\
    --form "description=Cervecería irlandesa famosa por su stout"\
    --form "website=https://www.guinness.com"\
    --form "logo_url=https://example.com/logos/guinness.png"\
    --form "logo=@/tmp/php0u63c5qde4058ghConO" </code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/breweries"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'Guinness');
body.append('country', 'Irlanda');
body.append('city', 'Dublín');
body.append('address', 'St. James's Gate, Dublín 8');
body.append('founded_year', '1759');
body.append('description', 'Cervecería irlandesa famosa por su stout');
body.append('website', 'https://www.guinness.com');
body.append('logo_url', 'https://example.com/logos/guinness.png');
body.append('logo', document.querySelector('input[name="logo"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-breweries">
                <blockquote>
                    <p>Example response (201):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 10,
        &quot;name&quot;: &quot;Guinness&quot;,
        &quot;country&quot;: &quot;Irlanda&quot;,
        &quot;city&quot;: &quot;Dubl&iacute;n&quot;,
        &quot;address&quot;: &quot;St. James&#039;s Gate, Dubl&iacute;n 8&quot;,
        &quot;founded_year&quot;: 1759,
        &quot;description&quot;: &quot;Cervecer&iacute;a irlandesa famosa por su stout&quot;,
        &quot;website&quot;: &quot;https://www.guinness.com&quot;,
        &quot;logo_url&quot;: &quot;https://example.com/logos/guinness.png&quot;,
        &quot;beers_count&quot;: 0
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Los datos proporcionados no son v&aacute;lidos.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;El campo nombre es obligatorio.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-breweries" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-breweries"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-breweries"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-breweries" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-breweries">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-breweries" data-method="POST"
                data-path="api/v1/breweries"
                data-authed="1"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-breweries', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-breweries"
                        onclick="tryItOut('POSTapi-v1-breweries');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-breweries"
                        onclick="cancelTryOut('POSTapi-v1-breweries');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-breweries"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/breweries</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-breweries"
                        value="multipart/form-data"
                        data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-breweries"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="POSTapi-v1-breweries"
                        value="Guinness"
                        data-component="body">
                    <br>
                    <p>Nombre de la cervecería. Example: <code>Guinness</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="POSTapi-v1-breweries"
                        value="Irlanda"
                        data-component="body">
                    <br>
                    <p>País de origen. Example: <code>Irlanda</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="POSTapi-v1-breweries"
                        value="Dublín"
                        data-component="body">
                    <br>
                    <p>Ciudad principal. Example: <code>Dublín</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="address" data-endpoint="POSTapi-v1-breweries"
                        value="St. James's Gate, Dublín 8"
                        data-component="body">
                    <br>
                    <p>Dirección física. Example: <code>St. James's Gate, Dublín 8</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>founded_year</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="founded_year" data-endpoint="POSTapi-v1-breweries"
                        value="1759"
                        data-component="body">
                    <br>
                    <p>Año de fundación. Example: <code>1759</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="description" data-endpoint="POSTapi-v1-breweries"
                        value="Cervecería irlandesa famosa por su stout"
                        data-component="body">
                    <br>
                    <p>Descripción de la cervecería. Example: <code>Cervecería irlandesa famosa por su stout</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>website</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="website" data-endpoint="POSTapi-v1-breweries"
                        value="https://www.guinness.com"
                        data-component="body">
                    <br>
                    <p>Sitio web oficial. Example: <code>https://www.guinness.com</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>logo_url</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="logo_url" data-endpoint="POSTapi-v1-breweries"
                        value="https://example.com/logos/guinness.png"
                        data-component="body">
                    <br>
                    <p>URL del logotipo. Example: <code>https://example.com/logos/guinness.png</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>logo</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="file" style="display: none"
                        name="logo" data-endpoint="POSTapi-v1-breweries"
                        value=""
                        data-component="body">
                    <br>
                    <p>Logo de la cervecería (JPG, PNG, WebP, máx 2MB). Example: <code>/tmp/php0u63c5qde4058ghConO</code></p>
                </div>
            </form>

            <h2 id="cervecerias-PUTapi-v1-breweries--id-">Actualizar cervecería</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Actualiza la información de una cervecería existente.</p>

            <span id="example-requests-PUTapi-v1-breweries--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/breweries/1" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=Mahou San Miguel"\
    --form "country=España"\
    --form "city=Madrid"\
    --form "address=Calle Titán, 15, 28045 Madrid"\
    --form "founded_year=1890"\
    --form "description=Cervecería española con gran tradición"\
    --form "website=https://www.mahou-sanmiguel.com"\
    --form "logo_url=http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"\
    --form "logo=@/tmp/phpkokrgdcn9hk5aDNACAP" </code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/breweries/1"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'Mahou San Miguel');
body.append('country', 'España');
body.append('city', 'Madrid');
body.append('address', 'Calle Titán, 15, 28045 Madrid');
body.append('founded_year', '1890');
body.append('description', 'Cervecería española con gran tradición');
body.append('website', 'https://www.mahou-sanmiguel.com');
body.append('logo_url', 'http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html');
body.append('logo', document.querySelector('input[name="logo"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-breweries--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Mahou San Miguel&quot;,
        &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
        &quot;city&quot;: &quot;Madrid&quot;,
        &quot;address&quot;: &quot;Calle Tit&aacute;n, 15, 28045 Madrid&quot;,
        &quot;founded_year&quot;: 1890,
        &quot;description&quot;: &quot;Cervecer&iacute;a espa&ntilde;ola con gran tradici&oacute;n&quot;,
        &quot;website&quot;: &quot;https://www.mahou-sanmiguel.com&quot;,
        &quot;logo_url&quot;: &quot;https://example.com/logos/mahou-sanmiguel.png&quot;,
        &quot;beers_count&quot;: 12
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cervecer&iacute;a solicitada.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Los datos proporcionados no son v&aacute;lidos.&quot;,
    &quot;errors&quot;: {
        &quot;website&quot;: [
            &quot;La URL del sitio web debe ser una URL v&aacute;lida.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-breweries--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-breweries--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-breweries--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-breweries--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-breweries--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-breweries--id-" data-method="PUT"
                data-path="api/v1/breweries/{id}"
                data-authed="1"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-breweries--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-breweries--id-"
                        onclick="tryItOut('PUTapi-v1-breweries--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-breweries--id-"
                        onclick="cancelTryOut('PUTapi-v1-breweries--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-breweries--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/breweries/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-breweries--id-"
                        value="multipart/form-data"
                        data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-breweries--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="PUTapi-v1-breweries--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cervecería. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="PUTapi-v1-breweries--id-"
                        value="Mahou San Miguel"
                        data-component="body">
                    <br>
                    <p>Nombre de la cervecería. Example: <code>Mahou San Miguel</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="PUTapi-v1-breweries--id-"
                        value="España"
                        data-component="body">
                    <br>
                    <p>País de origen. Example: <code>España</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="PUTapi-v1-breweries--id-"
                        value="Madrid"
                        data-component="body">
                    <br>
                    <p>Ciudad principal. Example: <code>Madrid</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="address" data-endpoint="PUTapi-v1-breweries--id-"
                        value="Calle Titán, 15, 28045 Madrid"
                        data-component="body">
                    <br>
                    <p>Dirección física. Example: <code>Calle Titán, 15, 28045 Madrid</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>founded_year</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="founded_year" data-endpoint="PUTapi-v1-breweries--id-"
                        value="1890"
                        data-component="body">
                    <br>
                    <p>Año de fundación. Example: <code>1890</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="description" data-endpoint="PUTapi-v1-breweries--id-"
                        value="Cervecería española con gran tradición"
                        data-component="body">
                    <br>
                    <p>Descripción de la cervecería. Example: <code>Cervecería española con gran tradición</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>website</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="website" data-endpoint="PUTapi-v1-breweries--id-"
                        value="https://www.mahou-sanmiguel.com"
                        data-component="body">
                    <br>
                    <p>Sitio web oficial. Example: <code>https://www.mahou-sanmiguel.com</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>logo_url</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="logo_url" data-endpoint="PUTapi-v1-breweries--id-"
                        value="http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"
                        data-component="body">
                    <br>
                    <p>URL del logotipo. Example: <code>http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>logo</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="file" style="display: none"
                        name="logo" data-endpoint="PUTapi-v1-breweries--id-"
                        value=""
                        data-component="body">
                    <br>
                    <p>Logo de la cervecería (JPG, PNG, WebP, máx 2MB). Example: <code>/tmp/phpkokrgdcn9hk5aDNACAP</code></p>
                </div>
            </form>

            <h2 id="cervecerias-DELETEapi-v1-breweries--id-">Eliminar cervecería</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Elimina una cervecería del sistema.</p>

            <span id="example-requests-DELETEapi-v1-breweries--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/breweries/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/breweries/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-breweries--id-">
                <blockquote>
                    <p>Example response (204):</p>
                </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cervecer&iacute;a solicitada.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (409):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se puede eliminar esta cervecer&iacute;a porque tiene cervezas asociadas.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-breweries--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-breweries--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-breweries--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-breweries--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-breweries--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-breweries--id-" data-method="DELETE"
                data-path="api/v1/breweries/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-breweries--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-breweries--id-"
                        onclick="tryItOut('DELETEapi-v1-breweries--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-breweries--id-"
                        onclick="cancelTryOut('DELETEapi-v1-breweries--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-breweries--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/breweries/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-breweries--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-breweries--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-breweries--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cervecería. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="cervecerias-GETapi-v1-breweries--id--beers">Cervezas de la cervecería</h2>

            <p>
            </p>

            <p>Obtiene todas las cervezas que pertenecen a una cervecería específica.</p>

            <span id="example-requests-GETapi-v1-breweries--id--beers">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/breweries/1/beers?sort=rating&amp;order=desc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/breweries/1/beers"
);

const params = {
    "sort": "rating",
    "order": "desc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-breweries--id--beers">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 1,
     &quot;name&quot;: &quot;Mahou Cl&aacute;sica&quot;,
     &quot;style&quot;: {
       &quot;id&quot;: 2,
       &quot;name&quot;: &quot;Lager&quot;
     },
     &quot;abv&quot;: 4.8,
     &quot;ibu&quot;: 20,
     &quot;description&quot;: &quot;Cerveza rubia tipo Lager&quot;,
     &quot;image_url&quot;: &quot;https://example.com/beers/mahou.png&quot;,
     &quot;rating_avg&quot;: 3.75,
     &quot;check_ins_count&quot;: 42,
     &quot;is_favorite&quot;: false
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...},
 &quot;brewery&quot;: {
   &quot;id&quot;: 1,
   &quot;name&quot;: &quot;Cervecer&iacute;a Mahou&quot;,
   &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
   &quot;logo_url&quot;: &quot;https://example.com/logos/mahou.png&quot;
 }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cervecer&iacute;a solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-breweries--id--beers" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-breweries--id--beers"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-breweries--id--beers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-breweries--id--beers" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-breweries--id--beers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-breweries--id--beers" data-method="GET"
                data-path="api/v1/breweries/{id}/beers"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-breweries--id--beers', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-breweries--id--beers"
                        onclick="tryItOut('GETapi-v1-breweries--id--beers');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-breweries--id--beers"
                        onclick="cancelTryOut('GETapi-v1-breweries--id--beers');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-breweries--id--beers"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/breweries/{id}/beers</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-breweries--id--beers"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-breweries--id--beers"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-breweries--id--beers"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cervecería. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-breweries--id--beers"
                        value="rating"
                        data-component="query">
                    <br>
                    <p>Ordenar por: name, abv, ibu, rating, created_at. Example: <code>rating</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-breweries--id--beers"
                        value="desc"
                        data-component="query">
                    <br>
                    <p>Dirección: asc, desc. Example: <code>desc</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-breweries--id--beers"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
            </form>

            <h1 id="cervezas">Cervezas</h1>

            <p>APIs para gestionar cervezas</p>

            <h2 id="cervezas-GETapi-v1-beers">Listar cervezas</h2>

            <p>
            </p>

            <p>Obtiene una lista paginada de cervezas con opciones de filtrado y ordenamiento.</p>

            <span id="example-requests-GETapi-v1-beers">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/beers?name=IPA&amp;brewery_id=1&amp;style_id=3&amp;min_abv=5&amp;max_abv=8&amp;min_ibu=20&amp;max_ibu=80&amp;min_rating=4&amp;sort=rating&amp;order=desc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"brewery_id\": 16,
    \"style_id\": 16,
    \"min_abv\": 22,
    \"max_abv\": 7,
    \"min_ibu\": 16,
    \"max_ibu\": 17,
    \"min_rating\": 5,
    \"sort\": \"created_at\",
    \"order\": \"asc\",
    \"per_page\": 8
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beers"
);

const params = {
    "name": "IPA",
    "brewery_id": "1",
    "style_id": "3",
    "min_abv": "5",
    "max_abv": "8",
    "min_ibu": "20",
    "max_ibu": "80",
    "min_rating": "4",
    "sort": "rating",
    "order": "desc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "brewery_id": 16,
    "style_id": 16,
    "min_abv": 22,
    "max_abv": 7,
    "min_ibu": 16,
    "max_ibu": 17,
    "min_rating": 5,
    "sort": "created_at",
    "order": "asc",
    "per_page": 8
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-beers">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Mahou Cl&aacute;sica&quot;,
            &quot;brewery&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Cervecer&iacute;a Mahou&quot;,
                &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
                &quot;logo_url&quot;: &quot;https://example.com/logos/mahou.png&quot;
            },
            &quot;style&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;Lager&quot;
            },
            &quot;abv&quot;: 4.8,
            &quot;ibu&quot;: 20,
            &quot;description&quot;: &quot;Cerveza rubia tipo Lager&quot;,
            &quot;image_url&quot;: &quot;https://example.com/beers/mahou.png&quot;,
            &quot;rating_avg&quot;: 3.75,
            &quot;check_ins_count&quot;: 42,
            &quot;is_favorite&quot;: false,
            &quot;created_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://127.0.0.1:8000/api/v1/beers?page=1&quot;,
        &quot;last&quot;: &quot;http://127.0.0.1:8000/api/v1/beers?page=5&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;http://127.0.0.1:8000/api/v1/beers?page=2&quot;
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: 1,
        &quot;last_page&quot;: 5,
        &quot;path&quot;: &quot;http://127.0.0.1:8000/api/v1/beers&quot;,
        &quot;per_page&quot;: 10,
        &quot;to&quot;: 10,
        &quot;total&quot;: 50
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-beers" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-beers"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-beers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-beers" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-beers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-beers" data-method="GET"
                data-path="api/v1/beers"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-beers', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-beers"
                        onclick="tryItOut('GETapi-v1-beers');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-beers"
                        onclick="cancelTryOut('GETapi-v1-beers');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-beers"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/beers</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-beers"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-beers"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-beers"
                        value="IPA"
                        data-component="query">
                    <br>
                    <p>Filtrar por nombre. Example: <code>IPA</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>brewery_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="brewery_id" data-endpoint="GETapi-v1-beers"
                        value="1"
                        data-component="query">
                    <br>
                    <p>Filtrar por ID de cervecería. Example: <code>1</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>style_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="style_id" data-endpoint="GETapi-v1-beers"
                        value="3"
                        data-component="query">
                    <br>
                    <p>Filtrar por ID de estilo. Example: <code>3</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>min_abv</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_abv" data-endpoint="GETapi-v1-beers"
                        value="5"
                        data-component="query">
                    <br>
                    <p>Filtrar por ABV mínimo. Example: <code>5</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>max_abv</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="max_abv" data-endpoint="GETapi-v1-beers"
                        value="8"
                        data-component="query">
                    <br>
                    <p>Filtrar por ABV máximo. Example: <code>8</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>min_ibu</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_ibu" data-endpoint="GETapi-v1-beers"
                        value="20"
                        data-component="query">
                    <br>
                    <p>Filtrar por IBU mínimo. Example: <code>20</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>max_ibu</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="max_ibu" data-endpoint="GETapi-v1-beers"
                        value="80"
                        data-component="query">
                    <br>
                    <p>Filtrar por IBU máximo. Example: <code>80</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-beers"
                        value="4"
                        data-component="query">
                    <br>
                    <p>Filtrar por calificación mínima (1-5). Example: <code>4</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-beers"
                        value="rating"
                        data-component="query">
                    <br>
                    <p>Ordenar por: name, abv, ibu, rating. Example: <code>rating</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-beers"
                        value="desc"
                        data-component="query">
                    <br>
                    <p>Dirección: asc, desc. Example: <code>desc</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-beers"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-beers"
                        value="b"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>b</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>brewery_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="brewery_id" data-endpoint="GETapi-v1-beers"
                        value="16"
                        data-component="body">
                    <br>
                    <p>The <code>id</code> of an existing record in the breweries table. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>style_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="style_id" data-endpoint="GETapi-v1-beers"
                        value="16"
                        data-component="body">
                    <br>
                    <p>The <code>id</code> of an existing record in the beer_styles table. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>min_abv</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_abv" data-endpoint="GETapi-v1-beers"
                        value="22"
                        data-component="body">
                    <br>
                    <p>Must be at least 0. Must not be greater than 100. Example: <code>22</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>max_abv</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="max_abv" data-endpoint="GETapi-v1-beers"
                        value="7"
                        data-component="body">
                    <br>
                    <p>Must be at least 0. Must not be greater than 100. Example: <code>7</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>min_ibu</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_ibu" data-endpoint="GETapi-v1-beers"
                        value="16"
                        data-component="body">
                    <br>
                    <p>Must be at least 0. Must not be greater than 200. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>max_ibu</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="max_ibu" data-endpoint="GETapi-v1-beers"
                        value="17"
                        data-component="body">
                    <br>
                    <p>Must be at least 0. Must not be greater than 200. Example: <code>17</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-beers"
                        value="5"
                        data-component="body">
                    <br>
                    <p>Must be at least 1. Must not be greater than 5. Example: <code>5</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-beers"
                        value="created_at"
                        data-component="body">
                    <br>
                    <p>Example: <code>created_at</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>name</code></li>
                        <li><code>abv</code></li>
                        <li><code>ibu</code></li>
                        <li><code>rating</code></li>
                        <li><code>created_at</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-beers"
                        value="asc"
                        data-component="body">
                    <br>
                    <p>Example: <code>asc</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>asc</code></li>
                        <li><code>desc</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-beers"
                        value="8"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>8</code></p>
                </div>
            </form>

            <h2 id="cervezas-GETapi-v1-beers--id-">Ver cerveza</h2>

            <p>
            </p>

            <p>Muestra información detallada de una cerveza específica.</p>

            <span id="example-requests-GETapi-v1-beers--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/beers/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beers/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-beers--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Mahou Cl&aacute;sica&quot;,
        &quot;brewery&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Cervecer&iacute;a Mahou&quot;,
            &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
            &quot;logo_url&quot;: &quot;https://example.com/logos/mahou.png&quot;
        },
        &quot;style&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Lager&quot;
        },
        &quot;abv&quot;: 4.8,
        &quot;ibu&quot;: 20,
        &quot;description&quot;: &quot;Cerveza rubia tipo Lager&quot;,
        &quot;image_url&quot;: &quot;https://example.com/beers/mahou.png&quot;,
        &quot;rating_avg&quot;: 3.75,
        &quot;check_ins_count&quot;: 42,
        &quot;is_favorite&quot;: false
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cerveza solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-beers--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-beers--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-beers--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-beers--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-beers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-beers--id-" data-method="GET"
                data-path="api/v1/beers/{id}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-beers--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-beers--id-"
                        onclick="tryItOut('GETapi-v1-beers--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-beers--id-"
                        onclick="cancelTryOut('GETapi-v1-beers--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-beers--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/beers/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-beers--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-beers--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-beers--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cerveza. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="cervezas-POSTapi-v1-beers">Crear cerveza</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Crea una nueva cerveza en el sistema.</p>

            <span id="example-requests-POSTapi-v1-beers">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/beers" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=Estrella Galicia Especial"\
    --form "brewery_id=2"\
    --form "style_id=2"\
    --form "abv=5.5"\
    --form "ibu=25"\
    --form "description=Cerveza premium con carácter atlántico"\
    --form "image_url=https://example.com/beers/estrella.png"\
    --form "image=@/tmp/php6b7ejq2ht34t7OJpgeO" </code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beers"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'Estrella Galicia Especial');
body.append('brewery_id', '2');
body.append('style_id', '2');
body.append('abv', '5.5');
body.append('ibu', '25');
body.append('description', 'Cerveza premium con carácter atlántico');
body.append('image_url', 'https://example.com/beers/estrella.png');
body.append('image', document.querySelector('input[name="image"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-beers">
                <blockquote>
                    <p>Example response (201):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 51,
        &quot;name&quot;: &quot;Estrella Galicia Especial&quot;,
        &quot;brewery&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Estrella Galicia&quot;
        },
        &quot;style&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Lager&quot;
        },
        &quot;abv&quot;: 5.5,
        &quot;ibu&quot;: 25,
        &quot;description&quot;: &quot;Cerveza premium con car&aacute;cter atl&aacute;ntico&quot;,
        &quot;image_url&quot;: &quot;https://example.com/beers/estrella.png&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Los datos proporcionados no son v&aacute;lidos.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;El campo nombre es obligatorio.&quot;
        ],
        &quot;brewery_id&quot;: [
            &quot;La cervecer&iacute;a seleccionada no existe.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-beers" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-beers"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-beers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-beers" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-beers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-beers" data-method="POST"
                data-path="api/v1/beers"
                data-authed="1"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-beers', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-beers"
                        onclick="tryItOut('POSTapi-v1-beers');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-beers"
                        onclick="cancelTryOut('POSTapi-v1-beers');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-beers"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/beers</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-beers"
                        value="multipart/form-data"
                        data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-beers"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="POSTapi-v1-beers"
                        value="Estrella Galicia Especial"
                        data-component="body">
                    <br>
                    <p>Nombre de la cerveza. Example: <code>Estrella Galicia Especial</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>brewery_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="brewery_id" data-endpoint="POSTapi-v1-beers"
                        value="2"
                        data-component="body">
                    <br>
                    <p>ID de la cervecería. Example: <code>2</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>style_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="style_id" data-endpoint="POSTapi-v1-beers"
                        value="2"
                        data-component="body">
                    <br>
                    <p>ID del estilo. Example: <code>2</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>abv</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="abv" data-endpoint="POSTapi-v1-beers"
                        value="5.5"
                        data-component="body">
                    <br>
                    <p>ABV (Alcohol By Volume) en %. Example: <code>5.5</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>ibu</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="ibu" data-endpoint="POSTapi-v1-beers"
                        value="25"
                        data-component="body">
                    <br>
                    <p>IBU (International Bitterness Units). Example: <code>25</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="description" data-endpoint="POSTapi-v1-beers"
                        value="Cerveza premium con carácter atlántico"
                        data-component="body">
                    <br>
                    <p>Descripción de la cerveza. Example: <code>Cerveza premium con carácter atlántico</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>image_url</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="image_url" data-endpoint="POSTapi-v1-beers"
                        value="https://example.com/beers/estrella.png"
                        data-component="body">
                    <br>
                    <p>URL de la imagen de la cerveza. Example: <code>https://example.com/beers/estrella.png</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>image</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="file" style="display: none"
                        name="image" data-endpoint="POSTapi-v1-beers"
                        value=""
                        data-component="body">
                    <br>
                    <p>Imagen de la cerveza (JPG, PNG, WebP, máx 2MB). Example: <code>/tmp/php6b7ejq2ht34t7OJpgeO</code></p>
                </div>
            </form>

            <h2 id="cervezas-PUTapi-v1-beers--id-">Actualizar cerveza</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Actualiza la información de una cerveza existente.</p>

            <span id="example-requests-PUTapi-v1-beers--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/beers/1" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=Mahou Clásica Edición Especial"\
    --form "brewery_id=1"\
    --form "style_id=2"\
    --form "abv=4.9"\
    --form "ibu=22"\
    --form "description=Versión mejorada de la clásica Mahou"\
    --form "image_url=http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"\
    --form "image=@/tmp/phph37c9cmokbjsbmaomgO" </code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beers/1"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'Mahou Clásica Edición Especial');
body.append('brewery_id', '1');
body.append('style_id', '2');
body.append('abv', '4.9');
body.append('ibu', '22');
body.append('description', 'Versión mejorada de la clásica Mahou');
body.append('image_url', 'http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html');
body.append('image', document.querySelector('input[name="image"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-beers--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Mahou Cl&aacute;sica Edici&oacute;n Especial&quot;,
        &quot;brewery&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Cervecer&iacute;a Mahou&quot;
        },
        &quot;style&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Lager&quot;
        },
        &quot;abv&quot;: 4.9,
        &quot;ibu&quot;: 22,
        &quot;description&quot;: &quot;Versi&oacute;n mejorada de la cl&aacute;sica Mahou&quot;,
        &quot;image_url&quot;: &quot;https://example.com/beers/mahou_especial.png&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cerveza solicitada.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Los datos proporcionados no son v&aacute;lidos.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;El nombre no puede superar los 255 caracteres.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-beers--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-beers--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-beers--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-beers--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-beers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-beers--id-" data-method="PUT"
                data-path="api/v1/beers/{id}"
                data-authed="1"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-beers--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-beers--id-"
                        onclick="tryItOut('PUTapi-v1-beers--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-beers--id-"
                        onclick="cancelTryOut('PUTapi-v1-beers--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-beers--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/beers/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-beers--id-"
                        value="multipart/form-data"
                        data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-beers--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="PUTapi-v1-beers--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cerveza. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="PUTapi-v1-beers--id-"
                        value="Mahou Clásica Edición Especial"
                        data-component="body">
                    <br>
                    <p>Nombre de la cerveza. Example: <code>Mahou Clásica Edición Especial</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>brewery_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="brewery_id" data-endpoint="PUTapi-v1-beers--id-"
                        value="1"
                        data-component="body">
                    <br>
                    <p>ID de la cervecería. Example: <code>1</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>style_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="style_id" data-endpoint="PUTapi-v1-beers--id-"
                        value="2"
                        data-component="body">
                    <br>
                    <p>ID del estilo. Example: <code>2</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>abv</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="abv" data-endpoint="PUTapi-v1-beers--id-"
                        value="4.9"
                        data-component="body">
                    <br>
                    <p>ABV (Alcohol By Volume) en %. Example: <code>4.9</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>ibu</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="ibu" data-endpoint="PUTapi-v1-beers--id-"
                        value="22"
                        data-component="body">
                    <br>
                    <p>IBU (International Bitterness Units). Example: <code>22</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="description" data-endpoint="PUTapi-v1-beers--id-"
                        value="Versión mejorada de la clásica Mahou"
                        data-component="body">
                    <br>
                    <p>Descripción de la cerveza. Example: <code>Versión mejorada de la clásica Mahou</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>image_url</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="image_url" data-endpoint="PUTapi-v1-beers--id-"
                        value="http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"
                        data-component="body">
                    <br>
                    <p>URL de la imagen de la cerveza. Example: <code>http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>image</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="file" style="display: none"
                        name="image" data-endpoint="PUTapi-v1-beers--id-"
                        value=""
                        data-component="body">
                    <br>
                    <p>Imagen de la cerveza (JPG, PNG, WebP, máx 2MB). Example: <code>/tmp/phph37c9cmokbjsbmaomgO</code></p>
                </div>
            </form>

            <h2 id="cervezas-DELETEapi-v1-beers--id-">Eliminar cerveza</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Elimina una cerveza del sistema.</p>

            <span id="example-requests-DELETEapi-v1-beers--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/beers/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beers/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-beers--id-">
                <blockquote>
                    <p>Example response (204):</p>
                </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cerveza solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-beers--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-beers--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-beers--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-beers--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-beers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-beers--id-" data-method="DELETE"
                data-path="api/v1/beers/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-beers--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-beers--id-"
                        onclick="tryItOut('DELETEapi-v1-beers--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-beers--id-"
                        onclick="cancelTryOut('DELETEapi-v1-beers--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-beers--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/beers/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-beers--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-beers--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-beers--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cerveza. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="cervezas-POSTapi-v1-beers--id--favorite">Marcar como favorita</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Marca una cerveza como favorita para el usuario autenticado.</p>

            <span id="example-requests-POSTapi-v1-beers--id--favorite">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/beers/1/favorite" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beers/1/favorite"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-beers--id--favorite">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Cerveza a&ntilde;adida a favoritos&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cerveza solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-beers--id--favorite" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-beers--id--favorite"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-beers--id--favorite"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-beers--id--favorite" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-beers--id--favorite">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-beers--id--favorite" data-method="POST"
                data-path="api/v1/beers/{id}/favorite"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-beers--id--favorite', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-beers--id--favorite"
                        onclick="tryItOut('POSTapi-v1-beers--id--favorite');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-beers--id--favorite"
                        onclick="cancelTryOut('POSTapi-v1-beers--id--favorite');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-beers--id--favorite"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/beers/{id}/favorite</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-beers--id--favorite"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-beers--id--favorite"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="POSTapi-v1-beers--id--favorite"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cerveza. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="cervezas-DELETEapi-v1-beers--id--unfavorite">Quitar de favoritos</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Elimina una cerveza de la lista de favoritos del usuario autenticado.</p>

            <span id="example-requests-DELETEapi-v1-beers--id--unfavorite">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/beers/1/unfavorite" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beers/1/unfavorite"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-beers--id--unfavorite">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Cerveza eliminada de favoritos&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-beers--id--unfavorite" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-beers--id--unfavorite"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-beers--id--unfavorite"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-beers--id--unfavorite" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-beers--id--unfavorite">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-beers--id--unfavorite" data-method="DELETE"
                data-path="api/v1/beers/{id}/unfavorite"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-beers--id--unfavorite', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-beers--id--unfavorite"
                        onclick="tryItOut('DELETEapi-v1-beers--id--unfavorite');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-beers--id--unfavorite"
                        onclick="cancelTryOut('DELETEapi-v1-beers--id--unfavorite');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-beers--id--unfavorite"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/beers/{id}/unfavorite</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-beers--id--unfavorite"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-beers--id--unfavorite"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-beers--id--unfavorite"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cerveza. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="cervezas-GETapi-v1-beers--id--similar">Cervezas similares</h2>

            <p>
            </p>

            <p>Obtiene una lista de cervezas similares a la cerveza especificada basada en estilo, IBU y ABV.</p>

            <span id="example-requests-GETapi-v1-beers--id--similar">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/beers/1/similar?limit=5" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beers/1/similar"
);

const params = {
    "limit": "5",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-beers--id--similar">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Estrella Damm&quot;,
            &quot;brewery&quot;: {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;Damm&quot;
            },
            &quot;style&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;Lager&quot;
            },
            &quot;abv&quot;: 4.6,
            &quot;ibu&quot;: 22,
            &quot;description&quot;: &quot;Cerveza mediterr&aacute;nea&quot;,
            &quot;image_url&quot;: &quot;https://example.com/beers/estrella_damm.png&quot;,
            &quot;rating_avg&quot;: 3.6,
            &quot;check_ins_count&quot;: 38,
            &quot;is_favorite&quot;: false,
            &quot;similarity_score&quot;: 0.92
        }
    ]
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cerveza solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-beers--id--similar" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-beers--id--similar"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-beers--id--similar"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-beers--id--similar" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-beers--id--similar">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-beers--id--similar" data-method="GET"
                data-path="api/v1/beers/{id}/similar"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-beers--id--similar', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-beers--id--similar"
                        onclick="tryItOut('GETapi-v1-beers--id--similar');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-beers--id--similar"
                        onclick="cancelTryOut('GETapi-v1-beers--id--similar');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-beers--id--similar"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/beers/{id}/similar</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-beers--id--similar"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-beers--id--similar"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-beers--id--similar"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la cerveza. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="limit" data-endpoint="GETapi-v1-beers--id--similar"
                        value="5"
                        data-component="query">
                    <br>
                    <p>Número máximo de cervezas a retornar (1-20). Example: <code>5</code></p>
                </div>
            </form>

            <h1 id="check-ins">Check-ins</h1>

            <p>APIs para gestionar check-ins de cervezas de los usuarios</p>

            <h2 id="check-ins-GETapi-v1-check-ins">Listar check-ins</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene un listado paginado de check-ins con opciones de filtrado y ordenamiento.</p>

            <span id="example-requests-GETapi-v1-check-ins">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/check-ins?beer_id=5&amp;brewery_id=3&amp;style_id=8&amp;location_id=2&amp;min_rating=4&amp;user_id=7&amp;sort=recent&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"beer_id\": 16,
    \"brewery_id\": 16,
    \"style_id\": 16,
    \"location_id\": 16,
    \"min_rating\": 2,
    \"user_id\": 16,
    \"sort\": \"rating\",
    \"per_page\": 22
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/check-ins"
);

const params = {
    "beer_id": "5",
    "brewery_id": "3",
    "style_id": "8",
    "location_id": "2",
    "min_rating": "4",
    "user_id": "7",
    "sort": "recent",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "beer_id": 16,
    "brewery_id": 16,
    "style_id": 16,
    "location_id": 16,
    "min_rating": 2,
    "user_id": 16,
    "sort": "rating",
    "per_page": 22
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-check-ins">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 42,
     &quot;user&quot;: {
       &quot;id&quot;: 3,
       &quot;name&quot;: &quot;Carlos Ruiz&quot;,
       &quot;profile_picture&quot;: &quot;https://example.com/avatars/carlos.jpg&quot;
     },
     &quot;beer&quot;: {
       &quot;id&quot;: 5,
       &quot;name&quot;: &quot;Founders Breakfast Stout&quot;,
       &quot;brewery&quot;: {
         &quot;id&quot;: 3,
         &quot;name&quot;: &quot;Founders Brewing Co.&quot;
       },
       &quot;style&quot;: {
         &quot;id&quot;: 8,
         &quot;name&quot;: &quot;Imperial Stout&quot;
       }
     },
     &quot;rating&quot;: 4.5,
     &quot;comment&quot;: &quot;Excelente balance entre caf&eacute; y chocolate&quot;,
     &quot;photo_url&quot;: &quot;https://example.com/photos/check_in_42.jpg&quot;,
     &quot;location&quot;: {
       &quot;id&quot;: 2,
       &quot;name&quot;: &quot;Beer Garden Madrid&quot;
     },
     &quot;likes_count&quot;: 15,
     &quot;comments_count&quot;: 3,
     &quot;is_liked&quot;: false,
     &quot;created_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;,
     &quot;updated_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-check-ins" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-check-ins"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-check-ins"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-check-ins" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-check-ins">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-check-ins" data-method="GET"
                data-path="api/v1/check-ins"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-check-ins', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-check-ins"
                        onclick="tryItOut('GETapi-v1-check-ins');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-check-ins"
                        onclick="cancelTryOut('GETapi-v1-check-ins');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-check-ins"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/check-ins</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-check-ins"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-check-ins"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>beer_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="beer_id" data-endpoint="GETapi-v1-check-ins"
                        value="5"
                        data-component="query">
                    <br>
                    <p>Filtrar por cerveza. Example: <code>5</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>brewery_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="brewery_id" data-endpoint="GETapi-v1-check-ins"
                        value="3"
                        data-component="query">
                    <br>
                    <p>Filtrar por cervecería. Example: <code>3</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>style_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="style_id" data-endpoint="GETapi-v1-check-ins"
                        value="8"
                        data-component="query">
                    <br>
                    <p>Filtrar por estilo de cerveza. Example: <code>8</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="location_id" data-endpoint="GETapi-v1-check-ins"
                        value="2"
                        data-component="query">
                    <br>
                    <p>Filtrar por ubicación. Example: <code>2</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-check-ins"
                        value="4"
                        data-component="query">
                    <br>
                    <p>Calificación mínima (1-5). Example: <code>4</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="user_id" data-endpoint="GETapi-v1-check-ins"
                        value="7"
                        data-component="query">
                    <br>
                    <p>Filtrar por usuario. Example: <code>7</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-check-ins"
                        value="recent"
                        data-component="query">
                    <br>
                    <p>Ordenar por: recent, rating, likes. Example: <code>recent</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-check-ins"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>beer_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="beer_id" data-endpoint="GETapi-v1-check-ins"
                        value="16"
                        data-component="body">
                    <br>
                    <p>The <code>id</code> of an existing record in the beers table. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>brewery_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="brewery_id" data-endpoint="GETapi-v1-check-ins"
                        value="16"
                        data-component="body">
                    <br>
                    <p>The <code>id</code> of an existing record in the breweries table. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>style_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="style_id" data-endpoint="GETapi-v1-check-ins"
                        value="16"
                        data-component="body">
                    <br>
                    <p>The <code>id</code> of an existing record in the beer_styles table. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="location_id" data-endpoint="GETapi-v1-check-ins"
                        value="16"
                        data-component="body">
                    <br>
                    <p>The <code>id</code> of an existing record in the locations table. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-check-ins"
                        value="2"
                        data-component="body">
                    <br>
                    <p>Must be at least 1. Must not be greater than 5. Example: <code>2</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="user_id" data-endpoint="GETapi-v1-check-ins"
                        value="16"
                        data-component="body">
                    <br>
                    <p>The <code>id</code> of an existing record in the users table. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-check-ins"
                        value="rating"
                        data-component="body">
                    <br>
                    <p>Example: <code>rating</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>recent</code></li>
                        <li><code>rating</code></li>
                        <li><code>likes</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-check-ins"
                        value="22"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>22</code></p>
                </div>
            </form>

            <h2 id="check-ins-POSTapi-v1-check-ins">Crear check-in</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Registra un nuevo check-in de cerveza.</p>

            <span id="example-requests-POSTapi-v1-check-ins">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/check-ins" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "beer_id=5"\
    --form "rating=4.5"\
    --form "comment=Excelente balance entre café y chocolate"\
    --form "location_id=2"\
    --form "serving=draft"\
    --form "purchase_price=6.50"\
    --form "purchase_currency=EUR"\
    --form "flavor_notes[]=café"\
    --form "photo=@/tmp/phpk0sh7q22la4c5EnNDfP" </code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/check-ins"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('beer_id', '5');
body.append('rating', '4.5');
body.append('comment', 'Excelente balance entre café y chocolate');
body.append('location_id', '2');
body.append('serving', 'draft');
body.append('purchase_price', '6.50');
body.append('purchase_currency', 'EUR');
body.append('flavor_notes[]', 'café');
body.append('photo', document.querySelector('input[name="photo"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-check-ins">
                <blockquote>
                    <p>Example response (201):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 42,
        &quot;user&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Carlos Ruiz&quot;,
            &quot;profile_picture&quot;: &quot;https://example.com/avatars/carlos.jpg&quot;
        },
        &quot;beer&quot;: {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;Founders Breakfast Stout&quot;,
            &quot;brewery&quot;: {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;Founders Brewing Co.&quot;
            },
            &quot;style&quot;: {
                &quot;id&quot;: 8,
                &quot;name&quot;: &quot;Imperial Stout&quot;
            }
        },
        &quot;rating&quot;: 4.5,
        &quot;comment&quot;: &quot;Excelente balance entre caf&eacute; y chocolate&quot;,
        &quot;photo_url&quot;: &quot;https://example.com/photos/check_in_42.jpg&quot;,
        &quot;location&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Beer Garden Madrid&quot;
        },
        &quot;likes_count&quot;: 0,
        &quot;comments_count&quot;: 0,
        &quot;serving&quot;: &quot;draft&quot;,
        &quot;purchase_price&quot;: 6.5,
        &quot;purchase_currency&quot;: &quot;EUR&quot;,
        &quot;flavor_notes&quot;: [
            &quot;caf&eacute;&quot;,
            &quot;chocolate&quot;,
            &quot;vainilla&quot;
        ],
        &quot;created_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Los datos proporcionados no son v&aacute;lidos.&quot;,
    &quot;errors&quot;: {
        &quot;beer_id&quot;: [
            &quot;El campo beer id es obligatorio.&quot;
        ],
        &quot;rating&quot;: [
            &quot;El campo rating debe estar entre 1 y 5.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-check-ins" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-check-ins"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-check-ins"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-check-ins" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-check-ins">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-check-ins" data-method="POST"
                data-path="api/v1/check-ins"
                data-authed="1"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-check-ins', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-check-ins"
                        onclick="tryItOut('POSTapi-v1-check-ins');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-check-ins"
                        onclick="cancelTryOut('POSTapi-v1-check-ins');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-check-ins"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/check-ins</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-check-ins"
                        value="multipart/form-data"
                        data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-check-ins"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>beer_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="beer_id" data-endpoint="POSTapi-v1-check-ins"
                        value="5"
                        data-component="body">
                    <br>
                    <p>ID de la cerveza. Example: <code>5</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>rating</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="rating" data-endpoint="POSTapi-v1-check-ins"
                        value="4.5"
                        data-component="body">
                    <br>
                    <p>Calificación de 1 a 5. Example: <code>4.5</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>comment</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="comment" data-endpoint="POSTapi-v1-check-ins"
                        value="Excelente balance entre café y chocolate"
                        data-component="body">
                    <br>
                    <p>Comentario sobre la cerveza. Example: <code>Excelente balance entre café y chocolate</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="location_id" data-endpoint="POSTapi-v1-check-ins"
                        value="2"
                        data-component="body">
                    <br>
                    <p>ID de la ubicación donde se consumió. Example: <code>2</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>photo</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="file" style="display: none"
                        name="photo" data-endpoint="POSTapi-v1-check-ins"
                        value=""
                        data-component="body">
                    <br>
                    <p>Foto de la cerveza (JPG, PNG, WebP, máx 2MB). Example: <code>/tmp/phpk0sh7q22la4c5EnNDfP</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>serving</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="serving" data-endpoint="POSTapi-v1-check-ins"
                        value="draft"
                        data-component="body">
                    <br>
                    <p>Forma de servir (draft, bottle, can). Example: <code>draft</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>purchase_price</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="purchase_price" data-endpoint="POSTapi-v1-check-ins"
                        value="6.50"
                        data-component="body">
                    <br>
                    <p>Precio pagado por la cerveza. Example: <code>6.50</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>purchase_currency</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="purchase_currency" data-endpoint="POSTapi-v1-check-ins"
                        value="EUR"
                        data-component="body">
                    <br>
                    <p>Moneda del precio (EUR, USD, etc). Example: <code>EUR</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>flavor_notes</code></b>&nbsp;&nbsp;
                    <small>string[]</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="flavor_notes[0]" data-endpoint="POSTapi-v1-check-ins"
                        data-component="body">
                    <input type="text" style="display: none"
                        name="flavor_notes[1]" data-endpoint="POSTapi-v1-check-ins"
                        data-component="body">
                    <br>
                    <p>Array de notas de sabor.</p>
                </div>
            </form>

            <h2 id="check-ins-GETapi-v1-check-ins--id-">Ver check-in</h2>

            <p>
            </p>

            <p>Muestra información detallada de un check-in específico, incluyendo comentarios.</p>

            <span id="example-requests-GETapi-v1-check-ins--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/check-ins/42" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/check-ins/42"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-check-ins--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 42,
        &quot;user&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Carlos Ruiz&quot;,
            &quot;profile_picture&quot;: &quot;https://example.com/avatars/carlos.jpg&quot;
        },
        &quot;beer&quot;: {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;Founders Breakfast Stout&quot;,
            &quot;brewery&quot;: {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;Founders Brewing Co.&quot;
            },
            &quot;style&quot;: {
                &quot;id&quot;: 8,
                &quot;name&quot;: &quot;Imperial Stout&quot;
            }
        },
        &quot;rating&quot;: 4.5,
        &quot;comment&quot;: &quot;Excelente balance entre caf&eacute; y chocolate&quot;,
        &quot;photo_url&quot;: &quot;https://example.com/photos/check_in_42.jpg&quot;,
        &quot;location&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Beer Garden Madrid&quot;
        },
        &quot;likes_count&quot;: 15,
        &quot;comments&quot;: [
            {
                &quot;id&quot;: 112,
                &quot;user&quot;: {
                    &quot;id&quot;: 7,
                    &quot;name&quot;: &quot;Mar&iacute;a L&oacute;pez&quot;,
                    &quot;profile_picture&quot;: &quot;https://example.com/avatars/maria.jpg&quot;
                },
                &quot;content&quot;: &quot;Totalmente de acuerdo, una maravilla de cerveza.&quot;,
                &quot;created_at&quot;: &quot;2023-04-18T19:15:00.000000Z&quot;
            }
        ],
        &quot;is_liked&quot;: false,
        &quot;created_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el check-in solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-check-ins--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-check-ins--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-check-ins--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-check-ins--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-check-ins--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-check-ins--id-" data-method="GET"
                data-path="api/v1/check-ins/{id}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-check-ins--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-check-ins--id-"
                        onclick="tryItOut('GETapi-v1-check-ins--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-check-ins--id-"
                        onclick="cancelTryOut('GETapi-v1-check-ins--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-check-ins--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/check-ins/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-check-ins--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-check-ins--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-check-ins--id-"
                        value="42"
                        data-component="url">
                    <br>
                    <p>ID del check-in. Example: <code>42</code></p>
                </div>
            </form>

            <h2 id="check-ins-PUTapi-v1-check-ins--id-">Actualizar check-in</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Actualiza la información de un check-in existente.</p>

            <span id="example-requests-PUTapi-v1-check-ins--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/check-ins/42" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "rating=4.0"\
    --form "comment=Un sabor potente pero equilibrado"\
    --form "location_id=3"\
    --form "serving=bottle"\
    --form "purchase_price=5.95"\
    --form "purchase_currency=EUR"\
    --form "flavor_notes[]=café"\
    --form "photo=@/tmp/phpqnfarppea22e3fFjhiP" </code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/check-ins/42"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('rating', '4.0');
body.append('comment', 'Un sabor potente pero equilibrado');
body.append('location_id', '3');
body.append('serving', 'bottle');
body.append('purchase_price', '5.95');
body.append('purchase_currency', 'EUR');
body.append('flavor_notes[]', 'café');
body.append('photo', document.querySelector('input[name="photo"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-check-ins--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 42,
        &quot;user&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Carlos Ruiz&quot;,
            &quot;profile_picture&quot;: &quot;https://example.com/avatars/carlos.jpg&quot;
        },
        &quot;beer&quot;: {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;Founders Breakfast Stout&quot;,
            &quot;brewery&quot;: {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;Founders Brewing Co.&quot;
            },
            &quot;style&quot;: {
                &quot;id&quot;: 8,
                &quot;name&quot;: &quot;Imperial Stout&quot;
            }
        },
        &quot;rating&quot;: 4,
        &quot;comment&quot;: &quot;Un sabor potente pero equilibrado&quot;,
        &quot;photo_url&quot;: &quot;https://example.com/photos/check_in_42.jpg&quot;,
        &quot;location&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Craft Beer Shop&quot;
        },
        &quot;likes_count&quot;: 15,
        &quot;comments_count&quot;: 3,
        &quot;serving&quot;: &quot;bottle&quot;,
        &quot;purchase_price&quot;: 5.95,
        &quot;purchase_currency&quot;: &quot;EUR&quot;,
        &quot;flavor_notes&quot;: [
            &quot;caf&eacute;&quot;,
            &quot;chocolate&quot;,
            &quot;avellanas&quot;
        ],
        &quot;created_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T19:45:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (403):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No tienes permiso para actualizar este check-in.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el check-in solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-check-ins--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-check-ins--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-check-ins--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-check-ins--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-check-ins--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-check-ins--id-" data-method="PUT"
                data-path="api/v1/check-ins/{id}"
                data-authed="1"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-check-ins--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-check-ins--id-"
                        onclick="tryItOut('PUTapi-v1-check-ins--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-check-ins--id-"
                        onclick="cancelTryOut('PUTapi-v1-check-ins--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-check-ins--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/check-ins/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-check-ins--id-"
                        value="multipart/form-data"
                        data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-check-ins--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="PUTapi-v1-check-ins--id-"
                        value="42"
                        data-component="url">
                    <br>
                    <p>ID del check-in. Example: <code>42</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>rating</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="rating" data-endpoint="PUTapi-v1-check-ins--id-"
                        value="4.0"
                        data-component="body">
                    <br>
                    <p>Calificación de 1 a 5. Example: <code>4.0</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>comment</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="comment" data-endpoint="PUTapi-v1-check-ins--id-"
                        value="Un sabor potente pero equilibrado"
                        data-component="body">
                    <br>
                    <p>Comentario sobre la cerveza. Example: <code>Un sabor potente pero equilibrado</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="location_id" data-endpoint="PUTapi-v1-check-ins--id-"
                        value="3"
                        data-component="body">
                    <br>
                    <p>ID de la ubicación donde se consumió. Example: <code>3</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>photo</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="file" style="display: none"
                        name="photo" data-endpoint="PUTapi-v1-check-ins--id-"
                        value=""
                        data-component="body">
                    <br>
                    <p>Foto de la cerveza (JPG, PNG, WebP, máx 2MB). Example: <code>/tmp/phpqnfarppea22e3fFjhiP</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>serving</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="serving" data-endpoint="PUTapi-v1-check-ins--id-"
                        value="bottle"
                        data-component="body">
                    <br>
                    <p>Forma de servir (draft, bottle, can). Example: <code>bottle</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>purchase_price</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="purchase_price" data-endpoint="PUTapi-v1-check-ins--id-"
                        value="5.95"
                        data-component="body">
                    <br>
                    <p>Precio pagado por la cerveza. Example: <code>5.95</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>purchase_currency</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="purchase_currency" data-endpoint="PUTapi-v1-check-ins--id-"
                        value="EUR"
                        data-component="body">
                    <br>
                    <p>Moneda del precio (EUR, USD, etc). Example: <code>EUR</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>flavor_notes</code></b>&nbsp;&nbsp;
                    <small>string[]</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="flavor_notes[0]" data-endpoint="PUTapi-v1-check-ins--id-"
                        data-component="body">
                    <input type="text" style="display: none"
                        name="flavor_notes[1]" data-endpoint="PUTapi-v1-check-ins--id-"
                        data-component="body">
                    <br>
                    <p>Array de notas de sabor.</p>
                </div>
            </form>

            <h2 id="check-ins-DELETEapi-v1-check-ins--id-">Eliminar check-in</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Elimina un check-in del sistema.</p>

            <span id="example-requests-DELETEapi-v1-check-ins--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/check-ins/42" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/check-ins/42"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-check-ins--id-">
                <blockquote>
                    <p>Example response (204):</p>
                </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
                <blockquote>
                    <p>Example response (403):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No tienes permiso para eliminar este check-in.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el check-in solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-check-ins--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-check-ins--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-check-ins--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-check-ins--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-check-ins--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-check-ins--id-" data-method="DELETE"
                data-path="api/v1/check-ins/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-check-ins--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-check-ins--id-"
                        onclick="tryItOut('DELETEapi-v1-check-ins--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-check-ins--id-"
                        onclick="cancelTryOut('DELETEapi-v1-check-ins--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-check-ins--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/check-ins/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-check-ins--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-check-ins--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-check-ins--id-"
                        value="42"
                        data-component="url">
                    <br>
                    <p>ID del check-in. Example: <code>42</code></p>
                </div>
            </form>

            <h2 id="check-ins-GETapi-v1-users--id--check-ins">Check-ins de un usuario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene todos los check-ins realizados por un usuario específico.</p>

            <span id="example-requests-GETapi-v1-users--id--check-ins">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/users/3/check-ins?per_page=15&amp;sort=recent" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users/3/check-ins"
);

const params = {
    "per_page": "15",
    "sort": "recent",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-users--id--check-ins">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 42,
     &quot;beer&quot;: {
       &quot;id&quot;: 5,
       &quot;name&quot;: &quot;Founders Breakfast Stout&quot;,
       &quot;brewery&quot;: {
         &quot;id&quot;: 3,
         &quot;name&quot;: &quot;Founders Brewing Co.&quot;
       },
       &quot;style&quot;: {
         &quot;id&quot;: 8,
         &quot;name&quot;: &quot;Imperial Stout&quot;
       }
     },
     &quot;rating&quot;: 4.5,
     &quot;comment&quot;: &quot;Excelente balance entre caf&eacute; y chocolate&quot;,
     &quot;photo_url&quot;: &quot;https://example.com/photos/check_in_42.jpg&quot;,
     &quot;location&quot;: {
       &quot;id&quot;: 2,
       &quot;name&quot;: &quot;Beer Garden Madrid&quot;
     },
     &quot;likes_count&quot;: 15,
     &quot;comments_count&quot;: 3,
     &quot;is_liked&quot;: false,
     &quot;created_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;
   }
 ],
 &quot;user&quot;: {
   &quot;id&quot;: 3,
   &quot;name&quot;: &quot;Carlos Ruiz&quot;,
   &quot;profile_picture&quot;: &quot;https://example.com/avatars/carlos.jpg&quot;,
   &quot;check_ins_count&quot;: 42
 },
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el usuario solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-users--id--check-ins" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-users--id--check-ins"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-users--id--check-ins"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-users--id--check-ins" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-users--id--check-ins">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-users--id--check-ins" data-method="GET"
                data-path="api/v1/users/{id}/check-ins"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users--id--check-ins', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-users--id--check-ins"
                        onclick="tryItOut('GETapi-v1-users--id--check-ins');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-users--id--check-ins"
                        onclick="cancelTryOut('GETapi-v1-users--id--check-ins');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-users--id--check-ins"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/users/{id}/check-ins</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-users--id--check-ins"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-users--id--check-ins"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-users--id--check-ins"
                        value="3"
                        data-component="url">
                    <br>
                    <p>ID del usuario. Example: <code>3</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-users--id--check-ins"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-users--id--check-ins"
                        value="recent"
                        data-component="query">
                    <br>
                    <p>Ordenar por: recent, rating. Example: <code>recent</code></p>
                </div>
            </form>

            <h2 id="check-ins-GETapi-v1-beers--id--check-ins">Check-ins de una cerveza</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene todos los check-ins realizados para una cerveza específica.</p>

            <span id="example-requests-GETapi-v1-beers--id--check-ins">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/beers/5/check-ins?per_page=15&amp;sort=likes" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beers/5/check-ins"
);

const params = {
    "per_page": "15",
    "sort": "likes",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-beers--id--check-ins">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 42,
     &quot;user&quot;: {
       &quot;id&quot;: 3,
       &quot;name&quot;: &quot;Carlos Ruiz&quot;,
       &quot;profile_picture&quot;: &quot;https://example.com/avatars/carlos.jpg&quot;
     },
     &quot;rating&quot;: 4.5,
     &quot;comment&quot;: &quot;Excelente balance entre caf&eacute; y chocolate&quot;,
     &quot;photo_url&quot;: &quot;https://example.com/photos/check_in_42.jpg&quot;,
     &quot;location&quot;: {
       &quot;id&quot;: 2,
       &quot;name&quot;: &quot;Beer Garden Madrid&quot;
     },
     &quot;likes_count&quot;: 15,
     &quot;comments_count&quot;: 3,
     &quot;is_liked&quot;: false,
     &quot;created_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;
   }
 ],
 &quot;beer&quot;: {
   &quot;id&quot;: 5,
   &quot;name&quot;: &quot;Founders Breakfast Stout&quot;,
   &quot;brewery&quot;: {
     &quot;id&quot;: 3,
     &quot;name&quot;: &quot;Founders Brewing Co.&quot;
   },
   &quot;style&quot;: {
     &quot;id&quot;: 8,
     &quot;name&quot;: &quot;Imperial Stout&quot;
   },
   &quot;check_ins_count&quot;: 87,
   &quot;avg_rating&quot;: 4.3
 },
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la cerveza solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-beers--id--check-ins" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-beers--id--check-ins"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-beers--id--check-ins"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-beers--id--check-ins" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-beers--id--check-ins">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-beers--id--check-ins" data-method="GET"
                data-path="api/v1/beers/{id}/check-ins"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-beers--id--check-ins', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-beers--id--check-ins"
                        onclick="tryItOut('GETapi-v1-beers--id--check-ins');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-beers--id--check-ins"
                        onclick="cancelTryOut('GETapi-v1-beers--id--check-ins');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-beers--id--check-ins"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/beers/{id}/check-ins</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-beers--id--check-ins"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-beers--id--check-ins"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-beers--id--check-ins"
                        value="5"
                        data-component="url">
                    <br>
                    <p>ID de la cerveza. Example: <code>5</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-beers--id--check-ins"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-beers--id--check-ins"
                        value="likes"
                        data-component="query">
                    <br>
                    <p>Ordenar por: recent, rating, likes. Example: <code>likes</code></p>
                </div>
            </form>

            <h1 id="comentarios">Comentarios</h1>

            <p>APIs para gestionar comentarios en los check-ins de cervezas</p>

            <h2 id="comentarios-POSTapi-v1-check-ins--id--comments">Crear comentario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Añade un nuevo comentario a un check-in.</p>

            <span id="example-requests-POSTapi-v1-check-ins--id--comments">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/check-ins/42/comments" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"content\": \"Totalmente de acuerdo, una maravilla de cerveza.\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/check-ins/42/comments"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "content": "Totalmente de acuerdo, una maravilla de cerveza."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-check-ins--id--comments">
                <blockquote>
                    <p>Example response (201):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 105,
        &quot;user&quot;: {
            &quot;id&quot;: 7,
            &quot;name&quot;: &quot;Mar&iacute;a L&oacute;pez&quot;,
            &quot;profile_picture&quot;: &quot;https://example.com/avatars/maria.jpg&quot;
        },
        &quot;content&quot;: &quot;Totalmente de acuerdo, una maravilla de cerveza.&quot;,
        &quot;created_at&quot;: &quot;2023-04-18T19:15:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T19:15:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el check-in solicitado.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;El comentario no puede estar vac&iacute;o.&quot;,
    &quot;errors&quot;: {
        &quot;content&quot;: [
            &quot;El contenido es obligatorio.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-check-ins--id--comments" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-check-ins--id--comments"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-check-ins--id--comments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-check-ins--id--comments" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-check-ins--id--comments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-check-ins--id--comments" data-method="POST"
                data-path="api/v1/check-ins/{id}/comments"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-check-ins--id--comments', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-check-ins--id--comments"
                        onclick="tryItOut('POSTapi-v1-check-ins--id--comments');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-check-ins--id--comments"
                        onclick="cancelTryOut('POSTapi-v1-check-ins--id--comments');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-check-ins--id--comments"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/check-ins/{id}/comments</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-check-ins--id--comments"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-check-ins--id--comments"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="POSTapi-v1-check-ins--id--comments"
                        value="42"
                        data-component="url">
                    <br>
                    <p>ID del check-in. Example: <code>42</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>content</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="content" data-endpoint="POSTapi-v1-check-ins--id--comments"
                        value="Totalmente de acuerdo, una maravilla de cerveza."
                        data-component="body">
                    <br>
                    <p>Contenido del comentario. Example: <code>Totalmente de acuerdo, una maravilla de cerveza.</code></p>
                </div>
            </form>

            <h2 id="comentarios-PUTapi-v1-comments--id-">Actualizar comentario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Actualiza el contenido de un comentario existente.</p>

            <span id="example-requests-PUTapi-v1-comments--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/comments/105" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"content\": \"He vuelto a probarla y sigue siendo excelente.\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/comments/105"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "content": "He vuelto a probarla y sigue siendo excelente."
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-comments--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 105,
        &quot;user&quot;: {
            &quot;id&quot;: 7,
            &quot;name&quot;: &quot;Mar&iacute;a L&oacute;pez&quot;,
            &quot;profile_picture&quot;: &quot;https://example.com/avatars/maria.jpg&quot;
        },
        &quot;content&quot;: &quot;He vuelto a probarla y sigue siendo excelente.&quot;,
        &quot;created_at&quot;: &quot;2023-04-18T19:15:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T19:45:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (403):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No tienes permiso para editar este comentario.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el comentario solicitado.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;El comentario no puede estar vac&iacute;o.&quot;,
    &quot;errors&quot;: {
        &quot;content&quot;: [
            &quot;El contenido es obligatorio.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-comments--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-comments--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-comments--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-comments--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-comments--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-comments--id-" data-method="PUT"
                data-path="api/v1/comments/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-comments--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-comments--id-"
                        onclick="tryItOut('PUTapi-v1-comments--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-comments--id-"
                        onclick="cancelTryOut('PUTapi-v1-comments--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-comments--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/comments/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-comments--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-comments--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="PUTapi-v1-comments--id-"
                        value="105"
                        data-component="url">
                    <br>
                    <p>ID del comentario. Example: <code>105</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>content</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="content" data-endpoint="PUTapi-v1-comments--id-"
                        value="He vuelto a probarla y sigue siendo excelente."
                        data-component="body">
                    <br>
                    <p>Nuevo contenido del comentario. Example: <code>He vuelto a probarla y sigue siendo excelente.</code></p>
                </div>
            </form>

            <h2 id="comentarios-DELETEapi-v1-comments--id-">Eliminar comentario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Elimina un comentario del sistema.</p>

            <span id="example-requests-DELETEapi-v1-comments--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/comments/105" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/comments/105"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-comments--id-">
                <blockquote>
                    <p>Example response (204):</p>
                </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
                <blockquote>
                    <p>Example response (403):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No tienes permiso para eliminar este comentario.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el comentario solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-comments--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-comments--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-comments--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-comments--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-comments--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-comments--id-" data-method="DELETE"
                data-path="api/v1/comments/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-comments--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-comments--id-"
                        onclick="tryItOut('DELETEapi-v1-comments--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-comments--id-"
                        onclick="cancelTryOut('DELETEapi-v1-comments--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-comments--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/comments/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-comments--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-comments--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-comments--id-"
                        value="105"
                        data-component="url">
                    <br>
                    <p>ID del comentario. Example: <code>105</code></p>
                </div>
            </form>

            <h1 id="endpoints">Endpoints</h1>



            <h2 id="endpoints-GETapi-v1-status">GET api/v1/status</h2>

            <p>
            </p>



            <span id="example-requests-GETapi-v1-status">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/status" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/status"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-status">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <details class="annotation">
                    <summary style="cursor: pointer;">
                        <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
                    </summary>
                    <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre>
                </details>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;online&quot;,
    &quot;version&quot;: &quot;1.0.0&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-status" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-status"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-status"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-status" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-status">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-status" data-method="GET"
                data-path="api/v1/status"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-status', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-status"
                        onclick="tryItOut('GETapi-v1-status');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-status"
                        onclick="cancelTryOut('GETapi-v1-status');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-status"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/status</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-status"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-status"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h1 id="estilos-de-cerveza">Estilos de Cerveza</h1>

            <p>APIs para gestionar estilos de cerveza</p>

            <h2 id="estilos-de-cerveza-GETapi-v1-beer-styles">Listar estilos de cerveza</h2>

            <p>
            </p>

            <p>Obtiene un listado paginado de estilos de cerveza con opciones de filtrado y ordenamiento.</p>

            <span id="example-requests-GETapi-v1-beer-styles">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/beer-styles?name=IPA&amp;sort=name&amp;order=asc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"sort\": \"created_at\",
    \"order\": \"asc\",
    \"per_page\": 22
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beer-styles"
);

const params = {
    "name": "IPA",
    "sort": "name",
    "order": "asc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "sort": "created_at",
    "order": "asc",
    "per_page": 22
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-beer-styles">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 1,
     &quot;name&quot;: &quot;IPA (India Pale Ale)&quot;,
     &quot;description&quot;: &quot;Cerveza p&aacute;lida y lupulada con un amargor caracter&iacute;stico&quot;,
     &quot;beers_count&quot;: 12,
     &quot;created_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;,
     &quot;updated_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-beer-styles" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-beer-styles"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-beer-styles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-beer-styles" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-beer-styles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-beer-styles" data-method="GET"
                data-path="api/v1/beer-styles"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-beer-styles', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-beer-styles"
                        onclick="tryItOut('GETapi-v1-beer-styles');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-beer-styles"
                        onclick="cancelTryOut('GETapi-v1-beer-styles');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-beer-styles"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/beer-styles</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-beer-styles"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-beer-styles"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-beer-styles"
                        value="IPA"
                        data-component="query">
                    <br>
                    <p>Filtrar por nombre. Example: <code>IPA</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-beer-styles"
                        value="name"
                        data-component="query">
                    <br>
                    <p>Ordenar por: name, created_at. Example: <code>name</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-beer-styles"
                        value="asc"
                        data-component="query">
                    <br>
                    <p>Dirección: asc, desc. Example: <code>asc</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-beer-styles"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-beer-styles"
                        value="b"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>b</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-beer-styles"
                        value="created_at"
                        data-component="body">
                    <br>
                    <p>Example: <code>created_at</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>name</code></li>
                        <li><code>created_at</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-beer-styles"
                        value="asc"
                        data-component="body">
                    <br>
                    <p>Example: <code>asc</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>asc</code></li>
                        <li><code>desc</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-beer-styles"
                        value="22"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>22</code></p>
                </div>
            </form>

            <h2 id="estilos-de-cerveza-GETapi-v1-beer-styles--id-">Ver estilo de cerveza</h2>

            <p>
            </p>

            <p>Muestra información detallada de un estilo de cerveza específico.</p>

            <span id="example-requests-GETapi-v1-beer-styles--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/beer-styles/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beer-styles/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-beer-styles--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;IPA (India Pale Ale)&quot;,
        &quot;description&quot;: &quot;Cerveza p&aacute;lida y lupulada con un amargor caracter&iacute;stico&quot;,
        &quot;beers_count&quot;: 12,
        &quot;created_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el estilo de cerveza solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-beer-styles--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-beer-styles--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-beer-styles--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-beer-styles--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-beer-styles--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-beer-styles--id-" data-method="GET"
                data-path="api/v1/beer-styles/{id}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-beer-styles--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-beer-styles--id-"
                        onclick="tryItOut('GETapi-v1-beer-styles--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-beer-styles--id-"
                        onclick="cancelTryOut('GETapi-v1-beer-styles--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-beer-styles--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/beer-styles/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-beer-styles--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-beer-styles--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-beer-styles--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del estilo de cerveza. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="estilos-de-cerveza-POSTapi-v1-beer-styles">Crear estilo de cerveza</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Crea un nuevo estilo de cerveza en el sistema.</p>

            <span id="example-requests-POSTapi-v1-beer-styles">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/beer-styles" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"American Pale Ale\",
    \"description\": \"Similar a la IPA pero menos amarga\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beer-styles"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "American Pale Ale",
    "description": "Similar a la IPA pero menos amarga"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-beer-styles">
                <blockquote>
                    <p>Example response (201):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 8,
        &quot;name&quot;: &quot;American Pale Ale&quot;,
        &quot;description&quot;: &quot;Similar a la IPA pero menos amarga&quot;,
        &quot;beers_count&quot;: 0,
        &quot;created_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Los datos proporcionados no son v&aacute;lidos.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;El campo nombre es obligatorio.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-beer-styles" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-beer-styles"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-beer-styles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-beer-styles" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-beer-styles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-beer-styles" data-method="POST"
                data-path="api/v1/beer-styles"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-beer-styles', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-beer-styles"
                        onclick="tryItOut('POSTapi-v1-beer-styles');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-beer-styles"
                        onclick="cancelTryOut('POSTapi-v1-beer-styles');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-beer-styles"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/beer-styles</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-beer-styles"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-beer-styles"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="POSTapi-v1-beer-styles"
                        value="American Pale Ale"
                        data-component="body">
                    <br>
                    <p>Nombre del estilo de cerveza. Example: <code>American Pale Ale</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="description" data-endpoint="POSTapi-v1-beer-styles"
                        value="Similar a la IPA pero menos amarga"
                        data-component="body">
                    <br>
                    <p>Descripción del estilo de cerveza. Example: <code>Similar a la IPA pero menos amarga</code></p>
                </div>
            </form>

            <h2 id="estilos-de-cerveza-PUTapi-v1-beer-styles--id-">Actualizar estilo de cerveza</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Actualiza la información de un estilo de cerveza existente.</p>

            <span id="example-requests-PUTapi-v1-beer-styles--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/beer-styles/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"IPA - India Pale Ale\",
    \"description\": \"Cerveza con alto contenido en lúpulo\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beer-styles/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "IPA - India Pale Ale",
    "description": "Cerveza con alto contenido en lúpulo"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-beer-styles--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;IPA - India Pale Ale&quot;,
        &quot;description&quot;: &quot;Cerveza con alto contenido en l&uacute;pulo&quot;,
        &quot;beers_count&quot;: 12,
        &quot;created_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el estilo de cerveza solicitado.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Los datos proporcionados no son v&aacute;lidos.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;El nombre ya est&aacute; en uso.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-beer-styles--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-beer-styles--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-beer-styles--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-beer-styles--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-beer-styles--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-beer-styles--id-" data-method="PUT"
                data-path="api/v1/beer-styles/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-beer-styles--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-beer-styles--id-"
                        onclick="tryItOut('PUTapi-v1-beer-styles--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-beer-styles--id-"
                        onclick="cancelTryOut('PUTapi-v1-beer-styles--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-beer-styles--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/beer-styles/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-beer-styles--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-beer-styles--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="PUTapi-v1-beer-styles--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del estilo de cerveza. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="PUTapi-v1-beer-styles--id-"
                        value="IPA - India Pale Ale"
                        data-component="body">
                    <br>
                    <p>Nombre del estilo de cerveza. Example: <code>IPA - India Pale Ale</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="description" data-endpoint="PUTapi-v1-beer-styles--id-"
                        value="Cerveza con alto contenido en lúpulo"
                        data-component="body">
                    <br>
                    <p>Descripción del estilo de cerveza. Example: <code>Cerveza con alto contenido en lúpulo</code></p>
                </div>
            </form>

            <h2 id="estilos-de-cerveza-DELETEapi-v1-beer-styles--id-">Eliminar estilo de cerveza</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Elimina un estilo de cerveza del sistema.</p>

            <span id="example-requests-DELETEapi-v1-beer-styles--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/beer-styles/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beer-styles/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-beer-styles--id-">
                <blockquote>
                    <p>Example response (204):</p>
                </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el estilo de cerveza solicitado.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (409):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se puede eliminar este estilo porque tiene cervezas asociadas.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-beer-styles--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-beer-styles--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-beer-styles--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-beer-styles--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-beer-styles--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-beer-styles--id-" data-method="DELETE"
                data-path="api/v1/beer-styles/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-beer-styles--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-beer-styles--id-"
                        onclick="tryItOut('DELETEapi-v1-beer-styles--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-beer-styles--id-"
                        onclick="cancelTryOut('DELETEapi-v1-beer-styles--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-beer-styles--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/beer-styles/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-beer-styles--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-beer-styles--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-beer-styles--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del estilo de cerveza. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="estilos-de-cerveza-GETapi-v1-beer-styles--id--beers">Cervezas de un estilo</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene todas las cervezas que pertenecen a un estilo específico.</p>

            <span id="example-requests-GETapi-v1-beer-styles--id--beers">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/beer-styles/1/beers?sort=rating&amp;order=desc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/beer-styles/1/beers"
);

const params = {
    "sort": "rating",
    "order": "desc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-beer-styles--id--beers">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 3,
     &quot;name&quot;: &quot;Founders All Day IPA&quot;,
     &quot;brewery&quot;: {
       &quot;id&quot;: 5,
       &quot;name&quot;: &quot;Founders Brewing Co.&quot;
     },
     &quot;abv&quot;: 4.7,
     &quot;ibu&quot;: 42,
     &quot;description&quot;: &quot;Session IPA de sabor intenso pero bajo alcohol&quot;,
     &quot;image_url&quot;: &quot;https://example.com/beers/founders_allday.png&quot;,
     &quot;rating_avg&quot;: 4.2,
     &quot;check_ins_count&quot;: 87,
     &quot;is_favorite&quot;: false
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...},
 &quot;style&quot;: {
   &quot;id&quot;: 1,
   &quot;name&quot;: &quot;IPA (India Pale Ale)&quot;,
   &quot;description&quot;: &quot;Cerveza p&aacute;lida y lupulada con un amargor caracter&iacute;stico&quot;
 }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el estilo de cerveza solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-beer-styles--id--beers" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-beer-styles--id--beers"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-beer-styles--id--beers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-beer-styles--id--beers" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-beer-styles--id--beers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-beer-styles--id--beers" data-method="GET"
                data-path="api/v1/beer-styles/{id}/beers"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-beer-styles--id--beers', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-beer-styles--id--beers"
                        onclick="tryItOut('GETapi-v1-beer-styles--id--beers');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-beer-styles--id--beers"
                        onclick="cancelTryOut('GETapi-v1-beer-styles--id--beers');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-beer-styles--id--beers"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/beer-styles/{id}/beers</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-beer-styles--id--beers"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-beer-styles--id--beers"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-beer-styles--id--beers"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del estilo de cerveza. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-beer-styles--id--beers"
                        value="rating"
                        data-component="query">
                    <br>
                    <p>Ordenar por: name, abv, ibu, rating, created_at. Example: <code>rating</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-beer-styles--id--beers"
                        value="desc"
                        data-component="query">
                    <br>
                    <p>Dirección: asc, desc. Example: <code>desc</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-beer-styles--id--beers"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
            </form>

            <h1 id="feed-de-actividad">Feed de Actividad</h1>

            <p>APIs para obtener diferentes feeds de actividad en la plataforma</p>

            <h2 id="feed-de-actividad-GETapi-v1-feed">Feed principal</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene un feed general de actividad reciente en la plataforma.</p>

            <span id="example-requests-GETapi-v1-feed">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/feed?type=beer&amp;time_range=week&amp;min_rating=3.5&amp;location_id=1&amp;sort=popular&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"beer\",
    \"time_range\": \"month\",
    \"min_rating\": 1,
    \"location_id\": 16,
    \"sort\": \"popular\",
    \"per_page\": 22
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/feed"
);

const params = {
    "type": "beer",
    "time_range": "week",
    "min_rating": "3.5",
    "location_id": "1",
    "sort": "popular",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "beer",
    "time_range": "month",
    "min_rating": 1,
    "location_id": 16,
    "sort": "popular",
    "per_page": 22
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-feed">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 42,
     &quot;user&quot;: {
       &quot;id&quot;: 3,
       &quot;name&quot;: &quot;Carlos Ruiz&quot;,
       &quot;profile_picture&quot;: &quot;https://example.com/avatars/carlos.jpg&quot;
     },
     &quot;beer&quot;: {
       &quot;id&quot;: 5,
       &quot;name&quot;: &quot;Founders Breakfast Stout&quot;,
       &quot;brewery&quot;: {
         &quot;id&quot;: 3,
         &quot;name&quot;: &quot;Founders Brewing Co.&quot;
       }
     },
     &quot;rating&quot;: 4.5,
     &quot;comment&quot;: &quot;Excelente balance entre caf&eacute; y chocolate&quot;,
     &quot;photo_url&quot;: &quot;https://example.com/photos/check_in_42.jpg&quot;,
     &quot;location&quot;: {
       &quot;id&quot;: 2,
       &quot;name&quot;: &quot;Beer Garden Madrid&quot;
     },
     &quot;likes_count&quot;: 15,
     &quot;comments_count&quot;: 3,
     &quot;is_liked&quot;: false,
     &quot;created_at&quot;: &quot;2023-04-18T18:30:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-feed" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-feed"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-feed"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-feed" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-feed">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-feed" data-method="GET"
                data-path="api/v1/feed"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-feed', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-feed"
                        onclick="tryItOut('GETapi-v1-feed');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-feed"
                        onclick="cancelTryOut('GETapi-v1-feed');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-feed"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/feed</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-feed"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-feed"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-feed"
                        value="beer"
                        data-component="query">
                    <br>
                    <p>Filtrar por tipo de check-in (beer, brewery, style). Example: <code>beer</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>time_range</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="time_range" data-endpoint="GETapi-v1-feed"
                        value="week"
                        data-component="query">
                    <br>
                    <p>Rango de tiempo (today, week, month, all). Example: <code>week</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-feed"
                        value="3.5"
                        data-component="query">
                    <br>
                    <p>Calificación mínima para filtrar (0-5). Example: <code>3.5</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="location_id" data-endpoint="GETapi-v1-feed"
                        value="1"
                        data-component="query">
                    <br>
                    <p>ID de la ubicación para filtrar. Example: <code>1</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-feed"
                        value="popular"
                        data-component="query">
                    <br>
                    <p>Ordenar por: recent, popular. Example: <code>popular</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-feed"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-feed"
                        value="beer"
                        data-component="body">
                    <br>
                    <p>Example: <code>beer</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>beer</code></li>
                        <li><code>brewery</code></li>
                        <li><code>style</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>time_range</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="time_range" data-endpoint="GETapi-v1-feed"
                        value="month"
                        data-component="body">
                    <br>
                    <p>Example: <code>month</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>today</code></li>
                        <li><code>week</code></li>
                        <li><code>month</code></li>
                        <li><code>all</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-feed"
                        value="1"
                        data-component="body">
                    <br>
                    <p>Must be at least 0. Must not be greater than 5. Example: <code>1</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>location_id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="location_id" data-endpoint="GETapi-v1-feed"
                        value="16"
                        data-component="body">
                    <br>
                    <p>The <code>id</code> of an existing record in the locations table. Example: <code>16</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-feed"
                        value="popular"
                        data-component="body">
                    <br>
                    <p>Example: <code>popular</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>recent</code></li>
                        <li><code>popular</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-feed"
                        value="22"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>22</code></p>
                </div>
            </form>

            <h2 id="feed-de-actividad-GETapi-v1-feed-friends">Feed de amigos</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene un feed de actividad de los usuarios que sigues.</p>

            <span id="example-requests-GETapi-v1-feed-friends">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/feed/friends?type=beer&amp;time_range=week&amp;min_rating=3.5&amp;sort=recent&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"style\",
    \"time_range\": \"week\",
    \"min_rating\": 1,
    \"sort\": \"recent\",
    \"per_page\": 22
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/feed/friends"
);

const params = {
    "type": "beer",
    "time_range": "week",
    "min_rating": "3.5",
    "sort": "recent",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "style",
    "time_range": "week",
    "min_rating": 1,
    "sort": "recent",
    "per_page": 22
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-feed-friends">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 56,
     &quot;user&quot;: {
       &quot;id&quot;: 7,
       &quot;name&quot;: &quot;Mar&iacute;a L&oacute;pez&quot;,
       &quot;profile_picture&quot;: &quot;https://example.com/avatars/maria.jpg&quot;
     },
     &quot;beer&quot;: {
       &quot;id&quot;: 12,
       &quot;name&quot;: &quot;La Cibeles Rubia&quot;,
       &quot;brewery&quot;: {
         &quot;id&quot;: 8,
         &quot;name&quot;: &quot;Cervezas La Cibeles&quot;
       }
     },
     &quot;rating&quot;: 4.0,
     &quot;comment&quot;: &quot;Muy refrescante. Ideal para el verano.&quot;,
     &quot;photo_url&quot;: null,
     &quot;location&quot;: {
       &quot;id&quot;: 5,
       &quot;name&quot;: &quot;Cervecer&iacute;a Internacional&quot;
     },
     &quot;likes_count&quot;: 8,
     &quot;comments_count&quot;: 2,
     &quot;is_liked&quot;: true,
     &quot;created_at&quot;: &quot;2023-04-17T14:20:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-feed-friends" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-feed-friends"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-feed-friends"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-feed-friends" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-feed-friends">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-feed-friends" data-method="GET"
                data-path="api/v1/feed/friends"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-feed-friends', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-feed-friends"
                        onclick="tryItOut('GETapi-v1-feed-friends');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-feed-friends"
                        onclick="cancelTryOut('GETapi-v1-feed-friends');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-feed-friends"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/feed/friends</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-feed-friends"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-feed-friends"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-feed-friends"
                        value="beer"
                        data-component="query">
                    <br>
                    <p>Filtrar por tipo de check-in (beer, brewery, style). Example: <code>beer</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>time_range</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="time_range" data-endpoint="GETapi-v1-feed-friends"
                        value="week"
                        data-component="query">
                    <br>
                    <p>Rango de tiempo (today, week, month, all). Example: <code>week</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-feed-friends"
                        value="3.5"
                        data-component="query">
                    <br>
                    <p>Calificación mínima para filtrar (0-5). Example: <code>3.5</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-feed-friends"
                        value="recent"
                        data-component="query">
                    <br>
                    <p>Ordenar por: recent, popular. Example: <code>recent</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-feed-friends"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-feed-friends"
                        value="style"
                        data-component="body">
                    <br>
                    <p>Example: <code>style</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>beer</code></li>
                        <li><code>brewery</code></li>
                        <li><code>style</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>time_range</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="time_range" data-endpoint="GETapi-v1-feed-friends"
                        value="week"
                        data-component="body">
                    <br>
                    <p>Example: <code>week</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>today</code></li>
                        <li><code>week</code></li>
                        <li><code>month</code></li>
                        <li><code>all</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-feed-friends"
                        value="1"
                        data-component="body">
                    <br>
                    <p>Must be at least 0. Must not be greater than 5. Example: <code>1</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-feed-friends"
                        value="recent"
                        data-component="body">
                    <br>
                    <p>Example: <code>recent</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>recent</code></li>
                        <li><code>popular</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-feed-friends"
                        value="22"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>22</code></p>
                </div>
            </form>

            <h2 id="feed-de-actividad-GETapi-v1-feed-popular">Feed popular</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene un feed de los check-ins más populares en la plataforma.</p>

            <span id="example-requests-GETapi-v1-feed-popular">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/feed/popular?type=beer&amp;time_range=week&amp;min_rating=4&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"brewery\",
    \"time_range\": \"month\",
    \"min_rating\": 1,
    \"per_page\": 22
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/feed/popular"
);

const params = {
    "type": "beer",
    "time_range": "week",
    "min_rating": "4",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "brewery",
    "time_range": "month",
    "min_rating": 1,
    "per_page": 22
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-feed-popular">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 78,
     &quot;user&quot;: {
       &quot;id&quot;: 4,
       &quot;name&quot;: &quot;Ana Mart&iacute;nez&quot;,
       &quot;profile_picture&quot;: &quot;https://example.com/avatars/ana.jpg&quot;
     },
     &quot;beer&quot;: {
       &quot;id&quot;: 22,
       &quot;name&quot;: &quot;Westvleteren 12&quot;,
       &quot;brewery&quot;: {
         &quot;id&quot;: 15,
         &quot;name&quot;: &quot;Brouwerij Westvleteren&quot;
       }
     },
     &quot;rating&quot;: 5.0,
     &quot;comment&quot;: &quot;La mejor cerveza trapista. Una experiencia &uacute;nica.&quot;,
     &quot;photo_url&quot;: &quot;https://example.com/photos/check_in_78.jpg&quot;,
     &quot;location&quot;: null,
     &quot;likes_count&quot;: 45,
     &quot;comments_count&quot;: 12,
     &quot;is_liked&quot;: false,
     &quot;created_at&quot;: &quot;2023-04-12T20:15:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-feed-popular" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-feed-popular"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-feed-popular"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-feed-popular" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-feed-popular">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-feed-popular" data-method="GET"
                data-path="api/v1/feed/popular"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-feed-popular', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-feed-popular"
                        onclick="tryItOut('GETapi-v1-feed-popular');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-feed-popular"
                        onclick="cancelTryOut('GETapi-v1-feed-popular');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-feed-popular"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/feed/popular</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-feed-popular"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-feed-popular"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-feed-popular"
                        value="beer"
                        data-component="query">
                    <br>
                    <p>Filtrar por tipo de check-in (beer, brewery, style). Example: <code>beer</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>time_range</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="time_range" data-endpoint="GETapi-v1-feed-popular"
                        value="week"
                        data-component="query">
                    <br>
                    <p>Rango de tiempo (today, week, month, all). Example: <code>week</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-feed-popular"
                        value="4"
                        data-component="query">
                    <br>
                    <p>Calificación mínima para filtrar (0-5). Example: <code>4</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-feed-popular"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-feed-popular"
                        value="brewery"
                        data-component="body">
                    <br>
                    <p>Example: <code>brewery</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>beer</code></li>
                        <li><code>brewery</code></li>
                        <li><code>style</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>time_range</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="time_range" data-endpoint="GETapi-v1-feed-popular"
                        value="month"
                        data-component="body">
                    <br>
                    <p>Example: <code>month</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>today</code></li>
                        <li><code>week</code></li>
                        <li><code>month</code></li>
                        <li><code>all</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>min_rating</code></b>&nbsp;&nbsp;
                    <small>number</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="min_rating" data-endpoint="GETapi-v1-feed-popular"
                        value="1"
                        data-component="body">
                    <br>
                    <p>Must be at least 0. Must not be greater than 5. Example: <code>1</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-feed-popular"
                        value="22"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>22</code></p>
                </div>
            </form>

            <h1 id="gestion-de-reportes">Gestión de Reportes</h1>

            <p>APIs para gestionar reportes de contenido inapropiado (solo administradores)</p>

            <h2 id="gestion-de-reportes-GETapi-v1-reports">Listar reportes</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene un listado paginado de reportes con opciones de filtrado.</p>

            <span id="example-requests-GETapi-v1-reports">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/reports?status=pending&amp;type=comment&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"status\": \"actioned\",
    \"type\": \"architecto\",
    \"per_page\": 22
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/reports"
);

const params = {
    "status": "pending",
    "type": "comment",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": "actioned",
    "type": "architecto",
    "per_page": 22
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-reports">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;data&quot;: [
    {
      &quot;id&quot;: 1,
      &quot;reason&quot;: &quot;offensive&quot;,
      &quot;details&quot;: &quot;Contiene lenguaje ofensivo e insultos.&quot;,
      &quot;status&quot;: &quot;pending&quot;,
      &quot;reporter&quot;: {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Juan P&eacute;rez&quot;
      },
      &quot;content_type&quot;: &quot;comment&quot;,
      &quot;content_id&quot;: 105,
      &quot;created_at&quot;: &quot;2023-04-18T19:15:00.000000Z&quot;
    }
  ],
  &quot;links&quot;: {...},
  &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-reports" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-reports"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-reports"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-reports" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-reports">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-reports" data-method="GET"
                data-path="api/v1/reports"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-reports', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-reports"
                        onclick="tryItOut('GETapi-v1-reports');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-reports"
                        onclick="cancelTryOut('GETapi-v1-reports');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-reports"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/reports</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-reports"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-reports"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="status" data-endpoint="GETapi-v1-reports"
                        value="pending"
                        data-component="query">
                    <br>
                    <p>Filtrar por estado (pending, reviewed, rejected, actioned). Example: <code>pending</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-reports"
                        value="comment"
                        data-component="query">
                    <br>
                    <p>Filtrar por tipo de contenido (comment, check_in, etc.). Example: <code>comment</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-reports"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="status" data-endpoint="GETapi-v1-reports"
                        value="actioned"
                        data-component="body">
                    <br>
                    <p>Example: <code>actioned</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>pending</code></li>
                        <li><code>reviewed</code></li>
                        <li><code>rejected</code></li>
                        <li><code>actioned</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-reports"
                        value="architecto"
                        data-component="body">
                    <br>
                    <p>Example: <code>architecto</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-reports"
                        value="22"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>22</code></p>
                </div>
            </form>

            <h2 id="gestion-de-reportes-GETapi-v1-reports--id-">Obtener detalles de un reporte</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene información detallada de un reporte específico.</p>

            <span id="example-requests-GETapi-v1-reports--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/reports/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/reports/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-reports--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;reason&quot;: &quot;offensive&quot;,
        &quot;details&quot;: &quot;Contiene lenguaje ofensivo e insultos.&quot;,
        &quot;status&quot;: &quot;pending&quot;,
        &quot;reporter&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Juan P&eacute;rez&quot;
        },
        &quot;content_type&quot;: &quot;comment&quot;,
        &quot;content_id&quot;: 105,
        &quot;created_at&quot;: &quot;2023-04-18T19:15:00.000000Z&quot;,
        &quot;admin_notes&quot;: null
    },
    &quot;reported_content&quot;: {
        &quot;id&quot;: 105,
        &quot;content&quot;: &quot;Este es un comentario ofensivo que ha sido reportado.&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Ana Garc&iacute;a&quot;
        }
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el reporte solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-reports--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-reports--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-reports--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-reports--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-reports--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-reports--id-" data-method="GET"
                data-path="api/v1/reports/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-reports--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-reports--id-"
                        onclick="tryItOut('GETapi-v1-reports--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-reports--id-"
                        onclick="cancelTryOut('GETapi-v1-reports--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-reports--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/reports/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-reports--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-reports--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-reports--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del reporte. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="gestion-de-reportes-PUTapi-v1-reports--id-">Actualizar estado de un reporte</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Actualiza el estado de un reporte y opcionalmente agrega notas administrativas.</p>

            <span id="example-requests-PUTapi-v1-reports--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/reports/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"status\": \"reviewed\",
    \"admin_notes\": \"Contenido revisado y advertencia enviada al usuario.\"
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/reports/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": "reviewed",
    "admin_notes": "Contenido revisado y advertencia enviada al usuario."
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-reports--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Reporte actualizado correctamente.&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;status&quot;: &quot;reviewed&quot;,
        &quot;resolved_at&quot;: &quot;2023-04-19T10:30:00.000000Z&quot;,
        &quot;admin_notes&quot;: &quot;Contenido revisado y advertencia enviada al usuario.&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el reporte solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-reports--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-reports--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-reports--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-reports--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-reports--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-reports--id-" data-method="PUT"
                data-path="api/v1/reports/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-reports--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-reports--id-"
                        onclick="tryItOut('PUTapi-v1-reports--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-reports--id-"
                        onclick="cancelTryOut('PUTapi-v1-reports--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-reports--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/reports/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-reports--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-reports--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="PUTapi-v1-reports--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del reporte. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="status" data-endpoint="PUTapi-v1-reports--id-"
                        value="reviewed"
                        data-component="body">
                    <br>
                    <p>Nuevo estado (reviewed, rejected, actioned). Example: <code>reviewed</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>admin_notes</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="admin_notes" data-endpoint="PUTapi-v1-reports--id-"
                        value="Contenido revisado y advertencia enviada al usuario."
                        data-component="body">
                    <br>
                    <p>Notas administrativas sobre la resolución. Example: <code>Contenido revisado y advertencia enviada al usuario.</code></p>
                </div>
            </form>

            <h1 id="likes">Likes</h1>

            <p>APIs para gestionar likes en los check-ins</p>

            <h2 id="likes-POSTapi-v1-check-ins--id--like">Dar like a un check-in</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Registra un like del usuario autenticado en un check-in específico.</p>

            <span id="example-requests-POSTapi-v1-check-ins--id--like">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/check-ins/42/like" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/check-ins/42/like"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-check-ins--id--like">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Like registrado correctamente.&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 105,
        &quot;user&quot;: {
            &quot;id&quot;: 7,
            &quot;name&quot;: &quot;Mar&iacute;a L&oacute;pez&quot;,
            &quot;profile_picture&quot;: &quot;https://example.com/avatars/maria.jpg&quot;
        },
        &quot;check_in_id&quot;: 42,
        &quot;created_at&quot;: &quot;2023-04-19T15:30:00.000000Z&quot;
    },
    &quot;likes_count&quot;: 16
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el check-in solicitado.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (409):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Ya has dado like a este check-in.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-check-ins--id--like" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-check-ins--id--like"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-check-ins--id--like"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-check-ins--id--like" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-check-ins--id--like">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-check-ins--id--like" data-method="POST"
                data-path="api/v1/check-ins/{id}/like"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-check-ins--id--like', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-check-ins--id--like"
                        onclick="tryItOut('POSTapi-v1-check-ins--id--like');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-check-ins--id--like"
                        onclick="cancelTryOut('POSTapi-v1-check-ins--id--like');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-check-ins--id--like"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/check-ins/{id}/like</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-check-ins--id--like"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-check-ins--id--like"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="POSTapi-v1-check-ins--id--like"
                        value="42"
                        data-component="url">
                    <br>
                    <p>ID del check-in. Example: <code>42</code></p>
                </div>
            </form>

            <h2 id="likes-DELETEapi-v1-check-ins--id--unlike">Quitar like de un check-in</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Elimina el like del usuario autenticado de un check-in específico.</p>

            <span id="example-requests-DELETEapi-v1-check-ins--id--unlike">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/check-ins/42/unlike" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/check-ins/42/unlike"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-check-ins--id--unlike">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Like eliminado correctamente.&quot;,
    &quot;likes_count&quot;: 15
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el check-in o no has dado like.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-check-ins--id--unlike" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-check-ins--id--unlike"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-check-ins--id--unlike"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-check-ins--id--unlike" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-check-ins--id--unlike">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-check-ins--id--unlike" data-method="DELETE"
                data-path="api/v1/check-ins/{id}/unlike"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-check-ins--id--unlike', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-check-ins--id--unlike"
                        onclick="tryItOut('DELETEapi-v1-check-ins--id--unlike');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-check-ins--id--unlike"
                        onclick="cancelTryOut('DELETEapi-v1-check-ins--id--unlike');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-check-ins--id--unlike"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/check-ins/{id}/unlike</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-check-ins--id--unlike"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-check-ins--id--unlike"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-check-ins--id--unlike"
                        value="42"
                        data-component="url">
                    <br>
                    <p>ID del check-in. Example: <code>42</code></p>
                </div>
            </form>

            <h1 id="notificaciones">Notificaciones</h1>

            <p>APIs para gestionar las notificaciones del usuario</p>

            <h2 id="notificaciones-GETapi-v1-notifications">Listar notificaciones</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene un listado paginado de las notificaciones del usuario autenticado.</p>

            <span id="example-requests-GETapi-v1-notifications">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/notifications?read=&amp;type=like&amp;per_page=15&amp;page=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"read\": false,
    \"type\": \"like\",
    \"per_page\": 1
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/notifications"
);

const params = {
    "read": "0",
    "type": "like",
    "per_page": "15",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "read": false,
    "type": "like",
    "per_page": 1
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-notifications">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 102,
     &quot;type&quot;: &quot;like&quot;,
     &quot;from_user&quot;: {
       &quot;id&quot;: 7,
       &quot;name&quot;: &quot;Mar&iacute;a L&oacute;pez&quot;,
       &quot;profile_picture&quot;: &quot;https://example.com/avatars/maria.jpg&quot;
     },
     &quot;data&quot;: {
       &quot;check_in_id&quot;: 42,
       &quot;beer_name&quot;: &quot;Founders Breakfast Stout&quot;
     },
     &quot;read&quot;: false,
     &quot;created_at&quot;: &quot;2023-04-19T15:30:00.000000Z&quot;
   },
   {
     &quot;id&quot;: 98,
     &quot;type&quot;: &quot;comment&quot;,
     &quot;from_user&quot;: {
       &quot;id&quot;: 12,
       &quot;name&quot;: &quot;Carlos G&oacute;mez&quot;,
       &quot;profile_picture&quot;: &quot;https://example.com/avatars/carlos.jpg&quot;
     },
     &quot;data&quot;: {
       &quot;check_in_id&quot;: 42,
       &quot;beer_name&quot;: &quot;Founders Breakfast Stout&quot;,
       &quot;comment_preview&quot;: &quot;Totalmente de acuerdo, una maravilla de cerveza.&quot;
     },
     &quot;read&quot;: true,
     &quot;created_at&quot;: &quot;2023-04-18T19:15:00.000000Z&quot;
   }
 ],
 &quot;unread_count&quot;: 3,
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-notifications" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-notifications"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-notifications"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-notifications" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-notifications">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-notifications" data-method="GET"
                data-path="api/v1/notifications"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-notifications', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-notifications"
                        onclick="tryItOut('GETapi-v1-notifications');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-notifications"
                        onclick="cancelTryOut('GETapi-v1-notifications');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-notifications"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/notifications</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-notifications"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-notifications"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>read</code></b>&nbsp;&nbsp;
                    <small>boolean</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <label data-endpoint="GETapi-v1-notifications" style="display: none">
                        <input type="radio" name="read"
                            value="1"
                            data-endpoint="GETapi-v1-notifications"
                            data-component="query">
                        <code>true</code>
                    </label>
                    <label data-endpoint="GETapi-v1-notifications" style="display: none">
                        <input type="radio" name="read"
                            value="0"
                            data-endpoint="GETapi-v1-notifications"
                            data-component="query">
                        <code>false</code>
                    </label>
                    <br>
                    <p>Filtrar por notificaciones leídas (true) o no leídas (false). Example: <code>false</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-notifications"
                        value="like"
                        data-component="query">
                    <br>
                    <p>Filtrar por tipo (follow, like, comment, check_in, report). Example: <code>like</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-notifications"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="page" data-endpoint="GETapi-v1-notifications"
                        value="1"
                        data-component="query">
                    <br>
                    <p>Número de página. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>read</code></b>&nbsp;&nbsp;
                    <small>boolean</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <label data-endpoint="GETapi-v1-notifications" style="display: none">
                        <input type="radio" name="read"
                            value="true"
                            data-endpoint="GETapi-v1-notifications"
                            data-component="body">
                        <code>true</code>
                    </label>
                    <label data-endpoint="GETapi-v1-notifications" style="display: none">
                        <input type="radio" name="read"
                            value="false"
                            data-endpoint="GETapi-v1-notifications"
                            data-component="body">
                        <code>false</code>
                    </label>
                    <br>
                    <p>Example: <code>false</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-notifications"
                        value="like"
                        data-component="body">
                    <br>
                    <p>Example: <code>like</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>follow</code></li>
                        <li><code>like</code></li>
                        <li><code>comment</code></li>
                        <li><code>check_in</code></li>
                        <li><code>report</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-notifications"
                        value="1"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="notificaciones-PUTapi-v1-notifications--id--read">Marcar notificación como leída</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Marca una notificación específica como leída.</p>

            <span id="example-requests-PUTapi-v1-notifications--id--read">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/notifications/102/read" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/notifications/102/read"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-notifications--id--read">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;La notificaci&oacute;n ha sido marcada como le&iacute;da.&quot;,
    &quot;unread_count&quot;: 2
}</code>
 </pre>
                <blockquote>
                    <p>Example response (403):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No tienes permiso para acceder a esta notificaci&oacute;n.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la notificaci&oacute;n solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-notifications--id--read" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-notifications--id--read"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-notifications--id--read"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-notifications--id--read" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-notifications--id--read">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-notifications--id--read" data-method="PUT"
                data-path="api/v1/notifications/{id}/read"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-notifications--id--read', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-notifications--id--read"
                        onclick="tryItOut('PUTapi-v1-notifications--id--read');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-notifications--id--read"
                        onclick="cancelTryOut('PUTapi-v1-notifications--id--read');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-notifications--id--read"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/notifications/{id}/read</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-notifications--id--read"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-notifications--id--read"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="PUTapi-v1-notifications--id--read"
                        value="102"
                        data-component="url">
                    <br>
                    <p>ID de la notificación. Example: <code>102</code></p>
                </div>
            </form>

            <h2 id="notificaciones-PUTapi-v1-notifications-read-all">Marcar todas las notificaciones como leídas</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Marca todas las notificaciones no leídas del usuario como leídas.</p>

            <span id="example-requests-PUTapi-v1-notifications-read-all">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/notifications/read-all" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/notifications/read-all"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-notifications-read-all">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Todas las notificaciones han sido marcadas como le&iacute;das.&quot;,
    &quot;count_updated&quot;: 3
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-notifications-read-all" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-notifications-read-all"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-notifications-read-all"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-notifications-read-all" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-notifications-read-all">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-notifications-read-all" data-method="PUT"
                data-path="api/v1/notifications/read-all"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-notifications-read-all', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-notifications-read-all"
                        onclick="tryItOut('PUTapi-v1-notifications-read-all');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-notifications-read-all"
                        onclick="cancelTryOut('PUTapi-v1-notifications-read-all');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-notifications-read-all"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/notifications/read-all</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-notifications-read-all"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-notifications-read-all"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
            </form>

            <h1 id="ubicaciones">Ubicaciones</h1>

            <p>APIs para gestionar ubicaciones donde se pueden encontrar cervezas</p>

            <h2 id="ubicaciones-GETapi-v1-locations">Listar ubicaciones</h2>

            <p>
            </p>

            <p>Obtiene un listado paginado de ubicaciones con opciones de filtrado y ordenamiento.</p>

            <span id="example-requests-GETapi-v1-locations">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/locations?name=Beer+Garden&amp;type=bar&amp;country=Espa%C3%B1a&amp;city=Madrid&amp;sort=name&amp;order=asc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"type\": \"tienda\",
    \"country\": \"n\",
    \"city\": \"g\",
    \"sort\": \"type\",
    \"order\": \"desc\",
    \"per_page\": 16
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/locations"
);

const params = {
    "name": "Beer Garden",
    "type": "bar",
    "country": "España",
    "city": "Madrid",
    "sort": "name",
    "order": "asc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "type": "tienda",
    "country": "n",
    "city": "g",
    "sort": "type",
    "order": "desc",
    "per_page": 16
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-locations">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 1,
     &quot;name&quot;: &quot;Cervecer&iacute;a La Cibeles&quot;,
     &quot;type&quot;: &quot;bar&quot;,
     &quot;address&quot;: &quot;Calle de Ponzano, 25, 28003 Madrid&quot;,
     &quot;city&quot;: &quot;Madrid&quot;,
     &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
     &quot;latitude&quot;: 40.439781,
     &quot;longitude&quot;: -3.694458,
     &quot;description&quot;: &quot;Bar especializado en cervezas artesanales&quot;,
     &quot;website&quot;: &quot;https://www.cervezaslacibeles.com/&quot;,
     &quot;phone&quot;: &quot;+34912345678&quot;,
     &quot;opening_hours&quot;: &quot;Lun-Jue: 17:00-00:00, Vie-S&aacute;b: 17:00-02:00, Dom: 17:00-22:00&quot;,
     &quot;image_url&quot;: &quot;https://example.com/locations/cibeles.jpg&quot;,
     &quot;created_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;,
     &quot;updated_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-locations" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-locations"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-locations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-locations" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-locations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-locations" data-method="GET"
                data-path="api/v1/locations"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-locations', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-locations"
                        onclick="tryItOut('GETapi-v1-locations');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-locations"
                        onclick="cancelTryOut('GETapi-v1-locations');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-locations"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/locations</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-locations"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-locations"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-locations"
                        value="Beer Garden"
                        data-component="query">
                    <br>
                    <p>Filtrar por nombre. Example: <code>Beer Garden</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-locations"
                        value="bar"
                        data-component="query">
                    <br>
                    <p>Filtrar por tipo (bar, restaurante, tienda, cervecería). Example: <code>bar</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="GETapi-v1-locations"
                        value="España"
                        data-component="query">
                    <br>
                    <p>Filtrar por país. Example: <code>España</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="GETapi-v1-locations"
                        value="Madrid"
                        data-component="query">
                    <br>
                    <p>Filtrar por ciudad. Example: <code>Madrid</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-locations"
                        value="name"
                        data-component="query">
                    <br>
                    <p>Ordenar por: name, type, country, city, created_at. Example: <code>name</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-locations"
                        value="asc"
                        data-component="query">
                    <br>
                    <p>Dirección: asc, desc. Example: <code>asc</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-locations"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-locations"
                        value="b"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>b</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-locations"
                        value="tienda"
                        data-component="body">
                    <br>
                    <p>Example: <code>tienda</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>bar</code></li>
                        <li><code>restaurante</code></li>
                        <li><code>tienda</code></li>
                        <li><code>cervecería</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="GETapi-v1-locations"
                        value="n"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>n</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="GETapi-v1-locations"
                        value="g"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>g</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-locations"
                        value="type"
                        data-component="body">
                    <br>
                    <p>Example: <code>type</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>name</code></li>
                        <li><code>type</code></li>
                        <li><code>country</code></li>
                        <li><code>city</code></li>
                        <li><code>created_at</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-locations"
                        value="desc"
                        data-component="body">
                    <br>
                    <p>Example: <code>desc</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>asc</code></li>
                        <li><code>desc</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-locations"
                        value="16"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>16</code></p>
                </div>
            </form>

            <h2 id="ubicaciones-GETapi-v1-locations--id-">Ver ubicación</h2>

            <p>
            </p>

            <p>Muestra información detallada de una ubicación específica.</p>

            <span id="example-requests-GETapi-v1-locations--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/locations/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/locations/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-locations--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Cervecer&iacute;a La Cibeles&quot;,
        &quot;type&quot;: &quot;bar&quot;,
        &quot;address&quot;: &quot;Calle de Ponzano, 25, 28003 Madrid&quot;,
        &quot;city&quot;: &quot;Madrid&quot;,
        &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
        &quot;latitude&quot;: 40.439781,
        &quot;longitude&quot;: -3.694458,
        &quot;description&quot;: &quot;Bar especializado en cervezas artesanales&quot;,
        &quot;website&quot;: &quot;https://www.cervezaslacibeles.com/&quot;,
        &quot;phone&quot;: &quot;+34912345678&quot;,
        &quot;opening_hours&quot;: &quot;Lun-Jue: 17:00-00:00, Vie-S&aacute;b: 17:00-02:00, Dom: 17:00-22:00&quot;,
        &quot;image_url&quot;: &quot;https://example.com/locations/cibeles.jpg&quot;,
        &quot;beers_available&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;La Cibeles Original&quot;,
                &quot;brewery&quot;: {
                    &quot;id&quot;: 5,
                    &quot;name&quot;: &quot;Cerveza La Cibeles&quot;
                }
            }
        ],
        &quot;recent_check_ins&quot;: [
            {
                &quot;id&quot;: 32,
                &quot;user&quot;: {
                    &quot;id&quot;: 7,
                    &quot;name&quot;: &quot;Mar&iacute;a L&oacute;pez&quot;,
                    &quot;profile_picture&quot;: &quot;https://example.com/avatars/maria.jpg&quot;
                },
                &quot;beer&quot;: {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;La Cibeles Original&quot;
                },
                &quot;rating&quot;: 4.5,
                &quot;created_at&quot;: &quot;2023-04-17T19:30:00.000000Z&quot;
            }
        ],
        &quot;created_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2023-04-18T00:00:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la ubicaci&oacute;n solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-locations--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-locations--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-locations--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-locations--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-locations--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-locations--id-" data-method="GET"
                data-path="api/v1/locations/{id}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-locations--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-locations--id-"
                        onclick="tryItOut('GETapi-v1-locations--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-locations--id-"
                        onclick="cancelTryOut('GETapi-v1-locations--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-locations--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/locations/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-locations--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-locations--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-locations--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la ubicación. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="ubicaciones-POSTapi-v1-locations">Crear ubicación</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Crea una nueva ubicación en el sistema.</p>

            <span id="example-requests-POSTapi-v1-locations">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/locations" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=Beer Garden Madrid"\
    --form "type=bar"\
    --form "address=Calle Gran Vía, 44, 28013 Madrid"\
    --form "city=Madrid"\
    --form "country=España"\
    --form "latitude=40.420139"\
    --form "longitude=-3.705224"\
    --form "description=Terraza cervecera con amplia selección de cervezas artesanales"\
    --form "website=https://beergarden.es"\
    --form "phone=+34912345678"\
    --form "opening_hours=Lun-Dom: 12:00-00:00"\
    --form "image_url=http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"\
    --form "image=@/tmp/phpdm2ai9f4ge9f9fCAcpP" </code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/locations"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'Beer Garden Madrid');
body.append('type', 'bar');
body.append('address', 'Calle Gran Vía, 44, 28013 Madrid');
body.append('city', 'Madrid');
body.append('country', 'España');
body.append('latitude', '40.420139');
body.append('longitude', '-3.705224');
body.append('description', 'Terraza cervecera con amplia selección de cervezas artesanales');
body.append('website', 'https://beergarden.es');
body.append('phone', '+34912345678');
body.append('opening_hours', 'Lun-Dom: 12:00-00:00');
body.append('image_url', 'http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html');
body.append('image', document.querySelector('input[name="image"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-locations">
                <blockquote>
                    <p>Example response (201):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 10,
        &quot;name&quot;: &quot;Beer Garden Madrid&quot;,
        &quot;type&quot;: &quot;bar&quot;,
        &quot;address&quot;: &quot;Calle Gran V&iacute;a, 44, 28013 Madrid&quot;,
        &quot;city&quot;: &quot;Madrid&quot;,
        &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
        &quot;latitude&quot;: 40.420139,
        &quot;longitude&quot;: -3.705224,
        &quot;description&quot;: &quot;Terraza cervecera con amplia selecci&oacute;n de cervezas artesanales&quot;,
        &quot;website&quot;: &quot;https://beergarden.es&quot;,
        &quot;phone&quot;: &quot;+34912345678&quot;,
        &quot;opening_hours&quot;: &quot;Lun-Dom: 12:00-00:00&quot;,
        &quot;image_url&quot;: &quot;https://example.com/locations/beergarden.jpg&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Los datos proporcionados no son v&aacute;lidos.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;El campo nombre es obligatorio.&quot;
        ],
        &quot;latitude&quot;: [
            &quot;El campo latitud debe ser un n&uacute;mero.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-locations" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-locations"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-locations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-locations" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-locations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-locations" data-method="POST"
                data-path="api/v1/locations"
                data-authed="1"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-locations', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-locations"
                        onclick="tryItOut('POSTapi-v1-locations');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-locations"
                        onclick="cancelTryOut('POSTapi-v1-locations');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-locations"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/locations</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-locations"
                        value="multipart/form-data"
                        data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-locations"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="POSTapi-v1-locations"
                        value="Beer Garden Madrid"
                        data-component="body">
                    <br>
                    <p>Nombre de la ubicación. Example: <code>Beer Garden Madrid</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="POSTapi-v1-locations"
                        value="bar"
                        data-component="body">
                    <br>
                    <p>Tipo de ubicación (bar, restaurante, tienda, cervecería). Example: <code>bar</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="address" data-endpoint="POSTapi-v1-locations"
                        value="Calle Gran Vía, 44, 28013 Madrid"
                        data-component="body">
                    <br>
                    <p>Dirección completa. Example: <code>Calle Gran Vía, 44, 28013 Madrid</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="POSTapi-v1-locations"
                        value="Madrid"
                        data-component="body">
                    <br>
                    <p>Ciudad. Example: <code>Madrid</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="POSTapi-v1-locations"
                        value="España"
                        data-component="body">
                    <br>
                    <p>País. Example: <code>España</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="latitude" data-endpoint="POSTapi-v1-locations"
                        value="40.420139"
                        data-component="body">
                    <br>
                    <p>Latitud en grados decimales. Example: <code>40.420139</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="longitude" data-endpoint="POSTapi-v1-locations"
                        value="-3.705224"
                        data-component="body">
                    <br>
                    <p>Longitud en grados decimales. Example: <code>-3.705224</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="description" data-endpoint="POSTapi-v1-locations"
                        value="Terraza cervecera con amplia selección de cervezas artesanales"
                        data-component="body">
                    <br>
                    <p>Descripción de la ubicación. Example: <code>Terraza cervecera con amplia selección de cervezas artesanales</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>website</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="website" data-endpoint="POSTapi-v1-locations"
                        value="https://beergarden.es"
                        data-component="body">
                    <br>
                    <p>URL del sitio web. Example: <code>https://beergarden.es</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="phone" data-endpoint="POSTapi-v1-locations"
                        value="+34912345678"
                        data-component="body">
                    <br>
                    <p>Número de teléfono. Example: <code>+34912345678</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>opening_hours</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="opening_hours" data-endpoint="POSTapi-v1-locations"
                        value="Lun-Dom: 12:00-00:00"
                        data-component="body">
                    <br>
                    <p>Horario de apertura. Example: <code>Lun-Dom: 12:00-00:00</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>image_url</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="image_url" data-endpoint="POSTapi-v1-locations"
                        value="http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"
                        data-component="body">
                    <br>
                    <p>URL de la imagen de la ubicación. Example: <code>http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>image</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="file" style="display: none"
                        name="image" data-endpoint="POSTapi-v1-locations"
                        value=""
                        data-component="body">
                    <br>
                    <p>Imagen de la ubicación (JPG, PNG, WebP, máx 2MB). Example: <code>/tmp/phpdm2ai9f4ge9f9fCAcpP</code></p>
                </div>
            </form>

            <h2 id="ubicaciones-PUTapi-v1-locations--id-">Actualizar ubicación</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Actualiza la información de una ubicación existente.</p>

            <span id="example-requests-PUTapi-v1-locations--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/locations/1" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=Cervecería La Cibeles Premium"\
    --form "type=bar"\
    --form "address=Calle de Ponzano, 28, 28003 Madrid"\
    --form "city=Madrid"\
    --form "country=España"\
    --form "latitude=40.439800"\
    --form "longitude=-3.694500"\
    --form "description=Bar premium especializado en cervezas artesanales"\
    --form "website=https://www.cervezaslacibeles.com/premium"\
    --form "phone=+34912345679"\
    --form "opening_hours=Lun-Dom: 17:00-02:00"\
    --form "image_url=http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"\
    --form "image=@/tmp/php8j52v165hd5t2CflDCa" </code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/locations/1"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'Cervecería La Cibeles Premium');
body.append('type', 'bar');
body.append('address', 'Calle de Ponzano, 28, 28003 Madrid');
body.append('city', 'Madrid');
body.append('country', 'España');
body.append('latitude', '40.439800');
body.append('longitude', '-3.694500');
body.append('description', 'Bar premium especializado en cervezas artesanales');
body.append('website', 'https://www.cervezaslacibeles.com/premium');
body.append('phone', '+34912345679');
body.append('opening_hours', 'Lun-Dom: 17:00-02:00');
body.append('image_url', 'http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html');
body.append('image', document.querySelector('input[name="image"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-locations--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Cervecer&iacute;a La Cibeles Premium&quot;,
        &quot;type&quot;: &quot;bar&quot;,
        &quot;address&quot;: &quot;Calle de Ponzano, 28, 28003 Madrid&quot;,
        &quot;city&quot;: &quot;Madrid&quot;,
        &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
        &quot;latitude&quot;: 40.4398,
        &quot;longitude&quot;: -3.6945,
        &quot;description&quot;: &quot;Bar premium especializado en cervezas artesanales&quot;,
        &quot;website&quot;: &quot;https://www.cervezaslacibeles.com/premium&quot;,
        &quot;phone&quot;: &quot;+34912345679&quot;,
        &quot;opening_hours&quot;: &quot;Lun-Dom: 17:00-02:00&quot;,
        &quot;image_url&quot;: &quot;https://example.com/locations/cibeles_premium.jpg&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la ubicaci&oacute;n solicitada.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Los datos proporcionados no son v&aacute;lidos.&quot;,
    &quot;errors&quot;: {
        &quot;latitude&quot;: [
            &quot;El campo latitud debe estar entre -90 y 90.&quot;
        ]
    }
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-locations--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-locations--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-locations--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-locations--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-locations--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-locations--id-" data-method="PUT"
                data-path="api/v1/locations/{id}"
                data-authed="1"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-locations--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-locations--id-"
                        onclick="tryItOut('PUTapi-v1-locations--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-locations--id-"
                        onclick="cancelTryOut('PUTapi-v1-locations--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-locations--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/locations/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-locations--id-"
                        value="multipart/form-data"
                        data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-locations--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="PUTapi-v1-locations--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la ubicación. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="PUTapi-v1-locations--id-"
                        value="Cervecería La Cibeles Premium"
                        data-component="body">
                    <br>
                    <p>Nombre de la ubicación. Example: <code>Cervecería La Cibeles Premium</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="PUTapi-v1-locations--id-"
                        value="bar"
                        data-component="body">
                    <br>
                    <p>Tipo de ubicación (bar, restaurante, tienda, cervecería). Example: <code>bar</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="address" data-endpoint="PUTapi-v1-locations--id-"
                        value="Calle de Ponzano, 28, 28003 Madrid"
                        data-component="body">
                    <br>
                    <p>Dirección completa. Example: <code>Calle de Ponzano, 28, 28003 Madrid</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="city" data-endpoint="PUTapi-v1-locations--id-"
                        value="Madrid"
                        data-component="body">
                    <br>
                    <p>Ciudad. Example: <code>Madrid</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="country" data-endpoint="PUTapi-v1-locations--id-"
                        value="España"
                        data-component="body">
                    <br>
                    <p>País. Example: <code>España</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="latitude" data-endpoint="PUTapi-v1-locations--id-"
                        value="40.439800"
                        data-component="body">
                    <br>
                    <p>Latitud en grados decimales. Example: <code>40.439800</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
                    <small>numeric</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="longitude" data-endpoint="PUTapi-v1-locations--id-"
                        value="-3.694500"
                        data-component="body">
                    <br>
                    <p>Longitud en grados decimales. Example: <code>-3.694500</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="description" data-endpoint="PUTapi-v1-locations--id-"
                        value="Bar premium especializado en cervezas artesanales"
                        data-component="body">
                    <br>
                    <p>Descripción de la ubicación. Example: <code>Bar premium especializado en cervezas artesanales</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>website</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="website" data-endpoint="PUTapi-v1-locations--id-"
                        value="https://www.cervezaslacibeles.com/premium"
                        data-component="body">
                    <br>
                    <p>URL del sitio web. Example: <code>https://www.cervezaslacibeles.com/premium</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="phone" data-endpoint="PUTapi-v1-locations--id-"
                        value="+34912345679"
                        data-component="body">
                    <br>
                    <p>Número de teléfono. Example: <code>+34912345679</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>opening_hours</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="opening_hours" data-endpoint="PUTapi-v1-locations--id-"
                        value="Lun-Dom: 17:00-02:00"
                        data-component="body">
                    <br>
                    <p>Horario de apertura. Example: <code>Lun-Dom: 17:00-02:00</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>image_url</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="image_url" data-endpoint="PUTapi-v1-locations--id-"
                        value="http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"
                        data-component="body">
                    <br>
                    <p>URL de la imagen de la ubicación. Example: <code>http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>image</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="file" style="display: none"
                        name="image" data-endpoint="PUTapi-v1-locations--id-"
                        value=""
                        data-component="body">
                    <br>
                    <p>Imagen de la ubicación (JPG, PNG, WebP, máx 2MB). Example: <code>/tmp/php8j52v165hd5t2CflDCa</code></p>
                </div>
            </form>

            <h2 id="ubicaciones-DELETEapi-v1-locations--id-">Eliminar ubicación</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Elimina una ubicación del sistema.</p>

            <span id="example-requests-DELETEapi-v1-locations--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/locations/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/locations/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-locations--id-">
                <blockquote>
                    <p>Example response (204):</p>
                </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado la ubicaci&oacute;n solicitada.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-locations--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-locations--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-locations--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-locations--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-locations--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-locations--id-" data-method="DELETE"
                data-path="api/v1/locations/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-locations--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-locations--id-"
                        onclick="tryItOut('DELETEapi-v1-locations--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-locations--id-"
                        onclick="cancelTryOut('DELETEapi-v1-locations--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-locations--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/locations/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-locations--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-locations--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-locations--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID de la ubicación. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="ubicaciones-GETapi-v1-locations-nearby">Ubicaciones cercanas</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene las ubicaciones cercanas a un punto geográfico específico.</p>

            <span id="example-requests-GETapi-v1-locations-nearby">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/locations/nearby?lat=40.416775&amp;lng=-3.703790&amp;radius=5&amp;type=bar&amp;limit=20" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/locations/nearby"
);

const params = {
    "lat": "40.416775",
    "lng": "-3.703790",
    "radius": "5",
    "type": "bar",
    "limit": "20",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-locations-nearby">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;F&aacute;brica Maravillas&quot;,
            &quot;type&quot;: &quot;bar&quot;,
            &quot;address&quot;: &quot;Calle de Valverde, 29, 28004 Madrid&quot;,
            &quot;city&quot;: &quot;Madrid&quot;,
            &quot;country&quot;: &quot;Espa&ntilde;a&quot;,
            &quot;latitude&quot;: 40.423397,
            &quot;longitude&quot;: -3.701789,
            &quot;description&quot;: &quot;Brewpub con cervezas artesanales propias&quot;,
            &quot;website&quot;: &quot;https://www.fabricamaravillas.com/&quot;,
            &quot;phone&quot;: &quot;+34915221653&quot;,
            &quot;opening_hours&quot;: &quot;Lun-Dom: 17:00-00:00&quot;,
            &quot;image_url&quot;: &quot;https://example.com/locations/fabrica.jpg&quot;,
            &quot;distance&quot;: 0.74
        }
    ]
}</code>
 </pre>
                <blockquote>
                    <p>Example response (422):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Las coordenadas son obligatorias.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-locations-nearby" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-locations-nearby"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-locations-nearby"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-locations-nearby" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-locations-nearby">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-locations-nearby" data-method="GET"
                data-path="api/v1/locations/nearby"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-locations-nearby', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-locations-nearby"
                        onclick="tryItOut('GETapi-v1-locations-nearby');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-locations-nearby"
                        onclick="cancelTryOut('GETapi-v1-locations-nearby');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-locations-nearby"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/locations/nearby</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-locations-nearby"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-locations-nearby"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>lat</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="lat" data-endpoint="GETapi-v1-locations-nearby"
                        value="40.416775"
                        data-component="query">
                    <br>
                    <p>numeric Latitud en grados decimales. Example: <code>40.416775</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>lng</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="lng" data-endpoint="GETapi-v1-locations-nearby"
                        value="-3.703790"
                        data-component="query">
                    <br>
                    <p>numeric Longitud en grados decimales. Example: <code>-3.703790</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>radius</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="radius" data-endpoint="GETapi-v1-locations-nearby"
                        value="5"
                        data-component="query">
                    <br>
                    <p>numeric Distancia máxima en kilómetros (1-50). Example: <code>5</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="type" data-endpoint="GETapi-v1-locations-nearby"
                        value="bar"
                        data-component="query">
                    <br>
                    <p>Filtrar por tipo (bar, restaurante, tienda, cervecería). Example: <code>bar</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="limit" data-endpoint="GETapi-v1-locations-nearby"
                        value="20"
                        data-component="query">
                    <br>
                    <p>Número máximo de resultados (1-50). Example: <code>20</code></p>
                </div>
            </form>

            <h1 id="usuarios">Usuarios</h1>

            <p>APIs para gestionar usuarios y sus relaciones sociales</p>

            <h2 id="usuarios-GETapi-v1-users--id-">Ver usuario</h2>

            <p>
            </p>

            <p>Muestra información detallada de un usuario específico.</p>

            <span id="example-requests-GETapi-v1-users--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/users/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-users--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Juan P&eacute;rez&quot;,
        &quot;bio&quot;: &quot;Amante de las IPA y cervecero casero&quot;,
        &quot;location&quot;: &quot;Madrid, Espa&ntilde;a&quot;,
        &quot;profile_picture&quot;: &quot;https://example.com/avatars/juan.jpg&quot;,
        &quot;check_ins_count&quot;: 42,
        &quot;followers_count&quot;: 12,
        &quot;following_count&quot;: 25,
        &quot;is_following&quot;: false,
        &quot;recent_check_ins&quot;: [
            {
                &quot;id&quot;: 123,
                &quot;beer&quot;: {
                    &quot;id&quot;: 5,
                    &quot;name&quot;: &quot;Founders Breakfast Stout&quot;,
                    &quot;brewery&quot;: {
                        &quot;id&quot;: 3,
                        &quot;name&quot;: &quot;Founders Brewing Co.&quot;
                    }
                },
                &quot;rating&quot;: 4.5,
                &quot;comment&quot;: &quot;Excelente combinaci&oacute;n de caf&eacute; y chocolate&quot;,
                &quot;created_at&quot;: &quot;2023-04-17T19:30:00.000000Z&quot;
            }
        ],
        &quot;top_beers&quot;: [
            {
                &quot;id&quot;: 5,
                &quot;name&quot;: &quot;Founders Breakfast Stout&quot;,
                &quot;brewery&quot;: {
                    &quot;id&quot;: 3,
                    &quot;name&quot;: &quot;Founders Brewing Co.&quot;
                },
                &quot;rating&quot;: 4.5
            }
        ],
        &quot;created_at&quot;: &quot;2023-01-15T00:00:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el usuario solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-users--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-users--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-users--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-users--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-users--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-users--id-" data-method="GET"
                data-path="api/v1/users/{id}"
                data-authed="0"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-users--id-"
                        onclick="tryItOut('GETapi-v1-users--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-users--id-"
                        onclick="cancelTryOut('GETapi-v1-users--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-users--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/users/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-users--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-users--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-users--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del usuario. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="usuarios-GETapi-v1-users">Listar usuarios</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene un listado paginado de usuarios con opciones de filtrado y ordenamiento.</p>

            <span id="example-requests-GETapi-v1-users">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/users?name=Juan&amp;sort=created_at&amp;order=desc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"sort\": \"name\",
    \"order\": \"desc\",
    \"per_page\": 22
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users"
);

const params = {
    "name": "Juan",
    "sort": "created_at",
    "order": "desc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "sort": "name",
    "order": "desc",
    "per_page": 22
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-users">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 1,
     &quot;name&quot;: &quot;Juan P&eacute;rez&quot;,
     &quot;bio&quot;: &quot;Amante de las IPA y cervecero casero&quot;,
     &quot;location&quot;: &quot;Madrid, Espa&ntilde;a&quot;,
     &quot;profile_picture&quot;: &quot;https://example.com/avatars/juan.jpg&quot;,
     &quot;check_ins_count&quot;: 42,
     &quot;followers_count&quot;: 12,
     &quot;following_count&quot;: 25,
     &quot;created_at&quot;: &quot;2023-01-15T00:00:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-users" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-users"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-users"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-users" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-users">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-users" data-method="GET"
                data-path="api/v1/users"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-users"
                        onclick="tryItOut('GETapi-v1-users');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-users"
                        onclick="cancelTryOut('GETapi-v1-users');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-users"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/users</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-users"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-users"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-users"
                        value="Juan"
                        data-component="query">
                    <br>
                    <p>Filtrar por nombre. Example: <code>Juan</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-users"
                        value="created_at"
                        data-component="query">
                    <br>
                    <p>Ordenar por: name, created_at, check_ins_count. Example: <code>created_at</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-users"
                        value="desc"
                        data-component="query">
                    <br>
                    <p>Dirección: asc, desc. Example: <code>desc</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-users"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="GETapi-v1-users"
                        value="b"
                        data-component="body">
                    <br>
                    <p>Must not be greater than 100 characters. Example: <code>b</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="sort" data-endpoint="GETapi-v1-users"
                        value="name"
                        data-component="body">
                    <br>
                    <p>Example: <code>name</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>name</code></li>
                        <li><code>created_at</code></li>
                        <li><code>check_ins_count</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>order</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="order" data-endpoint="GETapi-v1-users"
                        value="desc"
                        data-component="body">
                    <br>
                    <p>Example: <code>desc</code></p>
                    Must be one of:
                    <ul style="list-style-type: square;">
                        <li><code>asc</code></li>
                        <li><code>desc</code></li>
                    </ul>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-users"
                        value="22"
                        data-component="body">
                    <br>
                    <p>Must be at least 5. Must not be greater than 50. Example: <code>22</code></p>
                </div>
            </form>

            <h2 id="usuarios-PUTapi-v1-users--id-">Actualizar usuario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Actualiza la información de un usuario existente.</p>

            <span id="example-requests-PUTapi-v1-users--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request PUT \
    "http://127.0.0.1:8000/api/v1/users/1" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "name=Juan García Pérez"\
    --form "bio=Cervecero aficionado desde 2015"\
    --form "location=Barcelona, España"\
    --form "profile_picture=http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"\
    --form "avatar=@/tmp/php9f53qk0ncvsjdFCghjN" </code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users/1"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('name', 'Juan García Pérez');
body.append('bio', 'Cervecero aficionado desde 2015');
body.append('location', 'Barcelona, España');
body.append('profile_picture', 'http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html');
body.append('avatar', document.querySelector('input[name="avatar"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-PUTapi-v1-users--id-">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Juan Garc&iacute;a P&eacute;rez&quot;,
        &quot;bio&quot;: &quot;Cervecero aficionado desde 2015&quot;,
        &quot;location&quot;: &quot;Barcelona, Espa&ntilde;a&quot;,
        &quot;profile_picture&quot;: &quot;https://example.com/avatars/juan_nuevo.jpg&quot;,
        &quot;check_ins_count&quot;: 42,
        &quot;followers_count&quot;: 12,
        &quot;following_count&quot;: 25,
        &quot;created_at&quot;: &quot;2023-01-15T00:00:00.000000Z&quot;
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (403):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No tienes permisos para actualizar este usuario.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el usuario solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-PUTapi-v1-users--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-PUTapi-v1-users--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-PUTapi-v1-users--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-PUTapi-v1-users--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-PUTapi-v1-users--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-PUTapi-v1-users--id-" data-method="PUT"
                data-path="api/v1/users/{id}"
                data-authed="1"
                data-hasfiles="1"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-users--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-PUTapi-v1-users--id-"
                        onclick="tryItOut('PUTapi-v1-users--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-PUTapi-v1-users--id-"
                        onclick="cancelTryOut('PUTapi-v1-users--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-PUTapi-v1-users--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-darkblue">PUT</small>
                    <b><code>api/v1/users/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="PUTapi-v1-users--id-"
                        value="multipart/form-data"
                        data-component="header">
                    <br>
                    <p>Example: <code>multipart/form-data</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="PUTapi-v1-users--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="PUTapi-v1-users--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del usuario. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="name" data-endpoint="PUTapi-v1-users--id-"
                        value="Juan García Pérez"
                        data-component="body">
                    <br>
                    <p>Nombre del usuario. Example: <code>Juan García Pérez</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>bio</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="bio" data-endpoint="PUTapi-v1-users--id-"
                        value="Cervecero aficionado desde 2015"
                        data-component="body">
                    <br>
                    <p>Biografía del usuario. Example: <code>Cervecero aficionado desde 2015</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>location</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="location" data-endpoint="PUTapi-v1-users--id-"
                        value="Barcelona, España"
                        data-component="body">
                    <br>
                    <p>Ubicación del usuario. Example: <code>Barcelona, España</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>profile_picture</code></b>&nbsp;&nbsp;
                    <small>string</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="text" style="display: none"
                        name="profile_picture" data-endpoint="PUTapi-v1-users--id-"
                        value="http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html"
                        data-component="body">
                    <br>
                    <p>URL del avatar. Example: <code>http://www.bailey.biz/quos-velit-et-fugiat-sunt-nihil-accusantium-harum.html</code></p>
                </div>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>avatar</code></b>&nbsp;&nbsp;
                    <small>file</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="file" style="display: none"
                        name="avatar" data-endpoint="PUTapi-v1-users--id-"
                        value=""
                        data-component="body">
                    <br>
                    <p>Imagen del avatar (JPG, PNG, WebP, máx 2MB). Example: <code>/tmp/php9f53qk0ncvsjdFCghjN</code></p>
                </div>
            </form>

            <h2 id="usuarios-DELETEapi-v1-users--id-">Eliminar usuario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Elimina un usuario del sistema.</p>

            <span id="example-requests-DELETEapi-v1-users--id-">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/users/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-users--id-">
                <blockquote>
                    <p>Example response (204):</p>
                </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
                <blockquote>
                    <p>Example response (403):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No tienes permisos para eliminar este usuario.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el usuario solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-users--id-" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-users--id-"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-users--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-users--id-" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-users--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-users--id-" data-method="DELETE"
                data-path="api/v1/users/{id}"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-users--id-', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-users--id-"
                        onclick="tryItOut('DELETEapi-v1-users--id-');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-users--id-"
                        onclick="cancelTryOut('DELETEapi-v1-users--id-');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-users--id-"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/users/{id}</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-users--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-users--id-"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-users--id-"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del usuario. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="usuarios-POSTapi-v1-users--id--follow">Seguir usuario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Comienza a seguir a un usuario.</p>

            <span id="example-requests-POSTapi-v1-users--id--follow">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request POST \
    "http://127.0.0.1:8000/api/v1/users/2/follow" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users/2/follow"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-POSTapi-v1-users--id--follow">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Ahora sigues a este usuario.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (400):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No puedes seguirte a ti mismo.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el usuario solicitado.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (409):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Ya sigues a este usuario.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-POSTapi-v1-users--id--follow" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-POSTapi-v1-users--id--follow"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-POSTapi-v1-users--id--follow"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-POSTapi-v1-users--id--follow" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-POSTapi-v1-users--id--follow">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-POSTapi-v1-users--id--follow" data-method="POST"
                data-path="api/v1/users/{id}/follow"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-users--id--follow', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-POSTapi-v1-users--id--follow"
                        onclick="tryItOut('POSTapi-v1-users--id--follow');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-POSTapi-v1-users--id--follow"
                        onclick="cancelTryOut('POSTapi-v1-users--id--follow');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-POSTapi-v1-users--id--follow"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-black">POST</small>
                    <b><code>api/v1/users/{id}/follow</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="POSTapi-v1-users--id--follow"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="POSTapi-v1-users--id--follow"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="POSTapi-v1-users--id--follow"
                        value="2"
                        data-component="url">
                    <br>
                    <p>ID del usuario a seguir. Example: <code>2</code></p>
                </div>
            </form>

            <h2 id="usuarios-DELETEapi-v1-users--id--unfollow">Dejar de seguir usuario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Deja de seguir a un usuario.</p>

            <span id="example-requests-DELETEapi-v1-users--id--unfollow">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request DELETE \
    "http://127.0.0.1:8000/api/v1/users/2/unfollow" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users/2/unfollow"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-DELETEapi-v1-users--id--unfollow">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Has dejado de seguir a este usuario.&quot;
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No est&aacute;s siguiendo a este usuario.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-DELETEapi-v1-users--id--unfollow" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-DELETEapi-v1-users--id--unfollow"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-DELETEapi-v1-users--id--unfollow"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-DELETEapi-v1-users--id--unfollow" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-DELETEapi-v1-users--id--unfollow">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-DELETEapi-v1-users--id--unfollow" data-method="DELETE"
                data-path="api/v1/users/{id}/unfollow"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-users--id--unfollow', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-DELETEapi-v1-users--id--unfollow"
                        onclick="tryItOut('DELETEapi-v1-users--id--unfollow');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-DELETEapi-v1-users--id--unfollow"
                        onclick="cancelTryOut('DELETEapi-v1-users--id--unfollow');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-DELETEapi-v1-users--id--unfollow"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-red">DELETE</small>
                    <b><code>api/v1/users/{id}/unfollow</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="DELETEapi-v1-users--id--unfollow"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="DELETEapi-v1-users--id--unfollow"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="DELETEapi-v1-users--id--unfollow"
                        value="2"
                        data-component="url">
                    <br>
                    <p>ID del usuario que se dejará de seguir. Example: <code>2</code></p>
                </div>
            </form>

            <h2 id="usuarios-GETapi-v1-users--id--followers">Seguidores del usuario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene la lista de usuarios que siguen al usuario especificado.</p>

            <span id="example-requests-GETapi-v1-users--id--followers">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/users/1/followers?per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users/1/followers"
);

const params = {
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-users--id--followers">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 2,
     &quot;name&quot;: &quot;Mar&iacute;a L&oacute;pez&quot;,
     &quot;bio&quot;: &quot;Aficionada a las cervezas belgas&quot;,
     &quot;location&quot;: &quot;Valencia, Espa&ntilde;a&quot;,
     &quot;profile_picture&quot;: &quot;https://example.com/avatars/maria.jpg&quot;,
     &quot;check_ins_count&quot;: 28,
     &quot;is_following&quot;: true,
     &quot;created_at&quot;: &quot;2023-02-10T00:00:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el usuario solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-users--id--followers" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-users--id--followers"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-users--id--followers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-users--id--followers" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-users--id--followers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-users--id--followers" data-method="GET"
                data-path="api/v1/users/{id}/followers"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users--id--followers', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-users--id--followers"
                        onclick="tryItOut('GETapi-v1-users--id--followers');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-users--id--followers"
                        onclick="cancelTryOut('GETapi-v1-users--id--followers');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-users--id--followers"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/users/{id}/followers</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-users--id--followers"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-users--id--followers"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-users--id--followers"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del usuario. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-users--id--followers"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
            </form>

            <h2 id="usuarios-GETapi-v1-users--id--following">Usuarios seguidos</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene la lista de usuarios que el usuario especificado sigue.</p>

            <span id="example-requests-GETapi-v1-users--id--following">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/users/1/following?per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users/1/following"
);

const params = {
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-users--id--following">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 &quot;data&quot;: [
   {
     &quot;id&quot;: 3,
     &quot;name&quot;: &quot;Carlos Ruiz&quot;,
     &quot;bio&quot;: &quot;Homebrewer y juez certificado BJCP&quot;,
     &quot;location&quot;: &quot;Barcelona, Espa&ntilde;a&quot;,
     &quot;profile_picture&quot;: &quot;https://example.com/avatars/carlos.jpg&quot;,
     &quot;check_ins_count&quot;: 150,
     &quot;is_following&quot;: false,
     &quot;created_at&quot;: &quot;2022-11-05T00:00:00.000000Z&quot;
   }
 ],
 &quot;links&quot;: {...},
 &quot;meta&quot;: {...}
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el usuario solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-users--id--following" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-users--id--following"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-users--id--following"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-users--id--following" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-users--id--following">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-users--id--following" data-method="GET"
                data-path="api/v1/users/{id}/following"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users--id--following', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-users--id--following"
                        onclick="tryItOut('GETapi-v1-users--id--following');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-users--id--following"
                        onclick="cancelTryOut('GETapi-v1-users--id--following');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-users--id--following"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/users/{id}/following</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-users--id--following"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-users--id--following"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-users--id--following"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del usuario. Example: <code>1</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="per_page" data-endpoint="GETapi-v1-users--id--following"
                        value="15"
                        data-component="query">
                    <br>
                    <p>Elementos por página (5-50). Example: <code>15</code></p>
                </div>
            </form>

            <h2 id="usuarios-GETapi-v1-users--id--stats">Estadísticas de usuario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene estadísticas detalladas sobre la actividad del usuario.</p>

            <span id="example-requests-GETapi-v1-users--id--stats">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/users/1/stats" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/users/1/stats"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-users--id--stats">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Juan P&eacute;rez&quot;,
            &quot;profile_picture&quot;: &quot;https://example.com/avatars/juan.jpg&quot;
        },
        &quot;total_check_ins&quot;: 42,
        &quot;unique_beers&quot;: 38,
        &quot;avg_rating&quot;: 3.8,
        &quot;favorite_styles&quot;: [
            {
                &quot;name&quot;: &quot;IPA&quot;,
                &quot;count&quot;: 15
            },
            {
                &quot;name&quot;: &quot;Stout&quot;,
                &quot;count&quot;: 8
            }
        ],
        &quot;favorite_breweries&quot;: [
            {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;Founders Brewing Co.&quot;,
                &quot;count&quot;: 7
            }
        ],
        &quot;top_rated_beers&quot;: [
            {
                &quot;id&quot;: 15,
                &quot;name&quot;: &quot;KBS (Kentucky Breakfast Stout)&quot;,
                &quot;brewery&quot;: &quot;Founders Brewing Co.&quot;,
                &quot;rating&quot;: 4.9
            }
        ],
        &quot;check_ins_by_month&quot;: {
            &quot;2023-01&quot;: 5,
            &quot;2023-02&quot;: 8,
            &quot;2023-03&quot;: 12,
            &quot;2023-04&quot;: 17
        }
    }
}</code>
 </pre>
                <blockquote>
                    <p>Example response (404):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No se ha encontrado el usuario solicitado.&quot;
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-users--id--stats" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-users--id--stats"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-users--id--stats"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-users--id--stats" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-users--id--stats">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-users--id--stats" data-method="GET"
                data-path="api/v1/users/{id}/stats"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users--id--stats', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-users--id--stats"
                        onclick="tryItOut('GETapi-v1-users--id--stats');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-users--id--stats"
                        onclick="cancelTryOut('GETapi-v1-users--id--stats');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-users--id--stats"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/users/{id}/stats</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-users--id--stats"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-users--id--stats"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="id" data-endpoint="GETapi-v1-users--id--stats"
                        value="1"
                        data-component="url">
                    <br>
                    <p>ID del usuario. Example: <code>1</code></p>
                </div>
            </form>

            <h2 id="usuarios-GETapi-v1-recommendations">Recomendaciones para el usuario</h2>

            <p>
                <small class="badge badge-darkred">requires authentication</small>
            </p>

            <p>Obtiene recomendaciones de cervezas personalizadas para el usuario autenticado.</p>

            <span id="example-requests-GETapi-v1-recommendations">
                <blockquote>Example request:</blockquote>


                <div class="bash-example">
                    <pre><code class="language-bash">curl --request GET \
    --get "http://127.0.0.1:8000/api/v1/recommendations?limit=10" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"limit\": 16
}"
</code></pre>
                </div>


                <div class="javascript-example">
                    <pre><code class="language-javascript">const url = new URL(
    "http://127.0.0.1:8000/api/v1/recommendations"
);

const params = {
    "limit": "10",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "limit": 16
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre>
                </div>

            </span>

            <span id="example-responses-GETapi-v1-recommendations">
                <blockquote>
                    <p>Example response (200):</p>
                </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 25,
            &quot;name&quot;: &quot;La Chouffe&quot;,
            &quot;brewery&quot;: {
                &quot;id&quot;: 12,
                &quot;name&quot;: &quot;Brasserie d&#039;Achouffe&quot;
            },
            &quot;style&quot;: {
                &quot;id&quot;: 6,
                &quot;name&quot;: &quot;Belgian Strong Golden Ale&quot;
            },
            &quot;abv&quot;: 8,
            &quot;description&quot;: &quot;Cerveza belga dorada con notas de frutas y especias&quot;,
            &quot;image_url&quot;: &quot;https://example.com/beers/lachouffe.png&quot;,
            &quot;rating_avg&quot;: 4.2,
            &quot;check_ins_count&quot;: 87,
            &quot;recommendation_reason&quot;: &quot;Basado en tu gusto por cervezas belgas&quot;
        }
    ]
}</code>
 </pre>
            </span>
            <span id="execution-results-GETapi-v1-recommendations" hidden>
                <blockquote>Received response<span
                        id="execution-response-status-GETapi-v1-recommendations"></span>:
                </blockquote>
                <pre class="json"><code id="execution-response-content-GETapi-v1-recommendations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
            </span>
            <span id="execution-error-GETapi-v1-recommendations" hidden>
                <blockquote>Request failed with error:</blockquote>
                <pre><code id="execution-error-message-GETapi-v1-recommendations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
            </span>
            <form id="form-GETapi-v1-recommendations" data-method="GET"
                data-path="api/v1/recommendations"
                data-authed="1"
                data-hasfiles="0"
                data-isarraybody="0"
                autocomplete="off"
                onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-recommendations', this);">
                <h3>
                    Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                        style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-tryout-GETapi-v1-recommendations"
                        onclick="tryItOut('GETapi-v1-recommendations');">Try it out ⚡
                    </button>
                    <button type="button"
                        style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-canceltryout-GETapi-v1-recommendations"
                        onclick="cancelTryOut('GETapi-v1-recommendations');" hidden>Cancel 🛑
                    </button>&nbsp;&nbsp;
                    <button type="submit"
                        style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                        id="btn-executetryout-GETapi-v1-recommendations"
                        data-initial-text="Send Request 💥"
                        data-loading-text="⏱ Sending..."
                        hidden>Send Request 💥
                    </button>
                </h3>
                <p>
                    <small class="badge badge-green">GET</small>
                    <b><code>api/v1/recommendations</code></b>
                </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Content-Type" data-endpoint="GETapi-v1-recommendations"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                    <input type="text" style="display: none"
                        name="Accept" data-endpoint="GETapi-v1-recommendations"
                        value="application/json"
                        data-component="header">
                    <br>
                    <p>Example: <code>application/json</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                <div style="padding-left: 28px; clear: unset;">
                    <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="limit" data-endpoint="GETapi-v1-recommendations"
                        value="10"
                        data-component="query">
                    <br>
                    <p>Número de recomendaciones a obtener (1-20). Example: <code>10</code></p>
                </div>
                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
                <div style=" padding-left: 28px;  clear: unset;">
                    <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
                    <small>integer</small>&nbsp;
                    <i>optional</i> &nbsp;
                    <input type="number" style="display: none"
                        step="any" name="limit" data-endpoint="GETapi-v1-recommendations"
                        value="16"
                        data-component="body">
                    <br>
                    <p>Must be at least 1. Must not be greater than 20. Example: <code>16</code></p>
                </div>
            </form>




        </div>
        <div class="dark-box">
            <div class="lang-selector">
                <button type="button" class="lang-button" data-language-name="bash">bash</button>
                <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
            </div>
        </div>
    </div>
</body>

</html>