<?php
namespace Ely\Yii2;

include __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

use yii\base\Model;
use yii\console\Application;

class TempmailValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        new Application([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => __DIR__ . '/../vendor',
        ]);
    }

    public function testValidateValue()
    {
        $validator = new TempmailValidator();
        $this->assertTrue($validator->validate('team@ely.by'));
        $this->assertFalse($validator->validate('h4l29@spam4.me', $error));
        $this->assertSame('the input value is not allowed email address.', $error);
    }

    public function testValidatorOnModel()
    {
        $model = $this->createDummyModel();
        $model->email = 'team@ely.by';
        $this->assertTrue($model->validate());

        $model = $this->createDummyModel();
        $model->email = 'spam@spam4.me';
        $this->assertFalse($model->validate());
        $this->assertSame('Email is not allowed email address.', $model->getFirstError('email'));

        $model = $this->createDummyModel('{attribute} with custom message.');
        $model->email = 'spam@spam4.me';
        $this->assertFalse($model->validate());
        $this->assertSame('Email with custom message.', $model->getFirstError('email'));
    }

    private function createDummyModel(string $customMessage = null) {
        return new class(['customMessage' => $customMessage]) extends Model
        {
            public $email;

            public $customMessage;

            public function rules()
            {
                return [
                    [['email'], TempmailValidator::class, 'message' => $this->customMessage],
                ];
            }
        };
    }
}

