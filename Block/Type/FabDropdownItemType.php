<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Block\Type;

use Sonatra\Component\Block\AbstractType;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\BlockView;
use Sonatra\Component\Bootstrap\Block\Type\DropdownItemType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FabDropdownItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'style' => $options['style'],
            'size' => $options['size'],
            'fab_label' => $options['fab_label'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'style' => null,
            'size' => null,
            'fab_label' => null,
        ));

        $resolver->setAllowedTypes('style', array('null', 'string'));
        $resolver->setAllowedTypes('size', array('null', 'string'));
        $resolver->setAllowedTypes('fab_label', array('null', 'string'));

        $resolver->setAllowedValues('style', array(
            null, 'default', 'primary', 'accent', 'success', 'info', 'warning', 'danger',
        ));
        $resolver->setAllowedValues('size', array(null, 'xs', 'sm', 'lg'));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DropdownItemType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fab_dropdown_item';
    }
}
