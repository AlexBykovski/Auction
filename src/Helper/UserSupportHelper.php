<?php

namespace App\Helper;

use App\Entity\SupportQuestion;
use App\Entity\User;
use App\Upload\FileUpload;
use Doctrine\ORM\EntityManagerInterface;
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
     * LoginHelper constructor.
     * @param EntityManagerInterface $em
     * @param FileUpload $uploader
     */
    public function __construct(EntityManagerInterface $em, FileUpload $uploader)
    {
        $this->em = $em;
        $this->uploader = $uploader;

        $this->uploader->setFolder(FileUpload::USER_SUPPORT);
    }

    public function saveSupportQuestion(User $user, $message, $file)
    {
        $question = new SupportQuestion();

        $question->setQuestion($message);
        $question->setUser($user);

        if($file instanceof UploadedFile){
            $question->setImage($this->uploader->upload($file));
        }

        $this->em->persist($question);
        $this->em->flush();

        return $question;
    }
}