<?php

namespace App\Controller\Api;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/characters')]
class CharacterApiController extends AbstractController
{
    #[Route('', name: 'api_characters_list', methods: ['GET'])]
    public function list(CharacterRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $characters = $repo->findAll();
        $json = $serializer->serialize($characters, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', name: 'api_characters_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $character = new Character();
        $character->setName($data['name'] ?? '');
        $character->setStrength($data['strength'] ?? 1);
        $character->setAgility($data['agility'] ?? 1);
        $character->setIntelligence($data['intelligence'] ?? 1);
        $character->setCharisma($data['charisma'] ?? 1);
        $character->setQuote($data['quote'] ?? null);
        $character->setRole($data['role'] ?? null);
        $character->setMotto($data['motto'] ?? null);
        $character->setWeaknesses($data['weaknesses'] ?? null);
        $character->setStrengths($data['strengths'] ?? null);
        $character->setDescription($data['description'] ?? null);
        $character->setAuthor($this->getUser());
        $em->persist($character);
        $em->flush();
        $json = $serializer->serialize($character, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', name: 'api_characters_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Character $character, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($character, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'api_characters_update', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function update(Request $request, Character $character, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $character->getAuthor() !== $user) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
        $data = json_decode($request->getContent(), true);
        $character->setName($data['name'] ?? $character->getName());
        $character->setStrength($data['strength'] ?? $character->getStrength());
        $character->setAgility($data['agility'] ?? $character->getAgility());
        $character->setIntelligence($data['intelligence'] ?? $character->getIntelligence());
        $character->setCharisma($data['charisma'] ?? $character->getCharisma());
        $character->setQuote($data['quote'] ?? $character->getQuote());
        $character->setRole($data['role'] ?? $character->getRole());
        $character->setMotto($data['motto'] ?? $character->getMotto());
        $character->setWeaknesses($data['weaknesses'] ?? $character->getWeaknesses());
        $character->setStrengths($data['strengths'] ?? $character->getStrengths());
        $character->setDescription($data['description'] ?? $character->getDescription());
        $em->flush();
        $json = $serializer->serialize($character, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'api_characters_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Character $character, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $character->getAuthor() !== $user) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
        $em->remove($character);
        $em->flush();
        return new JsonResponse(null, 204);
    }
} 