<?php 

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Password
{
    /**
     * @var string The hashed password
     * @Assert\Length(
     *      min = 8,
     *      max = 250,
     *      minMessage = "Votre mot de passe doit comporter {{ limit }} caractères minimum",
     *      maxMessage = "Votre mot de passe doit comporter {{ limit }} caractères maximum"
     * )
     */
    private $oldPassword;

    /**
     * @var string The hashed password
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire au moins  caractères")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Vous n'avez pas correctemment confirmé votre mot de passe")
     */
    private $confirmPassword;

    /**
     * Get the value of oldPassword
     */ 
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Set the value of oldPassword
     *
     * @return  self
     */ 
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * Get the value of newPassword
     */ 
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * Set the value of newPassword
     *
     * @return  self
     */ 
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Get the value of confirmPassword
     */ 
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * Set the value of confirmPassword
     *
     * @return  self
     */ 
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}