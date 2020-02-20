<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="survey_results")
 */
class SurveyResults {
  /**
   * @ORM\Column(type="integer", options={"unsigned": true})
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="text", name="survey_id", length=65535, options={"collation": "utf8_general_ci"})
   */
  private $surveyId;
  /**
   * @ORM\Column(type="text", name="uuid", length=65535, options={"collation": "utf8_general_ci"})
   */
  private $uuid;
  /**
   * @ORM\Column(type="text", name="survey_definition", length=65535, options={"collation": "utf8_general_ci"})
   */
  private $surveyDefinition;
  /**
   * @ORM\Column(type="text", name="survey_answers", length=65535, options={"collation": "utf8_general_ci"})
   */
  private $surveyAnswers;
  /**
    * @ORM\Column(type="string", name="created_at", nullable=true )
   */
  private $createdAt;

  /**
   * Get the value of id
   */ 
  public function getId()
  {
    return $this->id;
  }
 

  /**
   * Get the value of surveyDefinition
   */ 
  public function getSurveyDefinition()
  {
    return $this->surveyDefinition;
  }

  /**
   * Set the value of surveyDefinition
   *
   * @return  self
   */ 
  public function setSurveyDefinition($surveyDefinition)
  {
    $this->surveyDefinition = $surveyDefinition;
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
   * Get the value of surveyAnswers
   */ 
  public function getSurveyAnswers()
  {
    return $this->surveyAnswers;
  }

  /**
   * Set the value of surveyAnswers
   *
   * @return  self
   */ 
  public function setSurveyAnswers($surveyAnswers)
  {
    $this->surveyAnswers = $surveyAnswers;

    return $this;
  }

  /**
   * Get the value of surveyId
   */ 
  public function getSurveyId()
  {
    return $this->surveyId;
  }

  /**
   * Set the value of surveyId
   *
   * @return  self
   */ 
  public function setSurveyId($surveyId)
  {
    $this->surveyId = $surveyId;

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
