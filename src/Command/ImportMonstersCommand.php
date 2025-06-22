<?php

namespace App\Command;

use App\Entity\Monster;
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
    name: 'app:import-monsters',
    description: 'Imports monsters from HTML files in html_template/monsters folder.',
)]
class ImportMonstersCommand extends Command
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
        $htmlTemplatePath = $this->projectDir . '/html_template/monsters';

        if (!is_dir($htmlTemplatePath)) {
            $io->error('The directory with HTML templates does not exist: ' . $htmlTemplatePath);
            return Command::FAILURE;
        }

        // Clear existing monsters to avoid duplicates
        $this->entityManager->createQuery('DELETE FROM App\Entity\Monster')->execute();

        $finder = new Finder();
        $finder->files()->in($htmlTemplatePath)->name('*.html')->notName('index.html');

        $io->progressStart(count($finder));

        foreach ($finder as $file) {
            $html = $file->getContents();
            $dom = new DOMDocument();
            @$dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new DOMXPath($dom);

            $monster = new Monster();

            // Extract data
            $nameNode = $xpath->query("//div[contains(@class, 'top-left')]/div[contains(@class, 'name')]")->item(0);
            if ($nameNode && !empty(trim($nameNode->textContent))) {
                $monster->setName(trim($nameNode->textContent));
            } else {
                $io->warning(sprintf('Monster name not found in %s. Skipping.', $file->getFilename()));
                continue;
            }

            $titleNode = $xpath->query("//div[contains(@class, 'top-left')]/div[2]")->item(0);
            if ($titleNode) {
                $monster->setTitle(trim($titleNode->textContent));
            }

            // Extract stats by counting filled stars
            $lethality = $xpath->evaluate("count(//div[contains(@class, 'top-right')]/div[contains(., 'Смертоносность')]/following-sibling::div[1]/div[contains(@class, 'filled')])");
            $monster->setLethality($lethality ?: 0);

            $speed = $xpath->evaluate("count(//div[contains(@class, 'top-right')]/div[contains(., 'Скорость')]/following-sibling::div[1]/div[contains(@class, 'filled')])");
            $monster->setSpeed($speed ?: 0);

            $stealth = $xpath->evaluate("count(//div[contains(@class, 'top-right')]/div[contains(., 'Скрытность')]/following-sibling::div[1]/div[contains(@class, 'filled')])");
            $monster->setStealth($stealth ?: 0);

            // Extract text blocks
            $descriptionNode = $xpath->query("//div[contains(@class, 'bottom-left')]/p[contains(strong, 'Описание:')]")->item(0);
            if ($descriptionNode) {
                $monster->setDescription(trim(str_replace('Описание:', '', $descriptionNode->textContent)));
            }
            
            $strengthsNode = $xpath->query("//div[contains(@class, 'bottom-left')]/p[contains(strong, 'Особенности:')]")->item(0);
            if ($strengthsNode) {
                $monster->setStrengths(trim(str_replace('Особенности:', '', $strengthsNode->textContent)));
            }

            $weaknessesNode = $xpath->query("//div[contains(@class, 'bottom-left')]/p[contains(strong, 'Слабость:')]")->item(0);
            if ($weaknessesNode) {
                $monster->setWeaknesses(trim(str_replace('Слабость:', '', $weaknessesNode->textContent)));
            }

            // Set default values for fields not in HTML
            $monster->setRank('Common');
            $monster->setDangerIndex(1);

            // Images
            $cardNode = $xpath->query("//div[contains(@class, 'card')]")->item(0);
            if ($cardNode) {
                $style = $cardNode->getAttribute('style');
                if (preg_match('/url\(([\'"]?)(..\/monsters_img\/.*?)\1\)/', $style, $matches)) {
                    $imagePath = $matches[2];
                    $sourceImagePath = $this->projectDir . '/html_template/' . str_replace('../', '', $imagePath);
                    $destinationDir = $this->projectDir . '/public/uploads/monsters/';
                    
                    if (file_exists($sourceImagePath)) {
                        $newFilename = uniqid() . '-' . basename($sourceImagePath);
                        $destinationPath = $destinationDir . $newFilename;
                        
                        if (!is_dir($destinationDir)) {
                            mkdir($destinationDir, 0777, true);
                        }
                        
                        copy($sourceImagePath, $destinationPath);
                        $monster->setPortrait($newFilename);
                        $monster->setBackgroundImage($newFilename);
                    }
                }
            }
            
            $this->entityManager->persist($monster);
            $io->progressAdvance();
        }

        $this->entityManager->flush();
        $io->progressFinish();
        $io->success('All monsters have been successfully imported from HTML files!');

        return Command::SUCCESS;
    }
} 