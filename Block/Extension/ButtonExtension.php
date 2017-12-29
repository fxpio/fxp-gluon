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
use Fxp\Component\Block\Util\BlockUtil;
use Fxp\Component\Bootstrap\Block\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Button Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ButtonExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'navbar_group' => null,
        ));

        $resolver->addAllowedValues('style', array('accent', 'navbar'));
        $resolver->addAllowedTypes('navbar_group', array('null', 'bool'));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        $useGroup = null === $options['navbar_group'] || true === $options['navbar_group'];

        if ('navbar' === $options['style'] && $useGroup) {
            BlockUtil::addAttributeClass($view, 'btn-navbar-group', false, 'btn_group_attr');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ButtonType::class;
    }
}
