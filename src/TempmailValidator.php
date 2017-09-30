<?php
namespace Ely\Yii2;

use EmailValidation\EmailAddress;
use EmailValidation\EmailDataProvider;
use EmailValidation\EmailValidator;
use EmailValidation\ValidationResults;
use EmailValidation\Validations\DisposableEmailValidator;
use Yii;
use yii\validators\Validator;

class TempmailValidator extends Validator
{
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is not allowed email address.');
        }
    }

    protected function validateValue($value)
    {
        $validator = $this->buildValidator($value);
        $results = $validator->getValidationResults()->asArray();
        if ($results['disposable_email_provider']) {
            return [$this->message, []];
        }

        return null;
    }

    protected function buildValidator(string $email): EmailValidator
    {
        $emailAddress = new EmailAddress($email);
        $emailDataProvider = new EmailDataProvider();
        $emailValidationResults = new ValidationResults();
        $emailValidator = new EmailValidator($emailAddress, $emailValidationResults, $emailDataProvider);
        $emailValidator->registerValidator(new DisposableEmailValidator());

        return $emailValidator;
    }
}
