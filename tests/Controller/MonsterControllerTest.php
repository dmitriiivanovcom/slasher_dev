<?php

namespace App\Tests\Controller;

use App\Entity\Monster;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MonsterControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $monsterRepository;
    private string $path = '/monster/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $em = $container->get('doctrine')->getManager();
        $hasher = $container->get(\Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface::class);

        // Очищаем пользователей
        $em->createQuery('DELETE FROM App\\Entity\\User')->execute();

        // Создаём пользователя-админа
        $user = new \App\Entity\User();
        $user->setEmail('admin@test.local');
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setPassword($hasher->hashPassword($user, 'adminpass'));
        $em->persist($user);
        $em->flush();

        $this->client->loginUser($user);

        $this->manager = $container->get('doctrine')->getManager();
        $this->monsterRepository = $this->manager->getRepository(\App\Entity\Monster::class);

        foreach ($this->monsterRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('All Monsters');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $crawler = $this->client->request('GET', $this->path . 'new');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Create', [
            'monster_form[title]' => 'Test Monster',
            'monster_form[description]' => 'Test description',
            'monster_form[strengths]' => 'Strong',
            'monster_form[weaknesses]' => 'Weak',
            'monster_form[lethality]' => 10,
            'monster_form[speed]' => 5,
            'monster_form[stealth]' => 7,
            'monster_form[name]' => 'TestName',
            'monster_form[rank]' => 'A',
            'monster_form[dangerIndex]' => 3,
            'monster_form[backstory]' => 'Backstory',
        ]);

        self::assertResponseRedirects('/monster');
        $this->client->followRedirect();
        self::assertStringContainsString('TestName', $this->client->getResponse()->getContent());
        self::assertSame(1, $this->monsterRepository->count([]));
    }


    public function testShow(): void
    {
        $monster = new \App\Entity\Monster();
        $monster->setPortrait('portrait.png');
        $monster->setBackgroundImage('bg.png');
        $monster->setTitle('Show Monster');
        $monster->setDescription('Show description');
        $monster->setStrengths('Strong');
        $monster->setWeaknesses('Weak');
        $monster->setLethality(8);
        $monster->setSpeed(6);
        $monster->setStealth(4);
        $monster->setName('ShowName');
        $monster->setRank('B');
        $monster->setDangerIndex(2);
        $monster->setBackstory('Show backstory');
        $this->manager->persist($monster);
        $this->manager->flush();

        $this->client->request('GET', $this->path . $monster->getId());
        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Show Monster', $this->client->getResponse()->getContent());
        self::assertStringContainsString('Show description', $this->client->getResponse()->getContent());
    }

    public function testEdit(): void
    {
        $monster = new \App\Entity\Monster();
        $monster->setPortrait('portrait.png');
        $monster->setBackgroundImage('bg.png');
        $monster->setTitle('Edit Monster');
        $monster->setDescription('Edit description');
        $monster->setStrengths('Strong');
        $monster->setWeaknesses('Weak');
        $monster->setLethality(5);
        $monster->setSpeed(5);
        $monster->setStealth(5);
        $monster->setName('EditName');
        $monster->setRank('C');
        $monster->setDangerIndex(1);
        $monster->setBackstory('Edit backstory');
        $this->manager->persist($monster);
        $this->manager->flush();

        $this->client->request('GET', $this->path . $monster->getId() . '/edit');
        $this->client->submitForm('Update', [
            'monster_form[title]' => 'Edited Monster',
            'monster_form[description]' => 'Edited description',
            'monster_form[strengths]' => 'Very strong',
            'monster_form[weaknesses]' => 'Very weak',
            'monster_form[lethality]' => 9,
            'monster_form[speed]' => 2,
            'monster_form[stealth]' => 1,
            'monster_form[name]' => 'EditedName',
            'monster_form[rank]' => 'S',
            'monster_form[dangerIndex]' => 99,
            'monster_form[backstory]' => 'Edited backstory',
        ]);
        
        self::assertResponseRedirects('/monster');
        $this->client->followRedirect();

        $updated = $this->monsterRepository->find($monster->getId());
        self::assertSame('Edited Monster', $updated->getTitle());
        self::assertSame('Edited description', $updated->getDescription());
    }


    public function testRemove(): void
    {
        $monster = new \App\Entity\Monster();
        $monster->setPortrait('portrait.png');
        $monster->setBackgroundImage('bg.png');
        $monster->setTitle('Delete Monster');
        $monster->setDescription('Delete description');
        $monster->setStrengths('Strong');
        $monster->setWeaknesses('Weak');
        $monster->setLethality(3);
        $monster->setSpeed(2);
        $monster->setStealth(1);
        $monster->setName('DeleteName');
        $monster->setRank('D');
        $monster->setDangerIndex(1);
        $monster->setBackstory('Delete backstory');
        $this->manager->persist($monster);
        $this->manager->flush();

        $this->client->request('GET', $this->path . $monster->getId());
        $this->client->submitForm('Delete');
        self::assertResponseRedirects('/monster');
        $this->client->followRedirect();
        self::assertSame(0, $this->monsterRepository->count([]));
    }
}
