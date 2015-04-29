<?php

namespace SolucionesCucuta\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends EntityRepository
{
    public function loadUserByUsername($username)
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            $message = sprintf(
                'Unable to find an active admin AppBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
        || is_subclass_of($class, $this->getEntityName());
    }

    public function getUsuariosRol($rol, $results = true){
        $qb = $this->createQueryBuilder('u');
        if(is_numeric($rol)){
            $qb->andWhere($qb->expr()->eq('u.rol',$rol));
        }elseif(is_string($rol)){
            $qb ->join('u.rol', 'ur')
                ->andWhere($qb->expr()->eq('ur.slug',$qb->expr()->literal($rol)));
        }
        if($results === null){
            return $qb;
        }
        if(!$results){
            return $qb->getQuery();
        }
        return $qb->getQuery()->getResult();
    }

    public function getClientesBySlug($slug, $slughijo, $results = true){
        $qb = $this->createQueryBuilder('u');
        $qb ->innerJoin('u.etiquetas', 'ue');
        if(is_string($slughijo)){
            $qb->where($qb->expr()->eq('ue.slug',$qb->expr()->literal($slughijo)));
        }else{
            $qb
                ->orWhere($qb->expr()->eq('ue.slug',$qb->expr()->literal($slug)))
                /*->leftJoin('ue.hijos', 'ue_hijo')
                ->orWhere('ue_hijo.slug=u.etiquetas')*/
            ;
        }
        if($results === null){
            return $qb;
        }
        if(!$results){
            return $qb->getQuery();
        }
        return $qb->getQuery()->getResult();
    }

    public function searchCliente($query){
        $qb = $this->createQueryBuilder('u');
        $qb ->innerJoin('u.etiquetas', 'ue');
        if(is_string($query)){
            $query = $this->slugify($query);
            $qb
                ->orWhere($qb->expr()->like('ue.slug',$qb->expr()->literal($query)))
                ->orWhere($qb->expr()->like('u.slug',$qb->expr()->literal($query)))
            ;
        }

        return $qb->getQuery()->getResult();
    }
    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}
