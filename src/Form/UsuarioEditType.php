<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UsuarioEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('nombre', TextType::class, array(
                'label' => 'Nombre'
            ))
            ->add('apellidos', TextType::class, array(
                'label' => 'Apellidos'
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email'
            ))
            ->add('role', ChoiceType::class, array(
                'choices' => [
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                ]
            ))
            //->add('imagen')
            ;
    }

    public function getSectors(array $sectors){
        $sectores = array();
        $sec = array();
        foreach ($sectors as $key => $sector) {
            //$sec= ['id' => $sector->getId(), 'nombre' => $sector->getNombre()];
            $sectores[$sector->getNombre()] = $sector->getNombre();
        }
        return $sectores;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
            'sectores' => array(),
        ]);
    }
}
