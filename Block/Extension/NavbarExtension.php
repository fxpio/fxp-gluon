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
use Sonatra\Component\Block\BlockView;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\Util\BlockUtil;
use Sonatra\Component\Bootstrap\Block\Type\NavbarType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Navbar Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class NavbarExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if ($options['hidden']) {
            BlockUtil::addAttributeClass($view, 'hidden');
        }

        if (null !== $options['sidebar_locked']) {
            BlockUtil::addAttribute($view, 'data-navbar-sidebar', 'true');
        }

        if (in_array($options['sidebar_locked'], array('left', 'right'))) {
            BlockUtil::addAttributeClass($view, 'navbar-sidebar-locked-'.$options['sidebar_locked']);
        } elseif ('full_left' === $options['sidebar_locked']) {
            BlockUtil::addAttributeClass($view, 'navbar-sidebar-full-locked-left');
        } elseif ('full_right' === $options['sidebar_locked']) {
            BlockUtil::addAttributeClass($view, 'navbar-sidebar-full-locked-right');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'hidden' => false,
            'sidebar_locked' => null,
        ));

        $resolver->addAllowedTypes('hidden', 'bool');
        $resolver->addAllowedTypes('sidebar_locked', array('null', 'string'));

        $resolver->setAllowedValues('sidebar_locked', array(null, 'left', 'right', 'full_left', 'full_right'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return NavbarType::class;
    }
}
