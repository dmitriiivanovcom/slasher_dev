<?php

namespace App\Controller\Api;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/locations')]
class LocationApiController extends AbstractController
{
    #[Route('', name: 'api_locations_list', methods: ['GET'])]
    public function list(LocationRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $locations = $repo->findAll();
        $json = $serializer->serialize($locations, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', name: 'api_locations_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $location = new Location();
        $location->setName($data['name'] ?? '');
        $location->setType($data['type'] ?? '');
        $location->setDescription($data['description'] ?? null);
        $location->setAuthor($this->getUser());
        $em->persist($location);
        $em->flush();
        $json = $serializer->serialize($location, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', name: 'api_locations_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Location $location, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($location, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'api_locations_update', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function update(Request $request, Location $location, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $location->getAuthor() !== $user) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
        $data = json_decode($request->getContent(), true);
        $location->setName($data['name'] ?? $location->getName());
        $location->setType($data['type'] ?? $location->getType());
        $location->setDescription($data['description'] ?? $location->getDescription());
        $em->flush();
        $json = $serializer->serialize($location, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'api_locations_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Location $location, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $location->getAuthor() !== $user) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
        $em->remove($location);
        $em->flush();
        return new JsonResponse(null, 204);
    }
} 