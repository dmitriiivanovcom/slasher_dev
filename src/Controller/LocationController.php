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

class LocationController extends AbstractController
{
    #[Route('/location/new', name: 'app_location_new')]
    public function new(Request $request, LocationRepository $locationRepository, \Doctrine\ORM\EntityManagerInterface $em): Response
    {
        $location = new \App\Entity\Location();
        $form = $this->createForm(LocationForm::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

    #[Route('/location/{id}', name: 'app_location_show')]
    public function show(Location $location): Response
    {
        return $this->render('location/show.html.twig', [
            'location' => $location,
        ]);
    }
} 