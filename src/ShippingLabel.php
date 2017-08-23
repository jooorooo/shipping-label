<?php
/**
 * ShippingLabel class
 */
namespace ShippingLabel;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use ShippingLabel\Common\PageSize;
use ShippingLabel\Common\Template;
use ShippingLabel\Common\TemplateCompiler;

class ShippingLabel
{
    /**
     * @var \Mpdf\Mpdf
     */
    protected $mPdf;

    /**
     * @var \ShippingLabel\Common\Template[]
     */
    protected $templates = [];

    /**
     * @var string
     */
    protected $template = 'a6';

    /**
     * @var string
     */
    protected $protection = ['print'];

    /**
     * @var string
     */
    protected $display_mode = 'fullpage';

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $date_send;

    /**
     * @var string
     */
    protected $date_delivery;

    /**
     * @var string
     */
    protected $weight;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $tracking;

    public function __construct(array $config = [])
    {
        $this->initConstructorParams($config);
        $this->registerCoreTemplates();
        $this->initMpdf();
    }

    /**
     * @param array $config
     * @return $this
     * @throws \ShippingLabel\ShippingLabelException
     */
    protected function initConstructorParams(array $config = [])
    {
        $constructor = [
            'protection', 'display_mode', 'title', 'author',
            'template', 'from', 'to', 'date_send', 'date_delivery',
            'weight', 'content', 'tracking'

        ];

        foreach ($constructor as $key) {
            if (isset($config[$key])) {
                $this->{$key} = $config[$key];
            }
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \ShippingLabel\ShippingLabelException
     * @throws \Mpdf\MpdfException
     */
    protected function initMpdf()
    {
        $template = $this->getTemplateObject();
        $this->mPdf = new Mpdf($template->toArray());
        if (!empty($this->protection)) {
            $this->mPdf->SetProtection((array)$this->protection);
        }
        if (!empty($this->display_mode)) {
            $this->mPdf->SetDisplayMode($this->display_mode);
        }
        if (!empty($this->title)) {
            $this->mPdf->SetTitle($this->title);
        }
        if (!empty($this->author)) {
            $this->mPdf->SetAuthor($this->author);
        }

        $template_compiler = new TemplateCompiler();
        $template_data = $template_compiler->compile($template->getTemplateHtml());

        $this->mPdf->WriteHTML($template_data);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return \ShippingLabel\Common\Template
     * @throws \ShippingLabel\ShippingLabelException
     */
    public function getTemplateObject()
    {
        if (!array_key_exists($template = $this->getTemplate(), $this->templates)) {
            throw new ShippingLabelException(sprintf('Template "%s" is not registered or missing!', $template));
        }
        return $this->templates[$template];
    }

    /**
     * @param string|null $template
     * @return $this
     * @throws \ShippingLabel\ShippingLabelException
     */
    public function useTemplate($template = null)
    {
        if (!is_null($template)) {
            $this->template = $template;
        }
        return $this->initMpdf();
    }

    /**
     * @param \ShippingLabel\Common\Template $template
     * @return $this
     * @throws \ShippingLabel\ShippingLabelException
     */
    public function setTemplate(Template $template)
    {
        $key = $template->getKey();
        if (array_key_exists($key, $this->templates)) {
            throw new ShippingLabelException(sprintf('Template "%s" is already registered!', $key));
        }
        $this->templates[$key] = $template;
        return $this;
    }

    /**
     * @return string
     * @throws \Mpdf\MpdfException
     */
    public function output()
    {
        $this->mPdf->Output('', Destination::INLINE);
    }

    /**
     * @return string
     * @throws \Mpdf\MpdfException
     */
    public function string()
    {
        return $this->mPdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * @param string $file
     * @return bool
     */
    public function save($file)
    {
        try {
            $this->mPdf->Output($file, Destination::FILE);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Register core templates
     * @throws \ShippingLabel\ShippingLabelException
     */
    protected function registerCoreTemplates()
    {
        $this->setTemplate(new Template('a6', __DIR__ . '/templates/a6.html', [
            'page_size' => PageSize::A6,
            'orientation' => 'L'
        ]));
    }

}
