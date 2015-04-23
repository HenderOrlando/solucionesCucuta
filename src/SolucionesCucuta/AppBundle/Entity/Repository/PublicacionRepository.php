<?php

namespace SolucionesCucuta\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PublicacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PublicacionRepository extends EntityRepository
{
    public function getPublicacionesTipo($tipo, $results = true){
        $qb = $this->createQueryBuilder('p');
        if(is_numeric($tipo)){
            $qb->andWhere($qb->expr()->eq('p.tipo',$tipo));
        }elseif(is_string($tipo)){
            $qb ->join('p.tipo', 'pt')
                ->andWhere($qb->expr()->eq('pt.slug',$qb->expr()->literal($tipo)));
        }
        if($results === null){
            return $qb;
        }
        if(!$results){
            return $qb->getQuery();
        }
        return $qb->getQuery()->getResult();
    }
}