<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Twig\Extension;

/**
 * Add the stylesheet links of google fonts.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class GoogleFontsExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $fonts;

    /**
     * Constructor.
     *
     * @param array $fonts The font links
     */
    public function __construct($fonts = array())
    {
        $this->fonts = $fonts;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('stylesheet_google_fonts', array($this, 'addStylesheetGoogleFonts'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Build the stylesheet links of google fonts.
     *
     * @return string
     */
    public function addStylesheetGoogleFonts()
    {
        $str = '';

        foreach ($this->fonts as $url) {
            $str .= sprintf('<link href="%s" rel="stylesheet">', $url);
        }

        return $str;
    }
}
