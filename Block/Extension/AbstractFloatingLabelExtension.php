<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\Extension;

use Fxp\Component\Block\AbstractTypeExtension;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Floating Label Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
abstract class AbstractFloatingLabelExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $form, array $options)
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
    }
}
