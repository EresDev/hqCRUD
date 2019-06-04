<?php

namespace App\Tests\Unit\Domain\Service\Responder;

use App\Domain\Entity\Entity;
use App\Domain\Repository\Repository;
use App\Domain\Service\Responder\CreatorResponder;
use App\Domain\Service\Factory\HttpResponseFactory;
use App\Domain\Service\Factory\RepositoryFactory;
use App\Domain\Service\Validator;
use App\Domain\Service\ValueObject\HttpResponse;
use App\Domain\Service\ValueObject\ValidatorResponse;
use PHPUnit\Framework\TestCase;

class CreatorResponderBuilder extends TestCase
{
    private $validator;
    private $repositoryFactory;
    private $httpResponseFactory;

    public static function getInstance(): CreatorResponderBuilder
    {
        return new self();
    }

    public function __construct()
    {
        $this->validator = $this->createMock(Validator::class);
        $this->repositoryFactory = $this->createMock(
            RepositoryFactory::class
        );
        $this->httpResponseFactory = $this->createMock(
            HttpResponseFactory::class
        );


    }

    public function withValidValidatorResponse() : self
    {
        $validatorResponse = new ValidatorResponse(true, []);

        $this->validator->method('validate')
            ->willReturn($validatorResponse);

        $this->withHttpResponseFactoryForValidResponse();

        return $this;
    }

    private function withHttpResponseFactoryForValidResponse(): void
    {
        $this->httpResponseFactory->method('create')
            ->willReturn(new HttpResponse(200, 'Test content.'));

    }

    public function withInvalidValidatorResponse(): self
    {
        $validatorResponse = new ValidatorResponse(false, ['An error message.']);

        $this->validator->method('validate')
            ->willReturn($validatorResponse);

        $this->withHttpResponseFactoryForInvalidResponse();

        return $this;
    }

    private function withHttpResponseFactoryForInvalidResponse(): void
    {
        $this->httpResponseFactory->method('create')
            ->willReturn(new HttpResponse(422, 'An error message.'));
    }

    public function withRepositoryFactory() : self
    {
        $this->repositoryFactory->expects($this->any())
            ->method('create')
            ->with($this->isInstanceOf(Entity::class))
            ->willReturn(
                $this->createMock(Repository::class)
            );

        return $this;
    }

    public function build() : CreatorResponder
    {
        $this->withValidValidatorResponse()
            ->withRepositoryFactory();

        return new CreatorResponder(
            $this->validator,
            $this->httpResponseFactory,
            $this->repositoryFactory
        );
    }
}