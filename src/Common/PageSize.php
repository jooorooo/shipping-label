<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 23.8.2017 г.
 * Time: 14:12 ч.
 */

namespace ShippingLabel\Common;

use ReflectionClass;
use ShippingLabel\ShippingLabelException;

class PageSize
{

    protected $formats;

    protected static $_instance;

    const FOUR_A0 = '4A0';
    const TWO_A0 = '2A0';
    const A0 = 'A0';
    const A1 = 'A1';
    const A2 = 'A2';
    const A3 = 'A3';
    const A4 = 'A4';
    const A5 = 'A5';
    const A6 = 'A6';
    const A7 = 'A7';
    const A8 = 'A8';
    const A9 = 'A9';
    const A10 = 'A10';
    const B0 = 'B0';
    const B1 = 'B1';
    const B2 = 'B2';
    const B3 = 'B3';
    const B4 = 'B4';
    const B5 = 'B5';
    const B6 = 'B6';
    const B7 = 'B7';
    const B8 = 'B8';
    const B9 = 'B9';
    const B10 = 'B10';
    const C0 = 'C0';
    const C1 = 'C1';
    const C2 = 'C2';
    const C3 = 'C3';
    const C4 = 'C4';
    const C5 = 'C5';
    const C6 = 'C6';
    const C7 = 'C7';
    const C8 = 'C8';
    const C9 = 'C9';
    const C10 = 'C10';
    const RA0 = 'RA0';
    const RA1 = 'RA1';
    const RA2 = 'RA2';
    const RA3 = 'RA3';
    const RA4 = 'RA4';
    const SRA0 = 'SRA0';
    const SRA1 = 'SRA1';
    const SRA2 = 'SRA2';
    const SRA3 = 'SRA3';
    const SRA4 = 'SRA4';
    const LETTER = 'LETTER';
    const LEGAL = 'LEGAL';
    const LEDGER = 'LEDGER';
    const TABLOID = 'TABLOID';
    const EXECUTIVE = 'EXECUTIVE';
    const FOLIO = 'FOLIO';
    const B = 'B';
    const A = 'A';
    const DEMY = 'DEMY';
    const ROYAL = 'ROYAL';

    /**
     * PageSize constructor.
     */
    protected function __construct() {
    }

    /**
     * @return static
     */
    protected static function instance() {
        if(is_null(static::$_instance)) {
            static::$_instance = new static;
        }
        return static::$_instance;
    }

    /**
     * @param $format
     * @return string
     * @throws \ShippingLabel\ShippingLabelException
     */
    public static function validateFormat($format)
    {
        $format = strtoupper($format);
        if (!in_array($format, static::instance()->getConstants())) {
            throw new ShippingLabelException(sprintf('Unknown page format %s', $format));
        }
        return $format;
    }

    /**
     * @return array
     */
    public static function getConstants()
    {
        if(is_null(static::instance()->formats)) {
            $reflection = new ReflectionClass(__CLASS__);
            static::instance()->formats = $reflection->getConstants();
        }
        return static::instance()->formats;
    }
}