<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterForm;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/character')]
final class CharacterController extends AbstractController
{
    #[Route(name: 'app_character_index', methods: ['GET'])]
    public function index(CharacterRepository $characterRepository): Response
    {
        return $this->render('character/index.html.twig', [
            'characters' => $characterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_character_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterForm::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Обработка портрета
            $portraitFile = $form->get('portrait')->getData();
            if ($portraitFile) {
                $originalFilename = pathinfo($portraitFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$portraitFile->guessExtension();
                try {
                    $portraitFile->move(
                        $this->getParameter('characters_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // обработайте ошибку при загрузке файла
                }
                $character->setPortrait($newFilename);
            }
            // Обработка фонового изображения
            $backgroundFile = $form->get('backgroundImage')->getData();
            if ($backgroundFile) {
                $originalFilename = pathinfo($backgroundFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$backgroundFile->guessExtension();
                try {
                    $backgroundFile->move(
                        $this->getParameter('characters_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // обработайте ошибку при загрузке файла
                }
                $character->setBackgroundImage($newFilename);
            }
            $entityManager->persist($character);
            $entityManager->flush();
            return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('character/new.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_character_show', methods: ['GET'])]
    public function show(Character $character): Response
    {
        return $this->render('character/show.html.twig', [
            'character' => $character,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CharacterForm::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Обработка портрета
            $portraitFile = $form->get('portrait')->getData();
            if ($portraitFile) {
                $originalFilename = pathinfo($portraitFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$portraitFile->guessExtension();
                try {
                    $portraitFile->move(
                        $this->getParameter('characters_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // обработайте ошибку при загрузке файла
                }
                $character->setPortrait($newFilename);
            }
            // Обработка фонового изображения
            $backgroundFile = $form->get('backgroundImage')->getData();
            if ($backgroundFile) {
                $originalFilename = pathinfo($backgroundFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$backgroundFile->guessExtension();
                try {
                    $backgroundFile->move(
                        $this->getParameter('characters_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // обработайте ошибку при загрузке файла
                }
                $character->setBackgroundImage($newFilename);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('character/edit.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_character_delete', methods: ['POST'])]
    public function delete(Request $request, Character $character, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$character->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
    }
}
