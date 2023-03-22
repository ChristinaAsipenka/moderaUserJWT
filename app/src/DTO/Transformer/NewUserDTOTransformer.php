<?php

namespace App\DTO\Transformer;

use App\DTO\NewUserDTO;
use Symfony\Component\HttpFoundation\Request;

class NewUserDTOTransformer
{
    public function transform(Request $request): NewUserDTO
    {
        $user = new NewUserDTO();
        $user ->setEmail($request->get('email'));
        $user ->setPassword($request->get('password'));
        $user ->setName($request->get('name'));

        return $user;
    }
}
