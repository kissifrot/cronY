<?php


namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class CronExpression
 *
 * @Annotation
 *
 * @package AppBundle\Validator\Constraints
 */
class CronExpression extends Constraint
{

    /**
     * Constraint error message
     *
     * @var string
     */
    public $message = 'The string "{{ string }}" is not a valid cron expression.';
}
