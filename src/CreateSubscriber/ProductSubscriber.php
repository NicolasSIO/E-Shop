<?php


namespace App\CreateSubscriber;


use App\Entity\Product;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ProductSubscriber implements EventSubscriber
{

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->sendWelcomeEmail($args);
    }

    public function sendWelcomeEmail(LifecycleEventArgs $args)
    {
        # 1. Récupération de l'Objet concerné
        $entity = $args->getObject();

        # 2. Si mon objet n'est pas une instance de "Product" on quitte.
        if (!$entity instanceof Product) {
            return;
        }

        $email = (new Email())
            ->from('noreply@eshop.com')
            ->to("marketing@eshop.com")
            ->subject("Mis en place d'un nouveau produit")
            ->html('<p>Bonjour, un nouveau produit a été ajouté !</p>');

        $this->mailer->send($email);
    }
}