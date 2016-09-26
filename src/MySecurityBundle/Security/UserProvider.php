<?php

namespace MySecurityBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface
{ 
    public function loadUserByUsername($username) {
        $authenticated = false;
        $salt = null;
        if(isset($_REQUEST['_password']))
        {
            $password = $_REQUEST['_password'];
        }
        else
        {
            $password = "";
        }
        
        if ($password == "manager") {
            $roles = array('ROLE_MANAGER');
            $authenticated = true;
        } elseif ($password == "moderator") {
            $roles = array('ROLE_MODERATOR');
            $authenticated = true; 
        } else {
            $authenticated = false;   
        }
        
        if ($authenticated) {
            
            return new User($username, $password, $salt, $roles);
        }
        
        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }
    
    /*public function loadUserByUsername($username)
    {
        $authenticated = false;
        $salt = null;
        
        if(isset($_REQUEST['_password']))
        {
            $password = $_REQUEST['_password'];
        }
        else
        {
            $password = "";
        }
            
        
        if($username === 'pera' && $password === 'peric')
        {
            $roles = array('ROLE_ROLA1');
            $authenticated = true;
        }
        
        if($username === 'sima' && $password === 'simic')
        {
            $roles = array('ROLE_ROLA2');
            $authenticated = true;
        }
        
        if ($authenticated) {
            return new User($username, $password, $salt, $roles);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }
     * 
     */
    
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }
        
        return $user;
    }

    public function supportsClass($class)
    {
        return $class === 'MySecurityBundle\Security\User';
    }
}