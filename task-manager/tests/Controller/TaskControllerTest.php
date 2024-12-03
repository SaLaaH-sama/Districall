<?php
// tests/Controller/TaskControllerTest.php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testCreateTask()
    {
        $client = static::createClient();
        $client->getKernel()->getContainer()->get('request_stack')->getCurrentRequest()->initialize([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'todo'
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    // Ajoutez d'autres tests pour update, delete, list, et search
}