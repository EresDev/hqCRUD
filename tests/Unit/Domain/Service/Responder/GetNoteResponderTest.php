<?php

namespace App\Tests\Unit\Domain\Service\Responder;

use App\Tests\Extra\ValidEntities;
use App\Tests\Extra\ValidValues;
use PHPUnit\Framework\TestCase;

class GetNoteResponderTest extends TestCase
{
    public function testPrepareForValidNote() : void
    {
        $note = ValidEntities::getNote();
        $responseForValidNote = ValidValues::getHttpResponse(200, $note);

        $responder = GetNoteResponderBuilder::getInstance()
            ->withHttpResponse($responseForValidNote)
            ->build();

        $response = $responder->prepare($note);

        $this->assertTrue($responseForValidNote->equals($response));
    }

    public function testPrepareForNullInPlaceOfNote() : void
    {
        $responseForNull = ValidValues::getHttpResponse(
            404,
            'Resource not found.'
        );

        $responder = GetNoteResponderBuilder::getInstance()
            ->withHttpResponse($responseForNull)
            ->build();

        $response = $responder->prepare(null);

        $this->assertTrue($responseForNull->equals($response));
    }
}