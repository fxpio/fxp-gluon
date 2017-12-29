<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Twig\Extension;

/**
 * Add the stylesheet links of google fonts.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
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
    public function __construct($fonts = [])
    {
        $this->fonts = $fonts;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('stylesheet_google_fonts', [$this, 'addStylesheetGoogleFonts'], ['is_safe' => ['html']]),
        ];
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
