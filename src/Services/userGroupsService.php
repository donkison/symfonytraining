<?php


namespace App\Services;

use App\Entity\userGroup;
use App\Repository\userGroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class userGroupsService
{
    private $entityManager;
    private $groupsRepository;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, userGroupRepository $groupsRepository, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->groupsRepository = $groupsRepository;
        $this->userRepository = $userRepository;
    }

    public function addGroup(string $name, array $usersId = null)
    {
        if( $this->groupsRepository->findBy( array('Name'=>$name))){
            throw new EntityNotFoundException('userGroup with name '.$name.' is already exists!');
        }
        $group = new userGroup();
        $group->setName($name);
        if($usersId)
        {
            foreach($usersId as $userId)  {
                $group->addUser($this->userRepository->find($userId));
            }
        }
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return 'userGroup created';
    }

    public function getGroup(int $groupId)
    {
       $group = $this->groupsRepository->find($groupId);
       if($group)
       {
           return $group;
       }
       else
       {
           throw new EntityNotFoundException('userGroup with id '.$groupId.' does not exist!');
       }

    }

    public function getAllGroups()
    {
        $groups = $this->groupsRepository->findAll();
        return $groups;
    }

    public function deleteGroup($groupId)
    {
        $group=$this->groupsRepository->find($groupId);
        if($group){
            if($group->UserInGroupCount() > 0)
                {
                    throw new EntityNotFoundException('userGroup with id '.$groupId.' have Users in!');
                }
            $this->entityManager->remove($group);
            $this->entityManager->flush();
            return "userGroup with id '$groupId' is successfully deleted";
        }
        throw new EntityNotFoundException('userGroup with id '.$groupId.' does not exist!');
   }

}