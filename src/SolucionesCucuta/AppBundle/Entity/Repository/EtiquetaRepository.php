<?php

namespace SolucionesCucuta\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EtiquetaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EtiquetaRepository extends EntityRepository
{
    public function getEtiquetasTipo($tipo, $results = true){
        $qb = $this->createQueryBuilder('e');
        if(is_numeric($tipo)){
            $qb->andWhere($qb->expr()->eq('e.tipo',$tipo));
        }elseif(is_string($tipo)){
            $qb ->join('e.tipo', 'et')
                ->andWhere($qb->expr()->eq('et.slug',$qb->expr()->literal($tipo)));
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
