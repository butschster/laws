<?php

namespace App\Contracts\Law;

interface UserInterface
{
    /**
     * @return string
     */
    public function fullName(): string;

    /**
     * @return string
     */
    public function address(): string;

    /**
     * @return string
     */
    public function phone(): string;
}