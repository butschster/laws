<?php

namespace App\Law;

use App\Contracts\Documents\ElementInterface;
use App\Court as CourtModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpOffice\PhpWord\Element\AbstractContainer;

class Court implements ElementInterface
{

    /**
     * Определение типа суда
     *
     * @param Plaintiff $plaintiff
     * @param Respondent $respondent
     * @param float $amount
     *
     * @return string
     */
    public static function detectType(Plaintiff $plaintiff, Respondent $respondent, float $amount = 0)
    {
        if ( !$plaintiff->isIndividual() && !$respondent->isIndividual()) {
            return \App\Court::TYPE_ARBITR_SUBJ;
        }

        if ($amount > 500000) {
            return \App\Court::TYPE_COMMON;
        }

        return \App\Court::TYPE_MIR;
    }

    /**
     * Определение подсудности
     *
     * @param Plaintiff $plaintiff
     * @param Respondent $respondent
     * @param float $amount
     *
     * @return CourtModel
     * @throws ModelNotFoundException
     */
    public static function detect(Plaintiff $plaintiff, Respondent $respondent, float $amount = 0): CourtModel
    {
        $courtType = static::detectType($plaintiff, $respondent, $amount);

        $address = app(\App\Services\Dadata\ClientInterface::class)
            ->suggest($respondent->factAddress())
            ->first();

        if ($courtType == CourtModel::TYPE_ARBITR_SUBJ) {
            return CourtModel::whereType($courtType)->with(['kladr' => function ($query) use ($address) {
                $query->where('region_kladr_id', $address['data']['region_kladr_id']);
            }])->firstOrFail();
        }

       // TODO: Реализовать поиск мирового суда и общей юрисдикции
    }


    /**
     * @var CourtModel
     */
    private $court;

    /**
     * @param CourtModel $court
     */
    public function __construct(CourtModel $court)
    {
        $this->court = $court;
    }

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container)
    {
        $textRun = $container->addTextRun();
        $textRun->addText($this->court->address);
        $textRun->addText(sprintf(' в %s.', $this->court->name), ['bold' => true]);
    }
}