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
use Fxp\Component\Block\BlockBuilderInterface;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Bootstrap\Block\Type\TableType;
use Fxp\Component\Gluon\Block\Type\TablePagerType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Table Pager Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TablePagerExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if ($options['pager']) {
            $builder->add('pager', TablePagerType::class, $options['pager_options']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (\in_array('table_pager', $child->vars['block_prefixes'])) {
                $view->vars['pager'] = $child;
                unset($view->children[$name]);
                break;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'pager' => true,
            'pager_options' => [],
        ]);

        $resolver->addAllowedTypes('pager', 'bool');
        $resolver->addAllowedTypes('pager_options', 'array');

        $resolver->setNormalizer('pager_options', function (Options $options, $value) {
            if (!isset($value['empty_type'])) {
                $value['empty_type'] = $options['empty_type'];
            }

            if (!isset($value['empty_options'])) {
                $value['empty_options'] = $options['empty_options'];
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [TableType::class];
    }
}
