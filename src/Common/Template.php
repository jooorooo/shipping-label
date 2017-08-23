<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 23.8.2017 г.
 * Time: 15:30 ч.
 */

namespace ShippingLabel\Common;


use ShippingLabel\ShippingLabelException;

class Template
{

    protected $key;

    protected $mode = 'UTF-8';

    protected $template;

    protected $template_html;

    protected $page_size = 'A4';

    protected $orientation = 'P';

    protected $font = '';

    protected $font_size = 0;

    protected $margin_left = 15;

    protected $margin_right = 15;

    protected $margin_top = 16;

    protected $margin_bottom = 16;

    protected $margin_header = 9;

    protected $margin_footer = 9;

    /**
     * Template constructor.
     * @param string $key
     * @param string $template
     * @param array $config
     */
    public function __construct($key, $template, array $config = []) {
        $this->key = $key;
        $this->template = $template;
        $this->initConstructorParams($config);
    }

    /**
     * @param array $config
     * @return $this
     * @throws \ShippingLabel\ShippingLabelException
     */
    protected function initConstructorParams(array $config = []) {
        $constructor = [
            'mode', 'page_size', 'font_size', 'font',
            'margin_left', 'margin_right', 'margin_top',
            'margin_bottom', 'margin_header', 'margin_footer', 'orientation',
        ];

        foreach ($constructor as $key) {
            if (isset($config[$key])) {
                if($key == 'page_size' && !is_array($config[$key])) {
                    $this->{$key} = PageSize::validateFormat($config[$key]);
                } elseif($key == 'orientation') {
                    $orientation = strtolower($config[$key]);
                    if(!in_array($orientation, ['p', 'l', 'portrait', 'landscape'])) {
                        throw new ShippingLabelException(sprintf('Incorrect orientation: %s', $orientation));
                    }
                    $this->{$key} = $config[$key];
                } else {
                    $this->{$key} = $config[$key];
                }
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getTemplateHtml() {
        if(is_null($this->template_html)) {
            if (is_file($file = $this->getTemplate())) {
                $this->template_html = file_get_contents($file);
            } else {
                $this->template_html = $this->getTemplate();
            }
        }
        return $this->template_html;
    }

    /**
     * @return string
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getPageSize() {
        return $this->page_size;
    }

    /**
     * @return string
     */
    public function getOrientation() {
        return $this->orientation;
    }

    /**
     * @return string
     */
    public function getFont() {
        return $this->font;
    }

    /**
     * @return int
     */
    public function getFontSize() {
        return $this->font_size;
    }

    /**
     * @return int
     */
    public function getMarginLeft() {
        return $this->margin_left;
    }

    /**
     * @return int
     */
    public function getMarginRight() {
        return $this->margin_right;
    }

    /**
     * @return int
     */
    public function getMarginTop() {
        return $this->margin_top;
    }

    /**
     * @return int
     */
    public function getMarginBottom() {
        return $this->margin_bottom;
    }

    /**
     * @return integer
     */
    public function getMarginHeader() {
        return $this->margin_header;
    }

    /**
     * @return integer
     */
    public function getMarginFooter() {
        return $this->margin_footer;
    }

    /**
     * @return array
     */
    public function toArray() {
        return [
            'mode' => $this->getMode(),
            'format' => is_array($this->getPageSize()) ? $this->getPageSize() : $this->getPageSize() . '-' . $this->getOrientation(),
            'default_font_size' => $this->getFontSize(),
            'default_font' => $this->getFont(),
            'margin_left' => $this->getMarginLeft(),
            'margin_right' => $this->getMarginRight(),
            'margin_top' => $this->getMarginBottom(),
            'margin_bottom' => $this->getMarginBottom(),
            'margin_header' => $this->getMarginHeader(),
            'margin_footer' => $this->getMarginFooter(),
            'orientation' => $this->getOrientation(),
        ];
    }
}