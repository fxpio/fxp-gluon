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
use Fxp\Component\Block\Exception\InvalidConfigurationException;
use Fxp\Component\Block\Util\BlockFormUtil;
use Fxp\Component\Block\Util\BlockUtil;
use Fxp\Component\Block\Util\BlockViewUtil;
use Fxp\Component\Bootstrap\Block\Type\ButtonType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Panel Cell Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PanelCellType extends AbstractType
{
    /**
     * @var PropertyAccessorInterface
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
        $builder->setAttribute('form_name', $options['form_name']);

        if (null !== $options['help']) {
            $hOpts = array_replace($options['help_options'], array(
                'label' => '?',
                'translation_domain' => false,
                'style' => 'info',
                'size' => 'xs',
                'attr' => array('class' => 'panel-cell-help'),
                'popover' => $options['help'],
            ));

            $builder->add($builder->getName().'_help', ButtonType::class, $hOpts);
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
        $this->injectFormCell($view, $block);

        foreach ($view->children as $name => $child) {
            if (in_array('button', $child->vars['block_prefixes'])) {
                $view->vars['button_help'] = $child;
                unset($view->children[$name]);
            }
        }

        if (isset($view->vars['form_cell']) && $view->vars['form_cell'] instanceof FormView) {
            $form = $view->vars['form_cell'];
            $view->vars['has_form'] = $form;
            $form->vars['label'] = ' ';

            if (count($form->children) > 0) {
                $keys = array_keys($form->children);
                $view->vars['has_form'] = $form->children[$keys[0]];
            }

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
            'hidden' => false,
            'help' => null,
            'help_options' => array(),
            'form_name' => function (Options $options) {
                return is_string($options['property_path'])
                    ? $options['property_path']
                    : null;
            },
        ));

        $resolver->addAllowedTypes('formatter', array('null', 'string', 'Fxp\Component\Block\BlockTypeInterface'));
        $resolver->addAllowedTypes('formatter_options', 'array');
        $resolver->addAllowedTypes('control_attr', 'array');
        $resolver->addAllowedTypes('layout_size', 'string');
        $resolver->addAllowedTypes('layout', 'int');
        $resolver->addAllowedTypes('layout_max', 'int');
        $resolver->addAllowedTypes('layout_size', array('null', 'string'));
        $resolver->addAllowedTypes('label_style', array('null', 'string'));
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

    /**
     * Inject the form of panel cell in view.
     *
     * @param BlockView      $view  The block view
     * @param BlockInterface $block The block
     */
    protected function injectFormCell(BlockView $view, BlockInterface $block)
    {
        $section = BlockViewUtil::getParent($view, 'panel_section');

        if (null !== $section
                && $section->vars['rendered']
                && $view->vars['rendered']
                && null !== $formPath = $block->getConfig()->getAttribute('form_name')) {
            $parentForm = BlockFormUtil::getParentFormView($view);

            if (null !== $parentForm) {
                $formNames = explode('.', $formPath);
                $formCell = $parentForm;

                foreach ($formNames as $formName) {
                    $formCell = $formCell->children[$formName];
                }

                if (null !== $formCell && $formCell !== $parentForm) {
                    $view->vars['form_cell'] = $formCell;
                }
            }
        }
    }
}
