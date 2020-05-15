<?php

namespace src\model\abstractModel;

interface DBModel extends Model
{

    public function save();

    public function delete();

}