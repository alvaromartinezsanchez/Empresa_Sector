# Empresa_Sector
Prueba de nivel en Symfony

Pasos para desplegar el proyecto:

1- Descargar/Clonar el proyecto del repositorio de git en tu servidor.

2- Crear la base de datos, ejecutando el contenido sql del archivo database.sql ubicado en la raiz del proyecto.

3- Instalar dependencias: 
    - composer install
    - yarn install
    - yarn run dev
    - yarn run watch
    
4- Crear un host virtual, yo he utilizado la siguiente configuración: 

    --httpd-vhosts.conf---
    <VirtualHost *:80>   
        DocumentRoot "${INSTALL_DIR}/www/PruebaAttrinium/Empresa_Sector/public"
        ServerName Empresa_Sector.com.devel
        ServerAlias www.Empresa_Sector.com.devel
        <Directory "${INSTALL_DIR}/www/PruebaAttrinium/Empresa_Sector/public">
            Options Indexes FollowSymLinks     
            AllowOverride All
            Order Deny,Allow
            Allow from all     
        </Directory> 
    </VirtualHost>
    
    --hosts---
    127.0.0.1 Empresa_Sector.com.devel
    
