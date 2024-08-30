<?php
	namespace Application\Excelusuariostodo;

	use Zend\Form\Form;
	use Zend\Form\Element;

    class ExcelusuariostodoForm extends Form
    {
        function __construct()
        {
            parent::__construct($name = null);            
            parent::setAttribute('method', 'post');
            parent::setAttribute('action ', 'usuario');
            
            // create fields
            $this->add(array(
                'name' => 'primer_nombre',
                
                'attributes' => array(
                    'type' => 'text',
                    'size' => 25,
                    'placeholder' => 'Ingrese el primer nombre'
                ),
                'options' => array(
                    'label' => 'Primer Nombre:'
                )
            ));
            
            $this->add(array(
                'name' => 'segundo_nombre',
                'attributes' => array(
                    'type' => 'text',
                    'size' => 25,
                    'placeholder' => 'Ingrese el segundo nombre'
                ),
                'options' => array(
                    'label' => 'Segundo Nombre:'
                )
            ));
            
            $this->add(array(
                'name' => 'primer_apellido',
                'attributes' => array(
                    'type' => 'text',
                    'size' => 25,
                    'placeholder' => 'Ingrese el primer apellido'
                ),
                'options' => array(
                    'label' => 'Primer Apellido:'
                )
            ));
            
            $this->add(array(
                'name' => 'segundo_apellido',
                'attributes' => array(
                    'type' => 'text',
                    'size' => 25,
                    'placeholder' => 'Ingrese el segundo apellido'
                ),
                'options' => array(
                    'label' => 'Segundo Apellido:'
                )
            ));

            $this->add(array(
                'name' => 'email',
                'attributes' => array(
                    'type' => 'text',
                    'placeholder' => 'Ingrese el correo'
                ),
                'options' => array(
                    'label' => 'Email:'
                )
            ));
            
            $this->add(array(
                'name' => 'documento',
                'attributes' => array(
                    'type' => 'Number',
                    'size' => 15,
                    'placeholder' => 'Ingrese documento'
                ),
                'options' => array(
                    'label' => 'Documento:'
                )
            ));
            
            $this->add(array(
                'name'=>'submit',
                'attributes'=>array(
                  'type'=>'submit',
                  'required'=>'required',
                  'class'=>'btn',
                  'value'=>'Filtrar',
                ),
            ));
        }
    }