<?php

namespace App\Helper;

use App\Entity\SupportQuestion;
use App\Entity\User;
use App\Upload\FileUpload;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * LoginHelper constructor.
     * @param EntityManagerInterface $em
     * @param FileUpload $uploader
     * @param Swift_Mailer $mailer
     */
    public function __construct(EntityManagerInterface $em, FileUpload $uploader, Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->uploader = $uploader;
        $this->mailer = $mailer;

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
        $message = (new \Swift_Message('Support User Question'))
            ->setTo('info@lucky-deal.ru')
            ->setBody(
                $question->getQuestion()
            );

        $this->mailer->send($message);
    }
}