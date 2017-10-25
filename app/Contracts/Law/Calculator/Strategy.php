<?php

namespace App\Contracts\Law\Calculator;

interface Strategy
{
    public function calculate(): float;
}