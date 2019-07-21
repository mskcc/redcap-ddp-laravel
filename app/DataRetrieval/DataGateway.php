<?php

namespace App\DataRetrieval;

interface DataGateway
{
    public function retrieve($project, $fieldList);
}