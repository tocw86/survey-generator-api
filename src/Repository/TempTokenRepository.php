<?php
namespace App\Repository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use App\Entity\TempToken;

class TempTokenRepository
{

    private $entityManager;
    private $repository;
    public function __construct($doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->repository = $doctrine->getRepository(TempToken::class);
    }

    /**
     * Validation
     *
     * @param array $input
     * @return void
     */
    public function validateSurveyData(array $input){

        $validator = Validation::createValidator();
        $groups = new Assert\GroupSequence(['Default', 'custom']);

        $elt = $this->getToken($input['uuid']);
        if(empty($elt)) return 'token not exists';

        $constraint = new Assert\Collection([
            'clientSecret' => [new Assert\NotBlank(), new Assert\EqualTo($_ENV['CLIENT_SECRET'])],
            'uuid' => new Assert\NotBlank(),
            'token' => [new Assert\NotBlank(), new Assert\EqualTo($elt->getToken())],
            'surveyId' => new Assert\NotBlank(),
            'survey' => new Assert\NotBlank(),
        ]);
        
        return $validator->validate($input, $constraint, $groups);
    }

    /**
     * Remove token
     *
     * @param String $uuid
     * @return void
     */
    public function destroyToken(String $uuid)
    {
        $tempToken = $this->repository->findOneBy(['uuid' => $uuid]);
        $this->entityManager->remove($tempToken);
        $this->entityManager->flush();
    }

    /**
     * Get token
     *
     * @param String $uuid
     * @return void
     */
    public function getToken(String $uuid)
    {
        return $this->repository->findOneBy(['uuid' => $uuid]);
    }

    /**
     * Make new token
     *
     * @param String $uuid
     * @return string
     */
    public function createToken(String $uuid): string
    {
        $token = bin2hex(random_bytes(32));

        $tempToken = new TempToken();
        $tempToken->setUuid($uuid);
        $tempToken->setToken($token);
        $tempToken->setCreatedAt();

        $this->entityManager->persist($tempToken);
        $this->entityManager->flush();

        return $token;
    }

}
