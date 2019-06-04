<?php

namespace App\Tests\Unit\Domain\UseCase;

use App\Domain\UseCase\NoteCreator;

class CreateNoteUseCaseTest extends CreatorTestBase
{
    public function testExecute()
    {
        $entity = $this->getEntity(1);
        $entityFactory = $this->getEntityFactory($entity);

        $simpleHttpResponse = $this->getSimpleHttpResponse();
        $responder = $this->getResponder($entity, $simpleHttpResponse);

        $createNoteUseCase = new NoteCreator($entityFactory, $responder);

        $response = $createNoteUseCase->execute($this->dummyRequestParameters);

        $this->assertEquals(
            $response->getStatusCode(),
            $simpleHttpResponse->getStatusCode()
        );

        $this->assertEquals(
            $response->getContent(),
            $simpleHttpResponse->getContent()
        );
    }

}
