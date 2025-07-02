<?php

namespace App\Controller\Api;

use App\Entity\Weapon;
use App\Repository\WeaponRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/weapons')]
class WeaponApiController extends AbstractController
{
    #[Route('', name: 'api_weapons_list', methods: ['GET'])]
    public function list(WeaponRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $weapons = $repo->findAll();
        $json = $serializer->serialize($weapons, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', name: 'api_weapons_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $weapon = new Weapon();
        $weapon->setName($data['name'] ?? '');
        $weapon->setType($data['type'] ?? '');
        $weapon->setDamage($data['damage'] ?? 1);
        $weapon->setWeight($data['weight'] ?? 1);
        $weapon->setDescription($data['description'] ?? null);
        $weapon->setAuthor($this->getUser());
        $em->persist($weapon);
        $em->flush();
        $json = $serializer->serialize($weapon, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', name: 'api_weapons_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Weapon $weapon, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($weapon, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'api_weapons_update', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function update(Request $request, Weapon $weapon, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $weapon->getAuthor() !== $user) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
        $data = json_decode($request->getContent(), true);
        $weapon->setName($data['name'] ?? $weapon->getName());
        $weapon->setType($data['type'] ?? $weapon->getType());
        $weapon->setDamage($data['damage'] ?? $weapon->getDamage());
        $weapon->setWeight($data['weight'] ?? $weapon->getWeight());
        $weapon->setDescription($data['description'] ?? $weapon->getDescription());
        $em->flush();
        $json = $serializer->serialize($weapon, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'api_weapons_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Weapon $weapon, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $weapon->getAuthor() !== $user) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
        $em->remove($weapon);
        $em->flush();
        return new JsonResponse(null, 204);
    }
} 