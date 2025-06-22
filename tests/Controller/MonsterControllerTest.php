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
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->monsterRepository = $this->manager->getRepository(Monster::class);

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
        self::assertPageTitleContains('Monster index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'monster[portrait]' => 'Testing',
            'monster[backgroundImage]' => 'Testing',
            'monster[title]' => 'Testing',
            'monster[description]' => 'Testing',
            'monster[strengths]' => 'Testing',
            'monster[weaknesses]' => 'Testing',
            'monster[lethality]' => 'Testing',
            'monster[speed]' => 'Testing',
            'monster[stealth]' => 'Testing',
            'monster[name]' => 'Testing',
            'monster[rank]' => 'Testing',
            'monster[dangerIndex]' => 'Testing',
            'monster[backstory]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->monsterRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Monster();
        $fixture->setPortrait('My Title');
        $fixture->setBackgroundImage('My Title');
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setStrengths('My Title');
        $fixture->setWeaknesses('My Title');
        $fixture->setLethality('My Title');
        $fixture->setSpeed('My Title');
        $fixture->setStealth('My Title');
        $fixture->setName('My Title');
        $fixture->setRank('My Title');
        $fixture->setDangerIndex('My Title');
        $fixture->setBackstory('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Monster');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Monster();
        $fixture->setPortrait('Value');
        $fixture->setBackgroundImage('Value');
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setStrengths('Value');
        $fixture->setWeaknesses('Value');
        $fixture->setLethality('Value');
        $fixture->setSpeed('Value');
        $fixture->setStealth('Value');
        $fixture->setName('Value');
        $fixture->setRank('Value');
        $fixture->setDangerIndex('Value');
        $fixture->setBackstory('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'monster[portrait]' => 'Something New',
            'monster[backgroundImage]' => 'Something New',
            'monster[title]' => 'Something New',
            'monster[description]' => 'Something New',
            'monster[strengths]' => 'Something New',
            'monster[weaknesses]' => 'Something New',
            'monster[lethality]' => 'Something New',
            'monster[speed]' => 'Something New',
            'monster[stealth]' => 'Something New',
            'monster[name]' => 'Something New',
            'monster[rank]' => 'Something New',
            'monster[dangerIndex]' => 'Something New',
            'monster[backstory]' => 'Something New',
        ]);

        self::assertResponseRedirects('/monster/');

        $fixture = $this->monsterRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getPortrait());
        self::assertSame('Something New', $fixture[0]->getBackgroundImage());
        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getStrengths());
        self::assertSame('Something New', $fixture[0]->getWeaknesses());
        self::assertSame('Something New', $fixture[0]->getLethality());
        self::assertSame('Something New', $fixture[0]->getSpeed());
        self::assertSame('Something New', $fixture[0]->getStealth());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getRank());
        self::assertSame('Something New', $fixture[0]->getDangerIndex());
        self::assertSame('Something New', $fixture[0]->getBackstory());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Monster();
        $fixture->setPortrait('Value');
        $fixture->setBackgroundImage('Value');
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setStrengths('Value');
        $fixture->setWeaknesses('Value');
        $fixture->setLethality('Value');
        $fixture->setSpeed('Value');
        $fixture->setStealth('Value');
        $fixture->setName('Value');
        $fixture->setRank('Value');
        $fixture->setDangerIndex('Value');
        $fixture->setBackstory('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/monster/');
        self::assertSame(0, $this->monsterRepository->count([]));
    }
}
