<?php

namespace App\Helper;

use App\Entity\SupportQuestion;
use App\Entity\User;
use App\Upload\FileUpload;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Attachment;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserSupportHelper
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FileUpload
     */
    private $uploader;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /** @var string */
    private $uploadDir;

    /**
     * LoginHelper constructor.
     * @param EntityManagerInterface $em
     * @param FileUpload $uploader
     * @param Swift_Mailer $mailer
     * @param string $uploadDir
     */
    public function __construct(
        EntityManagerInterface $em,
        FileUpload $uploader,
        Swift_Mailer $mailer,
        $uploadDir
    )
    {
        $this->em = $em;
        $this->uploader = $uploader;
        $this->mailer = $mailer;
        $this->uploadDir = $uploadDir;

        $this->uploader->setFolder(FileUpload::USER_SUPPORT);
    }

    public function saveSupportQuestion($user, $message, $file)
    {
        $question = new SupportQuestion();

        $question->setQuestion($message);
        $question->setUser($user);

        if($file instanceof UploadedFile){
            $question->setImage($this->uploader->upload($file));
        }

        $this->em->persist($question);
        $this->em->flush();

        $this->sendEmail($question);

        return $question;
    }

    protected function sendEmail(SupportQuestion $question)
    {
        $fromAddress = $question->getUser() ? $question->getUser()->getEmail() : "guest_message@lucky-deal.ru";

        $message = (new \Swift_Message('Support User Question'))
            ->setFrom($fromAddress)
            ->setTo('info@lucky-deal.ru')
            ->setBody(
                $question->getQuestion()
            );

        if($question->getImage()){
            $message = $message->attach(Swift_Attachment::fromPath($this->uploadDir . '/' . $question->getImage()));
        }

        $this->mailer->send($message);
    }
}