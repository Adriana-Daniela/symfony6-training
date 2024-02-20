<?php

namespace App\Book;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class BookManager
{
    public function __construct(
        protected EntityManagerInterface $manager,
        #[Autowire(lazy: true)]
        protected MailerInterface $mailer,
        #[Autowire(param: 'app.items_per_page')]
        protected int $itemsPerPage,
    )
    {
    }

    public function getOne(int $id): mixed
    {
        return $this->manager->find(Book::class, $id);
    }

    public function getPaginated(int $page = 1): array
    {
        return $this->manager->getRepository(Book::class)
            ->findBy([], [], $this->itemsPerPage, ($page - 1) * $this->itemsPerPage);
    }

    public function getAndSendMail(int $id): Book
    {
        $this->mailer->send((new Email())->subject('new book fetched'));

        return $this->manager->find(Book::class, $id);
    }
}
