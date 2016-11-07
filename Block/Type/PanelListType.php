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
use Sonatra\Component\Block\BlockBuilderInterface;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\Exception\InvalidConfigurationException;
use Sonatra\Component\Block\Util\BlockUtil;
use Sonatra\Component\Bootstrap\Block\Type\PanelHeaderType;
use Sonatra\Component\Bootstrap\Block\Type\PanelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel List Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
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
            $panels = $block->getAttribute('panels', array());

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
        $resolver->setDefaults(array(
            'selected' => null,
            'groups' => array(),
        ));

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
