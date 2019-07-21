<?php


namespace App\DataRetrieval;


interface DataGatewayInterface
{
    public function retrieve($project, $fieldList = []);
}