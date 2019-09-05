<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/6
// +----------------------------------------------------------------------


namespace AppBundle\Custom\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ArraySizeValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if(!is_array($value) || count($value) <= 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ name }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}