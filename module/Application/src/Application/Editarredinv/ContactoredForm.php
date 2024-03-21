<?php
namespace Application\Editarredinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ContactoredForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        $this->add(array(
            'name' => 'correo',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Ingrese el correo electrónico del contacto',
            ),
            'options' => array(
                'label' => 'Correo electrónico:',
            ),
        ));

        $this->add(array(
            'name' => 'paginaweb',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Ingrese la página web del contacto',
            ),
            'options' => array(
                'label' => 'Página Web:',
            ),
        ));

        $this->add(array(
            'name' => 'telefono_1',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Teléfono 1:',
            ),
        ));

        $this->add(array(
            'name' => 'telefono_2',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Teléfono 2:',
            ),
        ));

        $this->add(array(
            'name' => 'redsocial_1',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Red social 1:',
            ),
        ));

        $this->add(array(
            'name' => 'redsocial_2',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Red social 2:',
            ),
        ));

        $this->add(array(
            'name' => 'redsocial_3',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Red social 3:',
            ),
        ));

        $this->add(array(
            'name' => 'otro_contacto',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Otro tipo de contacto:',
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

