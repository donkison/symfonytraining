<?php

namespace App\Controller;


use App\Services\userGroupsService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @IsGranted("ROLE_USER")
 */
class userGroupsController extends AbstractFOSRestController
{
    private $groupsService;
    public function __construct(userGroupsService $groupsService){
        $this->groupsService=$groupsService;
    }

    /**
     * @Rest\Post("/groups")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addGroup(Request $request)
    {
            $name = $request->get('name');
            $usersId = $request->get('usersId');
            $message = $this->groupsService->addGroup($name, $usersId);
            return $this->json(['message'=>$message], Response::HTTP_CREATED);
            //return View::create($user, Response::HTTP_CREATED,[],[]);
    }

    /**
     * @Rest\Get("/groups")
     */
    public function showAllGroups()
    {
        $groups=$this->groupsService->getAllGroups();
        return $this->json($groups, Response::HTTP_OK,[],['groups'=>'main']);
        //return View::create($user, Response::HTTP_OK);
    }
    /**
     * @Rest\Get("/groups/{groupId}")
     *
     */
    public function showGroup(int $groupId)
    {
        $group=$this->groupsService->getGroup($groupId);
        return $this->json($group, Response::HTTP_OK,[],['groups'=>['main']]);
        //return View::create($user, Response::HTTP_OK);
    }
    /**
     * @Rest\Delete("/groups/{groupId}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUsers(int $groupId)
    {
       $this->groupsService->deleteGroup( $groupId);
       return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
