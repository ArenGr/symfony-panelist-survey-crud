<?php

namespace App\Form;

use App\Entity\Survey;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SurveyType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void {
		$builder
			->add('name', TextType::class, [
				'constraints' => [
					new Assert\NotBlank([
						'message' => 'Please enter your first name.',
					]),
				],
				'required' => true,
			])
			->add('isActive', CheckboxType::class, [
				'label' => 'Is active?',
				'required' => false,
			]);
			//->add('users');
	}

	public function configureOptions(OptionsResolver $resolver): void {
		$resolver->setDefaults([
			'data_class' => Survey::class,
		]);
	}
}
