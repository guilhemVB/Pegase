<?php

namespace AppCommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    private $commentClass;

    public function __construct($commentClass)
    {
        $this->commentClass = $commentClass;
    }

    /**
     * Configures a Comment form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('body', 'textarea');
        $builder->add('price', 'choice', ['choices' => [
            0   => "",
            5   => "moins de 10€",
            10  => "entre 10€ et 15€",
            15  => "entre 15€ et 20€",
            20  => "entre 20€ et 25€",
            25  => "entre 25€ et 30€",
            30  => "entre 30€ et 35€",
            35  => "entre 35€ et 40€",
            40  => "entre 40€ et 45€",
            45  => "entre 45€ et 50€",
            50  => "entre 50€ et 55€",
            55  => "entre 55€ et 60€",
            60  => "entre 60€ et 65€",
            65  => "entre 65€ et 70€",
            70  => "entre 70€ et 75€",
            75  => "entre 75€ et 80€",
            80  => "entre 80€ et 85€",
            85  => "entre 85€ et 90€",
            90  => "entre 90€ et 95€",
            95  => "entre 95€ et 100€",
            100 => "plus de 100€",
        ]]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->commentClass,
        ));
    }

    public function getName()
    {
        return "fos_comment_comment";
    }
}
