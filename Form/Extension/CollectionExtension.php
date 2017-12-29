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

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Collection Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class CollectionExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!isset($view->vars['selector'])) {
            return;
        }

        /* @var FormView $selectorView */
        $selectorView = $view->vars['selector'];

        $selectorView->vars = array_replace($selectorView->vars, [
            'row_attr' => $view->vars['row_attr'],
            'display_label' => $view->vars['display_label'],
            'size' => $view->vars['size'],
            'layout' => $view->vars['layout'],
            'layout_col_size' => $view->vars['layout_col_size'],
            'layout_col_label' => $view->vars['layout_col_label'],
            'layout_col_control' => $view->vars['layout_col_control'],
            'validation_state' => $view->vars['validation_state'],
            'static_control' => $view->vars['static_control'],
            'static_control_empty' => $view->vars['static_control_empty'],
            'help_text' => $view->vars['help_text'],
            'help_attr' => $view->vars['help_attr'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return CollectionType::class;
    }
}
