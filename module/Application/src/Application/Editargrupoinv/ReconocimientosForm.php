<?php
namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ReconocimientosForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create field
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type' => 'textarea',
                'placeholder' => 'Ingrese la descripción',
                'lenght' => 500
            ),
            'options' => array(
                'label' => 'Descripción :'
            ),
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 30000
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'num_acto',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el número de acto administrativo'
            ),
            'options' => array(
                'label' => 'Número Acto Admin :'
            )
        ));
        
        $this->add(array(
            'name' => 'valor',
            'attributes' => array(
                'type' => 'Number',
                'placeholder' => 'valor'
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Valor económico reconocido :'
            )
        ));
/*        
        $this->add(array(
            'id' => 'semestre',
            'name' => 'semestre',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => '0000-0',
                'maxlength'=>'6'
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Semestre :'
            )
        ));
*/
        $this->add(array(
            'id' => 'semestre',
            'name' => 'semestre',
            'attributes' => array(
                'type'  => 'date',
                'required'  => 'required'
            ),
            'options' => array(
                'label' => 'Fecha:',
            ),
       ));

        $this->add(array(
            'id' => 'nombre',
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
                'required'  => 'required'
            ),
            'options' => array(
                'label' => 'Nombre del reconocimiento:',
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

