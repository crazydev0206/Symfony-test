<?php

namespace App\Command;

use App\Entity\Fruit;
use App\Entity\Nutrition;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class GetFruitsCommand extends Command
{
    protected static $defaultName = 'app:get-fetch-fruits';
    private $entityManager;
    /**
     * @ var MailerInterface
     */
    private $mailer;
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Get all fruits from Fruityvice and save them into DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $response = $client->get('https://fruityvice.com/api/fruit/all');

        $fruits = json_decode($response->getBody(), true);
        //dd($fruits);
        $newFruitsCount = 0;
        foreach ($fruits as $fruitData) {
            $fruit = $this->entityManager->getRepository(Fruit::class)->findOneBy(['name' => $$fruitData['name']]);
            if (!$fruit) {
                $nutritionData = $fruitData['nutritions'];
                $nutrition = new Nutrition();
                // dd($nutritionData['carbohydrates']);
                $nutrition->setCarbohydrates($nutritionData['carbohydrates']);
                $nutrition->setProtein($nutritionData['protein']);
                $nutrition->setFat($nutritionData['fat']);
                $nutrition->setCalories($nutritionData['calories']);
                $nutrition->setSugar($nutritionData['sugar']);

                $fruit = new Fruit();
                $fruit->setName($fruitData['name']);
                $fruit->setFamily($fruitData['family']);
                $fruit->setNutrition($nutrition);
                $fruit->setIsFavorite(false);
                $fruit->setOrd($fruitData['order']);
                $fruit->setGenus($fruitData['genus']);
                $this->entityManager->persist($nutrition);
                $nutrition->setFruit($fruit);
                
                $this->entityManager->persist($fruit);
            }
        }
        $this->entityManager->flush();
        if ($newFruitsCount > 0) {
            $email = (new Email())
            ->from('test@fruit.com')
            ->to("admin@fruit.com")
            ->subject('New fruit added!')
            ->text(sprintf('A new fruit %s has been added to the database!', $fruit->getName()));

            $this->mailer->send($email);
        }
        $output->writeln('Fruits saved into DB.');

        return Command::SUCCESS;
    }
}
