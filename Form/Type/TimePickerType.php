<?php
/*
 * This file is part of NeutronFormBundle
 *
 * (c) Nikolay Georgiev <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\FormBundle\Form\Type;

use Symfony\Component\Form\FormView;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\OptionsResolver\Options;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;

use Symfony\Component\Form\ReversedTransformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\AbstractType;

/**
 * This class creates jquery timepicker element
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class TimePickerType extends AbstractType
{
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addViewTransformer(
            new DateTimeToStringTransformer($options['date_timezone'], $options['user_timezone'], $options['time_format'])
        );

        if ('string' === $options['input']) {
            $builder->addModelTransformer(new ReversedTransformer(
                new DateTimeToStringTransformer($options['date_timezone'], $options['date_timezone'], $options['time_format'])
            ));
        } elseif ('timestamp' === $options['input']) {
            $builder->addModelTransformer(new ReversedTransformer(
                new DateTimeToTimestampTransformer($options['date_timezone'], $options['date_timezone'])
            ));
        } elseif ('array' === $options['input']) {
            $builder->addModelTransformer(new ReversedTransformer(
                new DateTimeToArrayTransformer($options['date_timezone'], $options['date_timezone'], $options['parts'])
            ));
        }
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildView()
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['configs'] = $options['configs'];
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    
        $defaultConfigs = array(
            'showOn' => 'button',
            'timeFormat' => 'HH:mm',
            'showSecond' => false,
        );
    
        $resolver->setDefaults(array(
            'translation_domain' => 'NeutronFormBundle',
            'input' => 'datetime',
            'with_seconds' => false,
            'use_meridiem' => false,
            'date_timezone' => null,
            'user_timezone' => null,
            'time_format' => 'H:i',
            'parts' => array('hour', 'minute'),
            'configs' => $defaultConfigs
        ));
    
        $resolver->setNormalizers(array(
            'time_format' => function (Options $options, $value) {
                if ($options->has('with_seconds') && $options->get('with_seconds') === true){
                    return ($options->get('use_meridiem') === true) ? 'h:i:s a' : 'H:i:s';
                } else {
                    return ($options->get('use_meridiem') === true) ? 'h:i a' : 'H:i';
                } 
            },
            'parts' => function (Options $options, $value){
                if ($options->has('with_seconds') && $options->get('with_seconds') === true){
                    return  array('hour', 'minute', 'second');
                } else {
                    return array('hour', 'minute');
                }
            },
            'configs' => function (Options $options, $value) use ($defaultConfigs) {
                $configs = array_replace_recursive($defaultConfigs, $value);

                if (!$options->has('with_seconds') || $options->get('with_seconds') === false){
                    $configs['timeFormat'] =  ($options->get('use_meridiem') === true) ? 'hh:mm tt' : 'HH:mm';
                    $configs['showSecond'] = false;
                } else {
                    $configs['timeFormat'] =  ($options->get('use_meridiem') === true) ? 'hh:mm:ss tt' : 'HH:mm:ss';
                    $configs['showSecond'] = true;
                }

                return $configs;
            }
        ));
    
        $resolver->setAllowedValues(array(
            'input' => array(
                'datetime',
                'string',
                'timestamp',
                'array',
            ),
            'time_format' => array(
                'H:i:s',
                'h:i:s a',
                'H:i',
                'h:i a'
            ),
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::getParent()
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_timepicker';
    }

}