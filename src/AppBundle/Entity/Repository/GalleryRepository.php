<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * GalleryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GalleryRepository extends EntityRepository{


    /**
     * @param $securityContext
     * @return array
     */
    public function findViewableForCurrentUser($securityContext){
        //create the query
        $query = $this->createQueryBuilder('g')->select('g');

        if ( $securityContext->isGranted('ROLE_ADMIN') or
            $securityContext->isGranted('ROLE_SUPER_ADMIN')
        ){
            //Admin- and team-users can see every album. No need to change anything in the query.

        } elseif ( $securityContext->isGranted('ROLE_USER')){
            //Users should be able to view all public albums and albums defined to be viewable for members of specified departments
            //First get id of department user belongs to
            //$user = $securityContext->getToken()->getUser();
            //$department = $user->getFieldOfStudy()->getId();
            /*
                select g.*, u.id AS USERID, u.fieldOfStudy_id AS UFOS, f.department_id
                from gallery g
                INNER JOIN User u on g.user_id=u.id
                INNER JOIN Field_of_study f ON u.fieldOfStudy_id=f.id
                WHERE f.department_id=1
                MÅ HA MED ALLE PUBLIC OGSÅ, DEN WHERE CLAUSEN OVER HER SKAL BARE GJELDE PRIVATE
             */
            //todo: Now I am cheating by just filtering out results from the array of galleries. Fix it.
            $galleries = $query->getQuery()->getResult();
            foreach ($galleries as $key => $gallery) {
                if ($gallery->getPrivate()) {
                    //Get department of user that made the gallery
                    $galleryOwnerDepartment = $gallery->getCreatedByUser()->getFieldOfStudy()->getDepartment();
                    //Get department of user making this request
                    $userDepartment = $securityContext->getToken()->getUser()->getFieldOfStudy()->getDepartment();

                    if ($galleryOwnerDepartment->getId() != $userDepartment->getId()) {  //todo: is getId necessary?
                        unset($galleries[$key]);
                    }
                    //if ($tag_name == $found_tag['name']) {
                    //  unset($display_related_tags[$key]);
                    //}
                }
            }
            return $galleries;
        } else {
            //Other users of the website are allowed to view the public albums.
            $query->where('g.private = FALSE');
        }
        //Return the galleries from database
        return $query->getQuery()->getResult();
    }

    /**
     * Returns all albums whose photos the requesting user is allowed to edit/delete.
     */
    public function findEditableAlbums(){
        //todo: implement if needed else delete
    }
}
