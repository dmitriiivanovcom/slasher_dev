<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdminControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get('doctrine')->getManager();
        $hasher = $container->get(UserPasswordHasherInterface::class);

        // Очищаем пользователей
        $em->createQuery('DELETE FROM App\\Entity\\User')->execute();

        // Создаём пользователя-админа
        $user = new User();
        $user->setEmail('admin@test.local');
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setPassword($hasher->hashPassword($user, 'adminpass'));
        $em->persist($user);
        $em->flush();

        $client->loginUser($user);
        $client->request('GET', '/admin');

        // Проверяем редирект на /admin/user
        self::assertResponseRedirects('/admin/user');
    }
}
