<?php
namespace Application\Proyectos;

use Zend\Form\Form;
use Zend\Form\Element;

class ConsultaproyectoForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create field
        $this->add(array(
            'name' => 'id_proyecto',
            'attributes' => array(
                'type' => 'number',
                'placeholder' => 'Ingrese el ID del proyecto'
            ),
            'options' => array(
                'label' => 'ID del proyecto:'
            )
        ));
        
        $this->add(array(
            'name' => 'codigo_proy',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el código del proyecto'
            ),
            'options' => array(
                'label' => 'Código del proyecto:'
            )
        ));
        
        $this->add(array(
            'name' => 'nombre_proy',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el nombre del proyecto'
            ),
            'options' => array(
                'label' => 'Nombre del proyecto:'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'tipo_proyecto',
            'required' => 'required',
            'options' => array(
                'label' => 'Tipo de proyecto',
                'value_options' => array(
                    '' => 'Seleccione',
                    'I' => 'Interno',
                    'E' => 'Externo-cofinanciado',
                    'S' => 'Especial'
                )
            ),
            'attributes' => array(
                'value' => '1'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'required' => 'required',
                'class' => 'btn',
                'value' => 'Filtrar'
            )
        ));
    }
}

