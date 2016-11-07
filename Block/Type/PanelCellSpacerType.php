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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Cell Spacer Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelCellSpacerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'hidden' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return PanelCellType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'panel_cell_spacer';
    }
}
