<div align="center">
	<img align="center" src="./public/images/template.logo.svg" alt="logo sigapp" width="172"/>
	<p align="center">
		<h1 align="center" style="font-size:32px;margin:0;border:none;">qgis-server-api</h1>
		<img src="https://img.shields.io/badge/QGIS_Server->=3.4-yellow.svg?logo=Qgis&color=93b023&labelColor=455a64" alt="Qgis"/>
		<img src="https://img.shields.io/badge/PostgreSQL->=9.6-yellow.svg?logo=PostgreSQL&color=336791&labelColor=455a64" alt="Qgis"/>
		<img src="https://img.shields.io/badge/PHP->=7.2-yellow.svg?logo=PHP&color=777bb4&labelColor=455a64" alt="PHP"/>
		<br>
		<img src="https://img.shields.io/badge/License-MIT-blue.svg?color=0288d1&labelColor=455a64" alt="License"/>
	</p>
</div>



# <a name="abstract"></a>Abstract
**qgis-server-api** is a REST API for QGIS Server.


# Features

## QGIS Projects imports
The API makes it possible to load QGIS project files and embed local data.<br>
:heavy_check_mark: QGS, <br>
:heavy_check_mark: QGZ, <br>
:heavy_check_mark: ZIP *[QGS or QGZ + SHP, SQLITE, GeoJSON etc.]*

## Layers administration
:heavy_check_mark: read / update / delete, <br> 
:heavy_check_mark: metadata (source, abstract, attributions), <br> 
:heavy_check_mark: referencing / cataloging (GIS tree folders), <br> 
:heavy_check_mark: filter, <br> 
:heavy_check_mark: attribute table, <br> 
:heavy_check_mark: export (Shapefile, GeoJson, GeoPackage, SQLite, GML, Excel ...), <br> 
:heavy_check_mark: previews (PNG Thumbnails for layers and symbols)

## Datasources connection
:heavy_check_mark: databases connection manager, <br>
:heavy_check_mark: datastore for local data

## GIS catalog
A tree structure under three levels to organize the layers in thematic folders

## Basemaps manager
The import of basemaps from a qgis project (OSM, Carto, Thunderforest, IGN etc.) is detected and managed separately from the layers. <br>
:heavy_check_mark: WMTS, <br>
:heavy_check_mark: TMS

## Maps 
:heavy_check_mark: create / update / delete, <br>
:heavy_check_mark: printing, <br>
:heavy_check_mark: permalink, <br>
:heavy_check_mark: export (QGS + Sqlite or QGS + GeoPackage),

## Portals
A collection of maps



# Dependancies (not included)

- QGIS Server, 
- GDAL/OGR, 
- PostgreSQL/Postgis,
- PHP,
- *(optional)* Chromium for map printing

# <a name="install"></a>Install
To install and run **qgis-server-api**, the configuration below is required :
- Git
- QGIS Server >= 3.4
- Apache 2.4 (or any other HTTP server)
- PHP >= 7.2
- Composer
- ogr2ogr

## Download Github repo
~~~~bash
# Git
cd path/to/www
git clone https://github.com/vincjo/qgis-server-api.git
~~~~
## Preparing the database
- Create a new PostgreSQL database.<br>
- At the root of the folder, copy the ".env.example" file to ".env", and adapt the database connection settings.
<br>

## Install PHP dependancies + migration
~~~~bash
cd /path/to/qgis-server-api 
composer install
./vendor/bin/phinx migrate -c config/config-phinx.php
./vendor/bin/phinx seed:run -c config/config-phinx.php

~~~~

## Config example for Apache 2.4 
~~~~apache
Define WEBROOT "path/to/webroot"

Alias /qgis-server-api/ "${WEBROOT}/qgis-server-api/public/"
<Directory "${WEBROOT}/qgis-server-api/public/">
	RewriteRule .* - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
	Options -Indexes +FollowSymLinks +ExecCGI
	AllowOverride All
	Require all granted
	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteBase /qgis-server-api/
	RewriteRule ^ index.php [QSA,L]
</Directory>
~~~~

<!--
### OPTIONNEL : Générer la doc API
~~~~apache
# installation :
composer global require zircote/swagger-php

# Génération du fichier openapi.json
openapi --format json --output ./public/docs/openapi.json  ./public ./routes
~~~~
-->