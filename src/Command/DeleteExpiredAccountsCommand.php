<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;

#[AsCommand(
    name: 'DeleteExpiredAccountsCommand',
    description: 'Supprimer les comptes dont le contrat est terminé.',
)]
class DeleteExpiredAccountsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $currentDate = new \DateTime();
        $expiredUsers = $this->entityManager->getRepository(Utilisateur::class)->findExpiredContracts($currentDate);

        if (empty($expiredUsers)) {
            $io->success('Aucun compte à supprimer.');
            return Command::SUCCESS;
        }

        foreach ($expiredUsers as $user) {
            $this->entityManager->remove($user);
        }

        $this->entityManager->flush();

        $io->success(sprintf('%d compte(s) utilisateur ont été supprimés avec succès.', count($expiredUsers)));

        return Command::SUCCESS;
    }
}
