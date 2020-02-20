<?php
namespace App\Repository;

use App\Entity\SurveyResults;
use Symfony\Component\Dotenv\Dotenv;

class SurveyRepository
{

    const SURVEYS_PATH = '/../../surveys/';
    
    private $entityManager;
    private $repository;
    
    public function __construct($doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->repository = $doctrine->getRepository(SurveyResults::class);
    }
    
    /**
     * Check if result exists
     *
     * @param string $uuid
     * @param string $surveyId
     * @return boolean
     */
    public function userSurveyResultsAlreadyExist(string $uuid,string $surveyId) : bool
    {
       return (bool) $this->repository->findOneBy(['uuid' => $uuid, 'surveyId' => $surveyId]);
    }

    /**
     * Save to database
     *
     * @param array $data
     * @param array $surveyDefinition
     * @return boolean
     */
    public function storeSurveyResult(array $data = [], array $surveyDefinition): bool
    {
        $surveyResults = $data['survey'];
        $survey = new SurveyResults();
        $survey->setUuid($data['uuid']);
        $survey->setSurveyId($data['surveyId']);
        $survey->setSurveyDefinition(json_encode($surveyDefinition));
        $survey->setSurveyAnswers($surveyResults);
        $survey->setCreatedAt();
        $this->entityManager->persist($survey);
        $this->entityManager->flush();
        return true;
    }

    /**
     * Get actual survey json
     *
     * @return array
     */
    public function getSurveyDefinition(string $surveyId): array
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');

        if (!file_exists(__DIR__ . self::SURVEYS_PATH . $surveyId . '/' . $_ENV['SURVEY_FILENAME'])) {
            return [];
        }
        $surveyDefinition = file_get_contents(__DIR__ . self::SURVEYS_PATH . $surveyId . '/' . $_ENV['SURVEY_FILENAME']);
        return json_decode($surveyDefinition, true) ?? [];
    }
}
