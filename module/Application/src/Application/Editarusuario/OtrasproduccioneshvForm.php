<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class OtrasproduccioneshvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
       
       parent::setAttribute('method', 'post');
       parent::setAttribute('action ', 'usuario');
       
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);
       
       //create field
        $this->add(array(
            'name' => 'instituciones',
            'attributes' => array(
                'type'  => 'textarea',
                'lenght' => 5000,
                'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Instituciones que participaron en el proyecto:',
            ),
        ));
        
        $this->add(array(
            'name' => 'registro',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Registro:',
            ),
        ));
        
        $this->add(array(
            'name' => 'otra_info',
            'attributes' => array(
                'type'  => 'textarea',
                'lenght' => 5000,
                'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Otra información:',
            ),
        ));
       
        $this->add(array(
            'name' => 'tipo_producto',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Tipo producto:',
            ),
        ));
       
        $this->add(array(
            'name' => 'nombre_producto',
            'attributes' => array(
                'type'  => 'text',
                'maxlength' => 100,
                 'style' => 'max-width: 100%;width: 100%;'
            ),
            'options' => array(
                'label' => 'Nombre producto:',
            ),
        ));

        $this->add(array(
            'name' => 'descripcion_producto',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Descripción del producto:',
            ),
        ));

        $this->add(array(
            'name' => 'id_pais',
            'attributes' => array(
                'maxlength' => 200,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'País:'
            )
        ));

        $this->add(array(
            'name' => 'id_ciudad',
            'attributes' => array(
                'maxlength' => 200,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Ciudad:'
            )
        ));

        $this->add(array(
            'name' => 'ano',
            'attributes' => array(
                'type' => 'Number',
                'min' => 1980,
                'max' => 3000
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Año:'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'mes',
            'options' => array(
                    'label' => 'Mes:',
                    'empty_option' => 'Seleccione un mes',
                    'value_options' => array(
                      '1' => 'Enero',
                      '2' => 'Febrero',
                      '3' => 'Marzo',
                      '4' => 'Abril',
                      '5' => 'Mayo',
                      '6' => 'Junio',
                      '7' => 'Julio',
                      '8' => 'Agosto',
                      '9' => 'Septiembre',
                      '10' => 'Octubre',
                      '11' => 'Noviembre',
                      '12' => 'Diciembre'
                    ),
            ),
            'attributes' => array(
                'required'=>'required'
            )
        ));

        $this->add(array(
            'name' => 'autores',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Autores:',
            ),
        ));

        $this->add(array(
            'name' => 'filtro_autor',
            'attributes' => array(
                'id' => 'filtro_autor',
                'type' => 'Text',
                'placeholder' => 'Filtrar'
            ),
            'options' => array(
                'label' => 'Filtro autor: '
            )
        ));
            
       $this->add(array(
          'name'=>'submit',
          'attributes'=>array(
            'type'=>'submit',
            'required'=>'required',
            'class'=>'btn',
            'value'=>'Agregar',
          ),
       ));
       


    }
}

