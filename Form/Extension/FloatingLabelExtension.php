<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Form\Extension;

use Fxp\Component\Block\Util\BlockUtil;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Floating Label Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FloatingLabelExtension extends StaticFloatingLabelExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $block, array $options)
    {
        if ($options['floating_label']) {
            BlockUtil::addAttribute($view, 'data-floating-label', 'true');

            if (!BlockUtil::isEmpty($view->vars['value']) && !\is_object($view->vars['value'])) {
                BlockUtil::addAttributeClass($view, 'has-floating-content');
            }
        }

        parent::buildView($view, $block, $options);
    }
}
