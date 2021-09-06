<?php

namespace App\Interfaces;

interface OperationalInt{
    public function all($data);

    public function show($id);
    public function listAvailable($data);
}
