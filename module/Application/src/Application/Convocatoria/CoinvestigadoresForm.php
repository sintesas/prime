<?php
namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class CoinvestigadoresForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        $this->add(array(
            'name' => 'tipo_documento',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Tipo de documento: '
            ),
            'attributes' => array(
                'id' => 'tipo_documento'
            )
        ));

        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Número de documento:',
            ),
        ));

        $this->add(array(
            'name' => 'apellidos',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Apellidos:',
            ),
        ));

        $this->add(array(
            'name' => 'nombres',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Nombres:',
            ),
        ));

        $this->add(array(
            'name' => 'profesion',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Profesión:',
            ),
        ));

        $this->add(array(
            'name' => 'intitucion',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Nombre de la institución:',
            ),
        ));

        $this->add(array(
            'name' => 'telefono',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Teléfono o celular de contacto:',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Correo electrónico:',
            ),
        ));

        $this->add(array(
            'name' => 'horas',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Número de horas semanales dedicadas al proyecto:',
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

