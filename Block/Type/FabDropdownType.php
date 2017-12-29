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
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Bootstrap\Block\Type\DropdownType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FabDropdownType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'mask_content' => $options['mask_content'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'mask_content' => false,
            'pull_prefix' => 'fab-',
        ));

        $resolver->addAllowedTypes('mask_content', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DropdownType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fab_dropdown';
    }
}
