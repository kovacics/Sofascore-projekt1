<?php

namespace src\model\abstractModel;

interface Model extends \Serializable
{

    public function equals(Model $model);

}