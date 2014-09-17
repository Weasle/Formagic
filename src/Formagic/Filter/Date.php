<?php
/**
 * Formagic
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at
 * http://www.formagic-php.net/license-agreement/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@formagic-php.net so we can send you a copy immediately.
 *
 * @author      Florian Sonnenburg
 * @copyright   2007-2014 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Converts the given string into human readable time format.
 *
 * The following input formats are supported:
 *  - integer: $value is interpreted as Unix timestamp
 *  - (string)YYYY-MM-DD | YYYY-MM-DD HH:MM:SS: Date is converted into Unix
 *    timestamp
 * If $value cannot be converted into Unix timestamp, 'n/a' is returned instead.
 *
 * @package     Formagic\Filter
 * @author      Florian Sonnenburg
 * @since       2009 First time introduced
 **/
class Formagic_Filter_Date implements Formagic_Filter_Interface
{
    /**
     * Converts $value into human readable time format and returns the result.
     *
     * By default the time format used is strftime(%x %X).
     *
     * @param string $value The string to be filtered.
     * @return string The date output string.
     */
    public function filter($value)
    {
        if (!$value) {
            return '';
        } elseif (!is_numeric($value)) {
            // assume that date is pre-formatted
            if ($value == '0000-00-00' || $value == '0000-00-00 00:00:00') {
                return 'n/a';
            }

            $dateTime = explode(' ', $value);
            if (isset($dateTime[0])) {
                $date = $dateTime[0];
                $dateParts = explode('-', $date);
                if (isset($dateTime[1])) {
                    $time = $dateTime[1];
                    $timeParts = explode(':', $time);
                    $value = mktime($timeParts[0], $timeParts[1], $timeParts[2],
                        $dateParts[1], $dateParts[2], $dateParts[0]);
                    $format = '%x %X';
                } else {
                    $value = mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]);
                    $format = '%x';
                }
            }
        } else {
            $format = '%x %X';
        }
        $res = utf8_encode(strftime($format, $value));
        return $res;
    }
}