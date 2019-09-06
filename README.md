# Artisan Dynamic Data Pull
A [Laravel](https://laravel.com/) implementation of the Dynamic Data Pull (DDP) service for [REDCap](https://www.project-redcap.org/). 

Dynamic Data Pull (DDP) is a special feature for importing data into REDCap from an external source system. It provides an adjudication process whereby REDCap users can approve all incoming data from the source system before it is officially saved in their REDCap project.

***Note: This repo is under active development and should not be used in a production environment until we reach a ``1.x`` milestone.***

### Planned Features
- Data, Metadata, and User Access capabilities
- Support for database (MySQL, SQL Server, DB2, and PostgresSQL) and web service data retrieval
- An administration portal to manage your data sources and REDCap project metadata

### Getting Started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

This project is built with Laravel, and to make local development easier it provides an embedded
[Laravel Homestead](https://laravel.com/docs/5.8/installation) Vagrant box. Vagrant provides a simple, elegant way to manage and provision Virtual Machines.

#### Prerequisites
- [Vagrant](https://www.vagrantup.com/downloads.html)
- [VirtualBox 6.x](https://www.virtualbox.org/wiki/Downloads) (or another [supported alternative](https://laravel.com/docs/5.8/homestead#first-steps))
- [Composer](https://getcomposer.org/) 

#### Next Steps
1. Clone this repository:

    > ``git clone git@github.com:mskcc/redcap-ddp-laravel.git``

2. ``cd`` into the project directory and run ``composer install``.  This will install the vendor packages the project requires.

3. Run the ``make`` command to generate the ``Homestead.yaml`` file in your project root. The ``make`` command will automatically configure the ``sites`` and ``folders`` directives in the Homestead.yaml file.

    **Mac / Linux:**
    
    > ``php vendor/bin/homestead make``
  
    **Windows:**
    
    > ``vendor\\bin\\homestead make``

4. Update the `databases` key in Homestead.yaml to `ddp`.

    ```yaml
    databases:
        - ddp
    ```

5. Create a ``.env`` environment file in the project root. You can copy ``env.example`` for this. Once you've created the file, update the database keys as follows:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ddp
    DB_USERNAME=homestead
    DB_PASSWORD=secret
    ```

6. Next, run the ``vagrant up`` command in your terminal and access your project at ``http://homestead.test`` in your browser. Remember, you will still need to add an ``/etc/hosts`` file entry for ``homestead.test`` or the domain of your choice if you are not using automatic [hostname resolution](https://laravel.com/docs/5.8/homestead#hostname-resolution).

To learn more about how Homestead is configured or for installation specific guidance, please see the [documentation](https://laravel.com/docs/5.8/homestead).

### Database Platform Configuration
In order to connect to specific database platforms, you'll need to ensure your system is set up appropriately. This applies to local development on the Homestead box and when the DDP services are deployed to real servers.

The following are some helpful links to get you started:

- MySQL
  - https://www.php.net/manual/en/ref.pdo-mysql.php
- SQL Server
  - https://github.com/Microsoft/msphpsql
- DB2 (_Not yet supported_)
  - https://www.php.net/manual/en/book.ibm-db2.php
- PostgresSQL
  - https://www.php.net/manual/en/pgsql.installation.php

### Contributing
Contributions are welcome! We generally follow [Git Flow](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow) for feature development. If you have any questions, please reach out to the maintainers!

### Credits / Acknowledgements
Thanks to Weill Cornell Medicine for their work on a similar project that inspired this one:
https://github.com/wcmc-research-informatics/redcap-ddp
