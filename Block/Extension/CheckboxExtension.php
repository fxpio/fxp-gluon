<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Block\Extension;

use Sonatra\Component\Block\AbstractTypeExtension;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\BlockView;
use Sonatra\Component\Block\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Checkbox Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CheckboxExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'style' => $options['style'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'style' => null,
        ));

        $resolver->setAllowedTypes('style', array('null', 'string'));

        $resolver->setAllowedValues('style', array(null, 'default', 'primary', 'accent', 'success', 'info', 'warning', 'danger', 'link'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return CheckboxType::class;
    }
}
