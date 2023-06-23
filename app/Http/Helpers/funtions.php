<?php
function isValidDate($date, $format = 'Y-m-d'): bool
{
    $parsedDate = date_parse_from_format($format, $date);
    return $parsedDate['error_count'] === 0 && $parsedDate['warning_count'] === 0;
}
