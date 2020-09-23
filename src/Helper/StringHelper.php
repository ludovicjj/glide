<?php


namespace App\Helper;


class StringHelper
{
    /**
     * Replace last middle dash to dot
     *
     * @param string|null $subject
     * @param string $needle
     * @param string $replacement
     * @return string|null
     */
    public static function replaceLastMiddleDashToDot(
        ?string $subject,
        string $needle = '-',
        string $replacement = '.'
    ): ?string
    {
        if ($subject === null) {
            return null;
        }

        $start = strrpos($subject, $needle);

        if (!$start) {
            return null;
        }

        $end =  strlen($needle);
        return substr_replace($subject, $replacement, $start, $end);
    }
}