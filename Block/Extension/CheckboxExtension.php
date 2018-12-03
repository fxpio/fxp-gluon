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
use Fxp\Component\Block\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Checkbox Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class CheckboxExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'style' => $options['style'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'style' => null,
        ]);

        $resolver->setAllowedTypes('style', ['null', 'string']);

        $resolver->setAllowedValues('style', [null, 'default', 'primary', 'accent', 'success', 'info', 'warning', 'danger', 'link']);
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [CheckboxType::class];
    }
}
