<?php
namespace Application\Login;


use Zend\Form\Element;
use Zend\Form\Element\Captcha;
use Zend\Form\Form;
use Zend\Form\Custom\Captcha\CustomCaptcha;

class LoginForm extends Form

{
    public function __construct($name, $urlcaptcha)
    {
        parent::__construct($name);
        
        
        $this->setAttributes(array(    
            'action' => "",
            'method' => 'post'
        )
        );
        
        // add captcha element...
        $captchaImage = new CustomCaptcha(array(
            'font' =>  realpath('public/images/captcha/arial.ttf'),
            'width' => 200,
            'height' => 100,
            'wordLen' => 5,
            'dotNoiseLevel' => 50,
            'lineNoiseLevel' => 3
        ));
        $captchaImage->setImgDir('public/images/captcha');
        $captchaImage->setImgUrl($urlcaptcha);
        
        
        
        // add captcha element...
        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Ingrese la imagen'
            ),
            'name' => 'captcha',
            'options' => array(
                'label' => 'Verificación de seguridad',
                'captcha' => $captchaImage
            )
        ));

        $this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type' => 'text',
                'size' => 30,
                'required' => 'required',
                'placeholder' => 'Ingrese su usuario'
            ),
            'options' => array(
                'label' => 'Usuario:'
            )
        ));
        
        $this->add(array(
            'name' => 'contrasena',
            'attributes' => array(
                'id' => 'contrasena',
                'type' => 'password',
                'required' => 'required',
                'placeholder' => 'Ingrese su clave',
                'style' => 'max-width:160px'
            ),
            'options' => array(
                'label' => 'Clave:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn',
                'required' => 'required',
                'value' => 'Entrar'
            )
        ));
    }
}



