<?php

namespace MicroFrame\Entity;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    private $passwordEncoder;

    public function createAdminUser($username, $password)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setRoles('ROLE_ADMIN');

        $this->insert($user);

        return $user;
    }

    public function setPasswordEncoder(PasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function insert($user)
    {
        $this->encodePassword($user);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function objectToArray(User $user)
    {
        return array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'roles' => implode(',', $user->getRoles()),
            'created_at' => $user->getCreatedAt()->format(self::DATE_FORMAT),
        );
    }

    /**
     * Turns an array of data into a User object
     *
     * @param array $userArr
     * @param User $user
     * @return User
     */
    public function arrayToObject( $userArr, $user = null)
    {
        // create a User, unless one is given
        if (!$user) {
            $user = new User();

            $user->setId(isset($userArr['id']) ? $userArr['id'] : null);
        }

        $username = isset($userArr['username']) ? $userArr['username'] : null;
        $password = isset($userArr['password']) ? $userArr['password'] : null;
        $roles = isset($userArr['roles']) ? explode(',', $userArr['roles']) : array();
        $createdAt = isset($userArr['created_at']) ? \DateTime::createFromFormat(self::DATE_FORMAT, $userArr['created_at']) : null;

        if ($username) {
            $user->setUsername($username);
        }

        if ($password) {
            $user->setPassword($password);
        }

        if ($roles) {
            $user->setRoles($roles);
        }

        if ($createdAt) {
            $user->setCreatedAt($createdAt);
        }

        return $user;
    }

    public function loadUserByUsername($username)
    {
        //'Username' em findOneByUsername vem do doctrine - é um método mágico que pega o atributo do bd
        $user = $this->findOneByUsername($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('O usuário "%s" não existe.', $username));
        }

        return $this->arrayToObject($user->toArray());
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instâncias de: "%s" - não são aceitas.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'MicroFrame\Entity\User';
    }

    /**
     * Encodes the user's password if necessary
     *
     * @param User $user
     */
    private function encodePassword(User $user)
    {
        if ($user->getPlainPassword()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user->getPlainPassword(), $user->getSalt()));
            $user->setPlainPassword('');
        }
    }
}