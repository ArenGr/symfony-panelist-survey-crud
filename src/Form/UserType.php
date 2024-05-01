<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('firstname', TextType::class, [
				'required' => true,
				'constraints' => [
					new Assert\NotBlank([
						'message' => 'Please enter your first name.',
					]),
				],
			])
			->add('lastname', TextType::class, [
				'label' => 'Last Name',
			])
			->add('email', EmailType::class, [
				'label' => 'Email',
				'constraints' => [
					new Assert\NotBlank([
						'message' => 'Please enter your email address.',
					]),
					new Assert\Email([
						'message' => 'The email address "{{ value }}" is not valid.',
					]),
				],
			])
			->add('phone', TextType::class, [
				'label' => 'Phone Number',
				'required' => false,
				'constraints' => [
					new Assert\Regex([
						'pattern' => '/^\d+$/',
						'message' => 'Phone number should contain only digits.',
					]),
				],
			])
			->add('country', TextType::class, [
				'label' => 'Country',
				'required' => false,
			])
			->add('newsletter_agreement', CheckboxType::class, [
				'label' => 'Subscribe to Newsletter',
				'required' => false,
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
