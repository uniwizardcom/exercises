<?php
/**
 * @author Wojciech Niewiadomski
 * @email wojciech.niewiadomski@duolife.eu
 * @copyright DuoLife S.A.
 */

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId() {
        return $this->id;
    }

}
