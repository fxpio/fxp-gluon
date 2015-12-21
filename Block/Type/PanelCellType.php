<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\GluonBundle\Block\Type;

use Sonatra\Bundle\BlockBundle\Block\AbstractType;
use Sonatra\Bundle\BlockBundle\Block\BlockBuilderInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Sonatra\Bundle\BlockBundle\Block\Exception\InvalidConfigurationException;
use Sonatra\Bundle\BlockBundle\Block\Extension\Core\Type\FormType;
use Sonatra\Bundle\BlockBundle\Block\Util\BlockUtil;
use Sonatra\Bundle\BootstrapBundle\Block\Type\ButtonType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Panel Cell Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelCellType extends AbstractType
{
    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * Constructor.
     *
     * @param PropertyAccessorInterface $propertyAccessor The property accessor
     */
    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        if (null !== $options['help']) {
            $hOpts = array_replace($options['help_options'], array(
                'label' => '?',
                'style' => 'info',
                'size' => 'xs',
                'attr' => array('class' => 'panel-cell-help'),
                'popover' => $options['help'],
            ));

            $builder->add($builder->getName().'_help', ButtonType::class, $hOpts);
        }

        if (null !== $options['form_name']) {
            $builder->add($options['form_name'], FormType::class, array('block_name' => $options['form_name']));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addParent(BlockInterface $parent, BlockInterface $block, array $options)
    {
        if (!BlockUtil::isBlockType($parent, array(PanelSectionType::class, PanelRowType::class))) {
            $msg = 'The "panel_cell" parent block (name: "%s") must be a "panel_section" block type';
            throw new InvalidConfigurationException(sprintf($msg, $block->getName()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if ($options['property_path'] && (is_object($block->getData()) || is_array($block->getData()))) {
            $value = $this->propertyAccessor->getValue($block->getData(), $options['property_path']);

            $view->vars = array_replace($view->vars, array(
                'data' => $value,
                'value' => $value,
            ));
        }

        BlockUtil::addAttributeClass($view, 'control-label', true, 'label_attr');

        if (null !== $options['label_style']) {
            BlockUtil::addAttributeClass($view, 'control-label-'.$options['label_style'], false, 'label_attr');
        }

        $view->vars = array_replace($view->vars, array(
            'control_attr' => $options['control_attr'],
            'layout_col_size' => $options['layout_size'],
            'layout_col_width' => $options['layout'],
            'layout_col_max' => $options['layout_max'],
            'layout_style' => $options['layout_style'],
            'label_style' => $options['label_style'],
            'rendered' => $options['rendered'],
            'hidden' => $options['hidden'],
            'value_formatter' => $options['formatter'],
            'value_formatter_options' => $options['formatter_options'],
        ));

        if ($view->vars['value'] === $options['empty_message']) {
            $view->vars['value_formatter'] = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (in_array('button', $child->vars['block_prefixes'])) {
                $view->vars['button_help'] = $child;
                unset($view->children[$name]);
            } elseif (in_array('form', $child->vars['block_prefixes']) && isset($child->vars['block_form'])) {
                /* @var FormView $form */
                $form = $child->vars['block_form'];
                $view->vars['has_form'] = $form;
                $form->vars['label'] = ' ';

                if (count($form->vars['errors']) > 0) {
                    BlockUtil::addAttributeClass($view, 'has-error', false, 'control_attr');
                }

                if ($form->vars['required']) {
                    BlockUtil::addAttributeClass($view, 'required', false, 'label_attr');
                }

                if (in_array('repeated', $form->vars['block_prefixes'])) {
                    BlockUtil::addAttributeClass($view, 'block-repeated', false, 'control_attr');

                    foreach ($form->children as $childForm) {
                        $childForm->vars['display_label'] = false;
                        break;
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => function (Options $options) {
                return null !== $options['property_path'];
            },
            'formatter' => null,
            'formatter_options' => array(),
            'control_attr' => array(),
            'layout_size' => 'sm',
            'layout' => 12,
            'layout_max' => 12,
            'layout_style' => null,
            'label_style' => null,
            'rendered' => true,
            'hidden' => false,
            'help' => null,
            'help_options' => array(),
            'form_name' => function (Options $options) {
                return is_string($options['property_path'])
                    ? $options['property_path']
                    : null;
            },
        ));

        $resolver->addAllowedTypes('formatter', array('null', 'string', 'Sonatra\Bundle\BlockBundle\Block\BlockTypeInterface'));
        $resolver->addAllowedTypes('formatter_options', 'array');
        $resolver->addAllowedTypes('control_attr', 'array');
        $resolver->addAllowedTypes('layout_size', 'string');
        $resolver->addAllowedTypes('layout', 'int');
        $resolver->addAllowedTypes('layout_max', 'int');
        $resolver->addAllowedTypes('layout_size', array('null', 'string'));
        $resolver->addAllowedTypes('label_style', array('null', 'string'));
        $resolver->addAllowedTypes('rendered', 'bool');
        $resolver->addAllowedTypes('hidden', 'bool');
        $resolver->addAllowedTypes('help', array('null', 'string', 'array'));
        $resolver->addAllowedTypes('help_options', 'array');
        $resolver->addAllowedTypes('form_name', array('null', 'string'));

        $resolver->addAllowedValues('layout_size', array('sm', 'md', 'lg'));
        $resolver->addAllowedValues('layout_style', array(null, 'horizontal', 'vertical'));
        $resolver->addAllowedValues('label_style', array(
            null,
            'default',
            'primary',
            'accent',
            'success',
            'info',
            'warning',
            'danger',
        ));

        $resolver->setNormalizer('layout', function (Options $options, $value) {
            $value = max($value, 1);
            $value = min($value, $options['layout_max']);

            return $value;
        });
        $resolver->setNormalizer('help', function (Options $options, $value) {
            if (null === $value) {
                return $value;
            } elseif (is_string($value)) {
                $value = array(
                    'content' => $value,
                );
            }

            $value = array_replace(array(
                'html' => true,
                'placement' => 'auto top',
            ), $value);

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'panel_cell';
    }
}
