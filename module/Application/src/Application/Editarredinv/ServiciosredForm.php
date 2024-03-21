<?php
namespace Application\Editarredinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ServiciosredForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        $this->add(array(
            'name' => 'servicios',
            'attributes' => array(
                'type'  => 'textarea',
                'placeholder' => 'Ingrese los servicios ofrecidos por la red',
                'lenght' => 500,
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Servicios que ofrece:',

            ),
        ));

        $this->add(array(
            'name' => 'eventos',
            'attributes' => array(
                'type'  => 'textarea',
                'placeholder' => 'Ingrese los eventos de la red',
                'lenght' => 500,
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Eventos de la red:',
            ),
        ));

        $this->add(array(
            'name' => 'noticias',
            'attributes' => array(
                'type'  => 'textarea',
                'placeholder' => 'Ingrese las noticias de la red',
                'lenght' => 500,
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Noticias de la red:',
            ),
        ));

        //Creacion de campos
        //create field
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')->setAttribute('id', 'image-file');
        $this->add($file);

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

