<?php


namespace AppBundle\Validator\Constraints;


use Cron\CronExpression;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class CronExpressionValidator
 *
 * @package AppBundle\Validator\Constraints
 */
class CronExpressionValidator extends ConstraintValidator
{

    /**
     * Checks if the passed valie is a valid cron expression
     *
     * @param mixed      $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $value = (string)$value;
        if (null === $value || '' === $value) {
            return;
        }
        if (!CronExpression::isValidExpression($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }

        return;
    }
}