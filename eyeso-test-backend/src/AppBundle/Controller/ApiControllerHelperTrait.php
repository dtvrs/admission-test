<?php

namespace AppBundle\Controller;

use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ApiControllerHelperTrait
{
    /**
     * Undocumented function
     *
     * @param ConstraintViolationListInterface $errors
     * @return array
     */
    protected function buildApiErrors(ConstraintViolationListInterface $errors): array
    {
        $apiErrors = [];

        foreach ($errors as $key => $error) {
            $property = $error->getPropertyPath();
            $message = $error->getMessage();

            if (!isset($apiErrors[$property])) {
                $apiErrors[$property] = [
                    'messages' => [],
                ];
            }

            array_push($apiErrors[$property]['messages'], $message);
        }

        return $apiErrors;
    }
}
