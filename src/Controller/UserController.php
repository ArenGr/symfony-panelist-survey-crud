<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\SurveyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
	#[Route('/', name: 'app_user_index', methods: ['GET'])]
	public function index(UserRepository $userRepository): Response
	{
		return $this->render('user/index.html.twig', [
			'users' => $userRepository->findAll(),
		]);
	}

	#[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
	public function new(Request $request, EntityManagerInterface $entityManager): Response
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
		}

		return $this->renderForm('user/new.html.twig', [
			'user' => $user,
			'form' => $form,
		]);
	}

	#[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
	public function show(User $user, SurveyRepository $surveyRepository): Response
	{
		return $this->render('user/show.html.twig', [
			'user' => $user,
			'assignedSurveys' => $user->getSurvey(),
			'surveys' => $surveyRepository->findBy(['isActive' => 1]), // ToDo Get only not assigned surveys
		]);
	}

	#[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
	public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
	{
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->flush();

			return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
		}

		return $this->renderForm('user/edit.html.twig', [
			'user' => $user,
			'form' => $form,
		]);
	}

	#[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
	public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
	{
		if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
			$entityManager->remove($user);
			$entityManager->flush();

			return new JsonResponse(['message' => 'Entity deleted successfully'], Response::HTTP_NO_CONTENT);
		}
		return new JsonResponse(['error' => 'Invalid CSRF token'], Response::HTTP_FORBIDDEN);
	}

	#[Route('/{id}/survey/{surveyId}', name: 'app_user_unassign_survey', methods: ['DELETE'])]
	public function unassignSurvey(Request $request, User $user, EntityManagerInterface $entityManager, SurveyRepository $surveyRepository, $surveyId): Response
	{
		if ($this->isCsrfTokenValid('unassign'.$user->getId(), $request->request->get('_token'))) {
			$survey = $surveyRepository->find($surveyId);
			if (!$survey) {
				return new JsonResponse(null, Response::HTTP_NOT_FOUND);
			}
			try {
				$user->removeSurvey($survey);
				$entityManager->persist($user);
				$entityManager->flush();
				return new JsonResponse(null, Response::HTTP_NO_CONTENT);
			} catch (\Exception $e) {
				return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
			}
		}
		return new JsonResponse(['error' => 'Invalid CSRF token'], Response::HTTP_FORBIDDEN);
	}

	#[Route('/{id}/survey/{surveyId}', name: 'app_user_assign_survey', methods: ['POST'])]
	public function assignSurvey(Request $request, User $user, EntityManagerInterface $entityManager, SurveyRepository $surveyRepository, $surveyId): Response
	{
		if ($this->isCsrfTokenValid('assign'.$user->getId(), $request->request->get('_token'))) {
			$survey = $surveyRepository->find($surveyId);
			if (!$survey) {
				return new JsonResponse(null, Response::HTTP_NOT_FOUND);
			}
			try {
				$user->addSurvey($survey);
				$entityManager->persist($user);
				$entityManager->flush();

				$data = [
					'id' => $survey->getId(),
					'name' => $survey->getName(),
					'status' => $survey->isActive(),
					'createdAt' => $survey->getCreatedAt(),
				];
				return new JsonResponse(['data' => $data], Response::HTTP_CREATED);
			} catch (\Exception $e) {
				return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
			}
		}
		return new JsonResponse(['error' => 'Invalid CSRF token'], Response::HTTP_FORBIDDEN);
	}
}
