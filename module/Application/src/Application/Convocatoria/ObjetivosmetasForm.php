<?php
namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class ObjetivosmetasForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        $this->add(array(
            'name' => 'meta',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Titulo de la meta:',
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

