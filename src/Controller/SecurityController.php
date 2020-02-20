<?php

namespace App\Controller;

use App\Repository\SurveyRepository;
use App\Repository\TempTokenRepository;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends FOSRestController
{

    /**
     * 
     * @FOSRest\Get("/")
     *
     * @return Response
     */
    public function indexAction()
    {
        return new JsonResponse(['message' => 'Not Found'], 404);
    }

    /**
     * Store survey result
     * @FOSRest\Post("/datacenter/store/survey")
     *
     * @return Response
     */
    public function storeSurveyResultAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $tempTokenRepo = new TempTokenRepository($this->getDoctrine());
        $surveyRepo = new SurveyRepository($this->getDoctrine());

        $validation = (string) $tempTokenRepo->validateSurveyData($data);

        if (!empty($validation)) {
            return new JsonResponse(['message' => 'Not Authorized'], 401);
        }

        $surveyDefinition = $surveyRepo->getSurveyDefinition($data['surveyId']);

        if (empty($surveyDefinition)) {
            return new JsonResponse(['message' => 'Not Authorized'], 401);
        }

        if($surveyRepo->userSurveyResultsAlreadyExist($data['uuid'], $data['surveyId'])){
            return new JsonResponse(['message' => 'the-survey-has-already-been-completed'], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->beginTransaction();
        try {

            $surveyRepo->storeSurveyResult($data, $surveyDefinition);
            $entityManager->getConnection()->commit();

        } catch (DBALException $e) {
            $entityManager->getConnection()->rollback();
            return new JsonResponse(['message' => 'Internal Server Error'], 500);
        } catch (PDOException $e) {
            $entityManager->getConnection()->rollback();
            return new JsonResponse(['message' => 'Internal Server Error'], 500);
        } catch (ORMException $e) {
            $entityManager->getConnection()->rollback();
            return new JsonResponse(['message' => 'Internal Server Error'], 500);
        } catch (Exception $e) {
            $entityManager->getConnection()->rollback();
            return new JsonResponse(['message' => 'Internal Server Error'], 500);
        }

        $tempTokenRepo->destroyToken($data['uuid']);
        return $this->handleView($this->view(['message' => 'Created'], Response::HTTP_CREATED));
    }

    /**
     * Get survey
     * @FOSRest\Post("/datacenter/get/survey")
     *
     * @return Response
     */
    public function getSurveyAction(Request $request)
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');

        $data = json_decode($request->getContent(), true);

        $surveyRepo = new SurveyRepository($this->getDoctrine());

        if (empty($data["surveyId"])) {
            return new JsonResponse(['message' => 'Not Authorized'], 401);
        }

        if ((!empty($data['clientSecret']) && $data['clientSecret'] != $_ENV['CLIENT_SECRET']) || (empty($data['uuid']))) {
            return new JsonResponse(['message' => 'Not Authorized'], 401);
        }

        if($surveyRepo->userSurveyResultsAlreadyExist($data['uuid'], $data['surveyId'])){
            return new JsonResponse(['message' => 'the-survey-has-already-been-completed'], 400);
        }

        $surveyDefinition = $surveyRepo->getSurveyDefinition($data['surveyId']);

        if (empty($surveyDefinition)) {
            return new JsonResponse(['message' => 'Not Authorized'], 401);
        }

        $tempTokenRepo = new TempTokenRepository($this->getDoctrine());

        $tempToken = $tempTokenRepo->getToken($data['uuid']);

        if (empty($tempToken)) {
            $token = $tempTokenRepo->createToken($data['uuid']);
        } else {
            $token = $tempToken->getToken();
        }

        return $this->handleView($this->view(['message' => "OK", 'uuid' => $data['uuid'], 'survey' => $surveyDefinition, 'token' => $token], Response::HTTP_CREATED));
    }

}
