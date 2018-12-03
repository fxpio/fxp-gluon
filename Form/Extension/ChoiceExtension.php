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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Choice Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ChoiceExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!$options['expanded']) {
            return;
        }

        foreach ($view->children as $child) {
            $child->vars['translation_domain'] = $options['choice_translation_domain'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [ChoiceType::class];
    }
}
