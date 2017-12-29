<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\Type;

use Fxp\Component\Block\AbstractType;
use Fxp\Component\Block\BlockBuilderInterface;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\Exception\InvalidConfigurationException;
use Fxp\Component\Block\Util\BlockUtil;
use Fxp\Component\Bootstrap\Block\Type\PanelHeaderType;
use Fxp\Component\Bootstrap\Block\Type\PanelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel List Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PanelListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isBlockType($child, PanelType::class)) {
            $panels = $block->getAttribute('panels', []);

            $block->remove($child->getName());
            $panels[$child->getName()] = $child;

            $block->setAttribute('panels', $panels);
        } elseif (!BlockUtil::isBlockType($child, PanelHeaderType::class)) {
            throw new InvalidConfigurationException('Only "panel" type child must be added into the panel list type');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'selected' => null,
            'groups' => [],
        ]);

        $resolver->setAllowedTypes('groups', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return PanelType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'panel_list';
    }
}
