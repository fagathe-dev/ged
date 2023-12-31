<?php

namespace App\Command;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an admin user',
)]
class CreateAdminCommand extends Command
{

    public function __construct(
        private UserService $service,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::OPTIONAL, 'Admin username')
            ->addArgument('email', InputArgument::OPTIONAL, 'Admin email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Admin password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        if (!$username) {
            $question = new Question('Nom d\'utilisateur : ');
            $username = $helper->ask($input, $output, $question);
        }

        $email = $input->getArgument('email');
        if (!$email) {
            $question = new Question('Adresse e-mail : ');
            $email = $helper->ask($input, $output, $question);
        }

        $password = $input->getArgument('password');
        if (!$password) {
            $question = new Question('Mot de passe : ');
            $password = $helper->ask($input, $output, $question);
        }
        $user = (new User);
        $user
            ->setPassword($this->hasher->hashPassword($user, $password))
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN'])
            ->setUsername($username)
            ->setCreatedAt(new \DateTimeImmutable)
        ;


        if ($this->service->save($user)) {
            $io->success('A new admin user has been created ! 🚀');
            return Command::SUCCESS;
        }

        $io->success('An error occured while creating admin !');
        return Command::FAILURE;
    }
}