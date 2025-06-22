<?php

namespace App\Command;

use App\Entity\Character;
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
    name: 'app:import-characters',
    description: 'Imports characters from HTML files in html_template/guys folder.',
)]
class ImportCharactersCommand extends Command
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
        $htmlTemplatePath = $this->projectDir . '/html_template/guys';

        if (!is_dir($htmlTemplatePath)) {
            $io->error('The directory with HTML templates does not exist: ' . $htmlTemplatePath);
            return Command::FAILURE;
        }
        
        // Clear existing characters to avoid duplicates
        $this->entityManager->createQuery('DELETE FROM App\Entity\Character')->execute();
        
        $finder = new Finder();
        $finder->files()->in($htmlTemplatePath)->name('*.html')->notName('index.html');

        $io->progressStart(count($finder));

        foreach ($finder as $file) {
            $html = $file->getContents();
            $dom = new DOMDocument();
            @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new DOMXPath($dom);

            $character = new Character();

            // Extract data
            $nameNode = $xpath->query("//div[contains(@class, 'top-left')]/div[contains(@class, 'name')]")->item(0);
            if ($nameNode && !empty(trim($nameNode->textContent))) {
                $character->setName(trim($nameNode->textContent));
            } else {
                $io->error(sprintf('Character name could not be found or is empty in file: %s. Aborting import.', $file->getFilename()));
                return Command::FAILURE;
            }

            $roleNode = $xpath->query("//div[contains(@class, 'top-left')]/div[2]")->item(0);
             if ($roleNode) {
                $character->setRole(trim($roleNode->textContent));
            }
            
            $descriptionNode = $xpath->query("//div[contains(@class, 'bottom-left')]/p[1]")->item(0);
            if ($descriptionNode) {
                $character->setDescription(str_replace('Описание:', '', trim($descriptionNode->textContent)));
            }

            $strengthsNode = $xpath->query("//div[contains(@class, 'bottom-left')]/p[2]")->item(0);
            if ($strengthsNode) {
                $character->setStrengths(str_replace('Сильная сторона:', '', trim($strengthsNode->textContent)));
            }

            $weaknessesNode = $xpath->query("//div[contains(@class, 'bottom-left')]/p[3]")->item(0);
            if ($weaknessesNode) {
                $character->setWeaknesses(str_replace('Слабая сторона:', '', trim($weaknessesNode->textContent)));
            }
            
            $mottoNode = $xpath->query("//div[contains(@class, 'bottom-right')]/div[contains(@class, 'motto')]")->item(0);
            if ($mottoNode) {
                 $character->setMotto(trim($mottoNode->textContent));
            }
            
            // Stats
            $character->setStrength(1);
            $character->setAgility(1);
            $character->setIntelligence(1);
            $character->setCharisma(1);

            // Images
            $cardNode = $xpath->query("//div[contains(@class, 'card')]")->item(0);
            if ($cardNode) {
                $style = $cardNode->getAttribute('style');
                if (preg_match('/url\(([\'"]?)(..\/guys_img\/.*?)\1\)/', $style, $matches)) {
                    $imagePath = $matches[2];
                    $sourceImagePath = $this->projectDir . '/html_template/' . str_replace('../', '', $imagePath);
                    $destinationDir = $this->projectDir . '/public/uploads/characters/';

                    if (file_exists($sourceImagePath)) {
                        if (!is_dir($destinationDir)) {
                            mkdir($destinationDir, 0777, true);
                        }
                        $newFilename = uniqid() . '-' . basename($sourceImagePath);
                        $destinationPath = $destinationDir . $newFilename;
                        copy($sourceImagePath, $destinationPath);
                        $character->setPortrait($newFilename);
                        $character->setBackgroundImage($newFilename);
                    }
                }
            }
            
            $this->entityManager->persist($character);
            $io->progressAdvance();
        }

        $this->entityManager->flush();
        $io->progressFinish();
        $io->success('All characters have been successfully imported from HTML files!');

        return Command::SUCCESS;
    }
}
