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
use Sonatra\Component\Block\BlockView;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\Util\BlockUtil;
use Sonatra\Component\Bootstrap\Block\Type\ButtonType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Button Navbar Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ButtonNavbarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if ($options['home']) {
            BlockUtil::addAttributeClass($view, 'btn-navbar-home');
        }

        if ($options['sidebar_toggle']) {
            BlockUtil::addAttributeClass($view, 'btn-sidebar-toggle');
        }

        if ($options['sidebar_locked_toggle']) {
            BlockUtil::addAttributeClass($view, 'sidebar-locked-toggle');
        }

        if (null !== $options['group_attr']) {
            $view->vars['btn_group_attr'] = $options['group_attr'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'style' => 'navbar',
            'render_id' => function (Options $options) {
                return $options['sidebar_toggle'] || $options['sidebar_locked_toggle'];
            },
            'sidebar_toggle' => function (Options $options) {
                return $options['sidebar_locked_toggle'];
            },
            'sidebar_locked_toggle' => false,
            'home' => function (Options $options) {
                return $options['sidebar_toggle'] || $options['sidebar_locked_toggle'];
            },
            'group_attr' => null,
        ));

        $resolver->setAllowedTypes('sidebar_toggle', 'bool');
        $resolver->setAllowedTypes('sidebar_locked_toggle', 'bool');
        $resolver->setAllowedTypes('render_id', 'bool');
        $resolver->setAllowedTypes('home', 'bool');
        $resolver->setAllowedTypes('group_attr', array('null', 'array'));

        $resolver->setNormalizer('navbar_group', function (Options $options, $value) {
            if (null === $value) {
                $value = $options['home'] ? false : true;
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ButtonType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'button_navbar';
    }
}
