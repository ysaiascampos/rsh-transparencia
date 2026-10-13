<?php
namespace App\Classes;


use Illuminate\Database\Eloquent\Model;


abstract class BaseService
{
    abstract public function insert(array $data);

    abstract public function update(Model $solicitud, Model $grupoFamiliar, array $data);

}
