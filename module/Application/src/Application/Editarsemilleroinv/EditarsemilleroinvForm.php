<?php
namespace Application\Editarsemilleroinv;

use Zend\Form\Form;
use Zend\Form\Element;

class EditarsemilleroinvForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');


            
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'required' => 'required',
                'class' => 'btn1',
                'value' => 'Guardar cambios'
            )
        ));
    }
}

