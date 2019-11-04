<?php

namespace App\DataRetrieval\Database;
use App\DatabaseSource;
use App\FieldSource;

class DB2Connection extends DatabaseConnectionBase
{
    public function __construct(DatabaseSource $dbsource, FieldSource $fieldsource)
    {
        parent::__construct($dbsource, $fieldsource);

        config(['database.connections' => [
            $this->dbSource->dataSource->name => [
                'driver' => 'db2_ibmi_ibm',
                'host' => $this->dbSource->server,
                'port' => $this->dbSource->port,
                'database' => $this->dbSource->db_name,
                'username' => $this->dbSource->username,
                'password' => $this->dbSource->password,
                'driverName' => '{IBM DB2 ODBC DRIVER}',
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'date_format' => 'Y-m-d H:i:s',
                'odbc_keywords' => [
                    'SIGNON' => 3,
                    'SSL' => 0,
                    'CommitMode' => 2,
                    'ConnectionType' => 0,
                    'DefaultLibraries' => '',
                    'Naming' => 0,
                    'UNICODESQL' => 0,
                    'DateFormat' => 5,
                    'DateSeperator' => 0,
                    'Decimal' => 0,
                    'TimeFormat' => 0,
                    'TimeSeparator' => 0,
                    'TimestampFormat' => 0,
                    'ConvertDateTimeToChar' => 0,
                    'BLOCKFETCH' => 1,
                    'BlockSizeKB' => 32,
                    'AllowDataCompression' => 1,
                    'CONCURRENCY' => 0,
                    'LAZYCLOSE' => 0,
                    'MaxFieldLength' => 15360,
                    'PREFETCH' => 0,
                    'QUERYTIMEOUT' => 1,
                    'DefaultPkgLibrary' => 'QGPL',
                    'DefaultPackage' => 'A /DEFAULT(IBM),2,0,1,0',
                    'ExtendedDynamic' => 0,
                    'QAQQINILibrary' => '',
                    'SQDIAGCODE' => '',
                    'LANGUAGEID' => 'ENU',
                    'SORTTABLE' => '',
                    'SortSequence' => 0,
                    'SORTWEIGHT' => 0,
                    'AllowUnsupportedChar' => 0,
                    'CCSID' => 819,
                    'GRAPHIC' => 0,
                    'ForceTranslation' => 0,
                    'ALLOWPROCCALLS' => 0,
                    'DB2SQLSTATES' => 0,
                    'DEBUG' => 0,
                    'TRUEAUTOCOMMIT' => 0,
                    'CATALOGOPTIONS' => 3,
                    'LibraryView' => 0,
                    'ODBCRemarks' => 0,
                    'SEARCHPATTERN' => 1,
                    'TranslationDLL' => '',
                    'TranslationOption' => 0,
                    'MAXTRACESIZE' => 0,
                    'MultipleTraceFiles' => 1,
                    'TRACE' => 0,
                    'TRACEFILENAME' => '',
                    'ExtendedColInfo' => 0,
                ],
            // 'options' => [
                // PDO::ATTR_CASE => PDO::CASE_LOWER,
                // PDO::ATTR_PERSISTENT => false,
                // PDO::I5_ATTR_DBC_SYS_NAMING => false,
                // PDO::I5_ATTR_COMMIT => PDO::I5_TXN_NO_COMMIT,
                // PDO::I5_ATTR_JOB_SORT => false,
                // PDO::I5_ATTR_DBC_LIBL => '',
                // PDO::I5_ATTR_DBC_CURLIB => '',
            // ]
            ]
        ]]);
    }

}
