<?php
namespace Application\Proyectos;

use Zend\Form\Form;
use Zend\Form\Element;

class TablaequipopForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create field
        $this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type' => 'text',
                'id' => 'usuario',
                'placeholder' => 'Diligencia el usuario para filtrar'
            ),
            'options' => array(
                'label' => 'Filtro Usuario:'
            )
        ));
        
        $this->add(array(
            'name' => 'primer_nombre',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Diligencia el primer nombre para filtrar'
            ),
            'options' => array(
                'label' => 'Filtro Primer Nombre:'
            )
        ));
        
        $this->add(array(
            'name' => 'segundo_nombre',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Diligencia el segundo nombre para filtrar'
            ),
            'options' => array(
                'label' => 'Filtro Segundo Nombre:'
            )
        ));
        
        $this->add(array(
            'name' => 'primer_apellido',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Diligencia el primer apellido para filtrar'
            ),
            'options' => array(
                'label' => 'Filtro Primer Apellido:'
            )
        ));
        
        $this->add(array(
            'name' => 'segundo_apellido',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Diligencia el segundo apellido para filtrar'
            ),
            'options' => array(
                'label' => 'Filtro Segundo Apellido:'
            )
        ));
        
        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type' => 'Number',
                'placeholder' => 'Diligencia el documento para filtrar'
            ),
            'options' => array(
                'label' => 'Filtro Documento:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'required' => 'required',
                'class' => 'btn',
                'value' => 'Buscar'
            )
        ));
    }
}

