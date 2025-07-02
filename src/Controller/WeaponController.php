<?php

namespace App\Controller;

use App\Entity\Weapon;
use App\Form\WeaponForm;
use App\Repository\WeaponRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/weapon')]
final class WeaponController extends AbstractController
{
    #[Route(name: 'app_weapon_index', methods: ['GET'])]
    public function index(WeaponRepository $weaponRepository): Response
    {
        return $this->render('weapon/index.html.twig', [
            'weapons' => $weaponRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weapon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $weapon = new Weapon();
        $form = $this->createForm(WeaponForm::class, $weapon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('weapons_images_directory'),
                    $newFilename
                );
                $weapon->setImage($newFilename);
            }
            $weapon->setAuthor($this->getUser());
            $entityManager->persist($weapon);
            $entityManager->flush();

            return $this->redirectToRoute('app_weapon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weapon/new.html.twig', [
            'weapon' => $weapon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weapon_show', methods: ['GET'])]
    public function show(Weapon $weapon): Response
    {
        return $this->render('weapon/show.html.twig', [
            'weapon' => $weapon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weapon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Weapon $weapon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeaponForm::class, $weapon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weapon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weapon/edit.html.twig', [
            'weapon' => $weapon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weapon_delete', methods: ['POST'])]
    public function delete(Request $request, Weapon $weapon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weapon->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($weapon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_weapon_index', [], Response::HTTP_SEE_OTHER);
    }
}
