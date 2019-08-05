<?php


namespace App\Controller;


use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractFOSRestController
{
    private $userRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager){
        $this->userRepository=$userRepository;
        $this->entityManager=$entityManager;
    }

    /**
     * @Rest\Post("/user")
     * @param Request $request
     */
    public function createUser(Request $request)
    {
        if( $this->userRepository->findBy( array('email'=>$request->get('email')))){
            return View::create(["message"=>"User already exists"], Response::HTTP_CONFLICT);
        }
        else {
            $user = new User();
            $user->setEmail($request->get('email'));
            $user->setUsername($request->get('username'));
            if($request->get('isAdmin')==1)
            {
                $user->setRoles(['ROLE_ADMIN']);
            }
            $apitoken = new ApiToken($user);
            $this->entityManager->persist($apitoken);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->json($user, Response::HTTP_CREATED,[],['groups'=>['main']]);
            //return View::create($user, Response::HTTP_CREATED,[],[]);

        }
    }

    /**
     * @Rest\Get("/user")
     *
     */
    public function showAllUsers()
    {
        $user=$this->userRepository->findAll();
        return $this->json($user, Response::HTTP_OK,[],['groups'=>['main']]);
        //return View::create($user, Response::HTTP_OK);

    }

    /**
     * @Rest\Get("/user/{userid}")
     *
     */
    public function showUsers(int $userid)
    {
        $user=$this->userRepository->find($userid);
        return $this->json($user, Response::HTTP_OK,[],['groups'=>['main']]);
        //return View::create($user, Response::HTTP_OK);

    }

    /**
     * @Rest\Delete("/user/{userid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUsers(int $userid)
    {
       $user= $this->userRepository->find( $userid);
        if($user){

            $this->entityManager->remove($user);
            $this->entityManager->flush();


            return View::create(["message"=>"User deleted"], Response::HTTP_OK);
        }
        return View::create(["message"=>"User not found"], Response::HTTP_NOT_FOUND);

    }


}