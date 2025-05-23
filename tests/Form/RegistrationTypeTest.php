<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;

class RegistrationTypeTest extends TypeTestCase
{
    public function testRegistrationFormHasCorrectFields(): void
    {
        $form = $this->factory->create(RegistrationType::class);
        
        // Check that form contains the expected fields
        $this->assertTrue($form->has('firstName'));
        $this->assertTrue($form->has('lastName'));
        $this->assertTrue($form->has('email'));
        $this->assertTrue($form->has('rawPassword'));
        
        // Get the form field types
        $this->assertInstanceOf(TextType::class, $form->get('firstName')->getConfig()->getType()->getInnerType());
        $this->assertInstanceOf(TextType::class, $form->get('lastName')->getConfig()->getType()->getInnerType());
        $this->assertInstanceOf(EmailType::class, $form->get('email')->getConfig()->getType()->getInnerType());
        $this->assertInstanceOf(RepeatedType::class, $form->get('rawPassword')->getConfig()->getType()->getInnerType());
    }
    
    public function testSubmitValidData(): void
    {
        $formData = [
            'firstName' => 'Petar',
            'lastName' => 'Petrovic',
            'email' => 'petar.petrovic@example.com',
            'rawPassword' => [
                'first' => 'password123',
                'second' => 'password123',
            ],
        ];
        
        $user = new User();
        $form = $this->factory->create(RegistrationType::class, $user);
        
        // Submit the form with test data
        $form->submit($formData);
        
        $this->assertTrue($form->isSynchronized());
        
        // Check that the form is valid
        $this->assertTrue($form->isValid());
        
        // Check that the form data was mapped to the entity
        $this->assertEquals('Petar', $user->getFirstName());
        $this->assertEquals('Petrovic', $user->getLastName());
        $this->assertEquals('petar.petrovic@example.com', $user->getEmail());
        $this->assertEquals('password123', $user->getRawPassword());
    }
    
    public function testConfigureOptions(): void
    {
        $form = $this->factory->create(RegistrationType::class);
        $options = $form->getConfig()->getOptions();
        
        // Test that data_class is set to User::class
        $this->assertEquals(User::class, $options['data_class']);
        
        // Test that validation_groups are set correctly
        $this->assertContains('Default', $options['validation_groups']);
        $this->assertContains('rawPassword', $options['validation_groups']);
    }
}