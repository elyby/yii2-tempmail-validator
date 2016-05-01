<?php
namespace Ely\Yii2;

include __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

use Ely\TempMailBuster\Storage;
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
        $this->assertNull($this->callValidateValue($validator, 'team@ely.by'));
        $this->assertEquals([$validator->message, []], $this->callValidateValue($validator, 'h4l29@spam4.me'));
    }

    public function testBuildValidator()
    {
        $validator = new TempmailValidator();
        $this->assertInstanceOf('\Ely\TempMailBuster\Validator', $this->callBuildValidator($validator));

        $domains = ['mojang\.com'];
        $validator = new TempmailValidator(['secondaryStorage' => $domains]);
        $buster = $this->callBuildValidator($validator);
        $this->assertInstanceOf('\Ely\TempMailBuster\Validator', $buster);
        $this->assertEquals($domains, $buster->getSecondaryStorage()->getItems());

        $validator = new TempmailValidator(['secondaryStorage' => new Storage($domains)]);
        $buster = $this->callBuildValidator($validator);
        $this->assertInstanceOf('\Ely\TempMailBuster\Validator', $buster);
        $this->assertEquals($domains, $buster->getSecondaryStorage()->getItems());
    }

    public function testValidatorOnModel()
    {
        $model = new DummyModel();
        $model->email = 'team@ely.by';
        $this->assertTrue($model->validate());

        $model = new DummyModel();
        $model->email = 'spam@spam4.me';
        $this->assertFalse($model->validate());
        $this->assertNotEmpty($model->getErrors('email'));
    }

    /**
     * @param TempmailValidator $object
     * @return \Ely\TempMailBuster\Validator
     */
    private function callBuildValidator($object)
    {
        $class = new \ReflectionClass($object);
        $method = $class->getMethod('buildValidator');
        $method->setAccessible(true);

        return $method->invoke($object);
    }

    private function callValidateValue($object, $value)
    {
        $class = new \ReflectionClass($object);
        $method = $class->getMethod('validateValue');
        $method->setAccessible(true);

        return $method->invokeArgs($object, [$value]);
    }
}

class DummyModel extends Model
{
    public $email;

    public function rules()
    {
        return [
            [['email'], TempmailValidator::className()],
        ];
    }
}
