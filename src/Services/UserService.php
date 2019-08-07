<?php


namespace App\Services;


use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class UserService
{
    private $entityManager;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function addUser(string $email, string $userName, string $isAdmin)
    {
        if( $this->userRepository->findBy( array('email'=>$email))){
            throw new EntityNotFoundException('User with id '.$email.' is already exists!');
        }
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($userName);
        if($isAdmin == 1) {
            $user->setRoles(['ROLE_ADMIN']);
        }
        $this->entityManager->persist($user);
        $apiToken = new ApiToken($user);
        $this->entityManager->persist($apiToken);
        $this->entityManager->flush();
        return 'User created';
    }

    public function getUser(int $userId)
    {
       $user = $this->userRepository->find($userId);
       if($user)
       {
           return $user;
       }
       else
       {
           throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
       }

    }

    public function getAllUser()
    {
        $users = $this->userRepository->findAll();
        return $users;
    }

    public function deleteUser($userId)
    {
        $user= $this->userRepository->find($userId);
        if($user){
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            return "User with id '$userId' is successfully deleted";
        }
        throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
    }

}