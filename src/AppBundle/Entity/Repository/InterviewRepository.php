<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * InterviewRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InterviewRepository extends EntityRepository
{

    public function findAllInterviewedInterviewsBySemester($semester){
        $interviews =  $this->getEntityManager()->createQuery("
		SELECT interview
		FROM AppBundle:Interview interview
		JOIN AppBundle:Application app
		WITH interview.application = app
		JOIN AppBundle:ApplicationStatistic appStat
		WITH app.statistic = appStat
		WHERE interview.interviewed = 1
		AND appStat.semester = :semester
		")
            ->setParameter('semester', $semester)
            ->getResult();

        return $interviews;
    }
}