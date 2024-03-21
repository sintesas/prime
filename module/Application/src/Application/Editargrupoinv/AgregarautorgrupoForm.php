<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class AgregarautorgrupoForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
       
       parent::setAttribute('method', 'post');
       parent::setAttribute('action ', 'usuario');
       
       //create field
        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type'  => 'text',
                'maxlength' => 50
            ),
            'options' => array(
                'label' => 'Filtro documento:',
            ),
        ));

        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                 'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Filtro nombre:',
            )
        ));


        $this->add(array(
            'name' => 'apellido',
            'attributes' => array(
                'type'  => 'text',
                'maxlength' => 50
            ),
            'options' => array(
                'label' => 'Filtro apellido:',
            ),
        ));

        $this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                 'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Filtro usuario:',
            )
        ));
        
        $this->add(array(
          'name'=>'submit',
          'attributes'=>array(
            'type'=>'submit',
            'required'=>'required',
            'class'=>'btn',
            'value'=>'Buscar',
          ),
       ));
    }
}

