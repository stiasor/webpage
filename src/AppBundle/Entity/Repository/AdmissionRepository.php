<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AdmissionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdmissionRepository extends EntityRepository {

    /**
     * Finds all applications that have a conducted interview.
     *
     * @param null $department
     * @param null $semester
     * @return array
     */
    public function findInterviewedApplicants($department = null, $semester = null) {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.statistic', 'stat')
            ->join('stat.semester', 'sem')
            ->join('sem.department', 'd')
            ->join('a.interview', 'i')
            ->where('i.interviewed = 1');

            if(null !== $department) {
               $qb->andWhere('d = :department')
                   ->setParameter('department', $department);
            }

            if(null !== $semester) {
                $qb->andWhere('sem = :semester')
                    ->setParameter('semester', $semester);
            }

        return $qb->getQuery()->getResult();
    }

    /**
     * Finds all applications that have been assigned an interview that has not yet been conducted.
     *
     * @param null $department
     * @param null $semester
     * @return array
     */
    public function findAssignedApplicants($department = null, $semester = null) {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.statistic', 'stat')
            ->join('stat.semester', 'sem')
            ->join('sem.department', 'd')
            ->join('a.interview', 'i')
            ->where('i.interviewed = 0');

             if(null !== $department) {
                 $qb->andWhere('d = :department')
                     ->setParameter('department', $department);
             }

            if(null !== $semester) {
                $qb->andWhere('sem = :semester')
                    ->setParameter('semester', $semester);
            }

        return $qb->getQuery()->getResult();
    }

    /**
     * Finds all applications without an interview, or without a conducted interview.
     *
     * @param null $department
     * @param null $semester
     * @return array
     */
    public function findNewApplicants($department = null, $semester = null) {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.statistic', 'stat')
            ->join('stat.semester', 'sem')
            ->join('sem.department', 'd')
            ->leftJoin('a.interview', 'i')
            ->where('i.interviewed = 0')
            ->orWhere('i is NULL');

            if(null !== $department) {
                $qb->andWhere('d = :department')
                    ->setParameter('department', $department);
            }

            if(null !== $semester) {
                $qb->andWhere('sem = :semester')
                    ->setParameter('semester', $semester);
            }

        return $qb->getQuery()->getResult();
    }

    /* Disse brukes ikke lenger(?)
    public function getAllApplicants(){

        $applicants =  $this->getEntityManager()->createQuery("

		SELECT a, s
		FROM AppBundle:Application a
		JOIN a.statistic s

		")
            ->getResult();

        return $applicants;
    }

    public function findAllApplicantsForDepartment($department){

        $applicants =  $this->getEntityManager()->createQuery("

		SELECT a, s
		FROM AppBundle:Application a
		JOIN a.statistic s
		JOIN s.fieldOfStudy fos
		JOIN fos.department department
		WHERE s.fieldOfStudy = fos.id
			AND fos.department = :department
		")
            ->setParameter('department', $department)
            ->getResult();

        return $applicants;
    }*/

    public function findApplicantById($id){
        return $this->createQueryBuilder('Application')
            ->select('Application')
            ->where('Application.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function findApplicantStatisticById($id){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('ApplicationStatistic')
            ->where('ApplicationStatistic.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function NumOfApplications(){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfSemesters(){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.semester)')
            ->distinct()
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function NumOfSemester($semester){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.semester)')
            ->where('ApplicationStatistic.semester = :semester')
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfGender($gender){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.gender)')
            ->where('ApplicationStatistic.gender = :gender')
            ->setParameter('gender', $gender)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfPreviousParticipation($participated){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.gender)')
            ->where('ApplicationStatistic.previousParticipation = :participated')
            ->setParameter('participated', $participated)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfAccepted($accepted){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.accepted)')
            ->where('ApplicationStatistic.accepted = :accepted')
            ->setParameter('accepted', $accepted)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfYearOfStudy($yearOfStudy){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.yearOfStudy)')
            ->where('ApplicationStatistic.yearOfStudy = :yearOfStudy')
            ->setParameter('yearOfStudy', $yearOfStudy)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfFieldOfStudy($fieldOfStudy){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.fieldOfStudy)')
            ->where('ApplicationStatistic.fieldOfStudy = :fieldOfStudy')
            ->setParameter('fieldOfStudy', $fieldOfStudy)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfDepartment($department){
        $numUsers =  $this->getEntityManager()->createQuery("

		SELECT COUNT (AppS.id)
		FROM AppBundle:ApplicationStatistic AppS
		JOIN AppS.semester s
		JOIN s.department d
		WHERE d.id = :department

		")
            ->setParameter('department', $department)
            ->getSingleScalarResult();

        return $numUsers;
    }

}