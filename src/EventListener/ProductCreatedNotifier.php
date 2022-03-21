<?php
namespace App\EventListener;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ProductCreatedNotifier
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postPersist(Product $product, LifecycleEventArgs $event): void
    {
        //sendin email notifcation
        try{
            $email = (new Email())
                ->from('hello@example.com')
                ->to('fake@example.com')
                ->subject('Product created: '.$product->getName())
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');
            $this->mailer->send($email);
        }catch (\Exception $ex){
//            dump($ex->getMessage()); die();
        }

    }
}