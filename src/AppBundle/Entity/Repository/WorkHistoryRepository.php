<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * WorkHistoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkHistoryRepository extends EntityRepository {
	
	public function findActiveWorkHistories(){
		
		$today = new \DateTime('now');
		$workHistories =  $this->getEntityManager()->createQuery("
		SELECT whistory
		FROM AppBundle:WorkHistory whistory
		JOIN whistory.startSemester startSemester
		LEFT JOIN whistory.endSemester endSemester
		WHERE (startSemester.semesterStartDate < :today
		AND endSemester.semesterEndDate > :today)
		OR (startSemester.semesterStartDate < :today
		AND endSemester.semesterEndDate is NULL)
		")
		->setParameter('today', $today)
		->getResult();

		return $workHistories;
	}

	public function findActiveWorkHistoriesByDepartment($department){

		$today = new \DateTime('now');
		$workHistories =  $this->getEntityManager()->createQuery("
		SELECT whistory
		FROM AppBundle:WorkHistory whistory
		JOIN whistory.team_id team
		JOIN whistory.startSemester startSemester
		LEFT JOIN whistory.endSemester endSemester
		WHERE (startSemester.semesterStartDate < :today
		AND endSemester.semesterEndDate > :today)
		OR (startSemester.semesterStartDate < :today
		AND endSemester.semesterEndDate is NULL)
		AND team.department_id = :department
		")
			->setParameter('today', $today)
			->setParameter('department', $department)
			->getResult();

		return $workHistories;
	}
	
	public function findActiveWorkHistoriesByUser($user){
		
		$today = new \DateTime('now');
		$workHistories =  $this->getEntityManager()->createQuery("
		SELECT whistory
		FROM AppBundle:WorkHistory whistory
		JOIN whistory.startSemester startSemester
		LEFT JOIN whistory.endSemester endSemester
		WHERE (startSemester.semesterStartDate < :today
		AND endSemester.semesterEndDate > :today
		AND whistory.user = :user)
		OR (startSemester.semesterStartDate < :today
		AND endSemester.semesterEndDate is NULL
		AND whistory.user = :user )
		
		")
		->setParameter('user', $user)
		->setParameter('today', $today)
		->getResult();

		return $workHistories;
	}
	
}
