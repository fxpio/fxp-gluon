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
use Sonatra\Component\Block\Exception\InvalidConfigurationException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Row Spacer Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelRowSpacerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        $msg = 'The "panel_row_spacer" block (name: "%s") can not have children';
        throw new InvalidConfigurationException(sprintf($msg, $child->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'hidden_if_empty' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return PanelRowType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'panel_row_spacer';
    }
}
