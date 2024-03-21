<?php
namespace Application\Editarredinv;

use Zend\Form\Form;
use Zend\Form\Element;

class EquipodirectivoForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        $this->add(array(
            'name' => 'cargo',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Nombre del cargo:',
            ),
        ));

        $this->add(array(
            'name' => 'institucion',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Institución:',
            ),
        ));

        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Nombre de la persona:',
            ),
        ));

        $this->add(array(
            'name' => 'pais',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'País:',
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

