<?php
namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class ContratacionpersonalForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        $this->add(array(
            'name' => 'tipo_vinculacion',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Tipo de vinculación: '
            ),
            'attributes' => array(
                'id' => 'tipo_vinculacion'
            )
        ));

        $this->add(array(
            'name' => 'personas',
            'attributes' => array(
                'type'  => 'number'
            ),
            'options' => array(
                'label' => 'Número de personas:',

            ),
        ));

        $this->add(array(
            'name' => 'objeto',
            'attributes' => array(
                'type'  => 'textarea',
                'lenght' => 2500,
                'maxlength' => 2500,
            ),
            'options' => array(
                'label' => 'Objeto del contrato:',
            ),
        ));

        $this->add(array(
            'name' => 'justificacion',
            'attributes' => array(
                'type'  => 'textarea',
                'lenght' => 2500,
                'maxlength' => 2500,
            ),
            'options' => array(
                'label' => 'Justificación:',
            ),
        ));

        $this->add(array(
            'name' => 'valor',
            'attributes' => array(
                'type'  => 'number'
            ),
            'options' => array(
                'label' => 'Valor solicitado para el contrato:',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'required' => 'required',
                'class' => 'btn',
                'value' => 'Agregar'
            )
        ));
    }
}

