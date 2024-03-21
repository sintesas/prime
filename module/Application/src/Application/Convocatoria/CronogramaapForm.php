<?php
namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class CronogramaapForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create fields
        $this->add(array(
            'name' => 'nombre_actividad',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 500
            ),
            'options' => array(
                'label' => 'Nombre de la actividad:',
            ),
        ));
        
        $this->add(array(
            'name' => 'fecha_inicio',
            'attributes' => array(
                'type' => 'Date',
                'required' => 'required',
                'placeholder' => 'YYYY-MM-DD'
            ),
            'options' => array(
                'label' => 'Fecha Inicio:'
            )
        ));
        
        $this->add(array(
            'name' => 'fecha_cierre',
            'attributes' => array(
                'type' => 'Date',
                'required' => 'required',
                'placeholder' => 'YYYY-MM-DD'
            ),
            'options' => array(
                'label' => 'Fecha Fin:'
            )
        ));
        
        $this->add(array(
            'name' => 'objetivo',
            'attributes' => array(
                'type' => 'textarea',
                'placeholder' => 'Ingrese el objetivo',
                'lenght' => 500
            ),
            'options' => array(
                'label' => 'Objetivo de la actividad:'
            )
        ));

        $this->add(array(
            'name' => 'objetivo_aplicari',
            'attributes' => array(
                'type' => 'textarea',
                'placeholder' => 'Ingrese el objetivo',
                'lenght' => 500
            ),
            'options' => array(
                'label' => 'Objetivo de la actividad:'
            )
        ));
        
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 500
            ),
            'options' => array(
                'label' => 'Descripción de la actividad:',
            ),
        ));

        $this->add(array(
            'name' => 'id_meta',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_meta'
            ),
            'options' => array(
                'label' => 'Objetivo:'
            )
        ));

        $this->add(array(
            'name' => 'id_rolresponsable',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_rolresponsable'
            ),
            'options' => array(
                'label' => 'Rol responsable:'
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

