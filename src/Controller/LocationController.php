<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LocationForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\FormError;

class LocationController extends AbstractController
{
    #[Route('/location/new', name: 'app_location_new')]
    public function new(Request $request, LocationRepository $locationRepository, \Doctrine\ORM\EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $location = new \App\Entity\Location();
        $form = $this->createForm(LocationForm::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('locations_images_directory'),
                    $newFilename
                );
                $location->setImage($newFilename);
            }
            $mapFile = $form->get('map')->getData();
            if ($mapFile) {
                $originalFilename = pathinfo($mapFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$mapFile->guessExtension();
                $mapFile->move(
                    $this->getParameter('locations_images_directory'),
                    $newFilename
                );
                $location->setMap($newFilename);
            }
            foreach ($form->get('subLocations') as $subForm) {
                $subFile = $subForm->get('image')->getData();
                if ($subFile) {
                    $originalFilename = pathinfo($subFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$subFile->guessExtension();
                    $subFile->move(
                        $this->getParameter('locations_images_directory'),
                        $newFilename
                    );
                    $subLocation = $subForm->getData();
                    $subLocation->setImage($newFilename);
                }
            }
            $location->setAuthor($this->getUser());
            $em->persist($location);
            $em->flush();
            return $this->redirectToRoute('app_location_index');
        }

        return $this->render('location/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/location', name: 'app_location_index')]
    public function index(LocationRepository $locationRepository): Response
    {
        return $this->render('location/index.html.twig', [
            'locations' => $locationRepository->findAll(),
        ]);
    }

    #[Route('/location/{id}', name: 'app_location_show', methods: ['GET'])]
    public function show(Location $location, Request $request): Response
    {
        error_log('SHOW: Route=' . $request->attributes->get('_route') . ' Controller=' . $request->attributes->get('_controller'));
        return $this->render('location/show.html.twig', [
            'location' => $location,
        ]);
    }

    #[Route('/location/{id}/edit', name: 'app_location_edit')]
    public function edit(Request $request, Location $location, \Doctrine\ORM\EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(\App\Form\LocationForm::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // обработка image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('locations_images_directory'),
                    $newFilename
                );
                $location->setImage($newFilename);
            }
            // обработка map
            $mapFile = $form->get('map')->getData();
            if ($mapFile) {
                $originalFilename = pathinfo($mapFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$mapFile->guessExtension();
                $mapFile->move(
                    $this->getParameter('locations_images_directory'),
                    $newFilename
                );
                $location->setMap($newFilename);
            }
            // обработка подлокаций
            foreach ($form->get('subLocations') as $subForm) {
                $subFile = $subForm->get('image')->getData();
                if ($subFile) {
                    $originalFilename = pathinfo($subFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$subFile->guessExtension();
                    $subFile->move(
                        $this->getParameter('locations_images_directory'),
                        $newFilename
                    );
                    $subLocation = $subForm->getData();
                    $subLocation->setImage($newFilename);
                }
            }
            $em->flush();
            return $this->redirectToRoute('app_location_show', ['id' => $location->getId()]);
        }

        return $this->render('location/edit.html.twig', [
            'form' => $form->createView(),
            'location' => $location,
        ]);
    }

    #[Route('/location/{id}', name: 'app_location_delete', methods: ['POST'])]
    public function delete(Request $request, Location $location, \Doctrine\ORM\EntityManagerInterface $em): Response
    {
        error_log('DELETE: Route=' . $request->attributes->get('_route') . ' Controller=' . $request->attributes->get('_controller'));
        $token = $request->request->get('_token');
        $expected = 'delete' . $location->getId();
        $isValid = $this->isCsrfTokenValid($expected, $token);
        error_log('CSRF: expected key=' . $expected . ' token=' . $token . ' isValid=' . ($isValid ? 'YES' : 'NO'));
        if ($isValid) {
            $em->remove($location); // subLocations удалятся автоматически
            $em->flush();
        }
        return $this->redirectToRoute('app_location_index');
    }
} 