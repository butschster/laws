<?php

namespace App\Services\Crawler\Parsers;

class CourtBalloonParser extends Parser
{

    // Паттерн разбора ответа списка судов
    const PATTERN = "/balloons_user\[\'(?<code>[0-9A-Z]+)\'\]\.length\]\=\{type\:\'(?<type>([a-z]+))\'\,name\:\'(?<name>(.*))\'\,adress\:\'(?<address>(.*))\'\,coord\:\[(?<lat>[0-9]{2,4}\.[0-9]+)\,(?<lon>[0-9]{2,4}\.[0-9]+)\]/";

    /**
     * @param string $js
     *
     * @return array
     */
    public function parse(string $js): array
    {
        $matches = [];

        preg_match_all(static::PATTERN, $js, $matches);

        $data = [];

        foreach ($matches as $group => $groupRows) {
            if (is_numeric($group)) {
                continue;
            }

            foreach ($groupRows as $i => $value) {
                $data[$i][$group] = $value;
            }
        }

        return $data;
    }
}