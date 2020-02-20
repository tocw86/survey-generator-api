<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="temp_token")
 */
class TempToken {
  /**
   * @ORM\Column(type="integer", options={"unsigned": true})
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="text", name="token", length=65535, options={"collation": "utf8_general_ci"})
   */
  private $token;
  /**
   * @ORM\Column(type="text", name="uuid", length=65535, options={"collation": "utf8_general_ci"})
   */
  private $uuid;
   /**
   * @ORM\Column(type="string", name="created_at", nullable=true )
   */
  private $createdAt;
 

  /**
   * Get the value of token
   */ 
  public function getToken()
  {
    return $this->token;
  }

  /**
   * Set the value of token
   *
   * @return  self
   */ 
  public function setToken($token)
  {
    $this->token = $token;

    return $this;
  }
 
  /**
   * Get the value of createdAt
   */ 
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * Set the value of createdAt
   *
   * @return  self
   */ 
  public function setCreatedAt()
  {
    $this->createdAt = date('Y-m-d H:i:s');

    return $this;
  }

  /**
   * Get the value of uuid
   */ 
  public function getUuid()
  {
    return $this->uuid;
  }

  /**
   * Set the value of uuid
   *
   * @return  self
   */ 
  public function setUuid($uuid)
  {
    $this->uuid = $uuid;

    return $this;
  }
}
