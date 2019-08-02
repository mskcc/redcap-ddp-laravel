# Artisan Dynamic Data Pull
A [Laravel](https://laravel.com/) implementation of the Dynamic Data Pull (DDP) service for [REDCap](https://www.project-redcap.org/). 

Dynamic Data Pull (DDP) is a special feature for importing data into REDCap from an external source system. It provides an adjudication process whereby REDCap users can approve all incoming data from the source system before it is officially saved in their REDCap project.

***Note: This repo is under active development and should not be used in a production environment.***

### Planned Features
- Data, Metadata, and User Access capabilities
- Support for database (MySQL, SQL Server, DB2, and PostgresSQL) and web service data retrieval
- An administration portal to manage your data sources and REDCap project metadata

### Database Platform Configuration
In order to connect to specific database platforms, you'll need to ensure your system is set up appropriately. The following are some helpful links to get you started:

- MySQL
  - https://www.php.net/manual/en/ref.pdo-mysql.php
- SQL Server
  - https://github.com/Microsoft/msphpsql
- DB2
  - https://www.php.net/manual/en/book.ibm-db2.php
- PostgresSQL
  - https://www.php.net/manual/en/pgsql.installation.php

