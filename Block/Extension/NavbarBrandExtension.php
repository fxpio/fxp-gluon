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
use Fxp\Component\Bootstrap\Block\Type\NavbarBrandType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Navbar Brand Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class NavbarBrandExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'switchable' => $options['switchable'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'switchable' => false,
        ]);

        $resolver->addAllowedTypes('switchable', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return NavbarBrandType::class;
    }
}
