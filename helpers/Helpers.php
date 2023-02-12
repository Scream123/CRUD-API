<?php

namespace helpers;

class Helpers
{
    /**
     * @param $data
     * @return string
     */
    public static function clearData($data): string
    {
        return trim(strip_tags(stripslashes(htmlspecialchars(htmlentities($data)))));
    }
}
