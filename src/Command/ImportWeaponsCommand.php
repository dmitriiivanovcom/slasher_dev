<?php

namespace App\Command;

use App\Entity\Weapon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use DOMDocument;
use DOMXPath;

#[AsCommand(
    name: 'app:import-weapons',
    description: 'Imports weapons from HTML files in html_template/weapons folder.',
)]
class ImportWeaponsCommand extends Command
{
    private $entityManager;
    private $projectDir;

    public function __construct(EntityManagerInterface $entityManager, string $projectDir)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->projectDir = $projectDir;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $htmlTemplatePath = $this->projectDir . '/html_template/weapons';

        if (!is_dir($htmlTemplatePath)) {
            $io->error('The directory with HTML templates does not exist: ' . $htmlTemplatePath);
            return Command::FAILURE;
        }

        $this->entityManager->createQuery('DELETE FROM App\Entity\Weapon')->execute();

        $finder = new Finder();
        $finder->files()->in($htmlTemplatePath)->name('*.html')->notName('index.html');

        $io->progressStart(count($finder));

        foreach ($finder as $file) {
            $html = $file->getContents();
            $dom = new DOMDocument();
            @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new DOMXPath($dom);

            $weapon = new Weapon();

            $nameNode = $xpath->query("//div[contains(@class, 'top-left')]/div[contains(@class, 'name')]")->item(0);
            if ($nameNode && !empty(trim($nameNode->textContent))) {
                $weapon->setName(trim($nameNode->textContent));
            } else {
                $io->warning(sprintf('Weapon name not found in %s. Skipping.', $file->getFilename()));
                continue;
            }

            $titleNode = $xpath->query("//div[contains(@class, 'top-left')]/div[2]")->item(0);
            if ($titleNode) {
                $weapon->setTitle(trim($titleNode->textContent));
            }

            $ammoStatusNode = $xpath->query("//div[contains(@class, 'ammo-warning')]")->item(0);
            if ($ammoStatusNode) {
                $weapon->setAmmoStatus(trim($ammoStatusNode->textContent));
            }

            $descriptionNode = $xpath->query("//div[contains(@class, 'bottom-left')]/p[contains(strong, 'Описание:')]")->item(0);
            if ($descriptionNode) {
                $weapon->setDescription(trim(str_replace('Описание:', '', $descriptionNode->textContent)));
            }

            $typeNode = $xpath->query("//div[contains(@class, 'bottom-left')]/p[contains(strong, 'Тип:')]")->item(0);
            if ($typeNode) {
                $weapon->setType(trim(str_replace('Тип:', '', $typeNode->textContent)));
            }

            // Set default values
            $weapon->setDamage(1);
            $weapon->setRange('Ближняя');

            // Image
            $cardNode = $xpath->query("//div[contains(@class, 'card')]")->item(0);
            if ($cardNode) {
                $style = $cardNode->getAttribute('style');
                if (preg_match('/url\(([\'"]?)(..\/weapons_img\/.*?)\1\)/', $style, $matches)) {
                    $imagePath = $matches[2];
                    $sourceImagePath = $this->projectDir . '/html_template/' . str_replace('../', '', $imagePath);
                    $destinationDir = $this->projectDir . '/public/uploads/weapons/';
                    
                    if (file_exists($sourceImagePath)) {
                        if (!is_dir($destinationDir)) {
                            mkdir($destinationDir, 0777, true);
                        }
                        $newFilename = uniqid() . '-' . basename($sourceImagePath);
                        $destinationPath = $destinationDir . $newFilename;
                        copy($sourceImagePath, $destinationPath);
                        $weapon->setImage($newFilename);
                    }
                }
            }
            
            $this->entityManager->persist($weapon);
            $io->progressAdvance();
        }

        $this->entityManager->flush();
        $io->progressFinish();
        $io->success('All weapons have been successfully imported from HTML files!');

        return Command::SUCCESS;
    }
} 