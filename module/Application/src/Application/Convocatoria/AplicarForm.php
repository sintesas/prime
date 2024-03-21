<?php
namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class AplicarForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create fields
        
        $this->add(array(
            'name' => 'nombre_proy',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el nombre del proyecto de investigación ',
                'required' => 'required'
            )
            ,
            'options' => array(
                'label' => 'Nombre del Proyecto de Investigación:'
            )
        ));
        
        $this->add(array(
            'name' => 'codigo_proy',
            'attributes' => array(
                'type' => 'text',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Código del Proyecto:'
            )
        ));
        
        $this->add(array(
            'name' => 'duracion',
            'attributes' => array(
                'type' => 'Number',
                'min' => 0,
                'placeholder' => 'Número de períodos académicos y/o meses',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Duración de la Investigación (Períodos/Meses):'
            )
        ));
        
        $this->add(array(
            'name' => 'recursos_funcion',
            'attributes' => array(
                'type' => 'number',
                'min' => 0,
                'placeholder' => 'Ingrese los recursos de funcionamiento',
                'required' => 'required',
                 'onkeyup' => 'sumValores()'
            ),
            'options' => array(
                'label' => 'Recursos de funcionamiento:'
            )
        ));
        
        $this->add(array(
            'name' => 'recursos_inversion',
            'attributes' => array(
                'type' => 'number',
                'min' => 0,
                'placeholder' => 'Ingrese los recursos de inversión u otro UPN',
                'required' => 'required',
                'onkeyup' => 'sumValores()'
            )
            ,
            'options' => array(
                'label' => 'Recursos de inversión y/o otro UPN:'
            )
        ));
        
        $this->add(array(
            'name' => 'total_financia',
            'attributes' => array(
                'type' => 'number',
                'min' => 0,
                'placeholder' => 'Ingrese el total de cofinanciación',
                'required' => 'required',
                'onkeyup' => 'sumValores()'
            ),
            'options' => array(
                'label' => 'Recursos de cofinanciación:'
            )
        ));
        
        $this->add(array(
            'name' => 'total_proy',
            'attributes' => array(
                'type' => 'number',
                'min' => 0,
                'placeholder' => 'Ingrese el total de financiación del proyecto',
                'required' => 'required',
                'readonly' => 'true'
            )
            ,
            'options' => array(
                'label' => 'Total financiación del proyecto:'
            )
        ));
        
        $this->add(array(
            'name' => 'resumen_ejecutivo',
            'attributes' => array(
                'type' => 'textarea',
                'placeholder' => 'Ingrese el resumen ejecutivo',
                'lenght' => 500,
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Resumen Ejecutivo :'
            )
        ));
        
        $this->add(array(
            'name' => 'objetivo_general',
            'attributes' => array(
                'type' => 'textarea',
                'placeholder' => 'Ingrese el objetivo general',
                'lenght' => 500,
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Objetivo General:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn',
                'value' => 'Guardar'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'periodo',
            
            'options' => array(
                'label' => 'Período',
                'value_options' => array(
                    '' => '',
                    'M' => 'Meses',
                    'S' => 'Semestres'
                )
            ),
            'attributes' => array(
                'value' => '1',
                'required' => 'required'
            ) // set selected to '1'

        ));
    }
}

