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
use Fxp\Component\Block\BlockView;
use Fxp\Component\Block\Extension\Core\Type\FormType;
use Fxp\Component\Block\Extension\Core\Type\TwigType;
use Fxp\Component\Block\Util\BlockUtil;
use Fxp\Component\Bootstrap\Block\Type\TableColumnType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Table Column Select Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TableColumnSelectType extends AbstractType
{
    /**
     * @var string
     */
    protected $resource;

    /**
     * Constructor.
     *
     * @param string $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if ($options['multiple']) {
            $builder->add(BlockUtil::createUniqueName(), FormType::class, [
                'type' => CheckboxType::class,
                'options' => [
                    'required' => false,
                    'label' => ' ',
                    'data' => $options['selected'],
                    'style' => $options['style'],
                    'attr' => [
                        'data-multi-selectable-all' => 'true',
                    ],
                ],
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if (null !== $view->parent && \in_array('table', $view->parent->vars['block_prefixes'])) {
            $view->parent->vars['attr']['data-table-select'] = 'true';
            $view->parent->vars['attr']['data-col-selectable'] = $block->getName();
            $view->parent->vars['column_selection_stype'] = $options['style'];

            if (null !== $options['max_selection']) {
                $view->parent->vars['attr']['data-max-selection'] = $options['max_selection'];
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'multiple' => false,
            'selected' => false,
            'max_selection' => null,
            'style' => 'accent',
            'options' => [],
            'max_width' => 34,
            'width' => 34,
            'formatter' => TwigType::class,
            'footable' => [
                'ignore' => true,
            ],
        ]);

        $resolver->addAllowedTypes('multiple', 'bool');
        $resolver->addAllowedTypes('selected', 'bool');
        $resolver->addAllowedTypes('max_selection', ['null', 'int']);
        $resolver->addAllowedTypes('style', ['null', 'string']);
        $resolver->addAllowedTypes('options', 'array');

        $resolver->setNormalizer('formatter_options', function (Options $options, $value) {
            $variables = isset($value['variables']) ? $value['variables'] : [];
            $variables['multiple'] = $options['multiple'];
            $variables['options'] = $options['options'];
            $variables['options']['data'] = $options['selected'];
            $variables['options']['required'] = false;
            $variables['options']['label'] = ' ';
            $variables['options']['style'] = $options['style'];
            $variables['max_width'] = $options['max_width'];

            $value['variables'] = $variables;
            $value['resource'] = $this->resource;
            $value['resource_block'] = 'table_column_select_content';
            $value['empty_data'] = $options['empty_data'];
            $value['empty_message'] = $options['empty_message'];

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TableColumnType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'table_column_select';
    }
}
