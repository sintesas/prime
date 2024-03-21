<?php
namespace Application\Editarredinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ProyectoredForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');

        //create field
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);
        
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Ingrese el nombre del proyecto',
                'style' => 'max-width: 100%;width: 100%;',
                'required'=>'required'
            ),
            'options' => array(
                'label' => 'Nombre del proyecto:',
            ),
        ));

        $this->add(array(
            'name' => 'codigo',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Ingrese el código del proyecto',
                'required'=>'required'
            ),
            'options' => array(
                'label' => 'Código del proyecto:',
            ),
        ));


        $this->add(array(
            'name' => 'tipo',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Ingrese el tipo de proyecto',
                'required'=>'required'
            ),
            'options' => array(
                'label' => 'Tipo de proyecto:',
            ),
        ));

        $today = date("Y-m-d");

        $this->add(array(
            'name' => 'fecha_inicio',
            'attributes' => array(
                'type'  => 'date',
                'max' => $today,
                'required'=>'required'
            ),
            'options' => array(
                'label' => 'Fecha inicio',
            ),
        ));

        $this->add(array(
            'name' => 'fecha_fin',
            'attributes' => array(
                'type'  => 'date',
                 'max' => $today
            ),
            'options' => array(
                'label' => 'Fecha fin:',
                'format' => 'Y-m-d',
            ),
        ));

        $this->add(array(
            'name' => 'resumen',
            'attributes' => array(
                'type'  => 'textarea',
                'placeholder' => 'Ingrese el resumen del proyecto'
            ),
            'options' => array(
                'label' => 'Resumen ejecutivo:'
            ),
        ));

        $this->add(array(
            'name' => 'objetivo',
            'attributes' => array(
                'type'  => 'textarea',
                'placeholder' => 'Ingrese el objetivo del proyecto'
            ),
            'options' => array(
                'label' => 'Objetivo general:',
            ),
        ));

        $this->add(array(
            'name' => 'productos',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Productos derivados:',
                'placeholder' => 'Ingrese los productos derivados del proyecto'
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

