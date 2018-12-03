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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Static Floating Label Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
abstract class AbstractStaticFloatingLabelExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'floating_label' => $options['floating_label'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'floating_label' => false,
        ]);

        $resolver->setAllowedTypes('floating_label', 'bool');

        $resolver->setNormalizer('floating_label', function (Options $options, $value) {
            return 'horizontal' === $options['layout']
                ? false
                : $value;
        });
    }
}
