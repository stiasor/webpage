<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SubstituteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SubstituteRepository extends EntityRepository
{
    /**
     * Finds all substitutes. Optionally filter by department and semester.
     *
     * @param null $department
     * @param null $semester
     * @return array
     */
    public function findSubstitutes($department = null, $semester = null)
    {
        $qb = $this->createQueryBuilder('sub')
            ->select('sub')
            ->join('sub.fieldOfStudy', 'f')
            ->join('sub.semester', 'sem')
            ->join('f.department', 'd');

        if (null !== $department) {
            $qb->andWhere('d = :department')
                ->setParameter('department', $department);
        }

        if (null !== $semester) {
            $qb->andWhere('sem = :semester')
                ->setParameter('semester', $semester);
        }

        return $qb->getQuery()->getResult();
    }
}