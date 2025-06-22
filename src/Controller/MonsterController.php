<?php

namespace App\Controller;

use App\Entity\Monster;
use App\Form\MonsterForm;
use App\Repository\MonsterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/monster')]
final class MonsterController extends AbstractController
{
    #[Route(name: 'app_monster_index', methods: ['GET'])]
    public function index(MonsterRepository $monsterRepository): Response
    {
        return $this->render('monster/index.html.twig', [
            'monsters' => $monsterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_monster_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $monster = new Monster();
        $form = $this->createForm(MonsterForm::class, $monster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleImageUpload($form, $monster, $slugger);

            $entityManager->persist($monster);
            $entityManager->flush();

            return $this->redirectToRoute('app_monster_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('monster/new.html.twig', [
            'monster' => $monster,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_monster_show', methods: ['GET'])]
    public function show(Monster $monster): Response
    {
        return $this->render('monster/show.html.twig', [
            'monster' => $monster,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_monster_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Monster $monster, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MonsterForm::class, $monster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleImageUpload($form, $monster, $slugger);

            $entityManager->flush();

            return $this->redirectToRoute('app_monster_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('monster/new.html.twig', [
            'monster' => $monster,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_monster_delete', methods: ['POST'])]
    public function delete(Request $request, Monster $monster, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$monster->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($monster);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_monster_index', [], Response::HTTP_SEE_OTHER);
    }

    private function handleImageUpload($form, Monster $monster, SluggerInterface $slugger): void
    {
        $portraitFile = $form->get('portrait')->getData();
        if ($portraitFile) {
            $newFilename = $this->uploadFile($portraitFile, $slugger);
            $monster->setPortrait($newFilename);
        }

        $backgroundFile = $form->get('backgroundImage')->getData();
        if ($backgroundFile) {
            $newFilename = $this->uploadFile($backgroundFile, $slugger);
            $monster->setBackgroundImage($newFilename);
        }
    }

    private function uploadFile($file, SluggerInterface $slugger): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->getParameter('monsters_images_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // обработайте ошибку при загрузке файла, если нужно
            return null;
        }

        return $newFilename;
    }
}
