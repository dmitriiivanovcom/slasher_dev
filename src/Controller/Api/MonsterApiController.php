<?php

namespace App\Controller\Api;

use App\Entity\Monster;
use App\Repository\MonsterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/monsters')]
class MonsterApiController extends AbstractController
{
    #[Route('', name: 'api_monsters_list', methods: ['GET'])]
    public function list(MonsterRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $monsters = $repo->findAll();
        $json = $serializer->serialize($monsters, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('', name: 'api_monsters_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $monster = new Monster();
        $monster->setName($data['name'] ?? '');
        $monster->setTitle($data['title'] ?? '');
        $monster->setRank($data['rank'] ?? '');
        $monster->setDangerIndex($data['dangerIndex'] ?? 1);
        $monster->setLethality($data['lethality'] ?? 1);
        $monster->setSpeed($data['speed'] ?? 1);
        $monster->setStealth($data['stealth'] ?? 1);
        $monster->setDescription($data['description'] ?? null);
        $monster->setStrengths($data['strengths'] ?? null);
        $monster->setWeaknesses($data['weaknesses'] ?? null);
        $monster->setBackstory($data['backstory'] ?? null);
        $monster->setAuthor($this->getUser());
        $em->persist($monster);
        $em->flush();
        $json = $serializer->serialize($monster, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 201, [], true);
    }

    #[Route('/{id}', name: 'api_monsters_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Monster $monster, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($monster, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'api_monsters_update', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function update(Request $request, Monster $monster, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $monster->getAuthor() !== $user) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
        $data = json_decode($request->getContent(), true);
        $monster->setName($data['name'] ?? $monster->getName());
        $monster->setTitle($data['title'] ?? $monster->getTitle());
        $monster->setRank($data['rank'] ?? $monster->getRank());
        $monster->setDangerIndex($data['dangerIndex'] ?? $monster->getDangerIndex());
        $monster->setLethality($data['lethality'] ?? $monster->getLethality());
        $monster->setSpeed($data['speed'] ?? $monster->getSpeed());
        $monster->setStealth($data['stealth'] ?? $monster->getStealth());
        $monster->setDescription($data['description'] ?? $monster->getDescription());
        $monster->setStrengths($data['strengths'] ?? $monster->getStrengths());
        $monster->setWeaknesses($data['weaknesses'] ?? $monster->getWeaknesses());
        $monster->setBackstory($data['backstory'] ?? $monster->getBackstory());
        $em->flush();
        $json = $serializer->serialize($monster, 'json', ['groups' => 'api']);
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/{id}', name: 'api_monsters_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Monster $monster, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $monster->getAuthor() !== $user) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }
        $em->remove($monster);
        $em->flush();
        return new JsonResponse(null, 204);
    }
} 