<?php

namespace App\Contracts\Law;

interface Person
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

    /**
     * @return string
     */
    public function type(): string;

    /**
     * Статус персоны: Физ лицо
     *
     * @return bool
     */
    public function isIndividual(): bool;

    /**
     * Статус персоны: ИП
     *
     * @return bool
     */
    public function isIndividualBusiness(): bool;

    /**
     * Статус персоны: Юр лицо
     *
     * @return bool
     */
    public function isLegalEntity(): bool;
}