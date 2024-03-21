<?php
namespace Application\Roles;

use Zend\Form\Form;
use Zend\Form\Element;

class EditarrolesForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Nombre rol:',
            ),
        ));

        $this->add(array(
            'name' => 'observaciones',
            'attributes' => array(
                'type'  => 'textarea'
            ),
            'options' => array(
                'label' => 'Descripción:',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'opcion_pantalla',
            'required' => 'required',
            'options' => array(
                'label' => 'Tipo de menú:',
                'value_options' => array(
                    '1' => 'Menú admin',
                    '2' => 'Menú limitado'
                )
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'required' => 'required',
                'class' => 'btn1',
                'value' => 'Actualizar'
            )
        ));
    }
}

