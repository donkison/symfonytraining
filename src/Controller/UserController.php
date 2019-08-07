<?php

namespace App\Controller;


use App\Services\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractFOSRestController
{
    private $userService;
    public function __construct(UserService $userService){
        $this->userService=$userService;
    }

    /**
     * @Rest\Post("/user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createUser(Request $request)
    {
            $email = $request->get('email');
            $userName = $request->get('username');
            $isAdmin=$request->get('username');
            $message = $this->userService->addUser($email, $userName, $isAdmin);
            return $this->json(['message'=>$message], Response::HTTP_CREATED,[],['groups'=>['main']]);
            //return View::create($user, Response::HTTP_CREATED,[],[]);
    }

    /**
     * @Rest\Get("/user")
     */
    public function showAllUsers()
    {
        $users=$this->userService->getAllUser();
        return $this->json($users, Response::HTTP_OK,[],['groups'=>['main']]);
        //return View::create($user, Response::HTTP_OK);
    }
    /**
     * @Rest\Get("/user/{userId}")
     *
     */
    public function showUser(int $userId)
    {
        $user=$this->userService->getUser($userId);
        return $this->json($user, Response::HTTP_OK,[],['groups'=>['main']]);
        //return View::create($user, Response::HTTP_OK);
    }
    /**
     * @Rest\Delete("/user/{userid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUsers(int $userid)
    {
       $this->userService->deleteUser( $userid);
       return $this->json([], Response::HTTP_NO_CONTENT);
    }
}