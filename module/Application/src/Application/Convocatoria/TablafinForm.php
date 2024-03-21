<?php
namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class TablafinForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create fields
        $this->add(array(
            'name' => 'valor',
            'attributes' => array(
                'type' => 'Number',
                'placeholder' => 'Ingrese el valor'
            ),
            'options' => array(
                'label' => 'Valor :'
            )
        ));
        
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type' => 'textarea',
                'placeholder' => 'Ingrese la descripción',
                'lenght' => 5000,
                'encoding' => 'UTF-8',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Descripción :'
            )
        ));
        
        $this->add(array(
            'name' => 'observaciones',
            'attributes' => array(
                'type' => 'textarea',
                'placeholder' => 'Ingrese la observaciones',
                'lenght' => 5000,
                'encoding' => 'UTF-8',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Observaciones :'
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
    }
}

